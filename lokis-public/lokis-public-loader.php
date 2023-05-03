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