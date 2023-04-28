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
