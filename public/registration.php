<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN); // DB credentials
require_once(CONF_DB_SHOP); // DB credentials

// load functions
require(FUNC_BASE); // Basic functions
require(FUNC_SHOP); // Shop functions
require(FUNC_LOGIN); // Login & registration functions
require(FUNC_WEBSEC); // Functions for the challenges
require(ERROR_HANDLING); // Error handling

// check if registration is disabled
if (!is_registration_enabled()) {

    // destroy session
    $_SESSION = array();
    session_destroy();

    // load error page
    $name = "Registration";
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
$thisPage = basename(__FILE__);
$name_get = get_var_set('username') ? $_GET['username'] : "";
$name_get = htmlentities($name_get);
$mail_get = get_var_set('mail') ? $_GET['mail'] : "";
$mail_get = htmlentities($mail_get);
$difficulty = get_global_difficulty();

// check if registration was requested 
if (post_var_set('username') && post_var_set('mail') && post_var_set('password') && post_var_set('confirmPassword')) {

    // load POST variables and sanitize input
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_STRING);
    $mail = filter_input(INPUT_POST, "mail", FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];

    // validate user input
    if (validate_registration_input($username, $mail, $password, $confirmPassword)) {
        try_registration($username, $mail, $password);
    }
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

    <title>WebSec | Registration</title>
</head>

<body>

    <!-- HTML Content BEGIN -->
    <div class="jumbotron shadow bg-light login-card overflow-auto">
        <form class="form-signin form-register" action="<?= $thisPage ?>" method="post">
            <h1 class="h3 mb-3 font-weight-normal text-center">User Registration</h1>

            <?=
                // get error message
                get_message();
            ?>

            <label for="username" class="register-label text-muted"><strong>Username:</strong></label>
            <input type="text" name="username" id="register-name" class="form-control" aria-describedby="username-help" value="<?= $name_get ?>" placeholder="Username" data-content="Please use only letters and numbers and 2 to 24 characters." data-toggle="popover" data-trigger="focus" data-placement="bottom" required>

            <label for="mail" class="register-label text-muted"><strong>Mail:</strong></label>
            <input type="email" name="mail" id="register-mail" class="form-control" aria-describedby="mail-help" value="<?= $mail_get ?>" placeholder="WWU Mail" data-content="Please use your @uni-muenster.de mail address." data-toggle="popover" data-trigger="focus" data-placement="bottom" required>

            <label for="password" class="register-label text-muted"><strong>Password:</strong></label>
            <input type="password" name="password" id="register-password" class="form-control" placeholder="Password" data-content="Please use at least 8 characters." data-toggle="popover" data-trigger="focus" data-placement="bottom" required>

            <label for="confirmPassword" class="register-label text-muted"><strong>Confirm Password:</strong></label>
            <input type="password" name="confirmPassword" id="confirm-password" class="form-control" placeholder="Confirm Password" required>

            <button type="submit" name="register-submit" id="registerBtn" class="btn btn-lg btn-register btn-block text-center">Register</button>
            <div class="text-center">
                <a href="index.php" class="btn btn-link login-link">Back to Login Page</a>
            </div>

            <div id="showSemester">
                <p class="mt-5 mb-3 text-muted text-center">&copy; <?= get_semester() ?></p>
                <hr class="accent-blue">
            </div>

        </form>
        <div class="text-center show-difficulty">
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
    // JS for Popover and Tooltip to call all functions
    // BOOTSTRAP_JS must be loaded before this scripts
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $(document).ready(function() {
        $('[data-toggle="popover"]').popover();
    });
</script>

</html>