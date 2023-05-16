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
            $current_games = []; // Array to store current games
        
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

                if ($expires_at > new DateTime()) {
                    // Store current game data in the array
                    $current_games[] = [
                        'id' => $id,
                        'title' => $title,
                        'url' => $url
                    ];
                }
            }
            if (!empty($current_games)) {
                ?>
                <div class="lokisloop-container-wrapper">
                    <div class="lokisloop-main-top">
                        <h5>Current Games</h5>
                    </div>
                    <table id="current-games" class="lookisloop-games">
                        <thead>
                            <tr>
                                <th>Game Name</th>
                                <th>Game Url</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php

                            //Number of game data per post
                            $games_per_page = 1;

                            //Calculate starting index for specific page
                            $paged = isset($_GET['current-games']) ? abs((int) $_GET['current-games']) : 1;

                            // Calculate the offset for the current page
                            $offset = ($paged - 1) * $games_per_page;

                            // Retrieve the total number of games
                            $total_games = count($current_games);

                            // Calculate the total number of pages
                            $total_pages = ceil($total_games / $games_per_page);

                            // Retrieve the subset of games for the current page
                            $current_page_games = array_slice($current_games, $offset, $games_per_page);

                            foreach ($current_page_games as $game) {
                                // Perform actions with the retrieved data
                                echo '<tr><td>' . $game['title'] . '</td><td><a class="lokisloop-visit-url" target="_blank" href="' . $game['url'] . '" title="' . $game['url'] . '">Visit Url</a>';
                                echo '<a data-url="' . $game['url'] . '" class="lokisloop-url-copy" >  ' . "     " . 'Copy URL</a></td><td>';
                                echo '<button type="button" class="button view-player" data-url="' . $game['url'] . '" id="view-player-' . $game['id'] . '">View Player</button>';
                                echo '<form method="POST" action="">';
                                echo '<input type="hidden" name="end_session" value="' . $game['id'] . '">';
                                echo '<button type="submit" class="button end-session">End Session</button>';
                                echo '</form></td></tr>';
                            }
                            if (isset($_POST['end_session'])) {
                                $id = $_POST['end_session'];
                                $current_time = date('Y-m-d H:i:s');
                                // Update the expires_in value in the database
                                $wpdb->update($lokis_game_sessions_table_name, ['expires_in' => $current_time], ['id' => $id]);
                                echo '<script>window.location.href = window.location.href;</script>';
                                // Redirect to the same page to update the displayed data
                            }
                            ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <?php
                        // Display the pagination links
                        for ($i = 1; $i <= $total_pages; $i++) {
                            echo '<a href="?current-games=' . $i . '"';
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
                echo "No currently active games session found.";
            }

        } ?>
    </div>
</div>
<?php get_footer(); ?>