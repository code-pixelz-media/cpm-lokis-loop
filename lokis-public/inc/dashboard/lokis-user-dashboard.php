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

        <div class="lokisloop-hosted-game">
            




        </div>




    </div>


    <?php
    return ob_get_clean();


}
add_shortcode('lokis_loop_user_dashboard', 'lokis_loop_user_dashboard');