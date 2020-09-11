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
    <link rel="stylesheet" href="/assets/css/bootstrap.css">

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
    <div class="con-search">
        <h1 class="display-4">Change your Password</h1>
        <div>
            <form class="form-signin" action="change_password.php" method="post">

                <?= get_message() ?>

                <label for="input-old-pwd" class="sr-only">Current Password</label>
                <input type="password" name="pwd" id="pwd" class="form-control" aria-describedby="pwd-help" placeholder="Current Password" required autofocus>
                <small id="pwd-help" class="form-text text-muted">Please enter your current password.</small>

                <label for="input-new-pwd" class="sr-only">New Password</label>
                <input type="password" name="new-pwd" id="new-pwd" class="form-control" placeholder="New Password" required>
                <small id="password-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>

                <label for="confirm-pwd" class="sr-only">Confirm Password</label>
                <input type="password" name="confirm-pwd" id="confirm-password" class="form-control" placeholder="Confirm Password" required>

                <button type="submit" name="change-pwd-submit" id="change-pwd-submit" class="btn btn-lg btn-primary btn-block">Change Password</button>
            </form>

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