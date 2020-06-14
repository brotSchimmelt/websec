<?php
session_start();

// includes
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

if (is_user_logged_in()) {
    header("location: " . MAIN_PAGE);
    exit();
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
    <form class="form-signin" action="form_handler.php" method="post">
        <h1 class="h3 mb-3 font-weight-normal">User Registration</h1>

        <label for="input-name" class="sr-only">Enter your Username</label>
        <input type="text" name="username" id="register-name" class="form-control" aria-describedby="username-help" placeholder="Username" required autofocus>
        <small id="username-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

        <label for="input-mail" class="sr-only">Enter your Mail</label>
        <input type="email" name="mail" id="register-mail" class="form-control" aria-describedby="mail-help" placeholder="WWU Mail" required>
        <small id="mail-help" class="form-text text-muted">Please use your <em>@uni-muenster.de</em> mail address.</small>

        <label for="input-password" class="sr-only">Password</label>
        <input type="password" name="password" id="register-password" class="form-control" placeholder="Password" required>
        <small id="password-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

        <label for="confirm-password" class="sr-only">Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirm-password" class="form-control" placeholder="Confirm Password" required>

        <button type="submit" name="register-submit" id="register-btn" class="btn btn-lg btn-primary btn-block">Register</button>
        <!-- <a href="index.php" id="login_btn" class="btn btn-lg btn-primary btn-block">Register</a> -->
        <a href="index.php" class="btn btn-link">Back to Login Page</a>

        <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>
    </form>
</body>

<?php
require("$_SERVER[DOCUMENT_ROOT]/../src/errors.php");
if (isset($_GET['error'])) {
    display_registration_error($_GET['error']);
}
?>

</html>