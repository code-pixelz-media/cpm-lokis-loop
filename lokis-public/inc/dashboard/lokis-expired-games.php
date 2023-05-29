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

        $host_id = get_current_user_id();

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

                        "game_id" => $game_id,

                        "session_id" => $session_id,

                        'started_at' => $started_at,
                        'expires_in' => $expires_in

                    ];

                }

            }
            // Sort the current games array by the "id" field in ascending order
            usort($expired_games, function ($a, $b) {
                return $b['id'] - $a['id'];
            });
            // Display expired games
        
            if (!empty($expired_games)) {

                ?>

                <div class="lokisloop-container-wrapper">

                    <h5> Expired Games</h5>

                    <table id="expired-games" class="lookisloop-games">

                        <div class="thead">

                            <tr>

                                <th scope="col">ID</th>
                                <th scope="col">Name</th>
                                <th scope="col">Url</th>
                                <th scope="col">Started</th>
                                <th scope="col">Expired</th>
                                <th scope="col">Action</th>

                            </tr>

                        </div>

                        <tbody>

                            <?php

                            //Set number of games per page
                    
                            $games_per_page = 3;

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

                            foreach ($expired_page_games as $index => $game) {

                                // Perform actions with the retrieved data
                    
                                // $sn = $expired_offset + $index + 1;
                    
                                $startedDateString = $game['started_at'];
                                $expiredDateString = $game['expires_in'];

                                $formattedStartedDate = date('F d, Y, g:i a', strtotime($startedDateString));
                                $formattedExpirationDate = date('F d, Y, g:i a', strtotime($expiredDateString));

                                echo '<tr><td  data-label="ID:">' . $game['id'] . '</td>';

                                echo '<td data-label="Name:"><p class="lokis-table-tooltip" data-tooltip="' . $game['session_id'] . '">' . $game['title'] . '</td>';

                                echo '<td  data-label="URL:"><div class="lokis-view-copy-btn"><a class="lokisloop-visit-url lokis-table-tooltip" target="_blank" href="' . $game['url'] . '" title="' . $game['url'] . '" data-tooltip="Visit Url"></a>';

                                echo '<a data-url="' . $game['url'] . '" class="lokisloop-url-copy lokis-table-tooltip" data-tooltip="Copy URL"><i class="fa-regular fa-copy"></i></a></td>';
                                echo '<td data-label="Started At:">' . $formattedStartedDate . '</td>';
                                echo '<td data-label="Expires In:">' . $formattedExpirationDate . '</td>';

                                echo '<td class="lokis-action-td-wrapper" data-label="Action:"><div class="lokis-action-td">';

                                echo '<form method="POST" action="">';

                                echo '<input type="hidden" name="game_id" value="' . $game['game_id'] . '">';

                                echo '<button id="lokisLoopModalBox" class="button view-player modal-toggle lokis-table-tooltip" name="view_player" data-game-id="' . $game['game_id'] . '" data-session-id="' . $game['session_id'] . '" data-tooltip="View Player"></button>';


                                echo '<input type="hidden" name="delete_session_data" value="' . $game['id'] . '">';

                                echo '<button type="submit" class="button lokis-table-button lokis-table-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></button>';


                                echo '</form></div></td></div></tr>';

                            }

                            // this function is responsible to delete the game table data
                            lokis_Delete_game_table_data();
                            ?>

                        </tbody>

                    </table>
                    <!-- 
                    <div class="lokis-pagination-wrapper">

                        <a href="#">&laquo;</a> -->
                    <?php

                    // $current_url = $_SERVER['REQUEST_URI'];
            
                    // for ($i = 1; $i <= $total_expired_pages; $i++) {
            
                    //     echo '<a href="?expired-games=' . $i . '"';
            
                    //     if ($i == $paged) {
            
                    //         echo ' class="active loki-pagination"';
            
                    //     } else {
            
                    //         echo 'class="loki-pagination"';
            
                    //     }
            
                    //     echo '>' . $i . '</a>';
            
                    // }
            
                    ?>
                    <!-- <a href="#">&raquo;</a>

                    </div> -->

                    <div class="lokis-loop-pagination">
                        <?php
                        $pagination_args = array(
                            'base' => esc_url_raw(add_query_arg('expired-games', '%#%')),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_expired_pages,
                            'current' => $expired_paged,
                            'mid_size' => 2,
                        );

                        echo paginate_links($pagination_args);
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