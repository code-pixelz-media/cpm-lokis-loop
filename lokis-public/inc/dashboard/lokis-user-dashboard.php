<?php

//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    ob_start();
    ?>

    <div class="lokisloop-dashboard-container">

        <aside>
            <div class="lokies-logo">
                <a id="home-page" class="nav-link " href="/">Loki's Loop</a>

            </div>

            <?php echo lokis_account_menu(); ?>

        </aside>


        <!-- for user profile -->

        <div class="lokisloop-container">
            <div id="logo" class="lokisloop-logo">Lokisloop</div>

            <div class="lokisloop-profile">
                <div class="lokisloop-user-profile"></div>
                <div class="container">

                    <div class="leftbox"></div>


                    <div class="rightbox">
                        <div class="profile">
                            <h1>Personal Info</h1>
                            <label for="loki-name" class="form_control">
                                Name <span class="lokis-req-field">*</span>
                            </label><br>
                            <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                                id='loki-name' placeholder="Enter your name"><br><br>

                            <label for="loki-email" class="form_control">
                                Email <span class="lokis-req-field">*</span>
                            </label><br>
                            <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                                id='loki-email' placeholder="Enter your Email"><br><br>

                            <label for="loki-organization" class="form_control">
                                Organization Name <span class="lokis-req-field">*</span>
                            </label><br>
                            <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                                id='loki-organization' placeholder="Enter your organization's name"><br><br>


                            <label for="organizational_name" class="form_control">
                                What best describes your organization <span class="lokis-req-field">*</span>
                            </label><br>

                            <input type="radio" id="library" name="loki_organization_type" value="Public-Library">
                            <label for="library" class="form_control">Public library</label><br>

                            <input type="radio" id="university/college" name="loki_organization_type"
                                value="University/College">
                            <label for="university/college" class="form_control">University/College</label><br>

                            <input type="radio" id="school" name="loki_organization_type" value="K-12 School">
                            <label for="school" class="form_control">K-12 school</label><br>

                            <input type="radio" id="museum" name="loki_organization_type" value="Museum">
                            <label for="museum" class="form_control">Museum</label><br>

                            <input type="radio" id="non-profit" name="loki_organization_type" value="Other Organization">
                            <label for="non-profit" class="form_control">Non-profit</label><br>

                            <input type="radio" id="other-organization" name="loki_organization_type" value="Non-profit">
                            <label for="other-organization" class="form_control">Other Organization
                                (non-library)</label><br><br>

                            <label for="loki-name" class="form_control">
                                Current Password <span class="lokis-req-field">*</span>
                            </label><br>
                            <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                                id='loki-name' placeholder="Enter your password"><br><br>

                            <label for="loki-name" class="form_control">
                                New Password <span class="lokis-req-field">*</span>
                            </label><br>
                            <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                                id='loki-name' placeholder="Enter your password"><br><br>

                            <label for="loki-name" class="form_control">
                                Re-type Password <span class="lokis-req-field">*</span>
                            </label><br>
                            <input aria label="single line text" maxlength="4000" class="lokis-input form-control"
                                id='loki-name' placeholder="Enter your password"><br><br>

                            <button class="update-button">Edit</button>
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