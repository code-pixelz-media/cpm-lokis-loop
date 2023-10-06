<?php
if (!defined('ABSPATH')) {
    exit;
}
/* Steps to make a custom endpoint in my accounts page:
   1. Find the lokis_endpoints()
   2. Add endpoints to the array loki_endpoints using array_push() and make endpoints
   3. Match the endpoint with file name for one of the proceeding functions to pull the template. Eg. lokis-(endpoint).php
   4. Add the tab name of the endpoint needed to be shown in loki_endpoint_name using array_push()
   5. Add the full icon class using array_push() in loki_account_icons
*/
/*Enqueue in the scripts*/
function cpm_lokis_public_scripts()
{
    $lokis_time = date('YmdHis', current_time('timestamp'));
    /* css for plugin  */
    wp_enqueue_style('cpm-lokis-public', plugin_dir_url(__FILE__) . 'assets/css/lokis-public-style.css', array(), $lokis_time, false, 'all');
    /* js for plugin  */
    wp_enqueue_script('cpm-lokis-public-js', plugin_dir_url(__FILE__) . 'assets/js/lokis-public-scripts.js', array('jquery'), $lokis_time, true);
    wp_enqueue_style('lokis-fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), false, 'all');
    wp_localize_script('cpm-lokis-public-js', 'gamesajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cpm_lokis_public_scripts');

/*Loads public files*/
require_once('inc/dashboard/lokis-user-dashboard.php');
require_once('inc/lokis-login.php');
require_once('inc/lokis-customizer.php');
require_once('inc/lokis-hosted-game-shortcode.php');

/*Loads single post template for custom post type of games*/
if (!function_exists('lokis_loop_single_post_template')) {
    function lokis_loop_single_post_template($single_template)
    {
        global $post;
        if ($post->post_type == 'games') {
            $single_template = plugin_dir_path(__FILE__) . '/inc/single-games.php';
        }
        return $single_template;
    }
    add_filter('single_template', 'lokis_loop_single_post_template');
}

/*Adding function to check answer of given by ajax post with database correct answer*/
if (!function_exists('lokis_check_answer')) {
    function lokis_check_answer()
    {
        /* Pulling data from Ajax and post meta table */
        $post_id = $_POST['post_id'];
        $answer = strtolower($_POST['answer']);
        $session_id = $_POST['session_id'];
        $correct_answer = strtolower(get_post_meta($post_id, 'lokis_loop_correct_answer', true));
        $redirect_uri = get_post_meta($post_id, 'lokis_loop_redirect_uri', true);
        if ($answer == $correct_answer) {
            if ($session_id) {
                $response = array(
                    'status' => 'success',
                    'redirect' => $redirect_uri . '/?game=' . $session_id,
                    'message' => 'correct'
                );
            } else {
                $response = array(
                    'status' => 'success',
                    'redirect' => $redirect_uri,
                    'message' => 'correct'
                );
            }

        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Incorrect answer'
            );
        }
        wp_send_json($response);
    }
    add_action('wp_ajax_lokis_check_answer', 'lokis_check_answer');
    add_action('wp_ajax_nopriv_lokis_check_answer', 'lokis_check_answer');
}

/*Function to register user*/
if (!function_exists('loki_user_registration')) {
    function loki_user_registration()
    {
        /*Pulling data from registration form*/
        $name = $_POST['name'];
        $email = $_POST['email'];
        $organization_name = $_POST['organization_name'];
        $organization_type = $_POST['organization_type'];
        $country = $_POST['country_name'];
        $zipcode = $_POST['zipcode'];

        if (wp_verify_nonce($_POST['nonce'], -1)) {
            /* Check if the email address is unique */
            if (email_exists($email)) {
                $response = [
                    'status' => 'error',
                    'type' => 'email',
                    'message' => 'The email address ' . $email . ' already exists.'
                ];
                wp_send_json($response);
            }

            /* Check if the email address meets formatting requirements */
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response = [
                    'status' => 'error',
                    'type' => 'email',
                    'message' => 'The email address ' . $email . ' is not valid.'
                ];
                wp_send_json($response);
            }

            //Check country and it's relevant postcode format
            if ($zipcode) {
                if ($country == "United States") {
                    if (!preg_match('/^\d{5}(-\d{4})?$/', $zipcode)) {
                        $response = [
                            'status' => 'error',
                            'message' => 'The zipcode is not a valid US zipcode.'
                        ];
                        wp_send_json($response);
                    }
                } elseif ($country == 'Canada') {
                    if (!preg_match('/^[A-Z]\d[A-Z] ?\d[A-Z]\d$/', $zipcode)) {
                        $response = [
                            'status' => 'error',
                            'message' => 'The zipcode is not a valid Canada zipcode.'
                        ];
                        wp_send_json($response);
                    }
                }
            }

            /*Creating new user*/
            $user_id = wp_create_user($email, wp_generate_password(), $email);

            /*Check for errors when creating new user*/
            if (empty($user_id)) {
                $response = [
                    'status' => 'error',
                    'message' => 'Sorry, user cannot be created.',
                ];
                wp_send_json($response);
            } else {

                // Generate the password reset key for the user.
                $user = get_userdata($user_id);
                $reset_key = get_password_reset_key($user);

                // // Get the password reset URL.
                $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($email), 'login');

                //Email Header
                $headers = array(
                    'From:  noreply-lokisloop@uw.edu' . "\r\n",
                    'Content-Type: text/plain; charset=UTF-8',
                );


                //Email Subject
                $lokis_subject = "lokisloop.org: Complete account set up";

                //Email Body
                $lokis_message = "Welcome to Loki’s Loop!\n\n";
                $lokis_message .= "Your account is almost ready. The last step you need to complete is to set up a password for your new account.\n\n";
                $lokis_message .= "Please click the following link to complete setting up your account:\n";
                $lokis_message .= $reset_url . "\n\n";
                $lokis_message .= "If you have any questions or require assistance, please contact lokisloop@uw.edu\n\n";
                $lokis_message .= "Thank you,\n\n";
                $lokis_message .= "Loki’s Loop";


                wp_mail($email, $lokis_subject, $lokis_message, $headers);


                /*Adding data to user meta*/
                update_user_meta($user_id, 'loki_fullname', $name);
                update_user_meta($user_id, 'loki_organization', $organization_name);
                update_user_meta($user_id, 'loki_organization_type', $organization_type);
                update_user_meta($user_id, 'loki_country', $country);
                update_user_meta($user_id, 'loki_zipcode', $zipcode);

                /*Pulling user data of new user*/
                $user = new WP_User($user_id);

                /* Remove role */
                $user->remove_role('subscriber');

                /* Add role */
                // $user->add_role($role);
                $user->add_role('host');

                $response = [
                    'status' => 'success',
                    'message' => 'User has been created. An email has been sent to set the password',
                ];
                wp_send_json($response);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'ACCESS DENIED',
            ];
            wp_send_json($response);
        }
    }
    add_action('wp_ajax_loki_user_registration', 'loki_user_registration');
    add_action('wp_ajax_nopriv_loki_user_registration', 'loki_user_registration');
}

