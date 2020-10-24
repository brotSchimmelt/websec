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
if (!isset($_GET['id']) or empty($_GET['id'])) {
    $productID = 1;
} else if (isset($_GET['id']) and (!empty($_GET['id']))) {
    $productID = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
}

$productData = get_product_data($productID);
$price = isset($productData['price']) ? $productData['price'] / 100 : 42;
$premiumPrice = round((0.5 * (float)$price), 2);
$difficulty = get_global_difficulty();
$solvedSQLi = lookup_challenge_status("sqli", $_SESSION['userName']);


// challenge
if (isset($_POST['userComment']) && (!empty($_POST['userComment']))) {

    if ($difficulty == "hard") {

        // filter all '<script>' tags (case insensitive)
        // solution for all tested browsers: <img src="" onerror=javascript:alert(document.cookie)>
        $filteredComment = str_ireplace("<script>", "", $_POST['userComment']);

        /*
        * Alternative: filter only '<script>' and '<SCRIPT>' tags
        * str_replace("<script>","", $rawSearchTerm)
        * str_replace("<SCRIPT>","", $rawSearchTerm)
        * Solution for all browsers: <ScRiPt>alert(document.cookie)</ScRiPt>
        */

        // additionally filter 'alert' command
        // solution: use confirm() or prompt()
        $filteredComment = str_replace("alert", "", $filteredComment);

        // add filtered comment to database
        add_comment_to_db($filteredComment, $_SESSION['userName']);
    } else {
        // normal difficulty
        add_comment_to_db($_POST['userComment'], $_SESSION['userName']);
    }
}
if (isset($_POST['add-product'])) {

    $productID = filter_input(INPUT_POST, 'product_id');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    add_product_to_cart($productID, $quantity);
    header("location: " . "/shop/product.php?id=" . $productID . "&success=prodAdded");
    exit();
}
$solved = lookup_challenge_status("stored_xss", $_SESSION['userName']);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <link rel="stylesheet" href="/assets/css/shop.css">

    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | Products</title>
</head>

<body>
    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);

    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    ?>
    <script type="text/javascript">
        // encrypted cookie
        var challengeCookie = "<?= base64_encode($_SESSION['storedXSS']) ?>";
    </script>
    <script type="text/javascript" src="/assets/js/stored_xss.js"></script>


    <?php if (!$solved) : ?>
        <a href="<?= get_challenge_badge_link('stored_xss') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">Stored XSS</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">Stored XSS</a>
    <?php endif; ?>

    <div class="page-center page-container jumbotron shadow container bg-light-grey">
        <div class="row">

            <div class="col-md-auto col-lg-6 col-xl-7 mt-5">
                <img class="img-fluid mb-3 shadow" src="<?= $productData['img_path'] ?>" alt="Product Image">
            </div>

            <div class="col-md-auto col-lg-6 col-xl-5 mt-5">
                <div class="text-left">
                    <span class="badge badge-success mb-4">NEW!</span>
                </div>
                <h2 class="display-5 text-left"><?= $productData['prod_title'] ?></h2>
                <p class="text-muted text-left mt-n3"><small><b>Article Code:</b> <?= str_shuffle("qwertz1357") ?></small></p>
                <div class="text-warning text-left mt-n1 mb-3">
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                    </svg>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                    </svg>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                    </svg>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z" />
                    </svg>
                    <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-star-half" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M5.354 5.119L7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.519.519 0 0 1-.146.05c-.341.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.171-.403.59.59 0 0 1 .084-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027c.08 0 .16.018.232.056l3.686 1.894-.694-3.957a.564.564 0 0 1 .163-.505l2.906-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.002 2.223 8 2.226v9.8z" />
                    </svg>
                </div>
                <?php if ($solvedSQLi) : ?>
                    <h3 class="display-5 text-left mb-3"><s>&euro; <?= $price ?></s>&nbsp;&nbsp;<b class="text-success">&euro;<?= $premiumPrice ?></b></h3>
                <?php else : ?>
                    <h3 class="display-5 text-left mb-3">&euro; <?= $price ?></h3>
                <?php endif; ?>

                <p class="text-left"><b>Availability:</b> In Stock</p>
                <p class="text-left"><b>Brand:</b> VeryFakeCompany Ltd.</p>
                <p class="text-left"><?= $productData['prod_description'] ?></p>


                <form class="mb-5 mt-5" action="product.php" method="post">
                    <div class="form-row">
                        <div class="col-5">
                            <span class="float-right">
                                <strong>Quantity:</strong>
                            </span>
                        </div>
                        <div class="col-2">
                            <span class="float-right">
                                <input type="hidden" name="product_id" value="<?= $productID ?>">
                                <input class="form-control number-field" type="number" name="quantity" value="1" min="1" max="10" placeholder="-" required>
                            </span>
                        </div>
                        <div class="col-5">
                            <span class="float-left">
                                <button type="submit" class="btn btn-wwu-cart btn-sm" name="add-product">Add to Cart</button>
                            </span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php
    // display warning after challenge is completed that user comment was deleted
    if ($solved) {
        echo '<div class="page-center page-container">';
        echo $alertProductComment;
        echo "</div>";
    }
    ?>
    <!-- CHALLENGE: Here begins the form -->
    <div class="row justify-content-center mt-5" id="comment-section">
        <!-- deeper ... -->
        <div class="col-xl-4 col-lg-6 col-md-auto">
            <!-- deeper ... -->
            <div class="be-comment-block">
                <!-- here you go! -->
                <form class="form-block" action="product.php" method="post" id="CSRForm">
                    <h4 class="display-5 mb-4">Write Your Own Comment</h4>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-12">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input class="form-input form-disabled-input" name="username" type="text" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-12">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-envelope-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414.05 3.555zM0 4.697v7.104l5.803-3.558L0 4.697zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586l-1.239-.757zm3.436-.586L16 11.801V4.697l-5.803 3.546z" />
                                    </svg>
                                </div>
                                <input class="form-input form-disabled-input" type="text" value="<?= $_SESSION['userMail'] ?>" disabled>
                            </div>
                        </div>
                    </div>
                    <!-- CHALLENGE: Comment -->
                    <div class="row">
                        <!-- deeper ... -->
                        <div class="col">
                            <!-- here you go! -->
                            <div class="form-group">
                                <input id="challengeUsername" type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
                                <?php
                                if ($difficulty == "hard") {
                                    echo "<!-- Token for CSRF challenge -->";
                                    echo '<input id="challengeToken" type="hidden" name="utoken" value="' . $_SESSION['fakeCSRFToken'] . '">';
                                }
                                ?>
                                <input id="challengePost" class="form-input pb-5" value="" name="userComment" placeholder="Your Comment" style="height:100px" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <button type="submit" class="btn btn-wwu-primary float-right">Post</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- CHALLENGE: END -->
        </div>

        <div class="col-xl-4 col-lg-6 col-md-auto">
            <div class="be-comment-block">
                <?php
                show_xss_comments();
                ?>
            </div>
        </div>
    </div>

    <?php
    // Load shop footer
    require(FOOTER_SHOP);
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