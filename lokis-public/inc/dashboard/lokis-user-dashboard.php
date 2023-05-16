<?php

//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    ob_start();
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
                                        <div class="lokis-form-item">
                                            <label for="loki-name" class="form_control">Name :</label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-name' placeholder="Enter your name">
                                        </div>

                                        <div class="lokis-form-item">
                                            <label for="loki-email" class="form_control"> Email : </label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-email' placeholder="Enter your Email">
                                        </div>

                                        <div class="lokis-form-item">
                                            <label for="loki-organization" class="form_control">Organization Name :</label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-organization' placeholder="Enter your organization's name">
                                        </div>


                                        <div class="lokis-form-item lokis-radio">
                                            <label for="organizational_name" class="form_control"> What best describes your
                                                organization : </label>
                                            <div class="lokisloop-selective-box"> <input type="radio" id="library"
                                                    name="loki_organization_type" value="Public-Library">
                                                <label for="library" class="form_control"><span>Public
                                                        library</span>
                                                </label>
                                            </div>
                                            <div class="lokisloop-selective-box">
                                                <input type="radio" id="university/college" name="loki_organization_type"
                                                    value="University/College">
                                                <label for="university/college"
                                                    class="form_control"><span>University/College</span>
                                                </label>
                                            </div>

                                            <div class="lokisloop-selective-box"> <input type="radio" id="school"
                                                    name="loki_organization_type" value="K-12 School">
                                                <label for="school" class="form_control">K-12 school</label>
                                            </div>


                                            <div class="lokisloop-selective-box">
                                                <input type="radio" id="museum" name="loki_organization_type"
                                                    value="Museum">
                                                <label for="museum" class="form_control">Museum</label>
                                            </div>
                                            <div class="lokisloop-selective-box">
                                                <input type="radio" id="non-profit" name="loki_organization_type"
                                                    value="Other Organization">
                                                <label for="non-profit" class="form_control">Non-profit</label>
                                            </div>
                                            <div class="lokisloop-selective-box">
                                                <input type="radio" id="other-organization" name="loki_organization_type"
                                                    value="Non-profit">
                                                <label for="other-organization" class="form_control">Other Organization
                                                    (non-library)</label>
                                            </div>
                                        </div>
                                        <div class="lokis-form-item">
                                            <label for="loki-password" class="form_control">Current Password :</label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-password' placeholder="Enter your Current Password">
                                        </div>
                                        <div class="lokis-form-item">

                                            <label for="password" class="form_control"> NewPassword : </label>
                                            <input aria label="single line text" maxlength="4000" class="lokis-form-control"
                                                id='loki-organization' placeholder="Enter your New Password">

                                            <div class="lokis-form-item">
                                                <label for="password" class="form_control">Re-type Password : </label>
                                                <input aria label="single line text" maxlength="4000"
                                                    class="lokis-form-control" id='loki-organization'
                                                    placeholder="Re-Type your Password">
                                            </div>
                                        </div>

                                        <button class="edit-button">Update</button>
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