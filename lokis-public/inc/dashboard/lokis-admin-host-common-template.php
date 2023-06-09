<?php
if (is_user_logged_in()) {
    get_header();
    ?>

    <div class="lokisloop-dashboard-container">
        <aside>
            <?php lokis_account_menu(); ?>
        </aside>

        <div class="lokisloop-container">
            <h5>
                <?php echo esc_html(get_the_title()); ?>
            </h5>
            <?php echo wp_kses_post(get_the_content()); ?>
        </div>
    </div>

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