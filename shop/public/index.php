<!doctype html>
<html lang="en">

<?php
include("$_SERVER[DOCUMENT_ROOT]/../config/test.php");
?>





<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="resources/css/bootstrap.css">

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="resources/css/login.css">

    <title>WebSec | Login</title>

</head>

<body class="text-center">
    <form class="form-signin" action="src/signin.php" method="post">
        <img class="mb-4" src="resources/img/wwu-cysec.png" alt="WWU Logo" width="280" height="150">
        <h1 class="h3 mb-3 font-weight-normal">WebSec Shop</h1>
        <label for="inputName" class="sr-only">Username</label>
        <input type="text" id="inputName" class="form-control" placeholder="Username" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>

        <a href="password_reset.php" id="forget_btn" class="btn btn-link">Forgot your password?</a>

        <a href="./../scr/main.php" id="login_btn" class="btn btn-lg btn-primary btn-block">Login</a>
        <a href="register.php" id="register_btn" class="btn btn-lg btn-outline-secondary btn-block">Register</a>
        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>

</html>