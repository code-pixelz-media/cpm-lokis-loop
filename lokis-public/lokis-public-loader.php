<?php

if (!defined('ABSPATH')) {
    exit;
}
/* Enqueuing the scripts and styles for the plugin on frontend  */
function cpm_lokis_public_scripts()
{
    /* css for plugin  */
    wp_enqueue_style('cpm-lokis-public-js', plugin_dir_url(__FILE__) . 'assets/css/lokis-public-style.css', array(), false, 'all');
    /* js for plugin  */
    wp_enqueue_script('cpm-lokis-public-js', plugin_dir_url(__FILE__) . 'assets/js/lokis-public-scripts.js', array('jquery'), '1.0.0', true);

    wp_enqueue_style('lokis-fontawesome-css', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css', array(), false, 'all');

    wp_localize_script('cpm-lokis-public-js', 'gamesajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cpm_lokis_public_scripts');

/*Loads public files*/
require_once('inc/dashboard/lokis-user-dashboard.php');

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
        $correct_answer = strtolower(get_post_meta($post_id, 'lokis_loop_correct_answer', true));
        $redirect_uri = get_post_meta($post_id, 'lokis_loop_redirect_uri', true);

        if ($answer == $correct_answer) {
            $response = array(
                'status' => 'success',
                'redirect' => $redirect_uri,
                'message' => 'correct'
            );

        } else {
            $response = array(
                'status' => 'error',
                'message' => 'Incorrect answer'
            );
        }

        wp_send_json($response);

    }
    add_action('wp_ajax_lokis_check_answer', 'lokis_check_answer');
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
        $username = str_replace(' ', '', $name);
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

                $user = new WP_User($user_id);

                /* Remove role */
                $user->remove_role('subscriber');

                /* Add role */
                $user->add_role($role);

                $response = [
                    'status' => 'success',
                    'message' => 'User has been created',
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

/*restricts user access to wp-admin*/
if (!function_exists('lokis_restrict_access')) {
    function lokis_restrict_access()
    {
        /* Check if the user is not an administrator*/
        if (!current_user_can('administrator') && (is_admin())) {
            /* Redirect them to the homepage or any other page*/
            wp_redirect(home_url());
            exit;
        }
    }
    add_action('admin_init', 'lokis_restrict_access');
}

if (!function_exists('lokis_endpoints')) {
    function lokis_endpoints()
    {
        global $lokis_endpoints;
        global $lokis_endpoint_name;

        $lokis_endpoints = array();
        $lokis_endpoint_name = array();

        // Add endpoints to the array using array_push() and make endpoints
        //Match with file name. Eg. lokis-(endpoint).php
        array_push($lokis_endpoints, 'host-game');
        array_push($lokis_endpoint_name, 'Host a Game');

        array_push($lokis_endpoints, 'hosted-game');
        array_push($lokis_endpoint_name, 'Hosted Games');


        // Register the endpoints
        foreach ($lokis_endpoints as $endpoint) {
            add_rewrite_endpoint($endpoint, EP_PAGES);
        }


        flush_rewrite_rules();
    }
    add_action('init', 'lokis_endpoints');
}

if (!function_exists('load_custom_endpoint_template')) {
    function load_custom_endpoint_template($template)
    {
        global $wp_query;
        global $lokis_endpoints;

        foreach ($lokis_endpoints as $endpoint) {
            $is_endpoint = isset($wp_query->query_vars[$endpoint]);

            if ($is_endpoint) {
                $template = locate_template('inc/dashboard/lokis-' . $endpoint . '.php');
                if (!$template) {
                    $template = plugin_dir_path(__FILE__) . 'inc/dashboard/lokis-' . $endpoint . '.php';
                }
            }

            return $template;
        }

    }
    add_filter('template_include', 'load_custom_endpoint_template');
}

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

if (!function_exists('lokis_account_menu')) {
    function lokis_account_menu()
    {
        global $lokis_url;
        global $lokis_endpoint_name;

        echo '
         <div class="lokisloop-dashboard-menu">
                <ul class="lokisloop-menu">

                    <li><a href="#"> <i class="fa-regular fa-user"></i>
                            <span class="nav-item">Profile </span>
                        </a>
                    </li>';

        $length = count($lokis_endpoint_name);
        for ($index = 0; $index < $length; $index++) {
            $link_name = $lokis_endpoint_name[$index];
            $link_url = $lokis_url[$index];
            ?>
            <li><a href="<?php echo $link_url; ?>">
                    <i class="fa-regular fa-chart-bar"></i>
                    <span class="nav-item">
                        <?php echo $link_name; ?>
                    </span>
                </a>
            </li>
        <?php }

        echo '

                    <li><a herf="#">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span class="nav-item">LogOut</span>
                        </a>
                    </li>

                </ul>
            </div>
        ';
    }
}