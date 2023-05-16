<?php

//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    ob_start();
    $current_user = wp_get_current_user();
    $user_id = $current_user->ID;
    $user_email = $current_user->user_email;
    ?>

    <div class="lokisloop-dashboard-container">

        <aside>
            <div class="lokis-logo">
                <a id="home-page" class="nav-link " href="/">Loki's Loop</a>

            </div>

            <?php lokis_account_menu(); ?>

        </aside>


        <!-- for user profile -->

        <div class="container lokisloop-container">
            <div id="logo" class="lokisloop-logo">Lokisloop</div>

            <div class="lokisloop-profile">
                <div class="container">
                    <div class="rightbox">
                        <div class="profile">
                            <h1>Personal Info</h1>
                            <div class="lokis-gamehost-profileform-container">
                                <div id='error-message'></div>
                                <div class="lokisloop-profile-form">

                                    <form action="" method="post">

                                        <?php wp_nonce_field(-1, 'loki_registration_nonce'); ?>

                                        <div class="lokis-form-item">
                                            <label for="loki-name" class="form_control">Name :</label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-name'
                                                value="<?php echo get_user_meta($user_id, 'loki_fullname', true); ?>"
                                                disabled>
                                        </div>

                                        <div class="lokis-form-item">
                                            <label for="loki-email" class="form_control"> Email : </label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-email' value="<?php echo $user_email; ?>" disabled>
                                        </div>

                                        <div class="lokis-form-item">
                                            <label for="loki-organization" class="form_control">Organization Name :</label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-organization'
                                                value="<?php echo get_user_meta($user_id, 'loki_organization', true); ?>"
                                                placeholder="Enter your organization's name">
                                        </div>

                                        <?php
                                        $preselected_value = get_user_meta($user_id, 'loki_organization_type', true);
                                        // Replace with the desired preselected value
                                    
                                        echo '<div class="lokis-form-item lokis-radio">

                                                <label for="organizational_name" class="form_control"> What best describes your organization: </label>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="library" name="loki_organization_type" value="Public-Library"' . ($preselected_value === 'Public-Library' ? ' checked' : '') . '>
                                                    <label for="library" class="form_control"><span>Public library</span></label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="university/college" name="loki_organization_type" value="University/College"' . ($preselected_value === 'University/College' ? ' checked' : '') . '>
                                                    <label for="university/college" class="form_control"><span>University/College</span></label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="school" name="loki_organization_type" value="K-12 School"' . ($preselected_value === 'K-12 School' ? ' checked' : '') . '>
                                                    <label for="school" class="form_control">K-12 school</label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="museum" name="loki_organization_type" value="Museum"' . ($preselected_value === 'Museum' ? ' checked' : '') . '>
                                                    <label for="museum" class="form_control">Museum</label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="non-profit" name="loki_organization_type" value="Other Organization"' . ($preselected_value === 'Other Organization' ? ' checked' : '') . '>
                                                    <label for="non-profit" class="form_control">Non-profit</label>
                                                </div>

                                                <div class="lokisloop-selective-box">
                                                    <input type="radio" id="other-organization" name="loki_organization_type" value="Non-profit"' . ($preselected_value === 'Non-profit' ? ' checked' : '') . '>
                                                    <label for="other-organization" class="form_control">Other Organization (non-library)</label>
                                                </div>

                                            </div>
                                            ';
                                        ?>

                                        <div class="lokis-form-item">
                                            <label for="loki-password" class="form_control">Current Password :</label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-old-password' placeholder="Enter your Current Password">
                                        </div>

                                        <div class="lokis-form-item">
                                            <label for="password" class="form_control"> NewPassword : </label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-new-password' placeholder="Enter your New Password">
                                        </div>

                                        <div class="lokis-form-item">
                                            <label for="password" class="form_control">Re-type Password : </label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-new-password-retype' placeholder="Re-Type your Password">
                                        </div>


                                        <button class="edit-button" id='lokis-profile-update-button' type="submit">Update</button>

                                    </form>

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


}
add_shortcode('lokis_loop_user_dashboard', 'lokis_loop_user_dashboard');