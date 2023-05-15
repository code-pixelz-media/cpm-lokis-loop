<?php
/*
Plugin Name: Lokis Loop
Plugin URI: https://codepixelzmedia.com/
Description: Integrates gDevelop iframes and lets people host games and play them.
Version: 1.0.0
Author: Codepixelzmedia
Author URI: https://codepixelzmedia.com/
Text Domain: lokis-loop
*/
if (!defined('ABSPATH')) {
    exit;
}
//Plugin Version
define('CPM_LOKIS_VERSION', '1.0.0');


//Loads admin main loader file for pluigin
require_once('lokis-admin/lokis-admin-loader.php');
//Loads public main loader file
require_once('lokis-public/lokis-public-loader.php');

// Creates custom tables named lokis_game_sessions and lokis_player_sessions
if (!function_exists('lokis_create_tables')) {
function lokis_create_tables() {
    global $wpdb;

    $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions';
    $lokis_player_sessions_table_name = $wpdb->prefix . 'lokis_player_sessions';

    $lokis_game_sessions_sql = "CREATE TABLE IF NOT EXISTS $lokis_game_sessions_table_name (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        host_id INT NOT NULL,
        game_id INT NOT NULL,
        session_id VARCHAR(18) NOT NULL,
        expires_in DATETIME NOT NULL,
        started_at DATETIME NOT NULL,
        gamesession_url VARCHAR(255) NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    $lokis_player_sessions_sql = "CREATE TABLE IF NOT EXISTS $lokis_player_sessions_table_name (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        player_id INT NOT NULL,
        session_id VARCHAR(18) NOT NULL,
        step INT NOT NULL,
        completed TINYINT(1) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    dbDelta($lokis_game_sessions_sql);
    dbDelta($lokis_player_sessions_sql);
}
register_activation_hook(__FILE__, 'lokis_create_tables');
}