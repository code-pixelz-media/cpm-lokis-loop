<?php



//Adds submenu to Post type 'Games'

function lokis_settings_submenu()
{

    add_submenu_page(

        'edit.php?post_type=games',

        'Settings',

        'Settings',

        'manage_options',

        'lokis_games_settings',

        'lokis_settings_callback'

    );

}

add_action('admin_menu', 'lokis_settings_submenu');



//Register custom settings

function lokis_settings_init()
{

    register_setting('lokis_settings_group', 'lokis_setting', 'lokis_settings_callback_cpm');

}

add_action('admin_init', 'lokis_settings_init');



//Configure login page and register page

function lokis_settings_callback()
{

    $games_setting_data = get_option('lokis_setting');

    settings_fields('lokis_settings_group');

    ?>



    <div class="lokis-game-wrap">

        <div class="lookisloop-navbar-item">

            <img class="lokisloop-logo" src="<?php echo plugin_dir_url(__DIR__) . 'assets/images/logo1.png'; ?>"
                alt='lokis-loop logo'>

            <legend>

                <h1>Settings</h1>

            </legend>

            <img class="lokisloop-footer-logo" src="<?php echo plugin_dir_url(__DIR__) . 'assets/images/footerlogo.png'; ?>"
                alt="University of Washington Center for an Informed Public">

        </div>



        <form method="post" action="options.php" class="lokis-game-setting">

            <?php

            settings_fields('lokis_settings_group');

            do_settings_sections('lokis_settings');

            ?>

            <div class="lokis-inputpage settings-options">

                <label for="dashboard-url"> Dashboard Page :</label>

            </div>


            <select class="lokis-dashboard " name="lokis_setting[dashboard]">

                <option value="">Select a page</option>

                <?php

                $pages = get_pages();

                foreach ($pages as $page) {

                    $option = '<option value="' . $page->ID . '"';

                    if (isset($games_setting_data['dashboard']) && $games_setting_data['dashboard'] == $page->ID) {

                        $option .= ' selected="selected"';

                    }

                    $option .= '>' . $page->post_title . '</option>';

                    echo $option;

                }

                ?>

            </select>
            <?php

            echo '<div class="lokis-settings-title settings-options"<h4>Content for Host an Online Game :<h4></div>';
            wp_editor(
                get_option('lokis-host-a-game', ''),
                // Load saved content
                'lokis-host-a-game',
                array(
                    'textarea_rows' => 5,
                    'media_buttons' => FALSE,
                )
            );
            echo '<div class="lokis-settings-title settings-options"<h4>Content for Current Online Games :<h4></div>';
            wp_editor(
                get_option('lokis-current-games', ''),
                // Load saved content
                'lokis-current-games',
                array(
                    'textarea_rows' => 5,
                    'media_buttons' => FALSE,
                )
            );
            echo '<div class="lokis-settings-title settings-options"<h4>Content for Expired Online Games :<h4></div>';
            wp_editor(
                get_option('lokis-expired-games', ''),
                // Load saved content
                'lokis-expired-games',
                array(
                    'textarea_rows' => 5,
                    'media_buttons' => FALSE,
                )
            );

            submit_button(null, 'success', 'loki-submit');

            settings_errors();

            ?>

        </form>

    </div>

    <?php

}


function save_lokis_game_settings()
{
    // Check if the form was submitted
    if (isset($_POST['loki-submit'])) {
        // Sanitize and save the content from the editors to options table
        update_option('lokis-host-a-game', wp_kses_post($_POST['lokis-host-a-game']));
        update_option('lokis-current-games', wp_kses_post($_POST['lokis-current-games']));
        update_option('lokis-expired-games', wp_kses_post($_POST['lokis-expired-games']));
    }
}
add_action('admin_init', 'save_lokis_game_settings');