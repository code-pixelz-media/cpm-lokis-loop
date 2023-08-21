<?php



/* The above code is a PHP function that generates a shortcode in WordPress. The shortcode is called
"lokis_loop_game_card" and when used in a post or page, it will display a set of game cards. */
if (!function_exists('lokis_loop_game_card')) {
    function lokis_loop_game_card()
    {
        ob_start();

        $args_pages = array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            // Set the number of pages to display (-1 for all)
            'orderby' => 'meta_value',
            'order' => 'DES',
            'meta_query' => array(
                array(
                    'key' => 'lokis_primary_game_checkbox',
                    'value' => '1',
                    'compare' => '='
                )
            )
        );

        $query_pages = new WP_Query($args_pages);

        $args_games = array(
            'post_type' => 'games',
            'posts_per_page' => -1,
            // Set the number of games to display (-1 for all)
            'orderby' => 'meta_value',
            'order' => 'DES',
            'meta_query' => array(
                array(
                    'key' => 'lokis_primary_game_checkbox',
                    'value' => '1',
                    'compare' => '='
                )
            )
        );

        $query_games = new WP_Query($args_games);

        $total_posts_pages = $query_pages->found_posts;

        $total_posts_games = $query_games->found_posts;
        $total_posts = $total_posts_pages + $total_posts_games;
        $remaining = 3 - ($total_posts % 3);

        $placeholder_image = plugin_dir_url(__FILE__) . '../assets/images/card-placeholder.png';

        if ($query_pages->have_posts() || $query_games->have_posts()) {

            echo '<div class="lokis-card-games-wrapper">';
            // Display posts from "pages"
            while ($query_pages->have_posts()) {
                $query_pages->the_post();
                $post_id = get_the_ID();
                // Display the post content from pages
                lokis_loop_game_card_content($post_id, get_permalink($post_id));
            }

            // Display posts from "games"
            while ($query_games->have_posts()) {
                $query_games->the_post();
                $post_id = get_the_ID();
                // Display the post content from games
                lokis_loop_game_card_content($post_id, get_permalink($post_id));
            }
            // Reset postdata after the loops
            wp_reset_postdata();

            // Display remaining empty spaces
            if ($remaining < 3 && $remaining > 0) {
                for ($i = 0; $i < $remaining; $i++) {
                    ?>
                    <div class="lokis-games-card empty-card lokis-flex-card">
                        <div class="lokis-game-placeholder-card-image">
                            <img src="<?php echo $placeholder_image ?>" alt="Placeholder Image">
                        </div>
                        <div class="lokis-game-placeholder-card-title">
                            <h3>
                                <?php echo esc_html(__('New Game Coming Soon', 'lokis-loop')); ?>
                            </h3>
                        </div>
                    </div>

                    <?php
                }
            }
            echo '</div>';
        } else {
            echo "<div class='lokis-empty-card-games-wrapper'>";
            // Display 3 empty spaces
            for ($i = 0; $i < 3; $i++) {
                ?>
                <div class="lokis-games-card empty-card lokis-flex-card">
                    <div class="lokis-game-placeholder-card-image">
                        <img src="<?php echo $placeholder_image ?>" alt="Placeholder Image">
                </div>
                <div class="lokis-game-placeholder-card-title">
                    <h3>
            <?php echo esc_html(__('New Game Coming Soon', 'lokis-loop')); ?>
                </h3>
            </div>
        </div>
<?php
            }
            echo '</div>';
        }
        return ob_get_clean();
    }
    add_shortcode('lokis_loop_game_card', 'lokis_loop_game_card');
}


/* The code block is defining a function called `lokis_hosted_games_card` in PHP. This function is used
to generate a card layout for displaying hosted games. */

