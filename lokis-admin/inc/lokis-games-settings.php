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

            <div class="lokis-inputpage">
                <label for="login-url"> Login Page :</label>
            </div>

            <select class="lokis-login" name="lokis_setting[login]">
                <option value="">Select a page</option>
                <?php
                $pages = get_pages();
                foreach ($pages as $page) {
                    $option = '<option value="' . $page->ID . '"';
                    if (isset($games_setting_data['login']) && $games_setting_data['login'] == $page->ID) {
                        $option .= ' selected="selected"';
                    }
                    $option .= '>' . $page->post_title . '</option>';
                    echo $option;
                }
                ?>
            </select>

            <div class="lokis-inputpage">
                <label for="register-url"> Register Page :</label>
            </div>

            <select class="lokis-register" name="lokis_setting[register]">
                <option value="">Select a page</option>
                <?php
                $pages = get_pages();
                foreach ($pages as $page) {
                    $option = '<option value="' . $page->ID . '"';
                    if (isset($games_setting_data['register']) && $games_setting_data['register'] == $page->ID) {
                        $option .= ' selected="selected"';
                    }
                    $option .= '>' . $page->post_title . '</option>';
                    echo $option;
                }
                ?>
            </select>

            <div class="lokis-inputpage">
                <label for="dashboard-url"> Dashboard Page :</label>
            </div>

            <select class="lokis-dashboard" name="lokis_setting[dashboard]">
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

            <div class="lokis-inputpage">
                <label for="thankyou-url"> Thank You Page :</label>
            </div>
            <select class="lokis-thankyou" name="lokis_setting[thankyou]">
                <option value="">Select a page</option>
                <?php
                $pages = get_pages();
                foreach ($pages as $page) {
                    $option = '<option value="' . $page->ID . '"';
                    if (isset($games_setting_data['thankyou']) && $games_setting_data['thankyou'] == $page->ID) {
                        $option .= ' selected="selected"';
                    }
                    $option .= '>' . $page->post_title . '</option>';
                    echo $option;
                }
                ?>
            </select>
            <?php
            submit_button(null, 'success', 'loki-submit');
            settings_errors();
            ?>
        </form>
    </div>
    <?php
}