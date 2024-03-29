<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN); // DB credentials

// load functions
require(FUNC_BASE); // basic functions
require(FUNC_LOGIN); // login & registration functions
require(FUNC_WEBSEC); // challenge and SQLi functions
require(ERROR_HANDLING); // error handling

// check if login is disabled
if (!is_login_enabled()) {

    // destroy session
    $_SESSION = array();
    session_destroy();

    // redirect to error page
    $name = "Login";
    include(INCL . "login_disabled.php");
    exit();
}

// check login status
if (is_user_logged_in()) {
    // redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// variables
$difficulty = get_global_difficulty();
$thisPage = basename(__FILE__);

// check if login was requested
if (post_var_set('loginUsername') && post_var_set('loginPwd')) {

    // load POST variables
    $username = $_POST['loginUsername'];
    $pwd = $_POST['loginPwd'];

    try_login($username, $pwd);
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/vendor/bootstrap.css">

    <!-- Custom CSS to overwrite Bootstrap.css -->
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>WebSec | Login</title>

</head>

<body class="text-center">

    <!-- HTML Content BEGIN -->
    <div class="jumbotron shadow bg-light login-card overflow-auto">
        <form class="form-signin form-login" action="<?= $thisPage ?>" method="post">
            <img class="mb-4" src="assets/img/wwu_cysec.png" alt="WWU Logo" width="210" height="110">

            <h1 class="h3 mb-3 font-weight-normal">WebSec Shop</h1>

            <!-- Check if JS is enabled -->
            <noscript>
                <div class="alert alert-warning shadow" role="alert">
                    You need to enabled <strong>Java Script</strong> in your browser in order to complete the challenges.
                </div>
            </noscript>

            <?=
                // get error message
                get_message();
            ?>

            <label for="input-name" class="sr-only">Username or WWU Mail</label>
            <input type="text" name="loginUsername" id="input-name" class="form-control" placeholder="Username or WWU Mail" required autofocus>
            <label for="input-password" class="sr-only">Password</label>
            <input type="password" name="loginPwd" id="input-password" class="form-control" placeholder="Password" required>

            <a href="password_reset.php" id="forget_btn" class="btn btn-link login-link">Forgot your password?</a>

            <button type="submit" name="login-submit" id="loginBtn" class="btn btn-lg btn-login btn-block">Login</button>
            <a href="registration.php" id="registerBtnLink" class="btn btn-lg btn-outline-register btn-block">Register</a>

            <p class="mt-5 mb-3 text-muted">&copy; <?= get_semester() ?></p>
            <hr class="accent-blue">
        </form>
        <div class="show-difficulty">
            <small>
                Challenge Difficulty Level:
                <span title="The difficulty is set by the lecturer." data-toggle="tooltip" data-trigger="hover" data-placement="bottom">
                    <strong><?= $difficulty ?></strong>
                </span>
            </small>
        </div>
    </div>
    <!-- HTML Content END -->
</body>

<?php
// load default Bootstrap JavaScript
require_once(JS_BOOTSTRAP);
?>
<script>
    // activate tool tips
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
</script>

</html>