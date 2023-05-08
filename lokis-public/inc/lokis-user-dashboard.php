<?php

//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    ob_start();
    ?>
    <script src="https://kit.fontawesome.com/yourcode.js" crossorigin="anonymous"></script>

    <div class="profile-header container-fluid">
        <div class="header-title">
            <h1> My profile </h1>
        </div>
    </div>
    <menu id="user" class="dynamicMenu">
        <div class="user-profile">
            <div class="profile-pic">
                <img src="https://images.unsplash.com/photo-1549669944-ca3e8b576248?ixlib=rb-1.2.1&auto=format&fit=crop&w=334&q=80"
                    alt="username here" />
            </div>
            <div class="user-info">
                <div class="username">
                    <p><strong> Laura Egan</strong> </p>
                </div>
                <ul class="profile-menu">
                    <li><a herf="/directoryphonebook" title="Directory Phonebook"><span><i class="<i class=" fa-regular
                                    fa-address-card></i>" style="color: #a1c0f5;"></i></span></a></li>
                    <li><a herf="#" title="Help"></a></li>
                    <li><a herf="#" title="Setting"></a></li>
                    <li><a herf="#" title="LogOut"></a></li>
                </ul>
            </div>
        </div>
        <ul class="admin-menu">
            <li> <a href="#"> Link Examples </a></li>
            <li> <a href="#"> Link Examples </a></li>
            <li> <a href="#"> Link Examples </a></li>
            <li> <a href="#"> Link Examples </a></li>
        </ul>
    </menu>





    <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_user_dashboard', 'lokis_loop_user_dashboard');