/* Creates endpoints, endpoint name and icon arrays */
if (!function_exists('lokis_endpoints')) {
    function lokis_endpoints()
    {
        global $lokis_endpoints;
        global $lokis_endpoint_name;
        global $lokis_account_icons;

        $lokis_endpoints = array();
        $lokis_endpoint_name = array();
        $lokis_account_icons = array();

        //Check whether the user is host or player
        //if (current_user_can('host')) {

        /*Host Game Endpoint*/
        $lokis_endpoints_page_name = __('Host an Online Game', 'lokis-loop');
        array_push($lokis_endpoints, 'host-game');
        array_push($lokis_endpoint_name, $lokis_endpoints_page_name);
        array_push($lokis_account_icons, 'fa-regular fa-chart-bar');

        /*Current Games Endpoint*/
        $lokis_endpoints_page_name = __('Current Online Games', 'lokis-loop');
        array_push($lokis_endpoints, 'current-games');
        array_push($lokis_endpoint_name, $lokis_endpoints_page_name);
        array_push($lokis_account_icons, 'fa-solid fa-list-check');

        /*Expired Games Endpoint*/
        $lokis_endpoints_page_name = __('Expired Online Games', 'lokis-loop');
        array_push($lokis_endpoints, 'expired-games');
        array_push($lokis_endpoint_name, $lokis_endpoints_page_name);
        array_push($lokis_account_icons, 'fa-solid fa-triangle-exclamation');

        // Register the endpoints
        foreach ($lokis_endpoints as $endpoint) {
            add_rewrite_endpoint($endpoint, EP_PAGES);
        }
        flush_rewrite_rules();
        // }
    }
    add_action('init', 'lokis_endpoints');
}

