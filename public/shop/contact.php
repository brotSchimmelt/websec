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

// check if post was made and contact field still open
if (isset($_POST['uname']) && isset($_POST['userPost']) && !lookup_challenge_status("csrf", $_SESSION['userName'])) {

    // filter post input
    $uname = filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_SPECIAL_CHARS);
    $userPost = filter_input(INPUT_POST, 'userPost', FILTER_SANITIZE_SPECIAL_CHARS);
    $userTokenCSRF = filter_input(INPUT_POST, 'utoken', FILTER_SANITIZE_SPECIAL_CHARS);

    $csrfResult = process_csrf($uname, $userPost, $_SESSION['userName'], $userTokenCSRF);
}

// check if challenge was solved
$solved = lookup_challenge_status("csrf", $_SESSION['userName']);

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
        <a href="<?= get_challenge_badge_link('csrf') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">CSRF</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">CSRF</a>
    <?php endif; ?>

    <div class="con-search">
        <div class="jumbotron form-container-shop shadow">

            <h1 class="display-5 text-center">Contact Form</h1>
            <p class="text-center text-muted">We'll never share your personal information. Ever!</p>

            <?= $solved ? $alertContactField : $alertContactFieldClosed ?>

            <form action="contact.php" method="post" id="reviewform">
                <div class="form-group">
                    <label for="contact-username"><b>Your Username:</b></label>
                    <input name="username" type="text" class="form-control" id="contact-username" aria-describedby="contact-username-help" value="<?= $_SESSION['userName'] ?>" disabled>
                </div>
                <div class="form-group">
                    <label for="contactMail"><b>Your E-Mail:</b></label>
                    <input type="email" class="form-control" id="contactMail" aria-describedby="emailHelp" placeholder="Enter Your Mail" disabled>
                </div>
                <div class="form-group">
                    <label for="exampleFormControlTextarea1"><b>Your Message:</b></label>
                    <textarea name="userPost" class="form-control" id="exampleFormControlTextarea1" rows="3" placeholder="How can we help you?" disabled></textarea>
                </div>
                <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
                <input type="hidden" name="utoken" value="<?= $_SESSION['fakeCSRFToken']; ?>">
                <br>
                <input class="btn btn-wwu-primary" type="submit" value="Submit" disabled>
            </form>
        </div>
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