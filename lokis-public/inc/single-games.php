<?php
wp_head();

//Check if it is the user that is accessing the page

if (is_user_logged_in()) {

    if (get_post_type() === 'games') {
        $session_id = lokis_getSessionIDFromURL();
        ?>
        <div class="lokisloop-container">
            <!-- creating lokis-loop paragraph-content -->
            <div class="lokis-paragraph-content">
                <?php
                echo get_post_field('post_content', get_the_ID());
                ?>
            </div>
            <!-- creating lokis-loop ifarme -->
            <div class="iframe-container">
                <div class="lokis-iframe">
                    <iframe id="game-iframe" src="<?php echo get_post_meta(get_the_ID(), 'lokis_loop_game_url', true); ?>"
                        allow="autoplay; fullscreen; gamepad; xr; gyroscope; accelerometer"
                        sandbox="allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-presentation allow-scripts allow-same-origin allow-popups-to-escape-sandbox allow-downloads"
                        scrolling="no" allowfullscreen="true" style="width:100%;height:400px;"></iframe>

                </div>
            </div>
            <!-- creating lokis-loop post-form -->
            <div class="lokis-post-form">
                <form action="" method="post">
                    <input type="hidden" value="<?php echo get_the_ID(); ?>" id="loki-post-id">
                    <input type="hidden" value="<?php echo $session_id; ?>" id="loki-session-id">
                    <input type="hidden" value="<?php echo get_current_user_id(); ?>" id="loki-player-id">
                    <div class="lokisloop-answer">
                        <label for="lokis-answer">Answer:</label>
                        <input type="text" id="lokis-answer" name="lokis-answer">
                        <button type="submit" id="lokis-submit-btn" class="button lokis-submit-btn">Check Answer</button>
                    </div>
                </form>
                <button id='lokis-fullscreen'>Go Fullscreen</button>
                <div id='lokis-feedback'></div>
            </div>
        </div>
        <?php
    }
} else {
    // if not logged in, show login form
    cpm_lokis_login_form();
}
wp_footer();