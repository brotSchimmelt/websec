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

// Load POST or GET variables and sanitize input BELOW this comment
$userName = $_SESSION['userName'];
// set_fake_cookie($userName); --> obsolete, since cookies are already set

if (isset($_GET['xss'])) {
    $searchTerm = filter_input(INPUT_GET, 'xss', FILTER_SANITIZE_SPECIAL_CHARS);
    $rawSearchTerm = $_GET['xss'];
}

// Other variables
$productsPerRow = 3;
$searchFieldWasUsed = (isset($_GET['xss']) && (!empty($_GET['xss']))) ? true : false;

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
    <a href="https://en.wikipedia.org/wiki/Cross-site_scripting#Non-persistent_(reflected)" class="badge badge-pill badge-warning shadow-sm" target="_blank">Reflective XSS</a>

    <!-- Search form -->
    <div class="con-center con-search">
        <h2 class="display-4">Product Search</h2>
        <form action="overview.php" method="get">
            <input class="form-control" type="text" name="xss" placeholder="Search for Products" aria-label="Search" autofocus>
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


    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    if ($searchFieldWasUsed && preg_match("/document.cookie/", $rawSearchTerm)) {
        echo "
            <script>
                $('#xss-solution').modal('show')
            </script>";
    }
    ?>
</body>

</html>