<?php
//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    //Check if it is the user that is accessing the page
    if (is_user_logged_in()) {
        ob_start();
        $current_user = wp_get_current_user();
        $user_id = $current_user->ID;
        $user_email = $current_user->user_email;
        ?>
        <div class="lokis-user-dashboard-section">
            <div class="lokisloop-dashboard-container">
                <aside>

                    <!--  here previously we had seperate menu that enabled the user to create a game and also host a game, the requirement has now changed but the code is there if in future if we need so we commented the menu and displayed the wordpress menu -->
                    <?php
                    echo lokis_dashboard_menus_items();
                    //lokis_account_menu(); 
                    ?>
                </aside>
                <!-- for user profile -->
                <div class="container lokisloop-container">
                    <div class="lokisloop-profile">
                        <div class="container">
                            <div class="rightbox">
                                <div class="profile">
                                    <h1>
                                        <?php _e('Personal Info', 'lokis-loop'); ?>
                                    </h1>
                                    <div class="lokis-gamehost-profileform-container">
                                        <div class="lokisloop-profile-form">
                                            <form action="" method="post">

                                                <?php wp_nonce_field(-1, 'loki_profile_nonce'); ?>

                                                <div class="lokis-form-item">
                                                    <label for="loki-name" class="form_control">
                                                        <?php _e('Name:', 'lokis-loop'); ?>
                                                    </label>
                                                    <p aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                        id='loki-name'>
                                                        <?php echo get_user_meta($user_id, 'loki_fullname', true); ?>

                                                    </p>
                                                </div>

                                                <div class="lokis-form-item">
                                                    <label for="loki-email" class="form_control">
                                                        <?php _e('Email:', 'lokis-loop'); ?>
                                                    </label>
                                                    <p aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                        id='loki-email'>
                                                        <?php echo $user_email; ?>

                                                    </p>
                                                </div>

                                                <div class="lokis-form-item" id="lokis-organization">
                                                    <label for="loki-organization" class="form_control">
                                                        <?php _e('Organization Name:', 'lokis-loop'); ?>
                                                    </label>
                                                    <input aria label="single line text" maxlength="4000"
                                                        class="lokis-form-control" id='loki-organization'
                                                        value="<?php echo get_user_meta($user_id, 'loki_organization', true); ?>"
                                                        placeholder="Enter your organization's name">
                                                </div>

                                                <?php
                                                $preselected_value = get_user_meta($user_id, 'loki_organization_type', true);
                                                echo '<div id="lokis-organization-type" class="lokis-form-item lokis-radio">
                                                <label for="loki_organization_type" class="form_control">' . __('What best describes your organization:', 'lokis-loop') . '</label>
                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="library" name="loki_organization_type" value="Public-Library"' . ($preselected_value === 'Public-Library' ? ' checked' : '') . '>
                                                    <label for="library" class="form_control"><span>' . __('Public library', 'lokis-loop') . '</span></label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="university/college" name="loki_organization_type" value="University/College"' . ($preselected_value === 'University/College' ? ' checked' : '') . '>
                                                    <label for="university/college" class="form_control"><span>' . __('University/College', 'lokis-loop') . '</span></label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="school" name="loki_organization_type" value="K-12 School"' . ($preselected_value === 'K-12 School' ? ' checked' : '') . '>
                                                    <label for="school" class="form_control">' . __('K-12 school', 'lokis-loop') . '</label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="museum" name="loki_organization_type" value="Museum"' . ($preselected_value === 'Museum' ? ' checked' : '') . '>
                                                    <label for="museum" class="form_control">' . __('Museum', 'lokis-loop') . '</label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="non-profit" name="loki_organization_type" value="Non-profit"' . ($preselected_value === 'Non-profit' ? ' checked' : '') . '>
                                                    <label for="non-profit" class="form_control">' . __('Non-profit', 'lokis-loop') . '</label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="other-organization" name="loki_organization_type" value="Other Organization"' . ($preselected_value === 'Other Organization' ? ' checked' : '') . '>
                                                    <label for="other-organization" class="form_control">' . __('Other Organization (non-library)', 'lokis-loop') . '</label>
                                                </div>
                                            </div>';
                                                ?>

                                                <div class="lokis-form-item">
                                                    <label for="loki-password" class="form_control">
                                                        <?php _e('Current Password:', 'lokis-loop'); ?>
                                                    </label>
                                                    <input aria label="single line text" maxlength="4000"
                                                        class="lokis-form-control" id='loki-old-password'
                                                        placeholder="Enter your Current Password">
                                                </div>

                                                <div class="lokis-form-item">
                                                    <label for="password" class="form_control">
                                                        <?php _e('New Password :', 'lokis-loop') ?>
                                                    </label>
                                                    <input aria label="single line text" maxlength="4000"
                                                        class="lokis-form-control" id='loki-new-password'
                                                        placeholder="Enter your New Password">
                                                </div>

                                                <div id="lokis-password" class="lokis-form-item">
                                                    <label for="password" class="form_control">
                                                        <?php _e('Re-type Password:', 'lokis-loop'); ?>
                                                    </label>
                                                    <input aria label="single line text" maxlength="4000"
                                                        class="lokis-form-control" id='loki-new-password-retype'
                                                        placeholder="Re-Type your Password">
                                                </div>

                                                <button type="button" class="edit-button" id="generate-password">
                                                    <?php _e('Generate Password', 'lokis-loop'); ?>
                                                </button>

                                                <button class="edit-button" id='lokis-profile-update-button' type="submit">
                                                    <?php _e('Update', 'lokis-loop'); ?>
                                                </button>
                                            </form>
                                            <div id='lokis-error-message'></div>
                                            <div id="lokis-feedback"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        return ob_get_clean();

    } else {
        ?>
        <script>
            window.location.href = '<?php echo wp_login_url(); ?>';
        </script>
        <?php
    }
}
add_shortcode('lokis_loop_user_dashboard', 'lokis_loop_user_dashboard');