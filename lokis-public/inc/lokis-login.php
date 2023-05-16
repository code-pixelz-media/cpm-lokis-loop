<?php

//Creating a shortcode for login form
function lokis_loop_login()
{
    ob_start();
    ?>



 <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_login', 'lokis_loop_login');