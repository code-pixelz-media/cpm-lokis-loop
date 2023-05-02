<?php

//Creating a shortcode for register form
function lokis_loop_register_form()
{
    ob_start();
    ?>

    <!-- creating game host application form -->

    <div class="gamehost-application-container">
        <div id='error-message'></div>
        <form action="" method="post">
            <div class="question-item">
                <label for="name" class="form_control">
                    <span class="question-order">1.</span>Name <span class="req-field">*</span>
                </label><br>
                <input aria label="single line text" maxlength="4000" class="form-control" id='loki-name'
                    placeholder="Enter your answer"><br><br>
            </div>

            <div class="question-item">
                <label for="Email" class="form_control">
                    <span class="question-order">2.</span>Email <span class="req-field">*</span>
                </label><br>
                <input aria label="single line text" maxlength="4000" class="form-control" id='loki-email'
                    placeholder="Enter your answer"><br><br>
            </div>

            <div class="question-item">
                <label for="organization" class="form_control">
                    <span class="question-order">3.</span>Organization Name <span class="req-field">*</span>
                </label><br>
                <input aria label="single line text" maxlength="4000" class="form-control" id='loki-organization'
                    placeholder="Enter your answer"><br><br>
            </div>


            <div class="question-item">
                <label for="organizational_name" class="form_control"><span class="question-order">
                        4.</span>What best describes your organization <span class="req-field">*</span>
                </label><br>

                <input type="radio" id="library" name="loki_organization_name" value="ORGANIZATION-NAME">
                <label for="library" class="form_control">Public library</label><br>

                <input type="radio" id="university/college" name="loki_organization_name" value="PUBLIC-LIBRARY">
                <label for="university/college" class="form_control">University/College</label><br>

                <input type="radio" id="school" name="loki_organization_name" value="UNIVERSITY/COLLEGE">
                <label for="school" class="form_control">K-12 school</label><br>

                <input type="radio" id="museum" name="loki_organization_name" value="k-12 school">
                <label for="museum" class="form_control">Museum</label><br>

                <input type="radio" id="non-profit" name="loki_organization_name" value="Meseum">
                <label for="non-profit" class="form_control">Non-profit</label><br>

                <input type="radio" id="other-organization" name="loki_organization_name" value="Non-profit">
                <label for="other-organization" class="form_control">Other Organization (non-library)</label><br><br>

            </div>
            <div class="question-item">
                <label for="country-name" class="form_control"><span class="question-order">5.</span>Country <span
                        class="req-field">*</span></label><br>
                <input aria label="single line text" maxlength="4000" class="form-control" id='loki-country'
                    placeholder="Enter your answer"><br><br>
            </div>
            <div class="question-item">

                <label for="role" class="form_control"><span class="question-order">6.</span>Role <span
                        class="req-field">*</span></label><br>
                <input type="radio" id="host" name="role" value="Role">
                <label for="host">Host</label><br>
                <input type="radio" id="player" name="role" value="player">
                <label for="player">Player</label><br><br>

            </div>
            <div class="question-item">
                <label for="zipcode" class="form control"><span class="question-order">7.</span>Zipcode (US and Canada
                    only) <span class="req-field">*</span> </label><br>
                <input type='number' class="form-control" id='loki-zipcode'
                    placeholder="Enter your Zipcode"><br><br>
            </div>
            <input type="submit" class="Submit-button" id="Submit-button">

    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');