/* Pulls template of the host game and hosted games tabs */
if (!function_exists('loki_load_custom_endpoint_template')) {
    function loki_load_custom_endpoint_template($loki_dashboard_template)
    {
        global $wp_query;
        global $lokis_endpoints;

        foreach ($lokis_endpoints as $endpoint) {
            $is_endpoint = isset($wp_query->query_vars[$endpoint]);

            if ($is_endpoint) {
                //if (current_user_can('host')) {
                $loki_dashboard_template = locate_template('inc/dashboard/lokis-' . $endpoint . '.php');
                if (!$loki_dashboard_template) {
                    $loki_dashboard_template = plugin_dir_path(__FILE__) . 'inc/dashboard/lokis-' . $endpoint . '.php';
                }
                //}
            }
        }
        return $loki_dashboard_template;
    }
    add_filter('template_include', 'loki_load_custom_endpoint_template');
}

/* Creates endpoint url from given endpoints on the basis of my account/dashboard page */
if (!function_exists('lokis_endpoint_url')) {
    function lokis_endpoint_url()
    {
        global $lokis_url;
        global $lokis_endpoints;
        $lokis_url = array();

        if (function_exists('get_query_var')) {
            foreach ($lokis_endpoints as $endpoint) {
                if (isset((get_option('lokis_setting'))['dashboard'])) {
                    $dashboard = (get_option('lokis_setting'))['dashboard'];
                }

                if ($endpoint) {
                    array_push($lokis_url, get_permalink($dashboard) . $endpoint . '/');
                }
            }
        }
    }
    add_action('init', 'lokis_endpoint_url');
}

/* Displays my account menu */
if (!function_exists('lokis_account_menu')) {
    function lokis_account_menu()
    {
        global $lokis_url;
        global $lokis_endpoint_name;
        global $lokis_account_icons;

        if (isset((get_option('lokis_setting'))['dashboard'])) {
            $dashboard = (get_option('lokis_setting'))['dashboard'];
        }

        // Create an empty multidimensional array to store the menu items
        $menu_items = array();

        // Add the profile menu item as the first element
        $menu_items[] = array(
            'name' => __('Profile', 'lokis-loop'),
            'url' => get_permalink($dashboard),
            'icon' => 'fa-regular fa-user'
        );

        // Loop through endpoints and add them to the menu items array
        $length = count($lokis_endpoint_name);
        for ($index = 0; $index < $length; $index++) {
            $link_name = $lokis_endpoint_name[$index];
            $link_url = $lokis_url[$index];
            $link_icon = $lokis_account_icons[$index];

            // Create an associative array for each menu item
            $menu_item = array(
                'name' => $link_name,
                'url' => $link_url,
                'icon' => $link_icon
            );

            // Append the menu item array to the multidimensional array
            $menu_items[] = $menu_item;
        }

        // Check if user can view private pages
        if (current_user_can('host') || current_user_can('administrator')) {
            // Retrieve private pages
            $private_pages = new WP_Query(
                array(
                    'meta_key' => 'lokis_private_page_checkbox',
                    'meta_value' => '1',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'posts_per_page' => -1,
                    'order' => 'ASC'
                )
            );

            // Loop through private pages and add them to the menu items array
            foreach ($private_pages->posts as $post) {
                $link_name = get_the_title($post->ID);
                $link_url = get_permalink($post->ID);
                $link_icon = 'fa-regular fa-file-lines';

                // Create an associative array for each menu item
                $menu_item = array(
                    'name' => $link_name,
                    'url' => $link_url,
                    'icon' => $link_icon
                );

                // Append the menu item array to the multidimensional array
                $menu_items[] = $menu_item;
            }
        }

        // Output the menu items
        echo '
         <div class="lokisloop-dashboard-menu">
                <ul class="lokisloop-menu">';

        $order = [0, 5, 1, 2, 3, 6, 4]; // Specify the desired order of the menu items

        // Loop through all items in the $order array
        $remaining_items = array_diff(range(0, count($menu_items) - 1), $order); // Get the indices of the remaining items

        // Loop through the specified order for the first 3 items
        foreach ($order as $index) {
            $menu_item = $menu_items[$index];
            echo '<li><a href="' . $menu_item['url'] . '">';
            echo '<i class="' . $menu_item['icon'] . '"></i>';
            echo '<span class="nav-item">' . $menu_item['name'] . '</span>';
            echo '</a></li>';
        }

        // Loop through the remaining items
        foreach ($remaining_items as $index) {
            $menu_item = $menu_items[$index];
            echo '<li><a href="' . $menu_item['url'] . '">';
            echo '<i class="' . $menu_item['icon'] . '"></i>';
            echo '<span class="nav-item">' . $menu_item['name'] . '</span>';
            echo '</a></li>';
        }


        echo '</ul>
            </div>';
    }
}




