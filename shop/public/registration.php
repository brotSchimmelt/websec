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
    <link rel="stylesheet" href="resources/css/bootstrap.css">

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="resources/css/login.css">

    <title>WebSec | Registration</title>

</head>

<body class="text-center">
    <form class="form-signin" action="action_handler.php" method="post">
        <h1 class="h3 mb-3 font-weight-normal">User Registration</h1>

        <label for="inputName" class="sr-only">Enter your Username</label>
        <input type="text" name="username" id="registerName" class="form-control" aria-describedby="usernameHelp" placeholder="Username" required autofocus>
        <small id="usernameHelp" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

        <label for="inputMail" class="sr-only">Enter your Mail</label>
        <input type="email" name="mail" id="registerMail" class="form-control" aria-describedby="mailHelp" placeholder="WWU Mail" required>
        <small id="mailHelp" class="form-text text-muted">Please use your <em>@uni-muenster.de</em> mail address.</small>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" id="registerPassword" class="form-control" placeholder="Password" required>
        <small id="passwordHelp" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

        <label for="confirmPassword" class="sr-only">Confirm Password</label>
        <input type="password" name="confirmPassword" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>

        <button type="submit" name="register-submit" id="register_btn" class="btn btn-lg btn-primary btn-block">Register</button>
        <!-- <a href="index.php" id="login_btn" class="btn btn-lg btn-primary btn-block">Register</a> -->
        <a href="index.php" class="btn btn-link">Back to Login Page</a>

        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>

<?php
require("$_SERVER[DOCUMENT_ROOT]/../src/errors.php");
if (isset($_GET['error'])) {
    display_registration_error($_GET['error']);
}
?>

</html>