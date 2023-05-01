<?php
get_header();
?>
<div class="paragraph-content">
    <P> Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptatem, aliquam. Modi a totam, enim eum asperiores,
        iste esse, at odit labore corrupti blanditiis consectetur autem mollitia debitis officia sit architecto!
    </P>
</div>
<div class="iframe">
    <iframe
        src="https://games.gdevelop-app.com/game-e80a4ac5-b828-4a36-8e0f-73f85fad831d/index.html?userSlug=antonretro&amp;gameSlug=paintra"
        allow="autoplay; fullscreen *; geolocation; microphone; camera; midi; monetization; xr-spatial-tracking; gamepad; gyroscope; accelerometer; xr; keyboard-map *; focus-without-user-activation *; screen-wake-lock; clipboard-read; clipboard-write"
        sandbox="allow-forms allow-modals allow-orientation-lock allow-pointer-lock allow-popups allow-presentation allow-scripts allow-same-origin allow-popups-to-escape-sandbox allow-downloads"
        scrolling="no" allowfullscreen="" style="width:100%;height:100%">
    </iframe>
</div>
<div class="post-form">
    <form action="/action_page.php" method="post" target="_blank">
        <label for="answer">Answer:</label>
        <input type="text" id="answer" name="answer"><br><br>
        <input type="submit" value="Submit">
    </form>
</div>
<?php
get_footer();
?>