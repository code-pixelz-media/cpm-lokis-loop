<?php


function lokis_loop_register_form()
{
    ob_start();
    ?>

    <!-- creating game host application form -->

    <div class="container">

        <form action="" method="post">
            <div class="mb-3">
                <label for="name" class="form control">Name <span class="req-field">*</span></label><br>
                <input type="text" class="form control" id="name" name="name" value="name"><br><br>
            </div>
            <div class="mb-3">
                <label for="Email">Email <span class="req-field">*</span></label><br>
                <input type="email" id="email" name="email" value="email"><br><br>
            </div>
            <div class="mb-3">
                <label for="organization">Organization Name <span class="req-field">*</span></label><br>
                <input type="text" id="organization" name="organization" value="organization"><br><br>
            </div>
            <div class="mb-3">
                <label for="organization_name" class="form control">What best describes your organization <span
                        class="req-field">*</span></label><br>
                <input type="radio" id="organization_name" name="organtization_name" value="organization_name">

                <label for="public library" class="form control">public library</label><br>
                <input type="radio" id="library" name="public_library" value="public library">

                <label for="university" class="form control">University/college</label><br>
                <input type="radio" id="university" name="university" value="University/college">

                <label for="school" class="form control">k-12 school</label><br>
                <input type="radio" id="school" name="school" value="k-12 school">

                <label for="meseum" class="form control">Meseum</label><br>
                <input type="radio" id="meseum" name="meseum" value="Meseum">

                <label for="non-profit" class="form control">Non-profit</label><br>
                <input type="radio" id="non-profit" name="non-profit" value="Non-profit">

                <label for="other-organization" class="form control">Other Organization (non-library)</label><br><br>
            </div>
            <div class="mb-3">
                <label for="country-name">Country <span class="req-field">*</span></label><br>
                <input type="text" id="country-name" name="country-name" value="Country"><br><br>
            </div>
            <div class="mb-3">

                <label for="role" class="form control">Role <span class="req-field">*</span></label><br>
                <input type="radio" id="role" name="role" value="Role">
                <label for="host">Host</label><br>
                <input type="radio" id="player" name="player">
                <label for="player">Player</label><br><br>
            </div>
            <div class="mb-3">
                <label for="zipcode">Zipcode (US and Canada only) </label><br>
                <input type="number" id="zipcode" name="zipcode" value="Zipcode"><br><br>
            </div>
            <div class="mb-3">
                <input type="submit" id="Submit-button">
            </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');