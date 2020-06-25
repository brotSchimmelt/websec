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

    <title>WebSec | Password Reset</title>

</head>



<body class="text-center">
    <!-- HTML Content BEGIN -->
    <div class="jumbotron shadow bg-light login-card">
        <form class="form-signin">
            <h1 class="h3 mb-3 font-weight-normal">Reset Your Password</h1>

            <label for="input-mail" class="sr-only">Enter your Mail</label>
            <input type="email" id="input-mail" class="form-control" aria-describedby="mail-help" placeholder="WWU Mail" required autofocus>
            <div class="pb-3" id="info-text">
                <small>Enter your <strong>@uni-muenster.de</strong> mail address. If you are already registered, you will receive a mail with further instructions to reset your password.</small>
            </div>

            <a href="form_handler.php" id="send-btn" class="btn btn-lg btn-register btn-block">Send Mail</a>
            <a href="index.php" class="btn btn-link login-link">Back to Login Page</a>

            <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>
            <hr class="accent-blue">
        </form>
    </div>
    <!-- HTML Content BEGIN -->
</body>

</html>