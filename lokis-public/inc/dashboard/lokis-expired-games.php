<?php
get_header(); ?>

<div class="lokisloop-dashboard-container">
    <aside>
        <div class="lokis-logo">
            <a id="home-page" class="nav-link " href="/">Loki's Loop</a>

        </div>

        <?php lokis_account_menu(); ?>

    </aside>

    <div class="lokisloop-hosted-game">
        <!-- here is the modal box code -->
    <div class="show_modal_test"></div>
        <?php
        global $wpdb;
        $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions';
        $host_id = get_current_user_id(); // Replace with the desired host ID
        
        // Query to retrieve data for a specific host ID
        $query = $wpdb->prepare("SELECT * FROM $lokis_game_sessions_table_name WHERE host_id = %d", $host_id);

        // Run the query
        $results = $wpdb->get_results($query);

        // Check if any rows were returned
        if ($wpdb->num_rows > 0) {
            $expired_games = []; // Array to store expired games

            // Loop through the results and access the data
            foreach ($results as $row) {

                // Access individual fields using object notation
                $id = $row->id;
                $host_id = $row->host_id;
                $game_id = $row->game_id;
                $session_id = $row->session_id;
                $expires_in = $row->expires_in;
                $started_at = $row->started_at;
                $url = $row->gamesession_url;

                $title = get_the_title($game_id);

                $expires_at = new DateTime($expires_in);

                if ($expires_at < new DateTime()) {
                    // Store expired game data in the array
                    $expired_games[] = [
                        'id' => $id,
                        'title' => $title,
                        'url' => $url,
                        "game_id" => $game_id
                    ];
                }
            }

            // Display expired games
            if (!empty($expired_games)) {
                ?>
                <div class="lokisloop-container-wrapper">
                    <h5> Expired Games</h5>
                    <table id="expired-games" class="lookisloop-games">
                        <div class="thead">
                            <tr>
                                <th>Game Name</th>
                                <th>Game Url</th>
                                <th>Action</th>
                            </tr>
                        </div>

                        <tbody>
                            <?php
                            //Set number of games per page
                            $games_per_page = 1;

                            //Calculate starting index for specific page
                            $expired_paged = isset($_GET['expired-games']) ? abs((int) $_GET['expired-games']) : 1;

                            // Calculate the offset for the expired page
                            $expired_offset = ($expired_paged - 1) * $games_per_page;

                            // Retrieve the total number of games
                            $total_expired_games = count($expired_games);

                            // Calculate the total number of pages
                            $total_expired_pages = ceil($total_expired_games / $games_per_page);

                            // Retrieve the subset of games for the expired page
                            $expired_page_games = array_slice($expired_games, $expired_offset, $games_per_page);

                            foreach ($expired_page_games as $game) {
                                // Perform actions with the retrieved data
                                echo '<tr><td>' . $game['title'] . '</td><td><div class="lokis-view-copy-btn"><a class="lokisloop-visit-url" target="_blank" href="' . $game['url'] . '" title="' . $game['url'] . '">Visit Url</a>';
                                echo '<a data-url="' . $game['url'] . '" class="lokisloop-url-copy" >  ' . "     " . 'Copy URL</a></td><td>';
                                echo '<div class="lokis-action-td">';
                                echo '<form method="POST" action="">';
                                echo '<input type="hidden" name="game_id" value="' . $game['game_id'] . '">';
                                echo '<button id="lokisLoopModalBox" class="button view-player modal-toggle" name="view_player" data-game-id="' . $game['game_id'] . '" >View Player</button>';
                                echo '</form></div></td></tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php
                        $current_url = $_SERVER['REQUEST_URI'];

                        for ($i = 1; $i <= $total_expired_pages; $i++) {
                            echo '<a href="?expired-games=' . $i . '"';
                            if ($i == $paged) {
                                echo ' class="active loki-pagination"';
                            } else {
                                echo 'class="loki-pagination"';
                            }
                            echo '>' . $i . '</a>';
                        }
                        ?>
                    </div>
                </div>
                <?php
            } else {
                echo "No expired games found.";
            }

        }
        ?>
    </div>
</div>
<?php get_footer(); ?>