<?php get_header(); ?>

<div class="lokisloop-dashboard-container">

    <aside>
        <div class="lokies-logo">
            <a id="home-page" class="nav-link " href="/">Loki's Loop</a>

        </div>

        <?php echo lokis_account_menu(); ?>

    </aside>
    <div class="lokisloop-hosted-game">
        <?php
        // code goes here
        $session_id = bin2hex(random_bytes(9));
        ?>

        <form method="POST" class="row g-3">

            <input type="hidden" name="sessionToken" id="sessionToken" value="<?php echo $session_id ?>">

            <div class="col-auto" bis_skin_checked="1">
                <label for="id_game" class="visually-hidden">Game</label>
                <select class="form-select" aria-label="Game" id="id_game" name="game">
                    <?php
                    $args = [
                        'post_type' => 'games',
                        'post_status' => 'publish',
                        'posts_per_page' => -1,
                        'meta_key' => 'lokis_checkbox',
                        'orderby' => 'meta_value',
                        'order' => 'ASC',
                        'meta_query' => [
                            [
                                'key' => 'lokis_checkbox',
                                'value' => '1',
                                'compare' => 'LIKE',
                            ],
                        ],
                    ];

                    $query = new WP_Query($args);

                    // Loop
                    if ($query->have_posts()):
                        while ($query->have_posts()):
                            $query->the_post();
                            $post_id = get_the_ID();
                            $post_title = get_the_title();
                            echo '<option value="' . $post_id . '">' . $post_title . '</option>';
                        endwhile;
                    endif;
                    ?>
                </select>
            </div>
            <div class="col-auto" bis_skin_checked="1">
                <div class="input-group" bis_skin_checked="1">
                    <label for="lokisLoop_expiration" class="visually-hidden">Expiration Time (hours)</label>
                    <input type="number" class="form-control" value="1" placeholder="Expiration Time (hours)"
                        id="lokisLoop_expiration" name="expiration">
                    <span class="input-group-text">hour(s)</span>

                </div>
            </div>
            <div class="col-auto" bis_skin_checked="1">
                <button type="submit" class="btn btn-primary mb-3">Start Game Session</button>
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

            // var_dump($previously_inserted_sessions);
        
            if ($previously_inserted_sessions == 0) {
                $data = [
                    'host_id' => $host_id,
                    'game_id' => $game_id,
                    'session_id' => $session_id,
                    'expires_in' => $end_timestamp,
                    'started_at' => date('Y-m-d H:i:s')
                ];
                // Insert the data into the table
                $wpdb->insert($lokis_game_sessions_table_name, $data);
            } else {
                echo "Session ID already exists";
            }
        }
        ?>
    </div>

</div>

<?php get_footer(); ?>