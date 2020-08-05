<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_LOGIN);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check login status
if (is_user_logged_in()) {
    // Redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="assets/css/login.css">

    <title>WebSec | Create new Password</title>
</head>

<body class="text-center">

    <!-- HTML content BEGIN -->
    <div class="jumbotron shadow bg-light login-card">
        <form class="form-signin" action="registration.php" method="post">
            <h1 class="h3 mb-3 font-weight-normal">Set a new Password</h1>

            <label for="input-password" class="sr-only">New Password</label>
            <input type="password" name="password" id="new-password" class="form-control" placeholder=" New Password" required autofocus>
            <small id="password-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>
            <br>
            <label for="confirm-password" class="sr-only">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirm-password" class="form-control" placeholder="Confirm Password" required>

            <button type="submit" name="reset-submit" id="register-btn" class="btn btn-lg btn-register btn-block">Set Password</button>

            <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>
            <hr class="accent-blue">
        </form>
    </div>
    <!-- HTML content END -->

    <footer></footer>

    <?php
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    ?>
</body>

</html>