<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment

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
        <form action="friends.php" method="post">
            search a username:
            <input type="text" name="sqli" size="50" value="">
            <input type="submit" value="Search User">
        </form>
        <br>
        <font size="-1">Info: We value our users' privacy. If you entered a username in the search field and there is no corresponding user then nothing is displayed.</font>
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