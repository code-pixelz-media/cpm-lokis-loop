<?php
if (is_user_logged_in()) {

    get_header(); ?>

    <div class="lokisloop-dashboard-container">


        <aside>
            <div class="lokis-logo">
                <a id="home-page" class="nav-link " href="/">Loki's Loop</a>
            </div>

            <?php lokis_account_menu(); ?>

        </aside>

        <div class="lokisloop-container">
            <h3>
                <?php echo get_the_title(); ?>
            </h3>
            <?php echo get_the_content(); ?>

        </div>

    </div>

    <?php get_footer(); 

    } else {
    ?>

    <script>
        window.location.href = '<?php echo wp_login_url(); ?>';

    </script>

    <?php

}
?>