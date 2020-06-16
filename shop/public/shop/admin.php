<?php
session_start();

// includes
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

if (is_user_logged_in() && is_user_admin()) {

    header("location: " . "/admin/dashboard.php");
    exit();
} else {

    // header("location: https://www.youtube.com/watch?v=dQw4w9WgXcQ");
    // exit();
?>

    <!doctype html>
    <html lang="en">

    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="../assets/css/bootstrap.css">

        <!-- Custom CSS to overwrite Bootstrap.css -->
        <link rel="stylesheet" href="../assets/css/login.css">

        <title>WebSec | ADMIN</title>

    </head>

    <body class="text-center">
        <form class="form-signin" action="form_handler.php" method="post">

            <img class="mb-4" src="../assets/img/lotr.png" alt="You.. Shall Not.. Pass!" width="280" height="180">
            <h1 class="h3 mb-3 font-weight-normal">Admin Login</h1>
            <div class="alert alert-danger shadow-sm" role="alert">
                Please use this login only when you are really an <strong>admin</strong> user!
            </div>
            <label for="inputName" class="sr-only">Username</label>
            <input type="text" class="form-control" value="admin" placeholder="Username" required autofocus>
            <label for="inputPassword" class="sr-only">Password</label>
            <input type="password" class="form-control" value="password123" placeholder="Password" required>

            <!-- <a href="password_forgotten.php" id="forget_btn" class="btn btn-link">Forgot your password?</a> -->
            <a class="btn btn-lg btn-primary btn-block" href="admin_redirect.php">Login</a>
            <!-- <a href="registration.php" id="register_btn_link" class="btn btn-lg btn-outline-secondary btn-block">Register</a> -->
            <p class="mt-5 mb-3 text-muted">&copy; <?php get_semester() ?></p>


        </form>
    </body>

    </html>



<?php
}
?>