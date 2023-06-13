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


// add_action('init', function () {
//     pll_register_string('lokis-loop', 'Username', 'lokis-loop');
//     pll_register_string('lokis-loop', 'Password', 'lokis-loop');
//     pll_register_string('lokis-loop', 'Remember Me', 'lokis-loop');
//     pll_register_string('lokis-loop', 'Log In', 'lokis-loop');
//     pll_register_string('lokis-loop', 'Forgot Password?', 'lokis-loop');
//     pll_register_string('lokis-loop', 'Register', 'lokis-loop');
// });
function translate_string($string)
{
    if (function_exists('pll__')) {
        return pll__($string);
    }
    return __($string, 'lokis-loop');
}
/**
 * This function redirects users to the login page if they try to login without entering a username or
 * password.
 * 
 * @return If the `` variable is empty, the function will return the output of
 * `wp_login_form()`. Otherwise, nothing is being returned as the function is being used as an action
 * hook to redirect the user to the login page if the username or password fields are empty.
 */
function lokis_redirect_empty_username_password()
{

    $login_page_id = (get_option('lokis_setting'))['login'];
    $lokis_login_page = get_permalink($login_page_id);

    if (empty($lokis_login_page)) {
        $lokis_login_page = wp_login_form();
        return $lokis_login_page;
    }
    if (empty($_POST['log']) || empty($_POST['pwd'])) {

        wp_redirect($lokis_login_page);
        // echo '<script>window.location.href = "' . $lokis_login_page . '"</script>';
        exit;
    }
}
// add_action('login_form', 'lokis_redirect_empty_username_password');
add_action('authenticate', 'lokis_redirect_empty_username_password', 1, 3);


/**
 * This function generates a login form with custom labels and links for password recovery and
 * registration.
 */
function cpm_lokis_login_form()
{
    echo '<div class="lokis-login-form-container"><h3>Login</h3>';

    // wp_login_form();
    custom_login_error_message();

    // Polylang functions are available

    wp_login_form(
        array(
            'redirect' => esc_url($_SERVER['REQUEST_URI']),
            // Redirect to the current page
            // 'label_username' => function_exists('pll__') ? pll__('Username') : __('Username', 'lokis-loop'),
            'label_username' => translate_string('Username'),
            'label_password' => function_exists('pll__') ? pll__('Password') : __('Password', 'lokis-loop'),
            'label_remember' => function_exists('pll__') ? pll__('Remember Me') : __('Remember Me', 'lokis-loop'),
            'label_log_in' => function_exists('pll__') ? pll__('Log In') : __('Log In', 'lokis-loop'),
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
    echo '<div class="lokis-loginPage"><p class="lokis-forgot-password-link"><a href="' . esc_url(wp_lostpassword_url()) . '">' . __("Forgot Password?", "lokis-loop") . '</a></p> <p class="lokis-register-link"> <a href="' . esc_url($lokis_register_page) . '">' . __("Register", "lokis-loop") . '</a></p></div></div>';

}



/**
 * This function redirects users to different pages based on their role after logging in.
 */
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

/**
 * The function redirects users to a specified login page after they log out of WordPress.
 */
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

/**
 * This PHP function hides the WordPress admin bar.
 */
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

/**
 * The function disables the WordPress admin bar for non-administrator users and hides the admin bar
 * settings on the profile page.
 */
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