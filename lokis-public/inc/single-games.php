<?php
wp_head();

// Check if the query parameter exists
if (isset($_GET['offlinegame'])) {
    $post_id = get_the_ID();
    ?>
    <div class="lokis-offline-modal-answer" id="lokisOfflineModal">
        <div class="lokis-offline-modal-content">
            <div class="lokis-post-form">
                <form action="" method="post">
                    <input type="hidden" value="<?php echo $post_id; ?>" id="loki-post-id">
                    <div class="lokis-offline-modal-title">
                        <h3>
                            <?php echo get_the_title($post_id); ?>
                        </h3>
                    </div>
                    <div class="lokisloop-answer">
                        <label for="lokis-answer">Answer:</label>
                        <input type="text" id="lokis-answer" name="lokis-answer">
                        <button type="submit" id="lokis-offline-submit-btn" class="button lokis-submit-btn">Check
                            Answer</button>
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
        <div class="lokisloop-iframe-container">
            <!-- creating lokis-loop paragraph-content -->
            <div class="lokis-paragraph-content">
                <?php
                echo get_post_field('post_content', get_the_ID());
                ?>
            </div>
            <!-- creating lokis-loop ifarme -->
            <div class="iframe-container">
                <div class="lokis-iframe">
                    <iframe id="loki-game-iframe" src="<?php echo get_post_meta(get_the_ID(), 'lokis_loop_game_url', true); ?>"
                        allow="autoplay; fullscreen; gamepad; xr; gyroscope; accelerometer"
                        sandbox="allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-presentation allow-scripts allow-same-origin allow-popups-to-escape-sandbox allow-downloads"
                        scrolling="no" allowfullscreen="true"></iframe>

                </div>
            </div>
            <!-- creating lokis-loop post-form -->
            <div class="lokis-post-form">
                <form action="" method="post">
                    <input type="hidden" value="<?php echo get_the_ID(); ?>" id="loki-post-id">
                    <input type="hidden" value="<?php echo $session_id; ?>" id="loki-session-id">
                    <div class="lokisloop-answer">
                        <label for="lokis-answer">Answer:</label>
                        <input type="text" id="lokis-answer" name="lokis-answer">
                        <button type="submit" id="lokis-submit-btn" class="button lokis-submit-btn">Check Answer</button>
                    </div>
                </form>
                <button id='lokis-fullscreen'>Go Fullscreen</button>
                <div id='lokis-feedback'></div>
                <!-- Cookie Consent Popup -->
                <?php lokis_cookies_content_popup() ?>
            </div>
        </div>
        <?php
    }
}
?>
<?php
wp_footer();