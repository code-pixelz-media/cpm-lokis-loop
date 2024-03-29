<?php
if (is_user_logged_in()) {

    get_header();

    ?>
    <main id="content" class="site-main post-21 page type-page status-publish hentry">
        <div class="lokis-user-dashboard-section">
            <div class="lokisloop-dashboard-container">
                <aside>
                    <!--  here previously we had seperate menu that enabled the user to create a game and also host a game, the requirement has now changed but the code is there if in future if we need so we commented the menu and displayed the wordpress menu -->
                    <?php
                    echo lokis_dashboard_menus_items();
                    //lokis_account_menu(); 
                    ?>
                </aside>

                <div class="lokisloop-container">
                    <h5>
                        <?php echo esc_html(get_the_title()); ?>
                    </h5>
                    <div class="lokis-loop-container-content">
                        <?php
                        the_content();
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php
    get_footer();
} else {
    ?>
    <script>
        window.location.href = '<?php echo esc_url(wp_login_url()); ?>';
    </script>
    <?php
}
?>