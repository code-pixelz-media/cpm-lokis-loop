<?php

//Creating a shortcode for user dashboard
function lokis_loop_user_dashboard()
{
    ob_start();
    ?>
    <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_user_dashboard', 'lokis_loop_user_dashboard');