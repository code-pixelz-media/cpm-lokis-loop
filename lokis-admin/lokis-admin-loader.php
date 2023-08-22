<?php

/* The above code is checking if the constant 'ABSPATH' is not defined and if so, it exits the current
script. */
if (!defined('ABSPATH')) {
    exit;
}

/* Enqueuing the scripts and styles for the plugin  */
function cpm_lokis_admin_scripts()
{
    /* css for plugin  */
    wp_enqueue_style('cpm-lokis-admin-style', plugin_dir_url(__FILE__) . 'assets/css/lokis-admin-style.css', array(), false, 'all');
    /* js for plugin  */
    wp_enqueue_script('cpm-lokis-admin-js', plugin_dir_url(__FILE__) . 'assets/js/lokis-admin-scripts.js', array('jquery'), '1.0.0', true);
}
add_action('admin_enqueue_scripts', 'cpm_lokis_admin_scripts');

/*Loads admin files*/
require_once('inc/cpm-lokis-admin-cpt.php');
require_once('inc/cpm-lokis-loop-custom-shortcodes.php');
require_once('inc/lokis-games-settings.php');

/*update post meta "loki_player_count" to show how many players played the game*/
if (!function_exists('loki_players_count')) {
    function loki_players_count()
    {
        $post_id = get_the_ID();
        $user_id = get_current_user_id();
        $players = get_post_meta($post_id, 'loki_player_count', true);

        if (get_post_type() === 'games') {
            $post_id = get_the_ID();
            $user_id = get_current_user_id();
            $players = get_post_meta($post_id, 'loki_player_count', true);
            $allowed_roles = array('host', 'player');
            $user = wp_get_current_user();

            ini_set('display_errors', 0);
            if (in_array($user->roles[0], $allowed_roles)) {
                if (!is_array($players)) {
                    $players = array();
                }

                if (!in_array($user_id, $players)) {
                    $players[] = $user_id;
                    update_post_meta($post_id, 'loki_player_count', $players);
                }
            }
        }
    }
    add_action('wp_head', 'loki_players_count');
}

/*Add capabilities to administrator and make host and player roles with no capabilities*/
if (!function_exists('loki_add_and_modify_roles')) {
    function loki_add_and_modify_roles()
    {
        //Editing administrator role
        $role = get_role('administrator');
        // Add capability of custom post type for administrator
        $role->add_cap('edit_others_games');
        $role->add_cap('delete_others_games');
        $role->add_cap('edit_private_games');
        $role->add_cap('read_private_games');
        $role->add_cap('edit_published_games');
        $role->add_cap('publish_games');
        $role->add_cap('delete_published_games');
        $role->add_cap('edit_games');
        $role->add_cap('delete_games');
        $role->add_cap('edit_game');
        $role->add_cap('read_game');
        $role->add_cap('delete_game');
        $role->add_cap('create_games');

        //Adding Host and Player roles
        add_role('host', 'Host');
        add_role('player', 'Player');
    }
    add_action('init', 'loki_add_and_modify_roles');
}

/*Pull session ID from URL*/
if (!function_exists('lokis_getSessionIDFromURL')) {
    function lokis_getSessionIDFromURL()
    {
        $url = $_SERVER['REQUEST_URI'];
        $query_params = parse_url($url, PHP_URL_QUERY);

        if ($query_params !== null) {
            parse_str($query_params, $params);
            $session_id = isset($params['game']) ? $params['game'] : '';
            if ($session_id === '') {
                $session_id = isset($params['offlinegames']) ? $params['offlinegames'] : '';
            }
        } else {
            $session_id = '';
        }
        return $session_id;
    }
}

/*Redirects user after session expiration*/
if (!function_exists('lokis_redirect_after_expiration')) {
    function lokis_redirect_after_expiration()
    {
        global $wpdb;
        // Check if the session has expired
        $lokis_host_table_name = $wpdb->prefix . 'lokis_game_sessions';
        $current_time = date('Y-m-d H:i:s');
        $session_id = lokis_getSessionIDFromURL();
        $results = $wpdb->get_results("SELECT expires_in FROM $lokis_host_table_name WHERE session_id = '{$session_id}'");

        if ($results) {
            foreach ($results as $row) {
                $expiration_time = $row->expires_in;
                if ($current_time >= $expiration_time) {
                    // Redirect to the dashboard page
                    echo "<script>alert('Session expired. Please contact the host.');</script>";
                    echo "<script>window.location.href = '" . site_url() . "';</script>";
                    exit();

                }
            }
        }
    }
    add_action('wp_head', 'lokis_redirect_after_expiration', 10);
}

/*Check session ID of game*/
if (!function_exists('lokis_check_session_id')) {
    function lokis_check_session_id()
    {
        if (is_single() && get_post_type() === 'games' && get_post_type() === 'offlinegames') {
            global $wpdb;
            $session_id = lokis_getSessionIDFromURL(); // Retrieve session ID from the cookie
            $lokis_host_table_name = $wpdb->prefix . 'lokis_game_sessions';
            // Check if the session ID exists in the database
            $existing_Sessionid_entry = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM $lokis_host_table_name WHERE session_id = %s",
                    $session_id
                )
            );
            if (isset($_GET['game']) || isset($_GET['offlinegames'])) {

                if ($existing_Sessionid_entry == 0) {
                    // Invalid session, handle accordingly
                    echo "<script>alert('Invalid session. Please contact the host.');</script>";
                    echo "<script>window.location.href = '" . site_url() . "';</script>";
                }
            }
        }
    }
    add_action('wp_head', 'lokis_check_session_id', 20);
}