/*Function to update user info from profile page*/
if (!function_exists('lokis_profile_update')) {
    function lokis_profile_update()
    {
        $organization_name = $_POST['organization_name'];
        $organization_type = $_POST['organization_type'];
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $userdata = get_user_by('ID', get_current_user_id());

        if (wp_verify_nonce($_POST['nonce'], -1)) {
            if ($old_password !== "") {
                if (wp_check_password($old_password, $userdata->user_pass, $userdata->ID) !== true) {
                    $response = [
                        'status' => 'error',
                        'message' => "Password Incorrect"
                    ];
                    wp_send_json($response);
                }
            }
            update_user_meta(get_current_user_id(), 'loki_organization', $organization_name);
            update_user_meta(get_current_user_id(), 'loki_organization_type', $organization_type);

            if ($new_password !== "") {
                wp_set_password($new_password, get_current_user_id());
                $response = [
                    'status' => 'success',
                    'message' => 'The profile and password has been updated. ',
                ];
                wp_send_json($response);
            }

            $response = [
                'status' => 'success',
                'message' => 'The profile has been updated.',
            ];
            wp_send_json($response);
        } else {
            $response = [
                'status' => 'error',
                'message' => 'ACCESS DENIED',
            ];
            wp_send_json($response);
        }
    }
    add_action('wp_ajax_lokis_profile_update', 'lokis_profile_update');
}

/*Function to delete data from game session table in  database*/
if (!function_exists('lokis_Delete_game_table_data')) {
    function lokis_Delete_game_table_data()
    {
        global $wpdb;
        $lokis_delete_message = '';
        // delete_expired_games
        if (isset($_POST['delete_game'])) {
            $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions';
            $delete_id = (int) $_POST['delete_session_data'];
            // Delete data in mysql from row that has this id 
            $result = $wpdb->delete($lokis_game_sessions_table_name, array('id' => $delete_id));

            // if successfully deleted
            if ($result) {
                $lokis_delete_message = '<div class="lokis-delete-success-box">Deleted Game ID-> ' . $delete_id . ' Successfully</div>';
            } else {
                $lokis_delete_message = '<div class="lokis-delete-error-box"> Data could not Deleted ! Please Try again</div>';
            }

            if ($lokis_delete_message) {
                echo $lokis_delete_message;
            }
        }
    }
}

