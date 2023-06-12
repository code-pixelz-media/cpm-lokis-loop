<?php

if (!defined('ABSPATH')) {

    exit;

}

//Create game_info class
if (!class_exists('lokis_loop_game_info')) {
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
            add_action("add_meta_boxes_page", array($this, "lokis_add_page_metabox"));
            add_action("save_post_page", array($this, "lokis_save_page_checkbox"));

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
                <?php _e('URL of the Game:', 'lokis-loop'); ?>
                <textarea type='text' class="widefat" name='lokis_loop_game_url'><?php echo $game_url; ?></textarea>
            </div>
            <div class="metabox-group">
                <?php _e('Correct Answer:', 'lokis-loop'); ?>
                <input type='text' class="widefat" name='lokis_loop_correct_answer' value='<?php echo $correct_answer; ?>' />
            </div>
            <div class="metabox-group">
                <?php _e('Re-Direct URI:', 'lokis-loop'); ?>
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

        // creating custom metabox field for custom post Games to select primary games
        public function lokis_loop_checkbox_metabox()
        {
            add_meta_box("lokis_loop_checkbox_metabox", "Loki's Loop Primary Game?", array($this, "lokis_loop_checkbox_field"), 'games', 'side', 'core');
        }

        // Output the meta box HTML
        public function lokis_loop_checkbox_field()
        {
            // Retrieve the current value of the meta field
            $checked = get_post_meta(get_the_ID(), 'lokis_primary_game_checkbox', true);

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
            update_post_meta($post_id, 'lokis_primary_game_checkbox', $value);
        }

        //Adds metabox to change page visibility according to user
        public function lokis_add_page_metabox()
        {
            add_meta_box("custom_checkbox_metabox", "Page Visibility", array($this, "lokis_render_checkbox_metabox"), "page", "side", "core");
        }

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
    }
    new lokis_loop_game_info();
}