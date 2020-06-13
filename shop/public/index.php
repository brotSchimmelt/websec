<?php
session_start();
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

    <title>WebSec | Login</title>

</head>

<body class="text-center">
    <form class="form-signin" action="form_handler.php" method="post">
        <img class="mb-4" src="assets/img/wwu-cysec.png" alt="WWU Logo" width="280" height="150">
        <h1 class="h3 mb-3 font-weight-normal">WebSec Shop</h1>
        <label for="inputName" class="sr-only">Username</label>
        <input type="text" name="loginUsername" id="inputName" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="loginPwd" id="inputPassword" class="form-control" placeholder="Password" required>

        <a href="password_forgotten.php" id="forget_btn" class="btn btn-link">Forgot your password?</a>

        <button type="submit" name="login-submit" id="login_btn" class="btn btn-lg btn-primary btn-block">Login</button>
        <a href="registration.php" id="register_btn_link" class="btn btn-lg btn-outline-secondary btn-block">Register</a>
        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>

</html>