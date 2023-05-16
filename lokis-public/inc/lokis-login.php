<?php

//Creating a shortcode for login form
function lokis_loop_login()
{
    ob_start();
    ?>
    <div class="lokisloop-loginform-container">
        <h2>Login Form</h2>
        <form action="" method="POST">
            <label for="username" class="form-label">Username:</label>
            <input type="text" class="form-control" id="username" name="username" />
            <label for="password" class="form-label">Password:</label><br />
            <input type="password" class="form-control" id="password" name="password" />
            <div class="lokisloop-login-checkbox"><input type="checkbox" class="checkbox" id="remember" name="remember" />
                <label for="remember" class="remember">Remember me</label>
            </div>

            <input type="submit" class="lokisloop-login-btn" value="Login" />
        </form>
        <p>Not a member? <a href="#">Sign up Now</a></p>
        <p><a href="#">Forgot password?</a></p>
    </div>


    <?php
    return ob_get_clean();
}
add_shortcode('lokis_loop_login', 'lokis_loop_login');