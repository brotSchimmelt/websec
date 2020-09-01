<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP);
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
$solved = lookup_challenge_status("csrf", $_SESSION['userName']);
$postRequestSent = false;

if (isset($_POST['uname']) && isset($_POST['userPost'])) {
    $username = filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_SPECIAL_CHARS);
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
    <?php if (!$solved) : ?>
        <a href="https://en.wikipedia.org/wiki/Cross-site_request_forgery" class="badge badge-pill badge-warning shadow-sm" target="_blank">CSRF</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">CSRF</a>
    <?php endif; ?>

    <div class="con-search">
        <h2 class="display-4">Contact Our Support Team</h2>
        We are here for you every day, twentyfour hours a day, 365 days a year!
        <br><br>
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
            <input class="btn btn-wwu-primary" type="submit" value="Submit" disabled>
        </form>
        <?= (isset($_SESSION['contactUsed']) && $_SESSION['contactUsed'] == true) ? $alertContactField : "" ?>
    </div>

    <?php
    if ($postRequestSent) {
        $csrfResult = process_csrf($username, $userPost);
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