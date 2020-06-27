<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_WEBSEC);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment
if (isset($_GET['sqli'])) {
    $searchTerm = filter_input(INPUT_GET, 'sqli', FILTER_SANITIZE_SPECIAL_CHARS);
}

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
    <a href="https://en.wikipedia.org/wiki/SQL_injection" class="badge badge-pill badge-warning shadow-sm" target="_blank">SQL Injection</a>

    <div class="prod-center page-center">
        <h4 class=display-4>Find your Friends</h4>
        You want to know what your friends bought in our shop?<br>
        We got you! Just use our absolutely privacy conform search form:
        <br><br>
        <form action="friends.php" method="get">
            <!-- <input type="text" name="sqli" value=""> -->
            <input class="form-control" size="50" type="text" name="sqli" placeholder="Search for Your Friends" aria-label="Search" autofocus>
        </form>
        <br>
        <small>Info: We value our users' privacy. If you entered a username in the search field and there is no corresponding user then nothing is displayed.</small>
    </div>

    <?php
    if (isset($_GET['sqli']) && (!empty($_GET['sqli']))) {
        query_sqli_db($searchTerm);
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