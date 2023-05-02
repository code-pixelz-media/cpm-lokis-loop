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
        add_action('init', array($this, 'lokis_loop_create_post_type'));
        add_action('add_meta_boxes', array($this, 'lokis_loop_game_info_metabox'));
        add_action('save_post', array($this, 'lokis_loop_save_game_info'));

    }

    //Creating Games Post Type
    public function lokis_loop_create_post_type()
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
    public function lokis_loop_game_info_metabox()
    {
        add_meta_box("lokis_loop_game_info_metabox", "Loki's Loop Game Info", array($this, "lokis_loop_game_info_field"), 'games', 'normal', 'low');
    }

    //custom metabox field for custom post Games
    public function lokis_loop_game_info_field()
    {
        $game_url = get_post_meta(get_the_ID(), 'lokis_loop_game_url', true);
        $correct_answer = get_post_meta(get_the_ID(), 'lokis_loop_correct_answer', true);
        $redirect_uri = get_post_meta(get_the_ID(), 'lokis_loop_redirect_uri', true);
        ?>

        <div class="metabox-group">
            URL of the Game:
            <textarea type='text' class="widefat" name='lokis_loop_game_url'><?php echo $game_url; ?></textarea>
        </div>

        <div class="metabox-group">
            Correct Answer:
            <input type='text' class="widefat" name='lokis_loop_correct_answer' value='<?php echo $correct_answer; ?>' />
        </div>

        <div class="metabox-group">
            Re-Direct URI:
            <input type='text' class="widefat" name='lokis_loop_redirect_uri' value='<?php echo $redirect_uri; ?>' />
        </div>

        <?php
    }

    //updating or saving data from Games Custom Post to Post Meta
    public function lokis_loop_save_game_info()
    {

        global $post;

        if (isset($_POST["lokis_loop_game_url"])):

            update_post_meta($post->ID, 'lokis_loop_game_url', $_POST["lokis_loop_game_url"]);

        endif;

        if (isset($_POST["lokis_loop_correct_answer"])):

            update_post_meta($post->ID, 'lokis_loop_correct_answer', $_POST["lokis_loop_correct_answer"]);

        endif;

        if (isset($_POST["lokis_loop_redirect_uri"])):

            update_post_meta($post->ID, 'lokis_loop_redirect_uri', $_POST["lokis_loop_redirect_uri"]);

        endif;

    }

}
new lokis_loop_game_info();