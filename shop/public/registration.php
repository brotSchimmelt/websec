<?php
session_start(); // Needs to be called first on every page

// Load dependencies
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN); // DB credentials
require(FUNC_BASE); // Basic functions
require(FUNC_LOGIN); // Login & registration functions
require(FUNC_WEBSEC); // Functions for the challenges
require(ERROR_HANDLING); // Error handling

// Check login status
if (is_user_logged_in()) {
    // Redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// Load GET variables and sanitize input
$name_get = get_var_set('username') ? $_GET['username'] : "";
$name_get = htmlentities($name_get);
$mail_get = get_var_set('mail') ? $_GET['mail'] : "";
$mail_get = htmlentities($mail_get);

// Check if POST variables are set
if (post_var_set('username') && post_var_set('mail') && post_var_set('password') && post_var_set('confirmPassword')) {

    // Load POST variables and sanitize input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    if (validate_registration_input($username, $mail, $password, $confirmPassword)) {
        try_registration($username, $mail, $password);
    }
}
?>
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

    <title>WebSec | Registration</title>

</head>

<body class="text-center">

    <!-- HTML Content BEGIN -->
    <div class="jumbotron shadow bg-light login-card">
        <form class="form-signin" action="registration.php" method="post">
            <h1 class="h3 mb-3 font-weight-normal">User Registration</h1>

            <?= get_message(); ?>

            <label for="input-name" class="sr-only">Enter your Username</label>
            <input type="text" name="username" id="register-name" class="form-control" aria-describedby="username-help" value="<?= $name_get ?>" placeholder="Username" required autofocus>
            <small id="username-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

            <label for="input-mail" class="sr-only">Enter your Mail</label>
            <input type="email" name="mail" id="register-mail" class="form-control" aria-describedby="mail-help" value="<?= $mail_get ?>" placeholder="WWU Mail" required>
            <small id="mail-help" class="form-text text-muted">Please use your <em>@uni-muenster.de</em> mail address.</small>

            <label for="input-password" class="sr-only">Password</label>
            <input type="password" name="password" id="register-password" class="form-control" placeholder="Password" required>
            <small id="password-help" class="form-text text-muted">Please use at least 8 characters.</small>

            <label for="confirm-password" class="sr-only">Confirm Password</label>
            <input type="password" name="confirmPassword" id="confirm-password" class="form-control" placeholder="Confirm Password" required>

            <button type="submit" name="register-submit" id="register-btn" class="btn btn-lg btn-register btn-block">Register</button>
            <a href="index.php" class="btn btn-link login-link">Back to Login Page</a>

            <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>
            <hr class="accent-blue">
        </form>
    </div>
    <!-- HTML Content END -->
</body>

</html>