<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);
require(MESSAGES);

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

// check if cart should be emptied
if (isset($_POST['doit-delete'])) {
    empty_cart($_SESSION['userName']);
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

    <title>Websec | Cart</title>
</head>


<body>

    <?php
    // load cart modal
    echo $modalConfirmDeleteCart;
    // Load navbar
    require(HEADER_SHOP);
    ?>

    <!-- HTML Content BEGIN -->
    <?php
    if (!is_cart_empty()) :
    ?>
        <header id="desert-section">
            <div class="dark-overlay2">
                <div id="home-inner2" class="page-container page-center">
                    <h1 class="display-4">Your cart is empty</h1>
                </div>
            </div>
        </header>
    <?php else : ?>
        <div class="cart-center mt-5">
            <h1 class="display-4">Your Cart</h1>
            <hr>
            <br>
            <div class="row justify-content-center">
                <div class="col-xl-8 col-lg-10 col-md-11 col-sm-auto">
                    <table class="table table-responsive-sm table-striped shadow">
                        <thead class="my-head">
                            <tr>
                                <th scope="col">Position</th>
                                <th scope="col">Product Name</th>
                                <th scope="col">Price</th>
                                <th scope="col">Quantity</th>
                                <th scope="col">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php show_cart_content() ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
        <div class="cart-center page-center">
            <button class="btn btn-danger btn" data-toggle="modal" data-target="#delete-cart">Delete all items</button>
            <span data-content="Due to recent hacker attacks our shop is currently closed! Don't worry, we will remember your cart items the next visit." data-toggle="popover" data-trigger="hover">
                <button class="btn btn-wwu-primary" style="pointer-events: none;" disabled>Checkout</button>
            </span>
        </div>
    <?php endif; ?>
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