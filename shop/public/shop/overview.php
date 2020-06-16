<?php
session_start();

// include config and basic functions
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

if (!is_user_logged_in()) {
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}
// include Header
require(HEADER_SHOP);
?>

<!doctype html>
<html lang="en">

<body>

    <h1>Here are our wonderful products!</h1>

    <!-- Search form -->
    <div class="search_box">
        <h2>Product Search</h2>
        <input class="form-control" type="text" placeholder="Search" aria-label="Search" autofocus>
        <p>You searched for [placeholder] </p>
    </div>

    <a href=""></a>

    <div class="container">
        <div class="row">
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header"><a href="product.php">Product Nr. 1</a></div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header"><a href="product.php">Product Nr. 2</a></div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card border-primary mb-3" style="max-width: 20rem;">
                    <div class="card-header"><a href="product.php">Product Nr. 3</a></div>
                    <div class="card-body">
                        <h4 class="card-title">Primary card title</h4>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>

</html>