if (!function_exists('lokis_hosted_games_card')) {
    function lokis_hosted_games_card()
    {
        ob_start();

        /* dr test */
        $args_pages = array(
            'post_type' => 'page',
            'posts_per_page' => -1,
            // Set the number of pages to display (-1 for all)
            'orderby' => 'meta_value',
            'order' => 'DES',
            'meta_query' => array(
                array(
                    'key' => 'lokis_primary_game_checkbox',
                    'value' => '1',
                    'compare' => '='
                )
            )
        );

        $query_pages = new WP_Query($args_pages);

        $args_games = array(
            'post_type' => 'games',
            'posts_per_page' => -1,
            // Set the number of games to display (-1 for all)
            'orderby' => 'meta_value',
            'order' => 'DES',
            'meta_query' => array(
                array(
                    'key' => 'lokis_primary_game_checkbox',
                    'value' => '1',
                    'compare' => '='
                )
            )
        );

        $query_games = new WP_Query($args_games);

        $total_posts_pages = $query_pages->found_posts;

        $total_posts_games = $query_games->found_posts;
        $total_posts = $total_posts_pages + $total_posts_games;
        $remaining = 3 - ($total_posts % 3);

        $placeholder_image = plugin_dir_url(__FILE__) . '../assets/images/card-placeholder.png';

        if ($query_pages->have_posts() || $query_games->have_posts()) {

            echo '<div class="lokis-card-games-wrapper">';
            // Display posts from "pages"
            while ($query_pages->have_posts()) {
                $query_pages->the_post();
                $post_id = get_the_ID();
                $redirect_url = home_url() . "/user-dashboard/current-games/?post_id=" . $post_id;
                // Display the post content from pages
                lokis_loop_game_card_content($post_id, $redirect_url);
            }

            // Display posts from "games"
            while ($query_games->have_posts()) {
                $query_games->the_post();
                $post_id = get_the_ID();
                $redirect_url = home_url() . "/user-dashboard/current-games/?post_id=" . $post_id;
                // Display the post content from pages
                lokis_loop_game_card_content($post_id, $redirect_url);
            }
            // Reset postdata after the loops
            wp_reset_postdata();

            // Display remaining empty spaces
            if ($remaining < 3 && $remaining > 0) {
                for ($i = 0; $i < $remaining; $i++) {
                    ?>
        <div class="lokis-games-card empty-card lokis-flex-card">
            <div class="lokis-game-placeholder-card-image">
                <img src="<?php echo $placeholder_image ?>" alt="Placeholder Image">
            </div>
            <div class="lokis-game-placeholder-card-title">
                <h3>
                    <?php echo esc_html(__('New Game Coming Soon', 'lokis-loop')); ?>
                </h3>
            </div>
        </div>

        <?php
                }
            }
            echo '</div>';
        } else {
            echo "<div class='lokis-empty-card-games-wrapper'>";
            // Display 3 empty spaces
            for ($i = 0; $i < 3; $i++) {
                ?>
                <div class="lokis-games-card empty-card lokis-flex-card">
                    <div class="lokis-game-placeholder-card-image">
                        <img src="<?php echo $placeholder_image ?>" alt="Placeholder Image">
                </div>
                <div class="lokis-game-placeholder-card-title">
                    <h3>
            <?php echo esc_html(__('New Game Coming Soon', 'lokis-loop')); ?>
                </h3>
            </div>
        </div>
<?php
            }
            echo '</div>';
        }
        return ob_get_clean();
    }
    add_shortcode('lokis_hosted_games_card', 'lokis_hosted_games_card');
}





/* The code block is defining a function called `lokis_loop_placeholder_games_card`. This function is
used to generate a placeholder card for games that are coming soon. */

if (!function_exists('lokis_loop_placeholder_games_card')) {
    function lokis_loop_placeholder_games_card()
    {
        ob_start();

        $placeholder_image = plugin_dir_url(__FILE__) . '../assets/images/card-placeholder.png';
        echo "<div class='lokis-empty-card-games-wrapper'>";
        // Display 3 empty spaces
        for ($i = 0; $i < 3; $i++) {
            ?>
            <div class="lokis-games-card empty-card lokis-flex-card">
                <div class="lokis-game-placeholder-card-image">
                    <img src="<?php echo $placeholder_image ?>" alt="Placeholder Image">
            </div>
            <div class="lokis-game-placeholder-card-title">
                <h3>
            <?php echo esc_html(__('New Game Coming Soon', 'lokis-loop')); ?>
            </h3>
        </div>
    </div>
<?php
        }
        echo '</div>';
        return ob_get_clean();
    }
    add_shortcode('lokis_loop_placeholder_games_card', 'lokis_loop_placeholder_games_card');
}