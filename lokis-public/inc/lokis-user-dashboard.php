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
    </div>


    <?php
    return ob_get_clean();
}