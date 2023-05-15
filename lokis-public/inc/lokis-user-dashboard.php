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

            <div class="lokisloop-dashboard-menu">
                <ul class="lokisloop-menu">

                    <li><a href="#"> <i class="fa-regular fa-user"></i>
                            <span class="nav-item">Profile </span>
                        </a>
                    </li>

                    <li><a href="#"> <i class="fa-regular fa-chart-bar"></i>
                            <span class="nav-item">Host A Game</span>
                        </a>
                    </li>

                    <li><a href="#" class="hosted-game"> <i class="fa-solid fa-list-check"></i>
                            <span class="nav-item"> Hosted Game</span>
                        </a>
                    </li>

                    <li><a herf="#">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i>
                            <span class="nav-item">LogOut</span>
                        </a>
                    </li>

                </ul>
            </div>

        </aside>

        <div class="lokisloop-hosted-game">
            <div class="lokisloop-container-wrapper">
                <div class="lokisloop-main-top">
                    <h5>Current Games</h5>
                </div>

                <table id="current-games" class="lookisloop-games">
                    <thead>
                        <tr>
                            <th>Game Name</th>
                            <th>Game Url</th>
                            <th>Action</th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Alfreds Futterkiste</td>
                            <td><a href="#">link text</a></td>
                            <td> <button type="button" class="button view-player">View Player</button>
                                <button type="button" class="button end-session">End Session</button>

                            </td>
                        </tr>
                        <tr>
                            <td>Berglunds snabbköp</td>
                            <td><a href="#">link text</a></td>
                            <td> <button class="button view-player">view player</button>
                                <button type="button" class="button end-session ">End Session</button>

                        </tr>
                        <tr>
                            <td>Centro comercial Moctezuma</td>
                            <td><a href="#">link text</a></td>
                            <td> <button type="button" class="button view-player">view player</button>
                                <button type="button" class="button end-session">End Session</button>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="lokisloop-container-wrapper">
                <h5> Expired Games</h5>
                <table id="expired-games" class="lookisloop-games">
                    <div class="thead">
                        <tr>
                            <td>Game Name</td>
                            <td>Game Url</td>
                            <td>Action</td>

                        </tr>
                    </div>

                    <tbody>
                        <tr>
                            <td>Alfreds Futterkiste</td>
                            <td><a href="#">link text</a></td>
                            <td> <button type="button" class="button view-player">view player</button></td>
                        </tr>
                        <tr>
                            <td>Berglunds snabbköp</td>
                            <td><a href="#">link text</a></td>
                            <td> <button type="button" class="button view-player">view player</button>

                        </tr>
                        <tr>
                            <td>Centro comercial Moctezuma</td>
                            <td><a href="#">link text</a></td>
                            <td> <button type="button" class="button view-player">view player</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
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
    <?php
    return ob_get_clean();
}