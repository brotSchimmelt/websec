<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);
require_once(CONF_DB_SHOP);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
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
$username = $_SESSION['userName'];

if (isset($_POST['simplexss']) && isset($_POST['doit-simplexss'])) {
    reset_reflective_xss_db($username);
}
if (isset($_POST['storedxss']) && isset($_POST['doit-storedxss'])) {
    reset_stored_xss_db($username);
}
if (isset($_POST['sqli']) && isset($_POST['doit-sqli'])) {
    reset_sqli_db($username);
}
if (isset($_POST['csrf']) && isset($_POST['doit-csrf'])) {
    reset_csrf_db($username);
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

    <title>Websec | Database Reset</title>
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
        <h4>RESET REFLECTIVE XSS</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="reset_db.php" method="post">
            your name:
            <input type="text" name="username" value="<?= $username; ?>" disabled><br>
            <input type="hidden" name="doit-simplexss" value="1">
            <input type="hidden" name="simplexss" value="1">
            <input type="submit" value="RESET REFLECTIVE XSS CHALLENGE">
        </form>
        <br>
        <hr><br>

        <h4>RESET STORED XSS</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="reset_db.php" method="post">
            your name:
            <input type="text" name="username" value="<?= $username; ?>" disabled><br>
            <input type="hidden" name="doit-storedxss" value="1">
            <input type="hidden" name="storedxss" value="1">
            <input type="submit" value="RESET STORED XSS CHALLENGE">
        </form>
        <br>
        <hr><br>

        <h4>RESET SQL DATABASE</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="reset_db.php" method="post">
            your name:
            <input type="text" name="username" value="<?= $username; ?>" disabled><br>
            <input type="hidden" name="doit-sqli" value="1">
            <input type="hidden" name="sqli" value="1">
            <input type="submit" value="RESET SQL DATABASE">
        </form>
        <br>
        <hr><br>

        <h4>RESET CONTACT FORM</h4>
        This will <strong>delete all your achievements</strong>!<br>
        <form action="reset_db.php" method="post">
            your name:
            <input type="text" name="username" value="<?= $username; ?>" disabled><br>
            <input type="hidden" name="doit-csrf" value="1">
            <input type="hidden" name="csrf" value="1">
            <input type="submit" value="RESET SUPPORT CONTACT">
        </form>
        <br><br>
        <hr><br>
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