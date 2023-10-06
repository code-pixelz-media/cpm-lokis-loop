<?php
/**
 * Here everything is about the login and its redirections 
 */

//Creating a shortcode for login form
if (!function_exists('cpm_lokis_login_function')) {
    function cpm_lokis_login_function()
    {
        ob_start();
        // if (!is_user_logged_in()) {
            cpm_lokis_login_form();
        // } else {
        //     echo '<script>window.location.href = "' . site_url() . '";</script>';
        //     exit;
        // }

        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }
    add_shortcode('lokis_loop_login', 'cpm_lokis_login_function');
}

/**
 * This function redirects users to the login page if they try to login without entering a username or password.
 * @return If the `` variable is empty, the function will return the output of
 * `wp_login_form()`. Otherwise, nothing is being returned as the function is being used as an action
 * hook to redirect the user to the login page if the username or password fields are empty.
 */

if (!function_exists('lokis_redirect_empty_username_password')) {
    function lokis_redirect_empty_username_password()
    {
        $username = isset($_POST['log']) ? sanitize_text_field($_POST['log']) : '';
        $password = isset($_POST['pwd']) ? $_POST['pwd'] : '';

        if (empty($username) || empty($password)) {
            // Redirect to the custom dashboard URL
            wp_redirect(wp_get_referer());
        }
    }
    add_action('authenticate', 'lokis_redirect_empty_username_password', 1, 3);
}


/**
 * This function generates a login form with custom labels and links for password recovery and
 * registration.
 */

if (!function_exists('cpm_lokis_login_form')) {
    function cpm_lokis_login_form()
    {
        echo '<div class="lokis-login-form-container">';
        custom_login_error_message();
        wp_login_form(
            array(
                'redirect' => esc_url($_SERVER['REQUEST_URI']),
                // Redirect to the current page
                'label_username' => __('Username', 'lokis-loop'),
                'label_password' => __('Password', 'lokis-loop'),
                'label_remember' => __('Remember Me', 'lokis-loop'),
                'label_log_in' => __('Enter', 'lokis-loop'),
                'id_username' => esc_attr(__('lokis-username', 'lokis-loop')),
                'id_password' => esc_attr(__('lokis-password', 'lokis-loop')),
                'id_remember' => esc_attr(__('lokis-rememberme', 'lokis-loop')),
                'id_submit' => esc_attr(__('lokis-submit', 'lokis-loop')),
                'remember' => true,
                'value_username' => '',
                'value_remember' => true,
            )
        );
        echo '<div class="lokis-loginPage"><p class="lokis-forgot-password-link"><a href="' . esc_url(wp_lostpassword_url()) . '">' . __("Forgot your password?", "lokis-loop") . '</a></p></div></div>';
    }
}

/**
 * This function redirects users to different pages based on their role after logging in.
 */

if (!function_exists('cpm_login_redirect')) {
    function cpm_login_redirect($redirect_to, $request, $user)
    {
        if (isset($user->roles) && is_array($user->roles)) {
            if (in_array('administrator', $user->roles)) {
                $redirect_to = admin_url();
            } else if (in_array('host', $user->roles)) {
                $custom_dashboard_url = home_url('/host-portal-games');
                $previous_url = wp_get_referer();
                if ($previous_url) {
                    $redirect_to =  $custom_dashboard_url;
                    // $redirect_to = remove_query_arg('login', $previous_url);
                } else {
                    // Otherwise, redirect to the home page
                    $redirect_to = $custom_dashboard_url;
                }
            } else {
                $redirect_to = admin_url();
            }
        }
        return $redirect_to;
    }
    add_filter('login_redirect', 'cpm_login_redirect', 10, 3);
}

/**
 * The function redirects users to a specified login page after they log out of WordPress.
 */

if (!function_exists('lokis_logout_redirect')) {
    function lokis_logout_redirect($redirect_to, $requested_redirect_to, $user)
    {
        $requested_redirect_to = site_url();
        return $requested_redirect_to;
    }

    add_filter('logout_redirect', 'lokis_logout_redirect', 10, 3);
}

/**
 * This PHP function hides the WordPress admin bar.
 */
if (!function_exists('lokis_hide_admin_bar_settings')) {
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
}

/**
 * The function disables the WordPress admin bar for non-administrator users and hides the admin bar
 * settings on the profile page.
 */
if (!function_exists('lokis_disable_admin_bar')) {
    function lokis_disable_admin_bar()
    {
        if (!current_user_can('administrator')) {
            add_filter('show_admin_bar', '__return_false');
            add_action('admin_print_scripts-profile.php', 'lokis_hide_admin_bar_settings');
        }
    }
    add_action('init', 'lokis_disable_admin_bar', 9);
}

// Handle failed login attempts
if (!function_exists('lokis_custom_login_failed')) {
    function lokis_custom_login_failed()
    {
        $previous_url = wp_get_referer();
        if ($previous_url) {
            $redirect_to = $previous_url;
        } else {
            // Otherwise, redirect to the home page
            $redirect_to = home_url('/');
        }
        wp_redirect(add_query_arg('login', 'failed', $redirect_to));
        exit;
    }
    add_action('wp_login_failed', 'lokis_custom_login_failed');
}
// Display custom error message on failed login
if (!function_exists('custom_login_error_message')) {
    function custom_login_error_message()
    {
        if (isset($_GET['login']) && $_GET['login'] === 'failed') {
            echo '<p class="lokis-error-credentials">Invalid login credentials. Please try again.</p>';
        }
    }
    add_action('login_form', 'custom_login_error_message');
}

// Redirect wp-login.php to custom login page
if (!function_exists('redirect_wp_login_to_custom_login')) {
    function redirect_wp_login_to_custom_login($login_url, $redirect, $force_reauth)
    {
        $custom_login_url = home_url('/admin');
        if (!empty($redirect)) {
            $custom_login_url = add_query_arg('redirect_to', urlencode($redirect), $custom_login_url);
        }
        if ($force_reauth) {
            $custom_login_url = add_query_arg('reauth', '1', $custom_login_url);
        }
        return $custom_login_url;
    }
    add_filter('login_url', 'redirect_wp_login_to_custom_login', 10, 3);
}

/**
 * The function `lokis_custom_logout_hook` performs a custom logout action in WordPress by checking if
 * the logout action is specified in the URL, logging out the user, and redirecting to the home page.
 */
if (!function_exists('lokis_custom_logout_hook')) {
    function lokis_custom_logout_hook()
    {
        // Check if the logout action is specified in the URL
        if (isset($_GET['action']) && $_GET['action'] === 'logout') {
            // Perform the logout action
            wp_logout();
            // Redirect to the home page or any desired URL
            wp_safe_redirect(home_url());
            exit;
        }
    }
    add_action('init', 'lokis_custom_logout_hook');
}