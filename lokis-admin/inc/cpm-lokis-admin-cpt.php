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

            add_action("add_meta_boxes_page", array($this, "lokis_add_page_metabox"));
            add_action("save_post_page", array($this, "lokis_save_page_checkbox"));
            // add_action("add_meta_boxes_page", array($this, "thankyou_metabox"));
            // add_action("save_post", array($this, "page_checkbox"));
            add_action('save_post', array($this, 'lokis_loop_save_checkbox'));
            add_action('admin_footer', array($this, 'lokis_loop_admin_footer_script'));


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
            add_meta_box("lokis_loop_checkbox_metabox_games", "Loki's Loop Primary Game?", array($this, "lokis_loop_checkbox_field"), 'games', 'side', 'core');

            add_meta_box("lokis_loop_checkbox_metabox_pages", "Loki's Loop Primary Game?", array($this, "lokis_loop_checkbox_field"), 'page', 'side', 'core');
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
            echo '<input type="hidden" name="lokis_metabox_nonce" value="' . wp_create_nonce('lokis_metabox_nonce') . '">';
        }

        // Updating or saving data from Games Custom Post to Post Meta
        public function lokis_loop_save_checkbox($post_id)
        {
            // Verify the nonce
            if (!isset($_POST['lokis_metabox_nonce']) || !wp_verify_nonce($_POST['lokis_metabox_nonce'], 'lokis_metabox_nonce')) {
                return;
            }

            /*     // Ignore autosaves and revisions
                if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                    return;
                }
                if (wp_is_post_revision($post_id)) {
                    return;
                } */

            // Update the meta field with the new value
            if (isset($_POST['lokis_checkbox'])) {
                $value = 1;
            } else {
                $value = 0;
            }
            update_post_meta($post_id, 'lokis_primary_game_checkbox', $value);
        }

        // Add JavaScript for autosave
        public function lokis_loop_admin_footer_script()
        {
            $screen = get_current_screen();
            if ($screen->base === 'post' && $screen->post_type === 'page') {
                ?>
                <script>
                    jQuery(document).ready(function ($) {
                        var lokisMetaboxData = {
                            'action': 'lokis_loop_autosave',
                            'post_id': <?php echo get_the_ID(); ?>,
                            'nonce': '<?php echo wp_create_nonce('lokis_loop_autosave_nonce'); ?>'
                        };

                        // Autosave metabox value on change
                        $('input[name="lokis_checkbox"]').on('change', function () {
                            var checkboxValue = $(this).is(':checked') ? 1 : 0;
                            lokisMetaboxData.checkbox_value = checkboxValue;
                            $.post(ajaxurl, lokisMetaboxData);
                        });
                    });
                </script>
                <?php
            }
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
            echo __("Only to Admin and Host", "lokis-loop");
            echo '</label>';
        }

        // Save the checkbox value when the page is saved
        function lokis_save_page_checkbox($post_id)
        {
            // Verify the nonce
            if (!isset($_POST['lokis_metabox_nonce']) || !wp_verify_nonce($_POST['lokis_metabox_nonce'], 'lokis_metabox_nonce')) {
                return;
            }
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

    // Handle autosave callback
    add_action('wp_ajax_lokis_loop_autosave', 'lokis_loop_handle_autosave');

    function lokis_loop_handle_autosave()
    {
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'lokis_loop_autosave_nonce')) {
            wp_send_json_error('Invalid nonce.');
        }


        if (!isset($_POST['post_id']) || !isset($_POST['checkbox_value'])) {
            wp_send_json_error('Missing data.');
        }

        $post_id = intval($_POST['post_id']);
        $checkbox_value = intval($_POST['checkbox_value']);

        update_post_meta($post_id, 'lokis_primary_game_checkbox', $checkbox_value);

        wp_send_json_success('Metabox value autosaved.');
    }

}


// Add meta boxes
if (!function_exists('lokis_custom_meta_boxes')) {
    function lokis_custom_meta_boxes()
    {
        add_meta_box('lokis_meta_group', 'Puzzle page content', 'render_lokis_meta_group', 'page', 'side', 'high');
    }
    add_action('add_meta_boxes', 'lokis_custom_meta_boxes');
}

// Render Meta Fields Group
if (!function_exists('render_lokis_meta_group')) {
    function render_lokis_meta_group($post)
    {
        $btn_title_value = get_post_meta($post->ID, 'lokis_btn_title', true);
        $btn_url_value = get_post_meta($post->ID, 'lokis_btn_url', true);
        $popup_content_value = get_post_meta($post->ID, 'lokis_popup_content', true);
        ?>
        <div class="lokis-puzzle-metafield-wrapper">
            <label for="lokis_popup_content">Popup Message Content:</label>
            <textarea class="widefat lokis-puzzle-metafield"
                name="lokis_popup_content"><?php echo esc_textarea($popup_content_value); ?></textarea>

            <label for="lokis_btn_title">Button Text:</label>
            <input type="text" class="widefat lokis-puzzle-metafield" name="lokis_btn_title"
                value="<?php echo esc_attr($btn_title_value); ?>">

            <label for="lokis_btn_url">Button URL:</label>
            <input type="text" class="widefat lokis-puzzle-metafield" name="lokis_btn_url"
                value="<?php echo esc_attr($btn_url_value); ?>">
        </div>
<?php
    }
}

// Save Meta Fields
if (!function_exists('lokis_save_custom_meta_fields')) {
    function lokis_save_custom_meta_fields($post_id)
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
            return;
        if (!current_user_can('edit_post', $post_id))
            return;

        if (isset($_POST['lokis_btn_title'])) {
            update_post_meta($post_id, 'lokis_btn_title', sanitize_text_field($_POST['lokis_btn_title']));
        }
        if (isset($_POST['lokis_btn_url'])) {
            update_post_meta($post_id, 'lokis_btn_url', sanitize_text_field($_POST['lokis_btn_url']));
        }
        if (isset($_POST['lokis_popup_content'])) {
            update_post_meta($post_id, 'lokis_popup_content', sanitize_text_field($_POST['lokis_popup_content']));
        }
    }
    add_action('save_post', 'lokis_save_custom_meta_fields');
}