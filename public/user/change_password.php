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
    <div class="row justify-content-center mt-5 card-page-width">
        <div class="col-xl-4 col-lg-8 col-md-9 col-sm-10 col-xs-11">
            <div class="jumbotron bg-light-grey shadow">
                <h1 class="display-5 text-center">Change Your Password</h1>
                <br>
                <div class="text-center">
                    <?= get_message() ?>
                </div>
                <br>
                <form class="form-signin" action="change_password.php" method="post">

                    <div class="row">
                        <div class="col">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-lock-fill mb-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.5 9a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V9z" />
                                        <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z" />
                                    </svg>
                                </div>
                                <input type="password" name="pwd" id="input-old-pwd" class="form-input" placeholder="Current Password" required autofocus>
                                <small id="pwd-help" class="form-text text-muted">Please enter your current password.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-lock-fill mb-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2.5 9a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-7a2 2 0 0 1-2-2V9z" />
                                        <path fill-rule="evenodd" d="M4.5 4a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z" />
                                    </svg>
                                </div>
                                <input type="password" name="new-pwd" id="input-new-pwd" class="form-input" placeholder="New Password" required>
                                <small id="password-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-arrow-counterclockwise mb-1" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 3a5 5 0 1 1-4.546 2.914.5.5 0 0 0-.908-.417A6 6 0 1 0 8 2v1z" />
                                        <path d="M8 4.466V.534a.25.25 0 0 0-.41-.192L5.23 2.308a.25.25 0 0 0 0 .384l2.36 1.966A.25.25 0 0 0 8 4.466z" />
                                    </svg>
                                </div>
                                <input type="password" name="confirm-pwd" id="confirm-pwd" class="form-input" placeholder="Confirm New Password" required>
                            </div>
                        </div>
                    </div>

                    <br>
                    <div class="row">
                        <div class="col">
                            <button type="submit" name="change-pwd-submit" id="change-pwd-submit" class="btn btn-wwu-primary float-right">Change Password</button>
                        </div>
                    </div>
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