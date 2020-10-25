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
    <header id="main-header-sqli" class="shadow">
        <div class="dark-overlay-sqli">
            <?php if (!$solved) : ?>
                <a href="<?= get_challenge_badge_link('sqli') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">SQL Injection</a>
            <?php else : ?>
                <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">SQL Injection</a>
            <?php endif; ?>
            <div class="container" id="header-container">


                <!-- SEARCH BAR -->
                <div class="search-bar-flat-container row justify-content-center" id="friend-search">
                    <h2 class="display-4 mt-4">Find Your Friends</h2>
                    <p class="text-center lead">
                        You are looking for the perfect present and want to know what your friends have on their wishlist?<br>
                        No Problemo! Just use our absolutely privacy conform search form.
                    </p>
                    <form action="friends.php" method="get" class="mt-5">
                        <div class="search-bar-flat-inner">
                            <div class="flat-search">
                                <div class="custom-input-field">
                                    <input class="form-control" size="50" type="text" name="sqli" placeholder="Search for Your Friends" aria-label="Search" <?= $difficulty == "hard" ? 'maxlength="10"' : "" ?> <?= $difficulty == "hard" ? 'data-content="Hint: The users are stored in a SQLite database." data-toggle="popover" data-trigger="focus" data-placement="bottom"' : "" ?>>
                                    <!-- <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>"> -->
                                    <div class="icon-wrap">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-search" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd" d="M10.442 10.442a1 1 0 0 1 1.415 0l3.85 3.85a1 1 0 0 1-1.414 1.415l-3.85-3.85a1 1 0 0 1 0-1.415z" />
                                            <path fill-rule="evenodd" d="M6.5 12a5.5 5.5 0 1 0 0-11 5.5 5.5 0 0 0 0 11zM13 6.5a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0z" />
                                        </svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <br>
                    <small><strong>Info:</strong> We value our users' privacy.
                        If you entered a username in the search field and there is no corresponding user then nothing is displayed.
                    </small>


                </div>
                <!-- SEARCH BAR -->

            </div>
        </div>
    </header>
    <?php
    // positioned down here since the results are directly echoed out
    if (isset($_GET['sqli']) && (!empty($_GET['sqli']))) {

        $searchTerm = $_GET['sqli'];

        // write SQLi Query to challenge input JSON file
        write_to_challenge_json(
            $_SESSION['userName'],
            $_SESSION['userMail'],
            "sqli",
            $_GET['sqli']
        );

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

    <script type="text/javascript" src="../assets/js/csrf.js"></script>
    <div>
        <?php
        echo $modalSuccessCSRFWrongReferrer;
        echo $modalInfoCSRFAlreadyPosted;
        echo $modalErrorCSRFUserMismatch;
        echo $modalSuccessCSRFWrongMessage;
        echo $modalSuccessCSRF;
        ?>
    </div>
</body>

</html>