<?php

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

            $allowed_roles = array('author', 'subscriber');
            $user = wp_get_current_user();
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

function loki_redirect_after_login($redirect_to, $user)
{
    // Modify the redirect URL as per your requirements
    $redirect_url = home_url().'/user-dashboard';

    // Redirect the user
    wp_redirect($redirect_url);
    exit;
}
add_action('wp_login', 'loki_redirect_after_login', 10, 3);