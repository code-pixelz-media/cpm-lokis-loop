<?php

//Creating a shortcode for login form
add_shortcode('lokis_loop_login', 'cpm_lokis_login_function');
function cpm_lokis_login_function()
{
    ob_start();
    if (!is_user_logged_in()) {
        cpm_lokis_login_form();
    } else {
        $dashboard_page_id = (get_option('lokis_setting'))['dashboard'];
        $lokis_dashboard_page = get_permalink($dashboard_page_id);

        if (empty($lokis_dashboard_page)) {
            $lokis_dashboard_page = site_url();
            return $lokis_dashboard_page;
        }
        echo '<script>window.location.href = "' . $lokis_dashboard_page . '";</script>';
    }
    $output = ob_get_contents();
    ob_end_clean();
    return $output;
}

add_filter('login_redirect', 'cpm_login_redirect', 10, 3);




function cpm_lokis_login_form()
{
    echo '<div class="lokis-login-form-container"><h3>Login</h3>';

    // wp_login_form();
    custom_login_error_message();

    wp_login_form(
        array(
            'redirect' => esc_url($_SERVER['REQUEST_URI']),
            // Redirect to the current page
            'label_username' => 'Username',
            'label_password' => 'Password',
            'label_remember' => 'Remember Me',
            'label_log_in' => 'Log In',
            'id_username' => 'lokis-username',
            'id_password' => 'lokis-password',
            'id_remember' => 'lokis-rememberme',
            'id_submit' => 'lokis-submit',
            'remember' => true,
            'value_username' => '',
            'value_remember' => true,
        )
    );
    $register_page_id = (get_option('lokis_setting'))['register'];
    $lokis_register_page = get_permalink($register_page_id);
    echo '<div class="lokis-loginPage"><p class="lokis-forgot-password-link"><a href="' . esc_url(wp_lostpassword_url()) . '">Forgot Password?</a></p> <p class="lokis-register-link"> <a href="' . esc_url($lokis_register_page) . '">Register</a></p></div></div>';
}



function cpm_login_redirect($redirect_to, $request, $user)
{
    $dashboard_page_id = (get_option('lokis_setting'))['dashboard'];
    $lokis_dashboard_page = get_permalink($dashboard_page_id);

    if (empty($lokis_dashboard_page)) {
        $lokis_dashboard_page = site_url();
        return $lokis_dashboard_page;
    }

    if (isset($user->roles) && is_array($user->roles)) {
        if (in_array('administrator', $user->roles)) {
            $redirect_to = admin_url();
        } else if (in_array('host', $user->roles) || in_array('player', $user->roles)) {

            $previous_url = wp_get_referer();
            if ($previous_url) {
                // $redirect_to = $previous_url;
                $redirect_to = remove_query_arg('login', $previous_url);
            } else {
                // Otherwise, redirect to the home page
                $redirect_to = $lokis_dashboard_page;
            }
        } else {
            $redirect_to = admin_url();
        }
    }
    return $redirect_to;
}

function lokis_logout_redirect($redirect_to, $requested_redirect_to, $user)
{
    $login_page_id = (get_option('lokis_setting'))['login'];
    $lokis_login_page = get_permalink($login_page_id);

    if (empty($lokis_login_page)) {
        $lokis_login_page = wp_login_form();
        return $lokis_login_page;
    }


    $requested_redirect_to = $lokis_login_page;

    return $requested_redirect_to;
}
add_filter('logout_redirect', 'lokis_logout_redirect', 10, 3);

function lokis_hide_admin_bar_settings()
{
    ?>
    <style type="text/css">
        .show-admin-bar {
            display: none;
        }
    </style>
    <?php
}
function lokis_disable_admin_bar()
{
    if (!current_user_can('administrator')) {
        add_filter('show_admin_bar', '__return_false');
        add_action('admin_print_scripts-profile.php', 'lokis_hide_admin_bar_settings');
    }
}
add_action('init', 'lokis_disable_admin_bar', 9);



// Handle failed login attempts
function lokis_custom_login_failed()
{

    $login_page_id = (get_option('lokis_setting'))['login'];
    $lokis_login_page = get_permalink($login_page_id);

    if (empty($lokis_login_page)) {
        $lokis_login_page = wp_login_form();
        return $lokis_login_page;
    }

    $previous_url = wp_get_referer();

    if ($previous_url) {
        $redirect_to = $previous_url;
    } else {
        // Otherwise, redirect to the home page
        $redirect_to = $lokis_login_page;
    }

    // $login_url = wp_login_url(); // Replace with the URL of your custom login page
    // wp_redirect($lokis_login_page . '?login=failed');
    wp_redirect(add_query_arg('login', 'failed', $redirect_to));


    exit;
}
add_action('wp_login_failed', 'lokis_custom_login_failed');

// Display custom error message
function custom_login_error_message()
{
    if (isset($_GET['login']) && $_GET['login'] === 'failed') {
        echo '<p class="lokis-error-credentials">Invalid login credentials. Please try again.</p>';
    }
}
add_action('login_form', 'custom_login_error_message');

// Add this code to your theme's functions.php file or in a custom plugin

// Redirect wp-login.php to custom login page
function redirect_wp_login_to_custom_login($login_url, $redirect, $force_reauth)
{
    $login_page_id = (get_option('lokis_setting'))['login'];
    $lokis_login_page = get_permalink($login_page_id);

    if (empty($lokis_login_page)) {
        $lokis_login_page = wp_login_form();
        return $lokis_login_page;
    }

    // $lokis_login_page = home_url('/custom-login'); // Replace with the URL of your custom login page

    if (!empty($redirect)) {
        $lokis_login_page = add_query_arg('redirect_to', urlencode($redirect), $lokis_login_page);
    }

    if ($force_reauth) {
        $lokis_login_page = add_query_arg('reauth', '1', $lokis_login_page);
    }

    return $lokis_login_page;
}
add_filter('login_url', 'redirect_wp_login_to_custom_login', 10, 3);