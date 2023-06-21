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
        <div id='lokis-error-message'></div>
        <form action="" class="lokis-gamehost-form" method="post">
            <?php wp_nonce_field(-1, 'loki_registration_nonce'); ?>
            <div id="lokis-feedback"></div>

            <div class="lokis-formquestion-item">
                <label for="loki-name" class="lokisloop-label">
                    <span class="lokis-formquestion-order">1. </span>
                    <?php _e('Name', 'lokis-loop'); ?> <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-name'
                    placeholder="Enter your name">
            </div>

            <div class="lokis-formquestion-item">
                <label for="loki-email" class="lokisloop-label">
                    <span class="lokis-formquestion-order">2. </span>
                    <?php _e('Email', 'lokis-loop'); ?> <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-email'
                    placeholder="Enter your Email">
            </div>

            <div class="lokis-formquestion-item">
                <label for="loki-organization" class="lokisloop-label">
                    <span class="lokis-formquestion-order">3. </span>
                    <?php _e('Organization Name ', 'lokis-loop'); ?><span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                    id='loki-organization' placeholder="Enter your organization's name">
            </div>

            <div class="lokis-formquestion-item">
                <label for="organizational_name" class="lokisloop-label"><span class="lokis-formquestion-order">
                        4. </span>
                    <?php _e('What best describes your organization ', 'lokis-loop'); ?><span
                        class="lokis-req-field">*</span>
                </label>
                <div class="lokisloop-selective-box">
                    <input type="radio" id="library" name="loki_organization_type" value="Public-Library">
                    <label for="library" class="lokisloop-label">
                        <?php _e('Public library', 'lokis-loop'); ?>
                    </label>
                </div>

                <div class="lokisloop-selective-box">
                    <input type="radio" id="university/college" name="loki_organization_type" value="University/College">
                    <label for="university/college" class="lokisloop-label">
                        <?php _e('University/College', 'lokis-loop'); ?>
                    </label>
                </div>

                <div class="lokisloop-selective-box">
                    <input type="radio" id="school" name="loki_organization_type" value="K-12 School">
                    <label for="school" class="lokisloop-label">
                        <?php _e('K-12 school', 'lokis-loop'); ?>
                    </label>
                </div>

                <div class="lokisloop-selective-box">
                    <input type="radio" id="museum" name="loki_organization_type" value="Museum">
                    <label for="museum" class="lokisloop-label">
                        <?php _e('Museum', 'lokis-loop'); ?>
                    </label>
                </div>

                <div class="lokisloop-selective-box">
                    <input type="radio" id="non-profit" name="loki_organization_type" value="Other Organization">
                    <label for="non-profit" class="lokisloop-label">
                        <?php _e('Non-profit', 'lokis-loop'); ?>
                    </label>
                </div>

                <div class="lokisloop-selective-box">
                    <input type="radio" id="other-organization" name="loki_organization_type" value="Non-profit">
                    <label for="other-organization" class="lokisloop-label">
                        <?php _e('Other Organization (non-library)', 'lokis-loop'); ?>
                    </label>
                </div>

                <div class="lokis-formquestion-item">
                    <label for="loki-country" class="lokisloop-label">
                        <span class="lokis-formquestion-order">5. </span>
                        <?php _e('Country', 'lokis-loop'); ?>
                        <span class="lokis-req-field">*</span>
                    </label>
                    <select id="loki-country" class="lokis-input form-control">
                        <option value=''>
                            <?php _e('Select a Value', 'lokis-loop'); ?>
                        </option>
                        <option value="United States">
                            <?php _e('United States', 'lokis-loop'); ?>
                        </option>
                        <option value="Canada">
                            <?php _e('Canada', 'lokis-loop'); ?>
                        </option>
                    </select>
                </div>

                <div class="lokis-formquestion-item">
                    <label for="loki-zipcode" class="lokisloop-label">
                        <span class="lokis-formquestion-order">6. </span>
                        <?php _e('Zipcode(US and Canada only)', 'lokis-loop'); ?><span class="lokis-req-field">*</span>
                    </label>
                    <input aria-label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-zipcode'
                        placeholder="<?php _e('Enter your zipcode', 'lokis-loop'); ?>">
                </div>

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