<?php
session_start(); // Needs to be called first on every page

// Include default config file
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

if (isset($_POST['register-submit'])) {
    require(SRC . "registration_script.php");
} else if (isset($_POST['login-submit'])) {
    require(SRC . "login_script.php");
} else if (isset($_POST['add-preview'])) {

    require_once(CONF_DB_SHOP);
    require_once(FUNC_SHOP);
    $productID = filter_input(INPUT_POST, 'product_id');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    add_product_to_cart($productID, $quantity);
    header("location: " . "/shop/overview.php" . "?cart=success");
    exit();
} else if (isset($_POST['add-product'])) {

    require_once(CONF_DB_SHOP);
    require_once(FUNC_SHOP);
    $productID = filter_input(INPUT_POST, 'product_id');
    $quantity = filter_input(INPUT_POST, 'quantity', FILTER_SANITIZE_NUMBER_INT);
    add_product_to_cart($productID, $quantity);
    header("location: " . "/shop/product.php?id=" . $productID . "&cart=success");
    exit();
} else {
    header("location: " . MAIN_PAGE);
    exit();
}
