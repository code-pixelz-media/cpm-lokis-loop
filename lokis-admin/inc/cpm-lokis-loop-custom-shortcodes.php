<?php


function lokis_loop_register_form()
{
    ob_start();
    ?>

    <!-- creating game host application form -->

    <form action="" method="post">
        <div class="mb-3">
            <label for="name" class="form control">Name:</label><br>
            <input type="text" class="form control" id="name" name="fname"><br><br>
        </div>
        <div class="mb-3">
            <label for="Email">Email:</label><br>
            <input type="email" id="email" name="f_name"><br><br>
        </div>
        <div class="mb-3">
            <label for="name">Organization Name:</label><br>
            <input type="text" id="name" name="name"><br><br>
        </div>
        <div class="mb-3">
            <label for="organization_name" class="form control">What best describes your organization:</label><br>
            <input type="radio" id="name" name="organtization_name">

            <label for="public library" class="form control">public library</label><br>
            <input type="radio" id="library" name="public_library">

            <label for="university" class="form control">University/college</label><br>
            <input type="radio" id="university" name="university">

            <label for="school" class="form control">k-12 school</label><br>
            <input type="radio" id="html" name="school">

            <label for="meseum" class="form control">Meseum</label><br>
            <input type="radio" id="meseum" name="meseum">

            <label for="non-profit" class="form control">Non-profit</label><br>
            <input type="radio" id="non-profit" name="non-profit">

            <label for="other-organization" class="form control">Other Organization (non-library)</label><br><br>
        </div>
        <div class="mb-3">
            <label for="country-name">Country:</label><br>
            <input type="text" id="country-name" name="country-name"><br><br>
        </div>
        <div class="mb-3">
            <label for="zipcode">Zipcode (US and Canada only):</label><br>
            <input type="number" id="zipcode" name="zipcode"><br><br>
        </div>
        <div class="mb-3">
            <input type="submit" id="Submit-button">
        </div>
        <?php
        return ob_get_clean();
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');