<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);

// check admin status
if (is_user_logged_in() && is_user_admin()) {

    header("location: " . "/admin/dashboard.php");
    exit();
}

// check if user is unlocked
if (!is_user_unlocked()) {
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
    <link rel="stylesheet" href="/assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/login.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | ADMIN</title>
</head>

<body class="text-center">

    <!-- HTML Content BEGIN -->
    <form class="form-signin" action="form_handler.php" method="post">

        <img class="mb-4" src="../assets/img/lotr.png" alt="You.. Shall Not.. Pass!" width="280" height="180">
        <h1 class="h3 mb-3 font-weight-normal">Admin Login</h1>
        <div class="alert alert-danger shadow-sm" role="alert">
            For <strong>admins</strong> only!
        </div>
        <label for="inputName" class="sr-only">Username</label>
        <input type="text" class="form-control" value="admin" placeholder="Username" required>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" class="form-control shadow-sm" value="password123" placeholder="Password" required>

        <!-- <a href="password_forgotten.php" id="forget_btn" class="btn btn-link">Forgot your password?</a> -->
        <a class="btn btn-lg btn-primary btn-block shadow" href="admin_redirect.php">Login</a>
        <!-- <a href="registration.php" id="register_btn_link" class="btn btn-lg btn-outline-secondary btn-block">Register</a> -->
        <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>
    </form>
    <!-- HTML Content END -->

</body>

</html>