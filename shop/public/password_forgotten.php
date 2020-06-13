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

    <title>WebSec | Password Reset</title>

</head>



<body class="text-center">
    <form class="form-signin">
        <h1 class="h3 mb-3 font-weight-normal">Password Reset</h1>


        <label for="inputMail" class="sr-only">Enter your Mail</label>
        <input type="email" id="inputMail" class="form-control" aria-describedby="mailHelp" placeholder="WWU Mail" required autofocus>
        <div id="info_text">
            <p>Enter your @uni-muenster.de mail address. If you are already registered, you will receive a mail with further instructions to reset your password.</p>
        </div>

        <a href="action_handler.php" id="send_btn" class="btn btn-lg btn-info btn-block">Send Mail</a>
        <a href="index.php" class="btn btn-link">Back to Login Page</a>

        <p class="mt-5 mb-3 text-muted">&copy; <?php echo date("Y"); ?></p>
    </form>
</body>

</html>