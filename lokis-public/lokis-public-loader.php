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
}
add_action('wp_enqueue_scripts', 'cpm_lokis_public_scripts');
