<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP);

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
$postRequestSent = false;
if (isset($_POST['uname']) && isset($_POST['userPost'])) {
    $userName = filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_SPECIAL_CHARS);
    $userPost = filter_input(INPUT_POST, 'userPost', FILTER_SANITIZE_SPECIAL_CHARS);

    $postRequestSent = true;
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

    <title>Websec | Contact</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery" class="badge badge-pill badge-warning shadow-sm" target="_blank">CSRF</a>
    <h2>Contact Our Support Team</h2>
    We are here for you every day, twentyfour hours a day, 365 days a year!
    <h4>Contact Form</h4>
    Dear customer,<br>
    our contact form has been temporarily disabled.<br>We were experiencing heavy hacker attacks at our website and decided<br>to shut down our services for a few days/weeks/months.<br>
    In urgent cases please contact our support team.<br>
    Thanks!<br>
    <br>
    <form action="contact.php" method="post" id="reviewform">
        your name:
        <input type="text" name="username" value="<?= $_SESSION['userName'] ?>" disabled><br>
        <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
        your message for us:
        <input type="text" name="userPost" size="30" disabled><br><br>
        <input type="submit" value="Submit" disabled>
    </form>

    <?php
    if ($postRequestSent) {
        process_csrf($userName, $userPost);
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