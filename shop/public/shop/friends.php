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

// Load POST or GET variables and sanitize input BELOW this comment
if (isset($_POST['sqli'])) {
    $searchTerm = filter_input(INPUT_POST, 'sqli', FILTER_SANITIZE_SPECIAL_CHARS);
}

// Variables
$solved = lookup_challenge_status("sqli", $_SESSION['userName']);

?>
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
        <a href="https://en.wikipedia.org/wiki/SQL_injection" class="badge badge-pill badge-warning shadow-sm" target="_blank">SQL Injection</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">SQL Injection</a>
    <?php endif; ?>




    <div class="con-center con-search">
        <h4 class=display-4>Find your Friends</h4>
        You want to know what your friends bought in our shop?<br>
        We got you! Just use our absolutely privacy conform search form:
        <br><br>
        <form action="friends.php" method="post">
            <!-- <input type="text" name="sqli" value=""> -->
            <input class="form-control" size="50" type="text" name="sqli" placeholder="Search for Your Friends" aria-label="Search" autofocus>
        </form>
        <br>
        <small><strong>Info:</strong> We value our users' privacy. If you entered a username in the search field and there is no corresponding user then nothing is displayed.</small>
    </div>

    <?php
    if (isset($_POST['sqli']) && (!empty($_POST['sqli']))) {
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