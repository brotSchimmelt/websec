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

// get product data from database
// $sql = "SELECT `prod_title`, `prod_description`, `price`, `img_path` FROM `products` WHERE `prod_id` = :prod_id";
// $stmt = get_shop_db()->prepare($sql);
// $stmt->execute(['prod_id' => $productID]);
// $productData = $stmt->fetch();
$productData = get_product_data($productID);

// difficulty
$difficulty = get_global_difficulty();


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
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">
    <link rel="stylesheet" href="/assets/css/card.css">
    <link rel="stylesheet" href="/assets/css/comment.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | Products</title>
</head>

<body>
    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>
    <!-- JavaScript -->
    <?php
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    ?>
    <script type="text/javascript">
        // encrypted cookie
        var challengeCookie = "<?= base64_encode($_SESSION['storedXSS']) ?>";
    </script>
    <script type="text/javascript" src="/assets/js/stored_xss.js"></script>


    <!-- HTML Content BEGIN -->
    <?php if (!$solved) : ?>
        <a href="<?= get_challenge_badge_link('stored_xss') ?>" class="badge badge-pill badge-warning shadow-sm" target="_blank">Stored XSS</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">Stored XSS</a>
    <?php endif; ?>

    <div class="page-center page-container jumbotron shadow container">
        <div class="row">

            <div class="col-md-7 mt-5">
                <img class="img-fluid mb-3 shadow" src="<?= $productData['img_path'] ?>" alt="Product Image">
            </div>

            <div class="col-md-5 mt-5">
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
                <h3 class="display-5 text-left mb-3"><b>&euro; <?= $productData['price'] ?></b></h3>
                <p class="text-left"><b>Availability:</b> In Stock</p>
                <!-- <p class="text-left"><b>Condition:</b> New</p> -->
                <p class="text-left"><b>Brand:</b> VeryFakeCompany Ltd.</p>
                <p class="text-left"><?= $productData['prod_description'] ?></p>


                <form action="product.php" method="post">
                    <div class="form-row">
                        <div class="col">
                            <span class="float-right">
                                <input type="hidden" name="product_id" value="<?= $productID ?>">
                                <input class="form-control number-field" type="number" name="quantity" value="1" min="1" max="10" placeholder="-" required>
                            </span>
                        </div>
                        <div class="col">
                            <span class="float-left">
                                <button type="submit" class="btn btn-wwu-cart btn-sm" name="add-product">Add to Cart</button>
                            </span>
                        </div>
                    </div>
                </form>



            </div>
        </div>
    </div>

    <div class="page-center page-container">
        <?php
        // display warning after challenge is completed that user comment was deleted
        if ($solved) {
            echo $alertProductComment;
        }
        ?>
    </div>
    <div class="comment-flex-container">
        <div class="form-comment-box">
            <!-- CHALLENGE: Here is the form for the contact form challenge -->
            <form class="text-center" action="product.php" method="post">
                <h2 class="display-5">Write Your Own Review</h2>
                <br>
                <div class="justify-content-center">
                    <label for="username"><b>Your Username:</b></label>
                    <input class="form-control review-name" type="text" name="username" value="<?= $_SESSION['userName']; ?>" disabled><br>
                    <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
                    <?php
                    if ($difficulty == "hard") {
                        echo "<!-- Token for CSRF challenge -->";
                        echo '<input type="hidden" name="utoken" value="' . $_SESSION['fakeCSRFToken'] . '">';
                    }
                    ?>
                    <label for="userComment"><b>Your Review:</b></label>
                    <input class="form-control review-text" type="text" name="userComment" size="50"><br>
                    <input class="btn btn-wwu-primary" type="submit" value="Submit Comment">
                </div>
            </form>
        </div>

        <div class="comment-box">
            <?php show_xss_comments(); ?>
        </div>
    </div>

    <!-- HTML Content END -->

    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    ?>
</body>

</html>