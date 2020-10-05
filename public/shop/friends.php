<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);

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

// Variables
$solved = lookup_challenge_status("sqli", $_SESSION['userName']);
$difficulty = get_global_difficulty();

?>
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

    <title>Websec | Friends</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <?php if (!$solved) : ?>
        <a href="<?= get_challenge_badge_link('sqli') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">SQL Injection</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">SQL Injection</a>
    <?php endif; ?>




    <div class="con-center con-search">
        <h4 class=display-4>Find your Friends</h4>
        You want to know what your friends bought in our shop?<br>
        We got you! Just use our absolutely privacy conform search form:
        <br><br>
        <form action="friends.php" method="post">
            <input class="form-control" size="50" type="text" name="sqli" placeholder="Search for Your Friends" aria-label="Search" <?= $difficulty == "hard" ? 'maxlength="10"' : "" ?> autofocus>
            <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
        </form>
        <br>
        <small><strong>Info:</strong> We value our users' privacy.
            If you entered a username in the search field and there is no corresponding user then nothing is displayed.
        </small>
    </div>

    <?php

    // positioned down here since the results are directly echoed out
    if (isset($_POST['sqli']) && (!empty($_POST['sqli']))) {

        // filter script tags etc.
        $searchTerm = $_POST['sqli'];

        try {
            $queryResultModal = query_sqli_db($searchTerm);
        } catch (Exception $e) {
            display_exception_msg($e, "054");
            exit();
        }
    }
    ?>
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