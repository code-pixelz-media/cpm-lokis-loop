<?php
// this page is displayed in players dashboard to show the completed games by the player  

get_header(); ?>

<div class="lokisloop-dashboard-container">
    <aside>
        <div class="lokis-logo">
            <a id="home-page" class="nav-link " href="/">Loki's Loop</a>

        </div>

        <?php lokis_account_menu(); ?>

    </aside>
    <div class="lokisloop-completed-games-container">
        <div class="lokisloop-main-top">
            <h5>Completed Games</h5>
        </div>
        <table id="current-gamess" class="lookisloop-games">
            <thead>
                <tr>
                    <th>I.D</th>
                    <th>Name</th>
                    <th>Url</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php

                global $wpdb;
                $session_id = '';
                $post_id = '';
                $player_game_id = '';
                $current_user_id = get_current_user_id();
                $lokis_player_table_name = $wpdb->prefix . 'lokis_player_sessions'; // Get the players table name
                // Query to get the player IDs and the game IDs
                
                $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions'; // Get the game sessions table name
                
                // Query to get  the game IDs
                $lokis_player_game_ids = $wpdb->get_results("SELECT id, session_id, step  FROM $lokis_player_table_name WHERE completed = '1' AND player_id = '$current_user_id' ORDER BY id DESC");



                $games_per_page = 2; // Change this value as per your requirement
                $total_games = count($lokis_player_game_ids); // Get the total number of games
                $total_pages = ceil($total_games / $games_per_page);
                $current_page = isset($_GET['completed-games']) ? abs((int) $_GET['completed-games']) : 1;
                $offset = ($current_page - 1) * $games_per_page;

                // Retrieve the subset of games for the current page              
                $current_page_games = array_slice($lokis_player_game_ids, $offset, $games_per_page);

                if ($current_page_games) {
                    // If games are found, loop through the results
                    foreach ($current_page_games as $single_player) {
                        // Accessing individual fields using object notation
                        $session_id = $single_player->session_id;
                        $post_id = $single_player->step;
                        $player_game_id = $single_player->id;

                        $lokis_completed_games = $wpdb->get_results("SELECT id, gamesession_url FROM $lokis_game_sessions_table_name WHERE session_id = '$session_id' ");

                        if (!empty($lokis_completed_games)) {
                            $game_url = $lokis_completed_games[0]->gamesession_url;
                            $host_game_id = $lokis_completed_games[0]->id;
                            $game_name = get_the_title($post_id);
                        }
                        echo "<tr>";
                        echo "<td>{$host_game_id}</td>"; // Display the calculated SN
                        echo "<td> <p data-tooltip=" . $session_id . ">{$game_name}</p></td>";
                        echo "<td><a>{$game_url}</a></td>";
                        echo '<td><form method="POST" action="">';
                        echo '<input type="hidden" name="delete_player_data" value="' . $player_game_id . '">';
                        echo '<button type="submit" class="lokis-table-button lokis-table-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></button>';
                        echo "</form></td></tr>";
                    }
                } else {
                    // If no games are found, display a message
                    echo "<tr>";
                    echo "<td colspan='4'>No games found</td>";
                    echo "</tr>";
                }
                // this function is responsible to delete the player data from the player table
                lokis_delete_player_table_data();
                ?>
            </tbody>
        </table>

        <div class="lokis-loop-pagination">
            <?php
            $pagination_args = array(
                'base' => esc_url_raw(add_query_arg('completed-games', '%#%')),
                'format' => '',
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
                'total' => $total_games,
                'current' => $current_page,
                'mid_size' => 2,
            );
            echo paginate_links($pagination_args);
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>