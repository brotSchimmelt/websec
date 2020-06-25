<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
// require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
// require(FUNC_LOGIN);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check login status
if (is_user_logged_in()) {
    // Redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
} ?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="assets/css/login.css">

    <title>WebSec | Login</title>

</head>

<body class="text-center">

    <!-- HTML Content BEGIN -->
    <div class="jumbotron shadow bg-light login-card">
        <form class="form-signin" action="form_handler.php" method="post">
            <img class="mb-4" src="assets/img/wwu_cysec.png" alt="WWU Logo" width="280" height="150">

            <h1 class="h3 mb-3 font-weight-normal">WebSec Shop</h1>
            <label for="input-name" class="sr-only">Username</label>
            <input type="text" name="loginUsername" id="input-name" class="form-control" placeholder="Username" required autofocus>
            <label for="input-password" class="sr-only">Password</label>
            <input type="password" name="loginPwd" id="input-password" class="form-control" placeholder="Password" required>

            <a href="password_reset.php" id="forget_btn" class="btn btn-link login-link">Forgot your password?</a>

            <button type="submit" name="login-submit" id="login-btn" class="btn btn-lg btn-login btn-block">Login</button>
            <a href="registration.php" id="register-btn-link" class="btn btn-lg btn-outline-register btn-block">Register</a>

            <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>
            <hr class="accent-blue">
        </form>
    </div>
    <!-- HTML Content END -->

</body>

</html>