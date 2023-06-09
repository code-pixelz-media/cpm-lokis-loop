<?php
get_header();
if (is_user_logged_in()) {
    ?>
    <div class="lokisloop-dashboard-container">
        <aside>
            <?php lokis_account_menu(); ?>
        </aside>

        <div class="lokisloop-hosted-game">
            <div class="lokisloop-container-wrapper">
                <!-- here is the modal box code -->
                <div class="lokis_show_modal_box"></div>
                <div class="lokisloop-main-top">
                    <h5>
                        <?php _e("Current Games", "lokis-loop") ?>
                    </h5>
                </div>
                <?php
                // this function is responsible to delete the game table data
                lokis_Delete_game_table_data();
                // this function is responsible to end the game session
                lokis_end_game_session();
                ?>
                <table id="current-games" class="lookisloop-games">
                    <thead>
                        <tr>
                            <th scope="col">
                                <?php _e("ID", "lokis-loop") ?>
                            </th>
                            <th scope="col">
                                <?php _e("Name", "lokis-loop") ?>
                            </th>
                            <th scope="col">
                                <?php _e("Url", "lokis-loop") ?>
                            </th>
                            <th scope="col">
                                <?php _e("Started At", "lokis-loop") ?>
                            </th>
                            <th scope="col">
                                <?php _e("Expires In", "lokis-loop") ?>
                            </th>
                            <th scope="col">
                                <?php _e("QR", "lokis-loop") ?>
                            </th>
                            <th scope="col">
                                <?php _e("Action", "lokis-loop") ?>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
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
                                $gm_merge_delete_message = '';

                                $lokis_offline_game_url = get_permalink($game_id) . '?offlinegame=' . $session_id;

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
                                        'lokis_offline_game_url' => $lokis_offline_game_url,
                                        'qr_code_image_url' => $row->qr_code_image_url,
                                    ];
                                }
                            }

                            // Sort the current games array by the "id" field in ascending order
                            usort($current_games, function ($a, $b) {
                                return $b['id'] - $a['id'];
                            });
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
                                    $lokis_content = '';
                                    $lokis_content .= '<tr><td  data-label="ID:">' . $game['id'] . '</td><td data-label="Name:"> <p class="lokis-table-tooltip" data-tooltip="' . $game['session_id'] . '">' . $game['title'] . '</p></td>';
                                    $lokis_content .= '<td  data-label="URL:"><div class="lokis-table-url-wrapper"><a class="lokisloop-visit-url lokis-table-tooltip" target="_blank" href="' . $game['url'] . '" data-tooltip="' . $game['url'] . '"><i class="fa-solid fa-arrow-up-right-from-square"></i></a>';
                                    $lokis_content .= '<a data-url="' . $game['url'] . '" class="lokisloop-url-copy lokis-table-tooltip" data-tooltip="Copy"><i class="fa-regular fa-copy"></i></a></div></td>';
                                    $lokis_content .= '<td data-label="Started At:">' . $formattedStartedDate . '</td>';
                                    $lokis_content .= '<td data-label="Expires In:">' . $formattedExpirationDate . '</td>';
                                    $lokis_content .= '<td data-label="QR:">';

                                    $lokis_content .= '<div class="lokis-qr-section-container">';
                                    $lokis_content .= '<input type="hidden" class="form-control lokis_qr_content" value="' . $game['lokis_offline_game_url'] . '">';
                                    $lokis_content .= '<input type="hidden" class="lokis_game_id" name="lokis_game_id" value="' . $game['id'] . '">';

                                    // Check if the QR code image URL is already available
                                    if (!empty($game['qr_code_image_url'])) {

                                        $lokis_content .= '<a href="' . $game['qr_code_image_url'] . '" target="_blank"><img src="' . $game['qr_code_image_url'] . '" class="lokis-qr-code"></a>';

                                    } else {
                                        $lokis_content .= '<img src="" class="lokis-qr-code lokis-table-tooltip" data-tooltip="Open Image in new tab" style="display: none;">';
                                        $lokis_content .= '<button type="button" class="button lokis-table-button lokis-generate-qr lokis-table-tooltip" data-tooltip="Generate QR"><i class="fa-solid fa-circle-plus"></i></button>';
                                    }
                                    $lokis_content .= '</div></td>';

                                    $lokis_content .= '<td class="lokis-action-td-wrapper" data-label="Action:"><div class="lokis-action-td">';
                                    $lokis_content .= '<button id="lokisLoopModalBox" class="button view-player modal-toggle lokis-table-tooltip" name="view_player" data-game-id="' . $game['game_id'] . '" data-session-id="' . $game['session_id'] . '" data-tooltip="View Players"></button>';

                                    $lokis_content .= '<form method="POST" action="">';
                                    $lokis_content .= '<input type="hidden" name="end_session" value="' . $game['id'] . '">';
                                    $lokis_content .= '<button type="submit" class="button end-session lokis-table-tooltip" data-tooltip="End Session"></button></form>';

                                    $lokis_content .= '<form method="POST" action="">';
                                    $lokis_content .= '<input type="hidden" name="delete_session_data" value="' . $game['id'] . '">';
                                    $lokis_content .= '<button type="submit" name="delete_game" class="button lokis-table-button lokis-table-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></button>';
                                    $lokis_content .= '</form></div></td>';
                                    $lokis_content .= '</tr>';

                                    echo $lokis_content;
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
                        <?php
                        if ($gm_merge_delete_message) {
                            echo $gm_merge_delete_message;
                        }
                        ?>
                    </div>
                    <?php
                            } else {
                                echo '<tr><td colspan="7"><p class="lokis-user-empty-games">' . esc_html__('No currently active games session found. Please start a new game session.', 'lokis-loop') . '</p></td></tr>';
                            }
                            echo '<tbody></table>';
                        } else {
                            echo '<tr><td colspan="7"><p class="lokis-user-empty-games">' . esc_html__('No games session found.Please start a new game session.', 'lokis-loop') . '</p></td></tr>';
                        }
                        echo '<tbody></table>'; ?>
        </div>
    </div>
    <?php
} else {
    ?>
    <script>
        window.location.href = '<?php echo wp_login_url(); ?>';
    </script>
    <?php
}
get_footer();