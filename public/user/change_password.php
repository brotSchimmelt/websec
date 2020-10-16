<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check login status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// check if user is unlocked
if (!is_user_unlocked()) {
    header("location: " . MAIN_PAGE);
    exit();
}

// Change Password
if (isset($_POST['change-pwd-submit'])) {

    if (post_var_set('pwd') && post_var_set('new-pwd') && post_var_set('confirm-pwd')) {

        // Load variables
        $username = $_SESSION['userName'];
        $pwd = $_POST['pwd'];
        $newPwd = $_POST['new-pwd'];
        $confirmPwd = $_POST['confirm-pwd'];

        change_password($username, $pwd, $newPwd, $confirmPwd);
    }
} ?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | Change Password</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <!-- <div class="page-container"> -->
    <div class="row justify-content-center mt-5">
        <div class="col-xl-4 col-lg-8 col-md-9 col-sm-10 col-xs-11">
            <div class="jumbotron bg-light-grey shadow">
                <h1 class="display-5 text-center">Change Your Password</h1>
                <br>
                <?= get_message() ?>
                <br>
                <form class="form-signin" action="change_password.php" method="post">

                    <div class="form-group">
                        <label for="input-old-pwd"><b>Current Password:</b></label>
                        <input type="password" name="pwd" id="input-old-pwd" class="form-control" aria-describedby="input-old-pwd" placeholder="Current Password" required autofocus>
                        <small id="pwd-help" class="form-text text-muted">Please enter your current password.</small>
                    </div>

                    <div class="form-group">
                        <label for="input-new-pwd"><b>New Password:</b></label>
                        <input type="password" name="new-pwd" id="input-new-pwd" class="form-control" placeholder="New Password" required>
                        <small id="password-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>
                    </div>

                    <div class="form-group">
                        <label for="confirm-pwd"><b>Confirm Password:</b></label>
                        <input type="password" name="confirm-pwd" id="confirm-pwd" class="form-control" placeholder="Confirm Password" required>
                    </div>
                    <br>
                    <button type="submit" name="change-pwd-submit" id="change-pwd-submit" class="btn btn-wwu-primary">Change Password</button>
                </form>
            </div>
        </div>
    </div>
    <!-- HTML Content END -->


    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    ?>
</body>

</html>