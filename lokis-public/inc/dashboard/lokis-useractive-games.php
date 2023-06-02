<?php
// this page is displayed in players dashboard to show the active games by the player
if (is_user_logged_in()) {

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
                <h5>Active Games</h5>
            </div>
            <table id="current-gamess" class="lookisloop-games">
                <thead>
                    <tr>
                        <th scope="col">I.D.</th>
                        <th scope="col">Name</th>
                        <th scope="col">URL</th>
                        <th scope="col">Session</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    global $wpdb;
                    $session_id = '';
                    $post_id = '';
                    $player_game_id = '';
                    $lokis_player_table_name = $wpdb->prefix . 'lokis_player_sessions'; // Get the players table name 
                    $lokis_game_sessions_table_name = $wpdb->prefix . 'lokis_game_sessions'; // Get the game sessions table name
                    $current_user_id = get_current_user_id();

                    // Query to get  the game IDs
                    $lokis_player_game_ids = $wpdb->get_results("SELECT id, session_id, step, game_url  FROM $lokis_player_table_name WHERE completed = '0' AND player_id = '$current_user_id' ");
                    $games_per_page = 2; // Change this value as per your requirement
                    $total_games = count($lokis_player_game_ids); // Get the total number of games
                    $total_pages = ceil($total_games / $games_per_page);
                    $current_page = isset($_GET['active-games']) ? abs((int) $_GET['active-games']) : 1;
                    $offset = ($current_page - 1) * $games_per_page;

                    // Retrieve the subset of games for the current page
                    $current_page_games = array_slice($lokis_player_game_ids, $offset, $games_per_page);
                    usort($current_page_games, function ($a, $b) {
                        return $b->id - $a->id;
                    });

                    if ($current_page_games) {
                        foreach ($current_page_games as $game_id) {

                            $session_id = $game_id->session_id;
                            $post_id = $game_id->step;
                            $player_game_id = $game_id->id;
                            $lokis_player_game_url = $game_id->game_url;


                            $lokis_game_expiration = $wpdb->get_results("SELECT id, expires_in, gamesession_url FROM $lokis_game_sessions_table_name WHERE session_id = '$session_id' ");


                            if (!empty($lokis_game_expiration)) {
                                // Retrieve the first value from the results
                                $game_expiration = $lokis_game_expiration[0]->expires_in;
                                // $game_url = $lokis_game_expiration[0]->gamesession_url;
                                $host_game_id = $lokis_game_expiration[0]->id;
                                $game_name = get_the_title($post_id);
                            }

                            if ($game_expiration < date('Y-m-d H:i:s')) {
                                $game_status = "Expired";
                            } else {
                                $game_status = "Active";
                            }


                            echo "<tr>";
                            echo '<td data-label="ID:">' . $host_game_id . '</td>'; // I.D.
                            echo "<td data-label='NAME:'> <p class='lokis-table-tooltip' data-tooltip=" . $session_id . ">{$game_name}</p></td>";
                            // echo "<td data-label='URL:'><a class='lokisloop-visit-url lokis-table-tooltip' href='" . $lokis_player_game_url . "' data-tooltip=" . $lokis_player_game_url . " target='_blank'><i class='fa-solid fa-arrow-up-right-from-square'></i></a></td>";
                            echo "<td data-label='URL:'><a href='" . $lokis_player_game_url . "' target='_blank'>" . $lokis_player_game_url . "</a></td>";

                            echo '<td data-label="STATUS:">' . $game_status . '</td>';
                            echo '<td data-label="Action:"><form method="POST" action="">';
                            echo '<input type="hidden" name="delete_player_data" value="' . $player_game_id . '">';
                            echo '<button type="submit" class="lokis-table-button lokis-table-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></button>';
                            echo "</form></td></tr>";
                        }
                    } else {
                        // If no games are found, display a message
                        echo "<tr>";
                        echo "<td colspan='5'>No games found</td>";
                        echo "</tr>";
                    }

                    // this function is responsible to delete the player data from the player table
                    lokis_delete_player_table_data();
                    ?>
                </tbody>
            </table>
            <?php
            $pagination_args = array(
                'base' => esc_url_raw(add_query_arg('active-games', '%#%')),
                'format' => '',
                'prev_text' => '&laquo;',
                'next_text' => '&raquo;',
                'total' => $total_pages,
                'current' => $current_page,
                'mid_size' => 2,
            );
            echo "<div class='lokis-pagination'>";
            // var_dump($pagination_args);
            echo paginate_links($pagination_args);
            echo "</div>";
            ?>
        </div>
    </div>
    <?php get_footer();

} else {
    ?>

    <script>
        window.location.href = '<?php echo wp_login_url(); ?>';

    </script>

    <?php

}
?>