<?php

get_header();

if (is_user_logged_in()) {

    ?>
    <main id="content" class="site-main post-21 page type-page status-publish hentry">
        <div class="lokis-user-dashboard-section">
            <div class="lokisloop-dashboard-container">
                <aside>
                    <?php lokis_account_menu(); ?>
                </aside>
                <div class="lokisloop-hosted-game">
                    <div class="lokis-host-game-title">
                        <h5>
                            <?php _e("Host a game", "lokis-loop"); ?>
                        </h5>
                        <?php
                        $session_id = bin2hex(random_bytes(9));
                        ?>
                        <form method="POST" class="row g-3">
                            <input type="hidden" name="sessionToken" id="sessionToken" value="<?php echo $session_id ?>">
                            <div class="lokis-loop-host-choose-game">
                                <select class="form-select" aria-label="Game" id="id_game" name="game">
                                    <option value="-1">
                                        <?php _e("Choose a Game", "lokis-loop"); ?>
                                    </option>

                                    <?php

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
                                    // Loop
                                
                                    if ($query_pages->have_posts() || $query_games->have_posts()) {
                                        // Display posts from "pages"
                                        while ($query_pages->have_posts()) {
                                            $query_pages->the_post();
                                            // Display the post content from pages
                                            $post_id = get_the_ID();
                                            $post_title = get_the_title();
                                            echo '<option value="' . $post_id . '">' . $post_title . '</option>';
                                        }

                                        // Display posts from "games"
                                        while ($query_games->have_posts()) {
                                            $query_games->the_post();
                                            // Display the post content from games
                                            $post_id = get_the_ID();
                                            $post_title = get_the_title();
                                            echo '<option value="' . $post_id . '">' . $post_title . '</option>';
                                        }
                                        // Reset postdata after the loops
                                        wp_reset_postdata();
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="lokis-loop-host-choose-time">
                                <div class="input-group">
                                    <input type="number" class="lokis-form-control" value="1" min="1"
                                        placeholder="Expiration Time (hours)" id="lokisLoop_expiration" name="expiration">
                                    <span class="input-group-text">
                                        <?php _e("hour(s)", "lokis-loop"); ?>
                                    </span>
                                </div>
                            </div>
                            <div class="lokis-loop-host-start-game">
                                <button type="submit" class="btn btn-primary mb-3" id="lokisStartGameButton" disabled>
                                    <?php _e("Start Game Session", "lokis-loop"); ?>
                                </button>
                            </div>
                        </form>
                        <?php
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            global $wpdb;
                            $host_id = get_current_user_id();
                            $game_id = $_POST['game'];
                            $hours = $_POST['expiration'];
                            $session_id = $_POST['sessionToken'];
                            // Inserting the data into the database or performing other operations
                    
                            $end_timestamp = date('Y-m-d H:i:s', strtotime("+$hours hours"));
                            $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions';
                            // Retrieve previously inserted sessions
                    
                            $previously_inserted_sessions = $wpdb->get_var("SELECT COUNT(session_id) FROM $lokis_game_sessions_table_name where session_id = '{$session_id}'");
                            if ($previously_inserted_sessions == 0) {
                                // Generate the URL
                    
                                $game_url = get_permalink($game_id) . '?game=' . $session_id;
                                $data = [
                                    'host_id' => $host_id,
                                    'game_id' => $game_id,
                                    'session_id' => $session_id,
                                    'expires_in' => $end_timestamp,
                                    'started_at' => date('Y-m-d H:i:s'),
                                    'gamesession_url' => $game_url,
                                ];
                                // Insert the data into the table
                    
                                $wpdb->insert($lokis_game_sessions_table_name, $data);
                                // sucess message 
                    
                                echo '<div class="lokis-loop-alert" role="alert"><p class="lokis-loop-alert-title">' . __("Game Session created successfully", "lokis-loop") . '</p> <p>' . __(
                                    "URL:",
                                    "lokis-loop"
                                ) . '<a href="' . $game_url . '" target="_blank">' . $game_url . '</p></a></div>';
                            } else {
                                echo '<div class="lokis-loop-incorrect" role="alert">' . __("Session ID already exists.", "lokis-loop") . '</div>';
                            }
                        }


                        $hostGameContent = get_option('lokis-host-a-game', '');
                        if (!empty($hostGameContent)) {
                            echo '<div class="lokisloop-content-container-wrapper">';
                            echo $hostGameContent;
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php get_footer();
} else {
    ?>
    <!-- <script>
        window.location.href = '<?php //echo wp_login_url(); ?>';
    </script> -->
    <?php
}