/*Updates the expires_in value in the database and displays a success or error message based on the result*/
if (!function_exists('lokis_end_game_session')) {
    function lokis_end_game_session()
    {
        if (isset($_POST['end_session'])) {
            global $wpdb;
            $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions';
            $id = $_POST['end_session'];
            $lokis_end_session_message = '';
            $current_time = date('Y-m-d H:i:s');
            // Update the expires_in value in the database
            $result = $wpdb->update($lokis_game_sessions_table_name, ['expires_in' => $current_time], ['id' => $id]);
            // Redirect to the same page to update the displayed data

            // if successfully deleted
            if ($result) {
                $lokis_end_session_message = '<div class="lokis-delete-success-box">Game Session with Game ID-> ' . $id . ' Ended</div>';
            } else {
                $lokis_end_session_message = '<div class="lokis-delete-error-box">Game Session Couldnot be ended ! Please Try again</div>';
            }

            if ($lokis_end_session_message) {
                echo $lokis_end_session_message;
            }
        }
    }
}

/*Function to show template on files with lokis_private_page_checkbox value of 1*/
if (!function_exists('lokis_pull_private_template')) {
    function lokis_pull_private_template($loki_common_template)
    {
        $lokis_private_page_checkbox = get_post_meta(get_the_ID(), 'lokis_private_page_checkbox', true);
        if ($lokis_private_page_checkbox === '1') {
            if (current_user_can('host') || current_user_can('administrator')) {

                // Load specific template content
                $loki_common_template = locate_template('inc/dashboard/lokis-admin-host-common-template.php');
                if (!$loki_common_template) {
                    $loki_common_template = plugin_dir_path(__FILE__) . 'inc/dashboard/lokis-admin-host-common-template.php';
                }
            } else {
                $dashboard_page_id = (get_option('lokis_setting'))['dashboard'];
                $lokis_dashboard_page = get_permalink($dashboard_page_id);
                if (empty($lokis_dashboard_page)) {
                    $lokis_dashboard_page = site_url();
                }
                wp_redirect($lokis_dashboard_page);
                exit;
            }
        }
        return $loki_common_template;
    }
    add_filter('template_include', 'lokis_pull_private_template');
}

/*Function to save a QR code image URL to a database table for a specific game session ID.*/
if (!function_exists('lokis_save_qr_code_callback')) {
    function lokis_save_qr_code_callback()
    {
        if (isset($_POST["qrImageUrl"])) {
            global $wpdb;
            $qrImageUrl = $_POST["qrImageUrl"];
            $lokis_game_id = $_POST["lokis_game_id"];
            $lokis_game_table_name = $wpdb->prefix . 'lokis_game_sessions';

            $sql = "UPDATE $lokis_game_table_name SET qr_code_image_url = '$qrImageUrl' WHERE id = '$lokis_game_id'";
            $wpdb->query($sql);

            wp_send_json_success();
        } else {
            wp_send_json_error("Invalid data");
        }
    }
}

add_action("wp_ajax_lokis_save_qr_code", "lokis_save_qr_code_callback");
add_action("wp_ajax_nopriv_lokis_save_qr_code", "lokis_save_qr_code_callback");

if (!function_exists('lokis_loop_game_card_content')) {
    function lokis_loop_game_card_content($post_id, $redirect_url)
    {
        $placeholder_image = plugin_dir_url(__FILE__) . 'assets/images/card-placeholder.png';
        ?>

        <div class="lokis-games-card lokis-flex-card">
            <a href="<?php echo $redirect_url; ?>">
                <div class="lokis-game-card-image">
                    <?php if (has_post_thumbnail()) {
                        the_post_thumbnail('full');
                    } else { ?>
                        <img src="<?php echo $placeholder_image ?>" alt="Placeholder Image">
                    <?php } ?>
                </div>
                <div class="lokis-game-card-title">
                    <h3>
                        <?php the_title(); ?>
                    </h3>
                </div>
            </a>
        </div>
        <?php
    }
}


