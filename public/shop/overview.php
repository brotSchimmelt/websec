<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP); // Credentials for the shop db
require_once(CONF_DB_LOGIN); // Credentials for the users db

// load functions
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);
require(ERROR_HANDLING);

// load user messages
require(MESSAGES);

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
$userName = $_SESSION['userName'];
$thisPage = basename(__FILE__);
$searchFieldWasUsed =
    (isset($_GET['xss']) && (!empty($_GET['xss']))) ? true : false;
$challengeFailed = false;
$solved = false;
$showSuccessModal = false;
$difficulty = get_global_difficulty();

// check if a search term was entered
if (isset($_GET['xss'])) {
    $searchTerm = filter_input(INPUT_GET, 'xss', FILTER_SANITIZE_SPECIAL_CHARS);
    $rawSearchTerm = $_GET['xss'];

    // write search term to challenge input JSON file
    write_to_challenge_json(
        $userName,
        $_SESSION['userMail'],
        "reflective_xss",
        $rawSearchTerm
    );

    if ($difficulty == "hard") {
        /* 
        * filter all '<script>' tags (case sensitive)
        * --> filter only '<script>' and '<SCRIPT>' tags
        * Solution for all browsers: <ScRiPt>alert(document.cookie)</ScRiPt>
        */
        $rawSearchTerm = str_replace("<script>", "", $rawSearchTerm);
        $rawSearchTerm = str_replace("<SCRIPT>", "", $rawSearchTerm);

        /*
        * Alternative: filter all '<script>' tags (case insensitive)
        * stri_replace("<script>","", $rawSearchTerm)
        * Solution for all tested browsers: <img src="" onerror=javascript:alert(document.cookie)>
        */
    }
}

// check if cookie was entered in modal
if (isset($_POST['xss-cookie'])) {
    $cookie = filter_input(
        INPUT_POST,
        'xss-cookie',
        FILTER_SANITIZE_SPECIAL_CHARS
    );

    // check if cookie is correct
    if (check_reflective_xss_challenge($cookie)) {

        // set challenge to solved
        set_challenge_status("reflective_xss", $userName);

        // get last user input for the challenge
        $solutionInput = get_last_challenge_input($userName, "reflective_xss");

        // write input to solution database
        save_challenge_solution($userName, $solutionInput, "reflective_xss");

        // show success modal!
        $showSuccessModal = true;
    } else {
        // show failure modal
        $challengeFailed = true;
    }
}

// check if reflective xss challenge was already solved
if (lookup_challenge_status("reflective_xss", $userName)) {
    $solved = true;
}

// check if a product was added to the cart
if (isset($_POST['add-preview'])) {
    $productID = filter_input(INPUT_POST, 'product_id');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    add_product_to_cart($productID, $quantity);
    header("location: " . "/shop/overview.php" . "?success=prodAdded");
    exit();
}
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

    <title>Websec | Overview</title>
</head>

<body>
    <?php
    // load navbar
    require(HEADER_SHOP);

    if ($searchFieldWasUsed) {
        // show cookie modal
        echo $modalInputXSSCookie;
    }
    ?>
    <!-- JavaScript -->
    <?php
    require_once(JS_BOOTSTRAP); // default Bootstrap JavaScript 
    ?>
    <script src="/assets/js/reflective_xss.js"></script>

    <!-- Page Content BEGIN -->
    <?php if (!$solved) : ?>
        <a href="<?= get_challenge_badge_link('reflective_xss') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">Reflective XSS</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">Reflective XSS</a>
    <?php endif; ?>

    <!-- Search form -->
    <div class="page-center page-container">
        <div class="search-bar-flat-container row justify-content-center">
            <form action="<?= $thisPage ?>" method="get">
                <div class="search-bar-flat-inner">
                    <div class="flat-search">
                        <div class="custom-input-field">
                            <input class="form-control" type="text" name="xss" placeholder="Search for Products" aria-label="Search" autofocus>
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
        </div>
        <?php if ($searchFieldWasUsed) : ?>
            <p>You searched for <strong><?= $rawSearchTerm ?></strong></p>
        <?php endif; ?>
    </div>

    <?php if ($searchFieldWasUsed) : ?>

        <!-- Search Results -->
        <section id="search-results">
            <?php show_search_results($searchTerm) ?>
        </section>

    <?php else : ?>

        <!-- Product previews -->
        <section id="products">
            <?php show_products() ?>
        </section>
    <?php endif; ?>
    <!-- Page Content END -->

    <!-- JavaScript BEGIN -->
    <?php
    // load shop footer
    require(FOOTER_SHOP);
    // load JavaScript
    require_once(JS_SHOP); // custom JavaScript
    ?>
    <!-- JavaScript END -->

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