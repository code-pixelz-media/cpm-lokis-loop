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

/*Store hosts game id in user meta*/
if (!function_exists('loki_store_games')) {
    function loki_store_games()
    {
        $user = wp_get_current_user();
        $user_id = $user->ID;
        $user_meta = get_userdata($user_id);

        if (current_user_can('administrator')) {
            return;
        }
            $user_roles = $user_meta->roles;
        

            if (in_array("author", $user_roles)) {

                // Retrieve all posts of post type 'games' authored by the current user
                $args = array(
                    'post_type' => 'games',
                    'author' => $user_id,
                    'posts_per_page' => -1,
                );
                $query = new WP_Query($args);

                // Check if the user meta 'lokis_hosts_games' is an array
                $games = get_user_meta($user_id, 'loki_hosts_games', true);
                if (!is_array($games)) {
                    $games = array();
                }

                // Store the IDs of the 'games' posts in the 'lokis_hosts_games' user meta
                if ($query->have_posts()) {
                    while ($query->have_posts()) {
                        $query->the_post();
                        $game_id = get_the_ID();
                        if (!in_array($game_id, $games)) {
                            $games[] = $game_id;
                        }
                    }
                    wp_reset_postdata();
                }

                update_user_meta($user_id, 'loki_hosts_games', $games);
            }
    }
    add_action('save_post', 'loki_store_games');
}