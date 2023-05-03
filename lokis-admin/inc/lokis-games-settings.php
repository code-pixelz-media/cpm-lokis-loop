<?php
//Adds submenu to Post type 'Games'
function lokis_settings_submenu()
{
    add_submenu_page(
        'edit.php?post_type=games',
        'Lokis Loops Games Settings',
        'Lokis Loops Games Settings',
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
        <h1>Loki Loops Game Settings</h1>
        <form method="post" action="options.php" class="lokis-game-setting">
            <?php
            settings_fields('lokis_settings_group');
            do_settings_sections('lokis_settings'); ?>
            <label for="login-url">Select Login Page</label>
            <select name="lokis_setting[login]">
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
            <label for="register-url">Select Register Page</label>
            <select name="lokis_setting[register]">
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
            <?php
            submit_button();
            settings_errors();
            ?>
        </form>
    </div>
    <?php
}