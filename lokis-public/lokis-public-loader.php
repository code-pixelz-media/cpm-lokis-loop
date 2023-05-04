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
    wp_localize_script('cpm-lokis-public-js', 'gamesajax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
add_action('wp_enqueue_scripts', 'cpm_lokis_public_scripts');

/*Loads public files*/
require_once('inc/lokis-user-dashboard.php');


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
;

/*Adding function to check answer of given by ajax post with database correct answer*/
if (!function_exists('lokis_check_answer')) {
    function lokis_check_answer()
    {
        //Pulling data from Ajax and post meta table
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
;

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

            // Remove role
            $user->remove_role('subscriber');

            // Add role
            $user->add_role($role);

            $response = [
                'status' => 'success',
                'message' => 'User has been created',
            ];
            wp_send_json($response);
        }
    }
    add_action('wp_ajax_loki_user_registration', 'loki_user_registration');
    add_action('wp_ajax_nopriv_loki_user_registration', 'loki_user_registration');
}
;

/*displays shortcode on selected page*/
if (!function_exists('lokis_shortcode_display')) {
    function lokis_shortcode_display($content)
    {
        $register = (get_option('lokis_setting'))['register'];
        if (get_the_ID() === (int) $register) {
            $content .= do_shortcode('[lokis_loop_register_form]');
        }
        return $content;
    }
    add_action('the_content', 'lokis_shortcode_display');
}
;

/*updates user meta 'visited_pages'*/
if (!function_exists('lokis_visited_pages')) {
    function lokis_visited_pages()
    {
        $post_id = get_the_ID();
        $user_id = get_current_user_id();

        $visited_pages = get_user_meta($user_id, 'visited_pages', true);
        if (!is_array($visited_pages)) {
            $visited_pages = array();
        }

        if ($post_id && get_post_type($post_id) === 'games') {

            if (!in_array($post_id, $visited_pages)) {
                $visited_pages[] = $post_id;
            }
            update_user_meta($user_id, 'visited_pages', $visited_pages);
        }
    }
    add_action('wp_head', 'lokis_visited_pages');
}
;

/*updates user meta 'visited_pages'*/
if (!function_exists('lokis_restrict_access')) {
    function lokis_restrict_access()
    {
        // Check if the user is not an administrator or author
        if (!current_user_can('administrator') && (is_admin())) {
            // Redirect them to the homepage or any other page
            wp_redirect(home_url());
            exit;
        }
    }
    add_action('admin_init', 'lokis_restrict_access');
}
;