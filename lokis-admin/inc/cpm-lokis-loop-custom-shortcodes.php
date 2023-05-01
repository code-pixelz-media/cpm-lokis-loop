<?php


function lokis_loop_register_form()
{
    ob_start();
    ?>


<?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_register_form', 'lokis_loop_register_form');