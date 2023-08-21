<?php
if (is_user_logged_in()) {

    get_header();

    ?>
    <main id="content" class="site-main post-21 page type-page status-publish hentry">
        <div class="lokis-user-dashboard-section">
            <div class="lokisloop-dashboard-container">
                <aside>
                    <?php lokis_account_menu(); ?>
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