// This function will hide a page for non-logged in users.
if (!function_exists('lokis_hide_page_for_non_logged_in_users')) {
    function lokis_hide_page_for_non_logged_in_users()
    {
        $page_id = get_the_ID();

        // Check if the user is logged in.
        if (!is_user_logged_in()) {
            // Check if the page ID matches the ID of the page we want to hide.
            if ($page_id == 21 || $page_id == 817) {
                // Redirect the user to the host login page.
                wp_redirect(home_url('/game-host-login'));
            }
        } else {
            if (isset($_POST['lokis-redirect'])) {
                $lokis_redirect = $_POST['lokis-redirect'];
                wp_redirect($lokis_redirect);
            } else if ($page_id == 380) {
                // Redirect the user to the host login page.
                wp_redirect(home_url('/host-portal-games'));
            } else if ($page_id == 405 || $page_id == 17 || $page_id == 19) {
                wp_redirect(home_url());
            }
        }
    }
    // Register the function to the template_redirect action hook.
    add_action('template_redirect', 'lokis_hide_page_for_non_logged_in_users');
}




//Function to change reset password email content type
if (!function_exists("lokis_password_reset_content_type")) {
    function lokis_password_reset_content_type($content_type)
    {
        return 'text/plain; charset=UTF-8';
    }
    add_filter('wp_mail_content_type', 'lokis_password_reset_content_type');
}

//Function to change reset password email from email
if (!function_exists("lokis_password_reset_from_mail")) {
    function lokis_password_reset_from_email($from_email)
    {
        return 'noreply-lokisloop@uw.edu';
    }
    add_filter('wp_mail_from', 'lokis_password_reset_from_email');
    add_filter('wp_mail_from_name', function ($from_name) {
        return 'Loki’s Loop';
    });
}

//Function to change reset password email title
if (!function_exists("lokis_password_reset_title")) {
    function lokis_password_reset_title($title)
    {
        $title = "lokisloop.org: Password Reset";
        return $title;
    }
    add_filter('retrieve_password_title', 'lokis_password_reset_title');

}


/*Function to register user*/
if (!function_exists('loki_user_registration')) {
    function loki_user_registration()
    {
        /*Pulling data from registration form*/
        $name = $_POST['name'];
        $email = $_POST['email'];
        $organization_name = $_POST['organization_name'];
        $organization_type = $_POST['organization_type'];
        $country = $_POST['country_name'];
        $zipcode = $_POST['zipcode'];

        if (wp_verify_nonce($_POST['nonce'], -1)) {
            /* Check if the email address is unique */
            if (email_exists($email)) {
                $response = [
                    'status' => 'error',
                    'type' => 'email',
                    'message' => 'The email address ' . $email . ' already exists.'
                ];
                wp_send_json($response);
            }

            /* Check if the email address meets formatting requirements */
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response = [
                    'status' => 'error',
                    'type' => 'email',
                    'message' => 'The email address ' . $email . ' is not valid.'
                ];
                wp_send_json($response);
            }

            //Check country and it's relevant postcode format
            if ($zipcode) {
                if ($country == "United States") {
                    if (!preg_match('/^\d{5}(-\d{4})?$/', $zipcode)) {
                        $response = [
                            'status' => 'error',
                            'message' => 'The zipcode is not a valid US zipcode.'
                        ];
                        wp_send_json($response);
                    }
                } elseif ($country == 'Canada') {
                    if (!preg_match('/^[A-Z]\d[A-Z] ?\d[A-Z]\d$/', $zipcode)) {
                        $response = [
                            'status' => 'error',
                            'message' => 'The zipcode is not a valid Canada zipcode.'
                        ];
                        wp_send_json($response);
                    }
                }
            }

            /*Creating new user*/
            $user_id = wp_create_user($email, wp_generate_password(), $email);

            /*Check for errors when creating new user*/
            if (empty($user_id)) {
                $response = [
                    'status' => 'error',
                    'message' => 'Sorry, user cannot be created.',
                ];
                wp_send_json($response);
            } else {

                // Generate the password reset key for the user.
                $user = get_userdata($user_id);
                $reset_key = get_password_reset_key($user);

                // // Get the password reset URL.
                $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($email), 'login');

                //Email Header
                $headers = array(
                    'From:  noreply-lokisloop@uw.edu' . "\r\n",
                    'Content-Type: text/plain; charset=UTF-8',
                );


                //Email Subject
                $lokis_subject = "lokisloop.org: Complete account set up";

                //Email Body
                $lokis_message = "Welcome to Loki’s Loop!\n\n";
                $lokis_message .= "Your account is almost ready. The last step you need to complete is to set up a password for your new account.\n\n";
                $lokis_message .= "Please click the following link to complete setting up your account:\n";
                $lokis_message .= $reset_url . "\n\n";
                $lokis_message .= "If you have any questions or require assistance, please contact lokisloop@uw.edu\n\n";
                $lokis_message .= "Thank you,\n\n";
                $lokis_message .= "Loki’s Loop";


                wp_mail($email, $lokis_subject, $lokis_message, $headers);


                /*Adding data to user meta*/
                update_user_meta($user_id, 'loki_fullname', $name);
                update_user_meta($user_id, 'loki_organization', $organization_name);
                update_user_meta($user_id, 'loki_organization_type', $organization_type);
                update_user_meta($user_id, 'loki_country', $country);
                update_user_meta($user_id, 'loki_zipcode', $zipcode);

                /*Pulling user data of new user*/
                $user = new WP_User($user_id);

                /* Remove role */
                $user->remove_role('subscriber');

                /* Add role */
                // $user->add_role($role);
                $user->add_role('host');

                $response = [
                    'status' => 'success',
                    'message' => 'User has been created. An email has been sent to set the password',
                ];
                wp_send_json($response);
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'ACCESS DENIED',
            ];
            wp_send_json($response);
        }
    }
    add_action('wp_ajax_loki_user_registration', 'loki_user_registration');
    add_action('wp_ajax_nopriv_loki_user_registration', 'loki_user_registration');
}


