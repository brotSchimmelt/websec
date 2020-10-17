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

    <div class="row justify-content-center mt-3">
        <div class="col-xl-4 col-lg-8 col-md-9 col-sm-10 col-xs-11">
            <div class="jumbotron bg-light-grey shadow">

                <h1 class="display-5 text-center">Contact Form</h1>
                <p class="text-center text-muted">We'll never share your personal information. Ever!</p>

                <?= $solved ? $alertContactField : $alertContactFieldClosed ?>

                <!-- CHALLENGE: Here is the form for the contact form challenge -->
                <form action="contact.php" method="post" id="reviewform">

                    <div class="row">
                        <div class="col">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username" type="text" class="form-input form-disabled-input" id="contact-username" aria-describedby="contact-username-help" value="<?= $_SESSION['userName'] ?>" disabled>
                                <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
                                <input type="hidden" name="utoken" value="<?= $_SESSION['fakeCSRFToken']; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-envelope-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z" />
                                    </svg>
                                </div>
                                <input type="email" class="form-input form-disabled-input" id="contactMail" aria-describedby="emailHelp" value="<?= $_SESSION['userMail'] ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <textarea class="form-input" name="userPost" placeholder="Your Comment" rows="3" placeholder="How can we help you?" disabled></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type=" submit" class="btn btn-wwu-primary float-right">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
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