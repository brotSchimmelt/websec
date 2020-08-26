<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP); // Credentials for the shop db
require_once(CONF_DB_LOGIN); // Credentials for the users db

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

// Load POST, GET or other variables and sanitize the input if necessary BELOW this comment
$userName = $_SESSION['userName'];
$thisPage = basename(__FILE__);
$productsPerRow = 3;
$searchFieldWasUsed = (isset($_GET['xss']) && (!empty($_GET['xss']))) ? true : false;
$challengeFailed = false;
$solved = false;

// check if a search term was entered
if (isset($_GET['xss'])) {
    $searchTerm = filter_input(INPUT_GET, 'xss', FILTER_SANITIZE_SPECIAL_CHARS);
    $rawSearchTerm = $_GET['xss'];
}

// check if cookie was entered in modal
if (isset($_POST['xss-cookie'])) {
    $cookie = filter_input(INPUT_POST, 'xss-cookie', FILTER_SANITIZE_SPECIAL_CHARS);

    // check if cookie is correct
    if (check_reflective_xss_challenge($userName, $cookie)) {

        // set challenge to solved
        set_challenge_status("reflective_xss", $userName);

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
    <link rel="stylesheet" href="/assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">
    <link rel="stylesheet" href="/assets/css/card.css">

    <title>Websec | Overview</title>
</head>

<body>
    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>

    <!-- Page Content BEGIN -->
    <?php if (!$solved) : ?>
        <a href="https://en.wikipedia.org/wiki/Cross-site_scripting#Non-persistent_(reflected)" class="badge badge-pill badge-warning shadow-sm" target="_blank">Reflective XSS</a>
    <?php else : ?>
        <a href=<?= SCORE ?> class="badge badge-pill badge-success shadow-sm">Reflective XSS</a>
    <?php endif; ?>

    <!-- Search form -->
    <div class="con-center con-search">
        <h2 class="display-4">Product Search</h2>
        <form action="<?= $thisPage ?>" method="get">
            <input class="form-control" type="text" name="xss" placeholder="Search for Products" aria-label="Search" autofocus <?= $solved ? "disabled" : "" ?>>
            <?= $solved ? "<br>You have already solved this challenge!" : "" ?>
        </form>
        <?php if ($searchFieldWasUsed) : ?>
            <p>You searched for <strong><?= $rawSearchTerm ?></strong></p>
        <?php endif; ?>
    </div>

    <?php if ($searchFieldWasUsed) : ?>

        <!-- Search Results -->
        <section id="search-results">
            <?php show_search_results($searchTerm, $productsPerRow) ?>
        </section>

    <?php else : ?>

        <!-- Product previews -->
        <section id="products">
            <?php show_products($productsPerRow) ?>
        </section>
    <?php endif; ?>
    <!-- Page Content END -->

    <!-- JavaScript BEGIN -->
    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    // show modal with field to enter solution
    if ($searchFieldWasUsed && preg_match("/document.cookie/", $rawSearchTerm)) {
        echo "
            <script>
                $('#xss-solution').modal('show')
            </script>";
    }
    // show failure modal
    if ($challengeFailed) {
        echo "
            <script>
                $('#xss-wrong').modal('show')
            </script>";
    }
    // show success modal
    if ($showSuccessModal) {
        echo "
            <script>
                $('#challenge-success').modal('show')
            </script>";
    }
    ?>
    <!-- JavaScript END -->
</body>

</html>