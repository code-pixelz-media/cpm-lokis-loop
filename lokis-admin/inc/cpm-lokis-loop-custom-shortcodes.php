<?php

//Creating a shortcode for register form
function lokis_loop_register_form()
{
    ob_start();
    ?>

    <!-- creating game host application form -->

    <div class="lokis-gamehost-application-container">
        <div id='error-message'></div>
        <form action="" method="post">
            <?php wp_nonce_field(-1, 'loki_registration_nonce'); ?>
            <div id="lokis-feedback"></div>

            <div class="lokis-formquestion-item">
                <label for="loki-name" class="form_control">
                    <span class="lokis-formquestion-order">1.</span>Name <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-name'
                    placeholder="Enter your name">
            </div>

            <div class="lokis-formquestion-item">
                <label for="loki-email" class="form_control">
                    <span class="lokis-formquestion-order">2.</span>Email <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-email'
                    placeholder="Enter your Email">
            </div>

            <div class="lokis-formquestion-item">
                <label for="loki-organization" class="form_control">
                    <span class="lokis-formquestion-order">3.</span>Organization Name <span class="lokis-req-field">*</span>
                </label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                    id='loki-organization' placeholder="Enter your organization's name">
            </div>


            <div class="lokis-formquestion-item">
                <label for="organizational_name" class="form_control"><span class="lokis-formquestion-order">
                        4.</span>What best describes your organization <span class="lokis-req-field">*</span>
                </label>

                <input type="radio" id="library" name="loki_organization_type" value="Public-Library">
                <label for="library" class="form_control">Public library</label>

                <input type="radio" id="university/college" name="loki_organization_type" value="University/College">
                <label for="university/college" class="form_control">University/College</label>

                <input type="radio" id="school" name="loki_organization_type" value="K-12 School">
                <label for="school" class="form_control">K-12 school</label>

                <input type="radio" id="museum" name="loki_organization_type" value="Museum">
                <label for="museum" class="form_control">Museum</label>

                <input type="radio" id="non-profit" name="loki_organization_type" value="Other Organization">
                <label for="non-profit" class="form_control">Non-profit</label>

                <input type="radio" id="other-organization" name="loki_organization_type" value="Non-profit">
                <label for="other-organization" class="form_control">Other Organization (non-library)</label>

            </div>
            <div class="lokis-formquestion-item">
                <label for="loki-country" class="form_control"><span class="lokis-formquestion-order">5.</span>Country <span
                        class="lokis-req-field">*</span></label>
                <input aria label="single line text" maxlength="4000" class="lokis-input form-control" id='loki-country'
                    placeholder="Enter your country's name">
            </div>
            <div class="lokis-formquestion-item">

                <label for="role" class="form_control"><span class="lokis-formquestion-order">6.</span>Role <span
                        class="lokis-req-field">*</span></label>
                <input type="radio" id="host" name="role" value="host">
                <label for="host">Host</label>
                <input type="radio" id="player" name="role" value="player">
                <label for="player">Player</label>

            </div>
            <div class="lokis-formquestion-item">
                <label for="loki-zipcode" class="form control"><span class="lokis-formquestion-order">7.</span>Zipcode (US
                    and
                    Canada
                    only) <span class="lokis-req-field">*</span> </label>
                <input type='number' class="lokis-input form-control" id='loki-zipcode' placeholder="Enter your Zipcode">
            </div>
            <input type="submit" class="lokis-registration-button" id="lokis-registration-button">

        </form>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');