<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="resources/css/bootstrap.css">

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="resources/css/register.css">

    <title>WebSec | Registration</title>

</head>



<body class="text-center">
    <form class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">User Registration</h1>

        <label for="inputName" class="sr-only">Enter your Username</label>
        <input type="text" id="inputName" class="form-control" aria-describedby="usernameHelp" placeholder="Username" required autofocus>
        <small id="usernameHelp" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

        <label for="inputMail" class="sr-only">Enter your Mail</label>
        <input type="email" id="inputMail" class="form-control" aria-describedby="mailHelp" placeholder="WWU Mail" required>
        <small id="mailHelp" class="form-text text-muted">Please use your @uni-muenster.de mail address.</small>

        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" id="inputPassword" class="form-control" placeholder="Password" required>
        <small id="passwordHelp" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

        <label for="confirmPassword" class="sr-only">Confirm Password</label>
        <input type="password" id="confirmPassword" class="form-control" placeholder="Confirm Password" required>

        <a href="index.php" id="login_btn" class="btn btn-lg btn-primary btn-block">Register</a>
        <a href="index.php" class="btn btn-link">Back to Login Page</a>

        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>

</html>