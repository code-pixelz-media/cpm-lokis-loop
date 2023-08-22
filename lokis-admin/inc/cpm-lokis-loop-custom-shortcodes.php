<?php

//Creating a shortcode for register form
function lokis_loop_register_form()
{
    // if (is_user_logged_in()) {
    //     ob_start();
    //     $dashboard_page_id = (get_option('lokis_setting'))['dashboard'];
    //     $lokis_dashboard_page = get_permalink($dashboard_page_id);

    //     if (empty($lokis_dashboard_page)) {
    //         $lokis_dashboard_page = site_url();
    //         return $lokis_dashboard_page;
    //     }
    //     echo '<script>window.location.href = "' . $lokis_dashboard_page . '";</script>';
    // } else {
    ob_start();
    ?>

    <!-- creating game host application form -->
    <div class="lokis-gamehost-application-container">
        <form action="" class="lokis-gamehost-form" method="post">
            <?php wp_nonce_field(-1, 'loki_registration_nonce'); ?>

            <div id="lokis-names" class="lokis-formquestion-item">
                <label for="loki-name" class="lokisloop-label">
                    <?php _e('Name', 'lokis-loop'); ?> <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-name'>
            </div>

            <div id="lokis-email" class="lokis-formquestion-item">
                <label for="loki-email" class="lokisloop-label">
                    <?php _e('Email', 'lokis-loop'); ?> <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-email'>
            </div>

            <div id="lokis-organization" class="lokis-formquestion-item">
                <label for="loki-organization" class="lokisloop-label">
                    <?php _e('Organization Name ', 'lokis-loop'); ?><span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                    id='loki-organization'>
            </div>

            <div id="lokis-organization-type" class="lokis-formquestion-item">
                <label for="organizational_name" class="lokisloop-label">
                    <?php _e('What best describes your organization ', 'lokis-loop'); ?><span
                        class="lokis-req-field">*</span>

                </label>
                <div class="lokisloop-organization-wrapper">
                    <div class="lokisloop-selective-box">
                        <input type="radio" id="library" name="loki_organization_type" value="Public-Library">
                        <label for="library" class="lokisloop-label">
                            <?php _e('Public library', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="other-library" name="loki_organization_type" value="other-library">
                        <label for="other-library" class="lokisloop-label">
                            <?php _e('Other library', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="school" name="loki_organization_type" value="K-12 School">
                        <label for="school" class="lokisloop-label">
                            <?php _e('K-12 school', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="University/College" name="loki_organization_type"
                            value="University/college">
                        <label for="University/college" class="lokisloop-label">
                            <?php _e('University/college', 'lokis-loop'); ?>
                        </label>
                    </div>

                    <div class="lokisloop-selective-box">
                        <input type="radio" id="other-organization" name="loki_organization_type" value="Non-profit">
                        <label for="other-organization" class="lokisloop-label">
                            <?php _e('Other', 'lokis-loop'); ?>
                        </label>
                    </div>
                </div>
            </div>

            <div id="lokis-country" class="lokis-formquestion-item">
                <label for="loki-country" class="lokisloop-label">
                    <?php _e('Country', 'lokis-loop'); ?>
                    <span class="lokis-req-field">*</span>
                </label>
                <select id="loki-country" class="lokis-input form-control">
                    <option value=''>
                        <?php // _e('Select a Value', 'lokis-loop'); ?>
                    </option>
                    <option value="United States">
                        <?php _e('United States', 'lokis-loop'); ?>
                    </option>
                    <option value="Canada">
                        <?php _e('Canada', 'lokis-loop'); ?>
                    </option>
                </select>
            </div>

            <div id="lokis-zipcode" class="lokis-formquestion-item">
                <label for="loki-zipcode" class="lokisloop-label">
                    <?php _e('Zipcode(US and Canada only)', 'lokis-loop'); ?>
                </label>
                <input aria-label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-zipcode'>
            </div>

            <div id="lokis-feedback"></div>

            <button class="button" id="lokis-registration-button" value='Submit'>
                <?php _e('Submit', 'lokis-loop'); ?>
            </button>
        </form>
    </div>
    <?php
    return ob_get_clean();
    // }
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');