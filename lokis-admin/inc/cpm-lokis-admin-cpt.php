<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registers a custom post type ''.
 *
 * @since 1.0.0
 *
 * @return void
 */

 //Create game_info class
 class lokis_loop_game_info
 {

     //call functions
     public function __construct()
     {
         add_action('init', array($this, 'create_post_type'));
         add_action('add_meta_boxes', array($this, 'game_info_metabox'));
         add_action('save_post', array($this, 'save_game_info'));

     }

     //Creating Games Post Type
     public function create_post_type()
     {

         $args = array(
             'labels' => array(
                 'name' => __('Games'),
                 'singular_name' => __('Games')
             ),
             'public' => true,
             'has_archive' => false,
             'supports' => array(
                 'title',
                 'editor',
                 'comments',
                 'revisions',
                 'trackbacks',
                 'author',
                 'excerpt',
                 'page-attributes',
                 'thumbnail',
                 'custom-fields',
                 'post-formats'
             ),
             'show_in_rest' => true,
             'rewrite' => array('slug' => 'games')
         );

         register_post_type('games', $args);
     }


     // creating custom metabox field for custom post Games
     public function game_info_metabox()
     {
         add_meta_box("game_info_metabox", "Game Info", array($this, "game_info_field"), null, "side");
     }

     //custom metabox field for custom post Games
     public function game_info_field()
     {
         $game_url = get_post_meta(get_the_ID(), 'lokis_loop_game_url', true);
         $correct_answer = get_post_meta(get_the_ID(), 'lokis_loop_correct_answer', true);
         $redirect_uri = get_post_meta(get_the_ID(), 'lokis_loop_redirect_uri', true);
         ?>

         URL of the Game:
         <input type='text' name='lokis_loop_game_url' value="<?php echo $game_url; ?>" />

         Correct Answer:
         <input type='text' name='lokis_loop_correct_answer' value='<?php echo $correct_answer; ?>' />

         Re-Direct URI:
         <input type='text' name='lokis_loop_redirect_uri' value='<?php echo $redirect_uri; ?>' />

         <?php
     }

     //updating or saving data from Games Custom Post to Post Meta
     public function save_game_info()
     {

         global $post;

         if (isset($_POST["lokis_loop_game_url"])) :

             update_post_meta($post->ID, 'lokis_loop_game_url', $_POST["lokis_loop_game_url"]);

         endif;

         if (isset($_POST["lokis_loop_correct_answer"])) :

             update_post_meta($post->ID, 'lokis_loop_correct_answer', $_POST["lokis_loop_correct_answer"]);

         endif;

         if (isset($_POST["lokis_loop_redirect_uri"])) :

             update_post_meta($post->ID, 'lokis_loop_redirect_uri', $_POST["lokis_loop_redirect_uri"]);

         endif;

     }

 }
 new lokis_loop_game_info();
