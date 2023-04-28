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

//Loads single post template for custom post type of games
function lokis_loop_single_post_template($single_template) {
    global $post;

    if ($post->post_type == 'games') {
        $single_template = plugin_dir_path(__FILE__) . '/lokis-public/inc/single-games.php';
    }

    return $single_template;
}
add_filter('single_template', 'lokis_loop_single_post_template');