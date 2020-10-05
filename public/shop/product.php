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

    <div class="con-center con-search jumbotron shadow container">
        <div class="row">

            <div class="col-md-6 mt-5">
                <img class="img-fluid mb-3 shadow" src="<?= $productData['img_path'] ?>" alt="Product Image">

            </div>

            <div class="col-md-6 mt-5">
                <h3 class="display-5"><?= $productData['prod_title'] ?></h3>
                <br>
                <p><?= $productData['prod_description'] ?></p>
                <div class="d-flex flex-row">
                    <div class="p-4 align-self-start">
                        <span class="badge badge-success">5 Stars</span>
                    </div>
                    <div class="p-4 align-self-end">
                        <blockquote class="blockquote">
                            <p class="mb-0">Some super awesome customer review about our product that is definitely not fake.</p>
                            <footer class="blockquote-footer">New York Times <cite title="Source Title">Fake Pundit</cite></footer>
                        </blockquote>
                    </div>
                </div>
                <div class="d-flex flex-row">
                    <div class="p-4 align-self-start">
                        <strong><?= $productData['price'] ?> &euro;</strong>
                    </div>
                    <form action="product.php" method="post">
                        <div class="p-4 align-self-start">
                            <input class="form-control number-field" type="number" name="quantity" value="1" min="1" max="3" placeholder="-" required>

                        </div>
                        <div class="p-4 align-self-end">
                            <input type="hidden" name="product_id" value="<?= $productID ?>">
                            <input type="submit" class="btn btn-wwu-cart btn-sm" name="add-product" value="Add To Cart">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="con-center con-search">
        <h4 class="display-5">Write Your Own Review!</h4>
        <form class="text-center" action="product.php" method="post">
            <div class="justify-content-center">
                Your Name:
                <input class="form-control review-name" type="text" name="username" value="<?= $_SESSION['userName']; ?>" disabled><br>
                <input type="hidden" name="uname" value="<?= $_SESSION['userName']; ?>">
                <!-- Hint: token only relevant when CSRF challenge is set to hard! Otherwise, ignore it.-->
                <input type="hidden" name="utoken" value="<?= $_SESSION['fakeCSRFToken']; ?>">
                Your Review:<br><br>
                <input class="form-control review-text" type="text" name="userComment" size="50"><br>
                <input class="btn btn-wwu-primary" type="submit" value="Submit Comment">
            </div>
        </form>
        <?php
        // display warning after challenge is completed that user comment was deleted
        if ($solved) {
            echo $alertProductComment;
        }
        ?>
    </div>

    <?php require(INCL . "comments.php"); ?>
    <!-- HTML Content END -->

    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    ?>
</body>

</html>