<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
// DB connection for the login 
require_once(CONF_DB_LOGIN);

// load functions
require(FUNC_BASE);
require(FUNC_LOGIN);
require(ERROR_HANDLING);

// check login status
if (is_user_logged_in()) {
    // redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// variables
$thisPage = basename(__FILE__);

// Check if password change was requested
$tokenTest = post_var_set('selector') && post_var_set('validator');
$pwdTest = post_var_set('pwd') && post_var_set('confirm-pwd');
if (isset($_POST['reset-submit']) && $pwdTest && $tokenTest) {

    // Get selector and validator token
    $selector = filter_input(INPUT_POST, 'selector', FILTER_SANITIZE_STRING);
    $validator = filter_input(INPUT_POST, 'validator', FILTER_SANITIZE_STRING);

    // Set the new password
    set_new_pwd($selector, $validator, $_POST['pwd'], $_POST['confirm-pwd'], $thisPage);
} else if (isset($_POST['reset-submit']) && (empty($_POST['selector']) || empty($_POST['validator']))) {

    // redirect to error page
    header("location: " . LOGIN_PAGE . "?error=missingToken");
    exit();
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

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="assets/css/login.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>WebSec | Create new Password</title>
</head>

<body class="text-center">

    <!-- HTML content BEGIN -->
    <div class="jumbotron shadow bg-light login-card overflow-auto">
        <form class="form-signin" action="<?= $thisPage ?>" method="post">
            <h1 class="h3 mb-3 font-weight-normal">Set a new Password</h1>

            <?=
                // load error messages
                get_message();
            ?>

            <!-- Selector and Validator from the URL -->
            <input type="hidden" name="selector" value="<?= $_GET['s'] ?>">
            <input type="hidden" name="validator" value="<?= $_GET['v'] ?>">
            <label for="input-password" class="sr-only">New Password</label>
            <input type="password" name="pwd" id="new-password" class="form-control" placeholder=" New Password" required autofocus>
            <small id="password-help" class="form-text text-muted">Please use only letters and numbers and 2 to 64 characters.</small>
            <br>
            <label for="confirm-password" class="sr-only">Confirm Password</label>
            <input type="password" name="confirm-pwd" id="confirm-password" class="form-control" placeholder="Confirm Password" required>
            <button type="submit" name="reset-submit" id="register-btn" class="btn btn-lg btn-register btn-block">Set New Password</button>

            <p class="mt-5 mb-3 text-muted">&copy;
                <?=
                    // display current semester
                    get_semester()
                ?>
            </p>
            <hr class="accent-blue">
        </form>
    </div>
    <!-- HTML content END -->

    <?php
    // load default Bootstrap JavaScript
    require_once(JS_BOOTSTRAP);
    ?>
</body>

</html>