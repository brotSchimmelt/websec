<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP); // Credentials for the shop db

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment
$userName = $_SESSION['userName'];

// Other variables
$productsPerRow = 3;

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

    <!-- Search form -->
    <div class="prod-center">
        <h2>Product Search</h2>
        <input class="form-control" type="text" placeholder="Search" aria-label="Search" autofocus>
        <p>You searched for <strong>[placeholder]</strong></p>
    </div>


    <!-- Product previews -->
    <section id="products">
        <?php show_products($productsPerRow) ?>
    </section>
    <!-- Page Content END -->


    <?php
    // Load shop footer
    require(FOOTER_SHOP);
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_SHOP); // Custom JavaScript
    ?>
</body>

</html>