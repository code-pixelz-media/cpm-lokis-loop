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
        add_action('add_meta_boxes', array($this, 'lokis_loop_checkbox_metabox'));
        add_action('save_post', array($this, 'lokis_loop_save_checkbox'));

    }

    //Creating Games Post Type
    public function lokis_loop_create_post_type()
    {

        $args = array(
            'labels' => array(
                'name' => __('Game Stages'),
                'singular_name' => __('Game Stages')
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
            'menu_icon' => 'dashicons-games',
            'show_in_rest' => true,
            'rewrite' => array('slug' => 'games'),
            'capabilities' => array(
                'edit_post' => 'edit_game',
                'read_post' => 'read_game',
                'delete_post' => 'delete_game',
                'edit_posts' => 'edit_games',
                'edit_others_posts' => 'edit_others_games',
                'publish_posts' => 'publish_games',
                'read_private_posts' => 'read_private_games',
                'edit_published_posts' => 'edit_published_games',
                'delete_published_posts' => 'delete_published_games',
                'delete_posts' => 'delete_games',
                'edit_private_posts' => 'edit_private_games',
                'create_posts' => 'create_games',
                'delete_others_posts' => 'delete_others_games'
            )
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

    // creating custom metabox field for custom post Games
    public function lokis_loop_checkbox_metabox()
    {
        add_meta_box("lokis_loop_checkbox_metabox", "Loki's Loop Primary Game?", array($this, "lokis_loop_checkbox_field"), 'games', 'side', 'core');
    }

    // Output the meta box HTML
    function lokis_loop_checkbox_field()
    {
        // Retrieve the current value of the meta field
        $checked = get_post_meta(get_the_ID(), 'lokis_checkbox', true);

        // Output the checkbox
        echo '<label>';
        echo '<input type="checkbox" name="lokis_checkbox" value="1" ' . checked($checked, 1, false) . ' />';
        echo ' Yes';
        echo '</label>';
    }

    //updating or saving data from Games Custom Post to Post Meta
    public function lokis_loop_save_checkbox()
    {
        global $post;

        $post_id = get_the_ID();

        // Update the meta field with the new value
        if (isset($_POST['lokis_checkbox'])) {
            $value = 1;
        } else {
            $value = 0;
        }
        update_post_meta($post_id, 'lokis_checkbox', $value);
    }


}
new lokis_loop_game_info();