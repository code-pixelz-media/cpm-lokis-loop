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
        $dashboard_page_id = (get_option('lokis_setting'))['dashboard'];
        $lokis_dashboard_page = get_permalink($dashboard_page_id);
        $session_id = lokis_getSessionIDFromURL();
        $results = $wpdb->get_results("SELECT expires_in FROM $lokis_host_table_name WHERE session_id = '{$session_id}'");

        if ($results) {
            foreach ($results as $row) {
                $expiration_time = $row->expires_in;
                if ($current_time >= $expiration_time) {
                    if (empty($lokis_dashboard_page)) {
                        // Redirect to the home page
                        wp_redirect(site_url());
                        exit;
                    } else {
                        // Redirect to the dashboard page
                        echo "<script>window.location.href = '{$lokis_dashboard_page}';</script>";
                        exit();
                    }
                }
            }
        }
    }
    add_action('wp_head', 'lokis_redirect_after_expiration', 10);
}

/*Stores session ID of game*/
if (!function_exists('lokis_store_session_id')) {
    function lokis_store_session_id()
    {
        if (is_single() && get_post_type() === 'games') {
            global $wpdb;

            $session_id = lokis_getSessionIDFromCookie(); // Retrieve session ID from the cookie

            // Check if the session ID exists in the database
            $existing_Sessionid_entry = $wpdb->get_var(
                $wpdb->prepare(
                    "SELECT COUNT(*) FROM {$wpdb->prefix}lokis_game_sessions WHERE session_id = %s",
                    $session_id
                )
            );

            if ($existing_Sessionid_entry == 0) {
                // Invalid session, handle accordingly
                echo "<script>alert('Invalid session. Please contact the host.');</script>";
                echo "<script>window.location.href = '" . site_url() . "';</script>";
            } else {
                // Store the site URL in the cookie
                setcookie('lokis_site_url', get_permalink(get_the_ID()), time() + (86400 * 30), '/'); // Set the cookie for 30 days
            }
        }
    }
    add_action('wp_head', 'lokis_store_session_id', 20);
}

/*Adds metabox to change page visibility according to user*/
if (!function_exists('lokis_add_page_metabox')) {
    // Add the metabox to the "Add New Page" screen
    function lokis_add_page_metabox()
    {
        add_meta_box("custom_checkbox_metabox", "Page Visibility", "lokis_render_checkbox_metabox", "page", "side", "core");
    }
    add_action("add_meta_boxes_page", "lokis_add_page_metabox");
}

/*Adds metabox to change page visibility according to the user*/
if (!function_exists('lokis_render_checkbox_metabox')) {
    // Render the metabox HTML
    function lokis_render_checkbox_metabox($post)
    {
        // Retrieve the current value of the meta field
        $checked = get_post_meta($post->ID, "lokis_private_page_checkbox", true);

        // Output the checkbox
        echo '<label>';
        echo '<input type="checkbox" name="lokis_private_page_checkbox" value="1" ' . checked($checked, 1, false) . ' />';
        echo 'Only to Admin and Host';
        echo '</label>';
    }
}

/*Function to save the data from the metabox*/
if (!function_exists('lokis_save_page_checkbox')) {
    // Save the checkbox value when the page is saved
    function lokis_save_page_checkbox($post_id)
    {
        // Update the meta field with the new value
        if (isset($_POST['lokis_private_page_checkbox'])) {
            $value = 1;
        } else {
            $value = 0;
        }
        update_post_meta($post_id, "lokis_private_page_checkbox", $value);
    }
    add_action("save_post_page", "lokis_save_page_checkbox");
}