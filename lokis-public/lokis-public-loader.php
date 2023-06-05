<?php



if (!defined('ABSPATH')) {
    exit;
}

/* Enqueuing the scripts and styles for the plugin on frontend  */

/* Steps to make a custom endpoint in my accounts page:

   1. Find the lokis_endpoints()

   2. Add endpoints to the array loki_endpoints using array_push() and make endpoints

   3. Match the endpoint with file name for one of the proceeding functions to pull the template. Eg. lokis-(endpoint).php

   4. Add the tab name of the endpoint needed to be shown in loki_endpoint_name using array_push()

   5. Add the full icon class using array_push() in loki_account_icons

*/



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
        $player_id = $_POST['current_user_id'];
        $correct_answer = strtolower(get_post_meta($post_id, 'lokis_loop_correct_answer', true));
        $redirect_uri = get_post_meta($post_id, 'lokis_loop_redirect_uri', true);
        $thankyou_page_id = (get_option('lokis_setting'))['thankyou'];
        $lokis_thankyou_page = get_permalink($thankyou_page_id);

        if ($answer == $correct_answer) {
            if ($redirect_uri == $lokis_thankyou_page) {
                global $wpdb;
                $lokis_player_table_name = $wpdb->prefix . 'lokis_player_sessions';
                $sql = "UPDATE $lokis_player_table_name SET completed = '1'  WHERE player_id = '$player_id' AND session_id = '$session_id'";
                $wpdb->query($sql);

                $response = array(
                    'status' => 'success',
                    'redirect' => $redirect_uri,
                    'message' => 'correct'
                );
            } else {
                $response = array(
                    'status' => 'success',
                    'redirect' => $redirect_uri . '/?game=' . $session_id,
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

/*Adding function to check answer of offline single page  given by ajax post with database correct answer*/
if (!function_exists('lokis_offline_check_answer')) {
    function lokis_offline_check_answer()
    {
        /* Pulling data from Ajax and post meta table */
        $post_id = $_POST['post_id'];
        $answer = strtolower($_POST['answer']);
        $session_id = $_POST['session_id'];
        $player_id = $_POST['current_user_id'];
        $correct_answer = strtolower(get_post_meta($post_id, 'lokis_loop_correct_answer', true));
        $redirect_uri = get_post_meta($post_id, 'lokis_loop_redirect_uri', true);
        $thankyou_page_id = (get_option('lokis_setting'))['thankyou'];
        $lokis_thankyou_page = get_permalink($thankyou_page_id);

        if ($answer == $correct_answer) {
            if ($redirect_uri == $lokis_thankyou_page) {
                global $wpdb;
                $lokis_player_table_name = $wpdb->prefix . 'lokis_player_sessions';
                $sql = "UPDATE $lokis_player_table_name SET completed = '1'  WHERE player_id = '$player_id' AND session_id = '$session_id'";
                $wpdb->query($sql);

                $response = array(
                    'status' => 'success',
                    'redirect' => $redirect_uri,
                    'message' => 'correct'
                );
            } else {
                $response = array(
                    'status' => 'success',
                    // 'redirect' => $redirect_uri . '/?offlinegame',
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
    add_action('wp_ajax_lokis_offline_check_answer', 'lokis_offline_check_answer');
    add_action('wp_ajax_nopriv_lokis_offline_check_answer', 'lokis_offline_check_answer');
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
        $role = $_POST['role'];
        $zipcode = $_POST['zipcode'];

        /*Creating username by removing spaces from name*/
        $username = str_replace(" ", "", $name);

        if (wp_verify_nonce($_POST['nonce'], -1)) {
            /* Check if the username and email address are unique */
            if (username_exists($username)) {
                $response = [
                    'status' => 'error',
                    'message' => 'The username ' . $name . ' already exists.'
                ];
                wp_send_json($response);
            }

            if (email_exists($email)) {
                $response = [
                    'status' => 'error',
                    'message' => 'The email address ' . $email . ' already exists.'
                ];
                wp_send_json($response);
            }

            /* Check if the username and email address meet formatting requirements */
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $response = [
                    'status' => 'error',
                    'message' => 'The username ' . $name . ' is not valid.'
                ];
                wp_send_json($response);
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $response = [
                    'status' => 'error',
                    'message' => 'The email address ' . $email . ' is not valid.'
                ];
                wp_send_json($response);
            }

            /*Creating new user*/
            $user_id = register_new_user($username, $email);

            /*Check for errors when creating new user*/
            if (empty($user_id)) {
                $response = [
                    'status' => 'error',
                    'message' => 'Sorry, user cannot be created.',
                ];
                wp_send_json($response);
            } else {
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
                $user->add_role($role);

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

/*displays shortcode on selected page*/
if (!function_exists('lokis_shortcode_display')) {
    function lokis_shortcode_display($content)
    {
        /* Display Registration form */
        if (isset((get_option('lokis_setting'))['register'])) {
            $register = (get_option('lokis_setting'))['register'];
        }

        if (!empty($register)) {
            if (get_the_ID() === (int) $register) {
                $content .= do_shortcode('[lokis_loop_register_form]');
                return $content;
            }
        }

        /* Display Dashboard */
        if (isset((get_option('lokis_setting'))['dashboard'])) {
            $dashboard = (get_option('lokis_setting'))['dashboard'];
        }

        if (!empty($dashboard)) {
            if (get_the_ID() === (int) $dashboard) {
                $content .= do_shortcode('[lokis_loop_user_dashboard]');
                return $content;
            }
        }

        /*Display Login Page*/
        if (isset((get_option('lokis_setting'))['login'])) {
            $login = (get_option('lokis_setting'))['login'];
        }

        if (!empty($login)) {
            if (get_the_ID() === (int) $login) {
                $content .= do_shortcode('[lokis_loop_login]');
                return $content;
            }
        }
    }
    add_action('the_content', 'lokis_shortcode_display');
}

/*updates user meta 'last_visited'*/
if (!function_exists('lokis_last_visited')) {
    function lokis_last_visited()
    {
        $post_id = get_the_ID();
        $user_id = get_current_user_id();
        $last_visited = get_user_meta($user_id, 'lokis_last_visited', true);

        if ($post_id && get_post_type($post_id) === 'games') {
            update_user_meta($user_id, 'lokis_last_visited', $post_id, $last_visited);
        }
    }
    add_action('wp_head', 'lokis_last_visited');
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
        if (current_user_can('host')) {

            /*Host Game Endpoint*/
            array_push($lokis_endpoints, 'host-game');
            array_push($lokis_endpoint_name, 'Host a Game');
            array_push($lokis_account_icons, 'fa-regular fa-chart-bar');

            /*Current Games Endpoint*/
            array_push($lokis_endpoints, 'current-games');
            array_push($lokis_endpoint_name, 'Current Games');
            array_push($lokis_account_icons, 'fa-solid fa-list-check');

            /*Expired Games Endpoint*/
            array_push($lokis_endpoints, 'expired-games');
            array_push($lokis_endpoint_name, 'Expired Games');
            array_push($lokis_account_icons, 'fa-solid fa-triangle-exclamation');

            // Register the endpoints
            foreach ($lokis_endpoints as $endpoint) {
                add_rewrite_endpoint($endpoint, EP_PAGES);
            }
            flush_rewrite_rules();

        }
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
                if (current_user_can('host')) {
                    $loki_dashboard_template = locate_template('inc/dashboard/lokis-' . $endpoint . '.php');
                    if (!$loki_dashboard_template) {
                        $loki_dashboard_template = plugin_dir_path(__FILE__) . 'inc/dashboard/lokis-' . $endpoint . '.php';
                    }
                }
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

        echo '
         <div class="lokisloop-dashboard-menu">
                <ul class="lokisloop-menu">
                    <li><a href="' . get_permalink($dashboard) . '"> <i class="fa-regular fa-user"></i>
                            <span class="nav-item">Profile </span>
                        </a>
                    </li>';
        $length = count($lokis_endpoint_name);
        for ($index = 0; $index < $length; $index++) {
            $link_name = $lokis_endpoint_name[$index];
            $link_url = $lokis_url[$index];
            $link_icon = $lokis_account_icons[$index];
            ?>
            <li><a href="<?php echo $link_url; ?>">
                    <i class="<?php echo $link_icon; ?>"></i>
                    <span class="nav-item">
                        <?php echo $link_name; ?>
                    </span>
                </a>
            </li>
        <?php }
        ;

        if (current_user_can('host') || current_user_can('administrator')) {

            $private_pages = new WP_Query(
                array(
                    'meta_key' => 'lokis_private_page_checkbox',
                    'meta_value' => '1',
                    'post_type' => 'page',
                    'post_status' => 'publish',
                    'posts_per_page' => -1
                )
            );
            foreach ($private_pages->posts as $post) {
                $link_name = get_the_title($post->ID);
                $link_url = get_permalink($post->ID);
                echo '<li><a href="' . $link_url . '">';
                echo '<i class="fa-regular fa-file-lines"></i>';
                echo '<span class="nav-item">' . $link_name . '</span>';
                echo '</a></li>';
            }
        }

        echo '<li><a href="' . wp_logout_url() . '"> <i class="fa-solid fa-arrow-right-from-bracket"></i>
             <span class="nav-item">LogOut</span></a></li></ul></div>';
    }
}

/* view players modal box*/
if (!function_exists('lokis_loop_modal_box')) {
    function lokis_loop_modal_box()
    {
        global $wpdb;
        $get_game_id = $_POST['game_id'];
        $host_session_id = $_POST['session_id'];

        // Query the custom table to fetch user IDs matching the session ID
        $table_name = $wpdb->prefix . 'lokis_player_sessions';
        $results = $wpdb->get_results("SELECT player_id,completed FROM $table_name WHERE session_id = '{$host_session_id}'");
        ?>

        <!-- The Modal -->
        <svg display="none" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
            width="768" height="800" viewBox="0 0 768 800">
            <defs>
                <g id="icon-close">
                    <path class="path1"
                        d="M31.708 25.708c-0-0-0-0-0-0l-9.708-9.708 9.708-9.708c0-0 0-0 0-0 0.105-0.105 0.18-0.227 0.229-0.357 0.133-0.356 0.057-0.771-0.229-1.057l-4.586-4.586c-0.286-0.286-0.702-0.361-1.057-0.229-0.13 0.048-0.252 0.124-0.357 0.228 0 0-0 0-0 0l-9.708 9.708-9.708-9.708c-0-0-0-0-0-0-0.105-0.104-0.227-0.18-0.357-0.228-0.356-0.133-0.771-0.057-1.057 0.229l-4.586 4.586c-0.286 0.286-0.361 0.702-0.229 1.057 0.049 0.13 0.124 0.252 0.229 0.357 0 0 0 0 0 0l9.708 9.708-9.708 9.708c-0 0-0 0-0 0-0.104 0.105-0.18 0.227-0.229 0.357-0.133 0.355-0.057 0.771 0.229 1.057l4.586 4.586c0.286 0.286 0.702 0.361 1.057 0.229 0.13-0.049 0.252-0.124 0.357-0.229 0-0 0-0 0-0l9.708-9.708 9.708 9.708c0 0 0 0 0 0 0.105 0.105 0.227 0.18 0.357 0.229 0.356 0.133 0.771 0.057 1.057-0.229l4.586-4.586c0.286-0.286 0.362-0.702 0.229-1.057-0.049-0.13-0.124-0.252-0.229-0.357z">
                    </path>
                </g>
            </defs>
        </svg>

        <!-- Modal content -->
        <div class="lokis-modal-box">
            <div class="lokis-modal-overlay lokis-modal-toggle"></div>
            <div class="lokis-modal-wrapper lokis-modal-transition">
                <div class="lokis-modal-header">
                    <button class="lokis-modal-close lokis-modal-toggle"><svg class="icon-close cpm-lokis-icon"
                            viewBox="0 0 32 32">
                            <use xlink:href="#icon-close"></use>
                        </svg></button>
                    <h2 class="lokis-modal-heading">Game:
                        <?php echo get_the_title($get_game_id); ?>
                    </h2>
                </div>

                <div class="lokis-modal-body">
                    <div class="lokis-modal-content">
                        <p>
                            <?php
                            if ($results) {
                                foreach ($results as $row) {
                                    $user_id = $row->player_id;
                                    $gameStatus = $row->completed;

                                    if ($gameStatus == 1) {
                                        $gameStatus = '<i class="fa fa-circle lokis-game-status-complete"></i>Completed';
                                    } else {
                                        $gameStatus = '<i class="fa fa-circle lokis-game-status-incomplete"></i>Incomplete';
                                    }

                                    $avatar = get_avatar($user_id);
                                    $user_info = get_userdata($user_id);
                                    if ($user_info) {
                                        /* displays user name */
                                        $player_full_name = get_user_meta($user_id, 'loki_fullname', true);
                                        $player_email_address = $user_info->user_email;
                                        // $user_name = $user_info->display_name;
                                        if (strpos($avatar, 'gravatar.com') !== false) {
                                            echo '<div class="lokis-user-modal-wrapper">';
                                            echo '<div class="lokis-user-details-wrapper">';
                                            echo '<div class="lokis-user-avatar">' . $avatar . '</div>';
                                            echo '<div class="lokis-modal-details">';
                                            echo '<div class="lokis-modal-fullName">' . $player_full_name . '</div>';
                                            echo '<div class="lokis-modal-userEmail">' . $player_email_address . '</div>';
                                            echo '</div></div>';
                                            echo '<div class="lokis-modal-game-status">Game Status:' . $gameStatus . '</div>';
                                            echo '</div>';
                                        } else {
                                            // Avatar doesn't exist, display a placeholder image
                                            $placeholder_image_url = plugin_dir_url(__FILE__) . 'assets/images/placeholder.png';
                                            echo '<img src="' . $placeholder_image_url . '" alt="Placeholder-Image">' . $player_full_name;
                                        }
                                    }
                                }
                            } else {
                                echo "<div class='lokis-user-not-found'><p>No user found.</p></div>";
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <?php
        die();
    }
    add_action('wp_ajax_lokis_loop_modal_box', 'lokis_loop_modal_box');
}

/*function to update user info from profile page*/
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
            } else {
                $response = [
                    'status' => 'error',
                    'message' => "Please enter the password"
                ];
                wp_send_json($response);
            }
            update_user_meta(get_current_user_id(), 'loki_organization', $organization_name);
            update_user_meta(get_current_user_id(), 'loki_organization_type', $organization_type);

            if ($new_password !== "") {
                wp_set_password($new_password, get_current_user_id());
                $response = [
                    'status' => 'success',
                    'message' => 'The profile and password has been updated. Please refresh the page and log back in',
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

/*function to delete data from game session table in  database*/
if (!function_exists('lokis_Delete_game_table_data')) {
    function lokis_Delete_game_table_data()
    {
        global $wpdb;
        $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions';
        $lokis_game_players_table_name = $wpdb->prefix . 'lokis_player_sessions';
        if (isset($_POST['delete_session_data'])) {
            $id = $_POST['delete_session_data'];
            $session_id = $_POST['lokis_session_id'];
            // Perform the deletion query using the game ID
            $wpdb->delete($lokis_game_sessions_table_name, ['id' => $id]);
            $wpdb->delete($lokis_game_players_table_name, ['session_id' => $session_id]);
            // Redirect to the same page to update the displayed data
            echo '<script>window.location.href = window.location.href;</script>';
        }
    }
}

/*function to delete data from player session table in  database*/
if (!function_exists('lokis_delete_player_table_data')) {
    function lokis_delete_player_table_data()
    {
        global $wpdb;
        $lokis_game_players_table_name = $wpdb->prefix . 'lokis_player_sessions';
        if (isset($_POST['delete_player_data'])) {
            $id = $_POST['delete_player_data'];
            // Perform the deletion query using the player ID
            $wpdb->delete($lokis_game_players_table_name, ['id' => $id]);
            // Redirect to the same page to update the displayed data
            echo '<script>window.location.href = window.location.href;</script>';

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

//Lightbox for cookies consent
function lokis_cookies_content_popup()
{
    if (!isset($_COOKIE['loki_user_id']) && !isset($_COOKIE['consent'])) {
        ?>
    <div id="lokisCookieConsent" class="lokis-cookie-consent">
        <div class="lokis-cookie-heading">
            <i class="fa-solid fa-cookie-bite"></i>Cookies Consent
        </div>
        <div class="lokis-cookie-message">
            This website uses cookies to ensure you get the best experience on our website.
        </div>
        <div class="lokis-cookie-buttons">
            <button class="lokis-cookie-btn lokis-cookie-accept" id='loki-cookie-accept'>Accept</button>
            <button class="lokis-cookie-btn lokis-cookie-reject" id='loki-cookie-reject'>Reject</button>
        </div>
    </div>
<?
    }
}