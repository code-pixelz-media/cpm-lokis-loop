<?php
get_header();
?>

<?php
//Check if it is the user that is accessing the page
if (is_user_logged_in()) {
    ?>

    <div class="lokisloop-container">

        <!-- creating lokis-loop paragraph-content -->

        <div class="lokis-paragraph-content">
            <P> Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem, aliquam. Modi a totam, enim eum
                asperiores,
                iste esse, at odit labore corrupti blanditiis consectetur autem mollitia debitis officia sit architecto!
            </P>
        </div>

        <!-- creating lokis-loop ifarme -->

        <div class="iframe-container">
            <div class="lokis-iframe">
                <iframe
                    src="<?php echo get_post_meta(get_the_ID(), 'lokis_loop_game_url', true);?>"
                    allow="autoplay; fullscreen *; geolocation; microphone; camera; midi; monetization; xr-spatial-tracking; gamepad; gyroscope; accelerometer; xr; keyboard-map *; focus-without-user-activation *; screen-wake-lock; clipboard-read; clipboard-write"
                    sandbox="allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-presentation allow-scripts allow-same-origin allow-popups-to-escape-sandbox allow-downloads"
                    scrolling="no" allowfullscreen="" style="width:100%;height:400px">
                </iframe>
            </div>
        </div>

        <!-- creating lokis-loop post-form -->

        <div class="lokis-post-form">
            <form action="" method="post">
                <div id='lokis-feedback'></div>
                <input type="hidden" value="<?php echo get_the_ID(); ?>" id="loki-post-id">
                <label for="lokis-answer">Answer:</label>
                <input type="text" id="lokis-answer" name="lokis-answer">
                <input type="submit" id="lokis-submit-btn" class='lokis-submit-btn' value="Check Answer">
            </form>
        </div>
    </div>

    <?php
    get_footer();
} else {
    get_footer();
}