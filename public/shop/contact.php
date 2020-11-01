<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP);
require_once(CONF_DB_LOGIN);

// load functions
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);
require(ERROR_HANDLING);

// check login status
if (!is_user_logged_in()) {
    // redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// check if user is unlocked
if (!is_user_unlocked()) {
    header("location: " . MAIN_PAGE);
    exit();
}

// variables
$difficulty = get_global_difficulty();
$thisPage = basename(__FILE__);

// check if post was made and if challenge is unsolved
if (
    isset($_POST['uname']) && isset($_POST['userPost'])
    && !lookup_challenge_status("csrf", $_SESSION['userName'])
) {

    // write post message to challenge input JSON file
    write_to_challenge_json(
        $_SESSION['userName'],
        $_SESSION['userMail'],
        "csrf_msg",
        $_POST['userPost']
    );

    // filter post input
    $uname = filter_input(INPUT_POST, 'uname', FILTER_SANITIZE_SPECIAL_CHARS);
    $userPost = filter_input(INPUT_POST, 'userPost', FILTER_SANITIZE_SPECIAL_CHARS);

    // check CSRF token on 'hard'
    if ($difficulty == "hard") {
        $userTokenCSRF = filter_input(INPUT_POST, 'utoken', FILTER_SANITIZE_SPECIAL_CHARS);
    } else {
        $userTokenCSRF = 1;
    }

    // set result
    $_SESSION['csrfResult'] = process_csrf($uname, $userPost, $_SESSION['userName'], $userTokenCSRF);
}

// check if challenge was solved
$solved = lookup_challenge_status("csrf", $_SESSION['userName']);
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <link rel="stylesheet" href="/assets/css/shop.css">

    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | Contact</title>
</head>

<body>
    <?php
    // load navbar
    require(HEADER_SHOP);
    // load error messages, user notifications etc.
    require(MESSAGES);
    ?>

    <?php if (!$solved) : ?>
        <a href="<?= get_challenge_badge_link('csrf') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">CSRF</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">CSRF</a>
    <?php endif; ?>

    <!-- CHALLENGE: Here is the form for the contact form challenge -->
    <div class="row justify-content-center mt-3 card-page-width">
        <!-- deeper ... -->
        <div class="col-xl-4 col-lg-8 col-md-9 col-sm-10 col-xs-11">
            <!-- deeper ... -->
            <div class="jumbotron bg-light-grey shadow">
                <h1 class="display-5 text-center">Contact Form</h1>
                <p class="text-center text-muted">We'll never share your personal information. Ever!</p>
                <?= $solved ? $alertContactField : $alertContactFieldClosed ?>
                <!-- CHALLENGE: This is the form for the contact form challenge -->
                <form action="<?= $thisPage ?>" method="post" id="reviewform">
                    <!-- Comment field ... -->
                    <div class="row">
                        <!-- Comment field ... -->
                        <div class="col">
                            <!-- Comment field! -->
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
                                <input class="form-input form-disabled-input pb-5" name="userPost" rows="3" placeholder="How can we help you?" value="" style="height:100px" disabled>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-wwu-primary float-right">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    // load shop footer
    require(FOOTER_SHOP);
    // load JavaScript
    require_once(JS_BOOTSTRAP); // default Bootstrap JavaScript
    require_once(JS_SHOP); // custom JavaScript
    ?>

    <script type="text/javascript" src="../assets/js/csrf.js"></script>
    <div>
        <?php
        // load modals for CSRF challenge
        echo $modalSuccessCSRFWrongReferrer;
        echo $modalInfoCSRFAlreadyPosted;
        echo $modalErrorCSRFUserMismatch;
        echo $modalSuccessCSRFWrongMessage;
        echo $modalSuccessCSRF;
        ?>
    </div>
</body>

</html>