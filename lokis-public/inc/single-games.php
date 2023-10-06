<?php
wp_head();

// Check if the query parameter exists
if (!is_user_logged_in() && isset($_GET['offlinegame'])) {
    $post_id = get_the_ID();
    $session_id = $_GET['offlinegame'];
    ?>
    <div class="lokis-offline-modal-answer lokis-loop-single-games-wrapper" id="lokisOfflineModal">
        <div class="lokis-offline-modal-content">
            <div class="lokis-offline-post-form">
                <form action="" method="post">
                    <input type="hidden" value="<?php echo $post_id; ?>" id="loki-post-id">
                    <div class="lokis-offline-modal-title">
                        <h3>
                            <?php echo get_the_title($post_id); ?>
                        </h3>
                    </div>
                    <div class="lokisloop-offline-answer">
                        <label for="lokis-answer">
                            <?php _e('Answer:', 'lokis-loop') ?>
                        </label>
                        <input type="text" id="lokis-answer" name="lokis-answer">
                        <input type="hidden" value="<?php echo $session_id; ?>" id="loki-session-id">
                        <button type="submit" id="lokis-submit-btn" class="button lokis-submit-btn">
                            <?php _e('Check Answer', 'lokis-loop') ?>
                        </button>
                    </div>
                </form>
                <div id="lokis-feedback"></div>
            </div>
        </div>
    </div>
    <?php
} else {
    if (get_post_type() === 'games') {
        $session_id = lokis_getSessionIDFromURL();
        ?>
        <div class="lokisloop-iframe-container lokis-loop-single-games-wrapper">
            <!-- creating lokis-loop paragraph-content -->
            <?php //the_title();?>
            <div class="lokis-paragraph-content">
                <?php
                the_content();
                ?>
            </div>
            <!-- creating lokis-loop ifarme -->
            <div class="iframe-container">
                <div class="lokis-iframe">
                    <?php
                    $gameUrl = get_post_meta(get_the_ID(), 'lokis_loop_game_url', true);
                    // Check if the 'src' attribute has a valid value (not empty)
                    if (!empty($gameUrl)) {
                        ?>
                        <iframe id="loki-game-iframe" src="<?php echo $gameUrl; ?>"
                            allow="autoplay; fullscreen; gamepad; xr; gyroscope; accelerometer"
                            sandbox="allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-presentation allow-scripts allow-same-origin allow-popups-to-escape-sandbox allow-downloads"
                            scrolling="no" allowfullscreen="true"></iframe>
                        <?php
                    }
                    ?>

                    <!-- creating lokis-loop post-form -->
                    <div class="lokis-post-form">
                        <form action="" method="post">
                            <input type="hidden" value="<?php echo get_the_ID(); ?>" id="loki-post-id">
                            <input type="hidden" value="<?php echo $session_id; ?>" id="loki-session-id">
                            <div class="lokisloop-answer">
                                <label for="lokis-answer">
                                    <?php
                                    _e('Answer:', 'lokis-loop');
                                    ?>
                                </label>
                                <input type="text" id="lokis-answer" name="lokis-answer">
                                <button type="submit" id="lokis-submit-btn" class="button lokis-submit-btn">
                                    <?php _e('Check Answer', 'lokis-loop') ?>
                                </button>
                            </div>
                        </form>
                        <?php
                        if (!empty($gameUrl)) {
                            echo "<i id='lokis-fullscreen' class='fa-solid fa-expand'></i>";
                        }
                        ?>
                    </div>
                </div>
            </div>
            <div id='lokis-feedback'></div>
        </div>
        <?php
    }
}
?>
<?php
wp_footer();