/*Function to send activation confirmation email*/
if (!function_exists('loki_send_activation_confirmation_email')) {
    function loki_send_activation_confirmation_email($user)
    {
        $lokis_email_sent = get_user_meta($user->ID, "lokis_password_email_sent", true);

        if (!$lokis_email_sent) {
            $email = $user->user_email;

            //Email Header
            $headers = array(
                'From: noreply-lokisloop@uw.edu' . "\r\n",
                'Content-Type: text/plain; charset=UTF-8',
            );

            //Email Subject
            $subject = "lokisloop.org: Account Activation Confirmation";

            //Email Body
            $message = "Welcome aboard!\n\n";
            $message .= "This is a confirmation that your account is active.\n\n";
            $message .= "To receive email updates on the latest games on Loki’s Loop in the future, consider joining our Loki’s Loop mailing list: https://forms.gle/3rpm5e1uUmsLHduLA \n\n";
            $message .= "For inquiries, please contact lokisloop@uw.edu\n\n";
            $message .= "We hope you enjoy Loki’s Loop offerings!\n\n";
            $message .= "Thank you,\n";
            $message .= "Loki’s Loop";

            // Send the activation confirmation email
            wp_mail($email, $subject, $message, $headers);

            update_user_meta($user->ID, "lokis_password_email_sent", true);
        }
    }
    add_action('password_reset', 'loki_send_activation_confirmation_email', 10, 2);
}

//Function to change reset password email message
if (!function_exists("lokis_password_reset_message")) {
    function lokis_password_reset_message($message, $key, $user_login, $user_data)
    {
        // Get the password reset URL.
        $reset_key = get_password_reset_key($user_data);
        $reset_url = network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user_login), 'login');

        // Customize the email message content here
        $message = "Hi!\n\n";
        $message .= "Please click the following link to reset your password:\n";
        $message .= $reset_url . "\n\n";
        $message .= "If you have any questions or require assistance, please contact lokisloop@uw.edu\n\n";
        $message .= "Thank you,\n\n";
        $message .= "Loki’s Loop";

        return $message; // Make sure to return the modified message.
    }
    add_filter('retrieve_password_message', 'lokis_password_reset_message', 10, 4);
}