<?php
if (is_user_logged_in()) {

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
            $host_id = get_current_user_id();
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

                    $lokis_offline_game_url = get_permalink($game_id) . '?offlinegame';

                    $expires_at = new DateTime($expires_in);
                    if ($expires_at > new DateTime()) {
                        // Store current game data in the array
                        $current_games[] = [
                            'id' => $id,
                            'title' => $title,
                            'url' => $url,
                            'game_id' => $game_id,
                            'session_id' => $session_id,
                            'started_at' => $started_at,
                            'expires_in' => $expires_in,
                            'lokis_offline_game_url' => $lokis_offline_game_url
                        ];
                    }
                }

                // Sort the current games array by the "id" field in ascending order
                usort($current_games, function ($a, $b) {
                    return $b['id'] - $a['id'];
                });
                ?>
                <div class="lokisloop-container-wrapper">
                    <!-- here is the modal box code -->
                    <div class="lokis_show_modal_box"></div>
                    <div class="lokisloop-main-top">
                        <h5>Current Games</h5>
                    </div>
                    <table id="current-games" class="lookisloop-games">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Url</th>
                                <th scope="col">Started At</th>
                                <th scope="col">Expires In</th>
                                <th scope="col">Action</th>
                                <th scope="col">QR</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if (!empty($current_games)) {

                                //Number of game data per post
                                $games_per_page = 2;
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
                                foreach ($current_page_games as $index => $game) {
                                    // Perform actions with the retrieved data
                                    $startedDateString = $game['started_at'];
                                    $expiredDateString = $game['expires_in'];
                                    $formattedStartedDate = date('F d, Y, g:i a', strtotime($startedDateString));
                                    $formattedExpirationDate = date('F d, Y, g:i a', strtotime($expiredDateString));
                                    echo '<tr><td  data-label="ID:">' . $game['id'] . '</td><td data-label="Name:"> <p class="lokis-table-tooltip" data-tooltip="' . $game['session_id'] . '">' . $game['title'] . '</p></td>';
                                    echo '<td  data-label="URL:"><div class="lokis-table-url-wrapper"><a class="lokisloop-visit-url lokis-table-tooltip" target="_blank" href="' . $game['url'] . '" data-tooltip="' . $game['url'] . '"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
                                    echo '<a data-url="' . $game['url'] . '" class="lokisloop-url-copy lokis-table-tooltip" data-tooltip="Copy"><i class="fa-regular fa-copy"></i></a></div></td>';
                                    echo '<td data-label="Started At:">' . $formattedStartedDate . '</td>';
                                    echo '<td data-label="Expires In:">' . $formattedExpirationDate . '</td>';
                                    echo '<td class="lokis-action-td-wrapper" data-label="Action:"><div class="lokis-action-td">';
                                    echo '<button id="lokisLoopModalBox" class="button view-player modal-toggle lokis-table-tooltip" name="view_player" data-game-id="' . $game['game_id'] . '" data-session-id="' . $game['session_id'] . '" data-tooltip="View Players"></button>';
                                    echo '<form method="POST" action="">';
                                    echo '<input type="hidden" name="end_session" value="' . $game['id'] . '">';
                                    echo '<button type="submit" class="button end-session lokis-table-tooltip" data-tooltip="End Session"></button></form>';
                                    echo '<form method="POST" action="">';
                                    echo '<input type="hidden" name="delete_session_data" value="' . $game['id'] . '">';
                                    echo '<button type="submit" class="button lokis-table-button lokis-table-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></button>';
                                    echo '</form></div></td>';
                                    echo '<td data-label="QR:">';
                                    echo '<div class="lokis-qr-section-container">';
                                    echo '<input type="hidden" class="form-control lokis_qr_content" value="' . $game['lokis_offline_game_url'] . '">';
                                    echo '<img src="" class="lokis-qr-code lokis-table-tooltip" data-tooltip="Open Image in new tab"><button type="button" class="button lokis-table-button  lokis-generate-qr lokis-table-tooltip" data-tooltip="Generate QR"><i class="fa-solid fa-circle-plus"></i></button></div></td>';
                                    echo '</tr>';
                                }

                                // Delete the game session data
                                if (isset($_POST['delete_session_data'])) {
                                    lokis_Delete_game_table_data();
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

                        <div class="lokis-loop-pagination">
                            <?php
                            $pagination_args = array(
                                'base' => esc_url_raw(add_query_arg('current-games', '%#%')),
                                'format' => '',
                                'prev_text' => '&laquo;',
                                'next_text' => '&raquo;',
                                'total' => $total_pages,
                                'current' => $paged,
                                'mid_size' => 2,
                            );
                            echo paginate_links($pagination_args);
                            ?>
                        </div>
                    </div>
                    <?php
                            } else {
                                echo '<tr><td colspan="7"><p class="lokis-user-empty-games">No currently active games session found.Please start a new game session.</p></td></tr>';
                            }
                            echo '<tbody></table>';
            } ?>
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