<?php

//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    ob_start();
    ?>





    <div class="lokisloop-dashboard-container">
        <aside>
            <ul>
                <li><a href="#" class="active">DashBoard </a> </li>


                <li><a href="#"> <i class="fa-regular fa-user"></i>
                        <span class="nav-item">Profile </span>
                    </a>
                </li>


                <li><a href="#"> <i class="fa-regular fa-chart-bar"></i>
                        <span class="nav-item">Analytics</span>
                    </a>
                </li>


                <li><a href="#"> <i class="fa-solid fa-list-check"></i>
                        <span class="nav-item"> Reports</span>
                    </a>
                </li>

                <li><a herf="#" title="Help">
                        <i class="fa-solid fa-circle-question"></i>
                        <span class="nav-item">Help</span>
                    </a>
                </li>

                <li><a href="#">
                        <i class="fa-solid fa-gear"></i>
                        <span class="nav-item">Setting</span>
                    </a>
                </li>

                <li><a herf="#" title="LogOut">
                        <i class="fa-solid fa-arrow-right-from-bracket"></i>
                        <span class="nav-item">LogOut</span>
                    </a>
                </li>


            </ul>
        </aside>



    </div>

    <section class="home-section">
        <nav>
            <div class="sidebar-button">
                <i class='bx bx-menu sidebarBtn'></i>
                <span class="dashboard">Dashboard</span>
            </div>
            <div class="search-box">
                <input type="text" placeholder="Search...">
                <i class='bx bx-search'></i>
            </div>
            <div class="profile-details">
                <img src="images/profile.jpg" alt="">
                <span class="admin_name">Lokisloop-Usename</span>
                <i class='bx bx-chevron-down'></i>
            </div>
        </nav>




        <?php
        return ob_get_clean();
}
add_shortcode('lokis_loop_user_dashboard', 'lokis_loop_user_dashboard');