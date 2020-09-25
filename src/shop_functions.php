<?php

// return a pdo connection to the shop db
function get_shop_db()
{
    static $dbShop;

    if ($dbShop instanceof PDO) {
        return $dbShop;
    }
    require_once(CONF_DB_SHOP);
    try {
        $dbShop = new PDO(DSN_SHOP, DB_USER_SHOP, DB_PWD_SHOP, OPTIONS_SHOP);
    } catch (PDOException $e) {
        $note = "The connection to our database could not be established. "
            . 'If this error persists, please post it to the '
            . '<a href="https://www.uni-muenster.de/LearnWeb/learnweb2/" '
            . 'target="_blank">Learnweb</a> forum.';
        display_exception_msg(null, "020", $note);
        exit();
    }
    return $dbShop;
}

// get the number of cart items
function get_number_of_cart_items()
{
    $sql = "SELECT SUM(`quantity`) FROM `cart` WHERE user_name=?";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$_SESSION['userName']]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        trigger_error("Code Error: The number of cart items could not be fetched.");
        return 0;
    }
}

// add product to shopping cart
function add_product_to_cart($productID, $quantity)
{

    // add existing product to cart
    if (is_product_in_cart($productID)) {

        $quantityQuery = "SELECT `quantity` FROM `cart` WHERE `prod_id` = "
            . ":prod_id AND `user_name` = :user_name";

        try {
            $stmtQuantity = get_shop_db()->prepare($quantityQuery);
            $stmtQuantity->execute([
                'prod_id' => $productID,
                'user_name' => $_SESSION['userName']
            ]);
            $result = $stmtQuantity->fetch();
        } catch (PDOException $e) {
            display_exception_msg($e, "151");
            exit();
        }

        // max quantity for a product is 1024
        if (($result['quantity'] + $quantity) >= 1024) {
            $quantity = 1024;
            $sql = "UPDATE `cart` SET `quantity` = :quantity, `timestamp` = "
                . ":date WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        } else if (($result['quantity'] + $quantity) <= 0) {
            $quantity = 1;
            $sql = "UPDATE `cart` SET `quantity` = :quantity, `timestamp` "
                . "= :date WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        } else {
            $sql = "UPDATE `cart` SET `quantity` = `quantity` + :quantity, "
                . "`timestamp` = :date WHERE `prod_id` = :prod_id AND "
                . "`user_name` = :user_name";
        }
        try {
            $stmt = get_shop_db()->prepare($sql);
            $stmt->execute([
                'prod_id' => $productID,
                'user_name' => $_SESSION['userName'],
                'quantity' => $quantity,
                'date' => date("Y-m-d H:i:s")
            ]);
        } catch (PDOException $e) {
            display_exception_msg($e, "152");
            exit();
        }
        // add new product to cart
    } else {
        // check if quantity sent by user is not greater than 3
        $quantity = $quantity > 10 ? 10 : $quantity;

        $sql = "INSERT INTO `cart` (`position_id`, `prod_id`, `user_name`, "
            . "`quantity`, `timestamp`) VALUES "
            . "(NULL, :prod_id, :user_name, :quantity, :date)";

        try {
            $stmt = get_shop_db()->prepare($sql);
            $stmt->execute([
                'prod_id' => $productID,
                'user_name' => $_SESSION['userName'],
                'quantity' => $quantity,
                'date' => date("Y-m-d H:i:s")
            ]);
        } catch (PDOException $e) {
            display_exception_msg($e, "153");
            exit();
        }
    }
}

// check if product type is already in the cart
function is_product_in_cart($productID)
{
    $sql = "SELECT * FROM `cart` WHERE user_name=:user_name AND prod_id=:prod_id";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(
            [
                'user_name' => $_SESSION['userName'],
                'prod_id' => $productID
            ]
        );
    } catch (PDOException $e) {
        display_exception_msg($e, "154");
        exit();
    }

    $num = $stmt->rowCount();
    // TODO: Ternary return 
    if ($num > 0) {
        return true;
    } else {
        return false;
    }
}

// get all products from the product database
function show_products($productsPerRow)
{
    $sql = "SELECT prod_id, prod_title, prod_description, "
        . "price, img_path FROM products";
    try {
        $result = get_shop_db()->query($sql);
    } catch (PDOException $e) {
        display_exception_msg($e, "155");
        exit();
    }

    $solvedStoredXSS = lookup_challenge_status("stored_xss", $_SESSION['userName']);


    $done = false; // is used in product_preview.php
    while ($row = $result->fetch()) {
        echo '<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">';

        $i = $productsPerRow;
        while ($i > 0) {
            // don't load a new prod if the first hasn't been displayed yet
            if ($i != $productsPerRow) {
                $row = $result->fetch();
            }
            include(INCL . "product_preview.php");
            $i--;
        }
        echo "</div>";
    }
}

// show current content of the users shopping cart
function show_cart_content()
{

    $sqlCart = "SELECT `prod_id`, `quantity`, `timestamp` FROM "
        . "`cart` WHERE `user_name` = :user_name";
    try {
        $stmtCart = get_shop_db()->prepare($sqlCart);
        $stmtCart->execute(['user_name' => $_SESSION['userName']]);
        $cart = $stmtCart->fetchAll();
    } catch (PDOException $e) {
        display_exception_msg($e, "156");
        exit();
    }

    $i = 0;
    $totalPrice = 0;
    foreach ($cart as $row) {

        $prodID = $row['prod_id'];
        $sqlProducts = "SELECT `prod_title`, `price`, `img_path` FROM "
            . "`products` WHERE `prod_id` = :prod_id";
        try {
            $stmtProd = get_shop_db()->prepare($sqlProducts);
            $stmtProd->execute(['prod_id' => $prodID]);
            $product = $stmtProd->fetch();
        } catch (PDOException $e) {
            display_exception_msg($e, "157");
            exit();
        }

        $rowPrice = $row['quantity'] * $product['price'];
        $i++;
        $totalPrice += $rowPrice;

        echo "<tr>";
        echo '<th scope="row">' . $i . '.</th>';
        echo '<td>' . $product['prod_title'] . '</td>';
        echo '<td>' . $product['price'] . ' &euro;</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . $rowPrice . ' &euro;</td>';
        echo "</tr>";
    }
    echo '<tr><th scope="row">Total</th>' . str_repeat("<td></td>", 3)
        . "<td><strong>" . $totalPrice . " &euro;</strong></td></tr>";
}

// check if there are no products in the shopping cart
function is_cart_empty()
{
    $sqlCart = "SELECT `prod_id`, `quantity`, `timestamp` "
        . "FROM `cart` WHERE `user_name` = :user_name";
    try {
        $stmtCart = get_shop_db()->prepare($sqlCart);
        $stmtCart->execute(['user_name' => $_SESSION['userName']]);
        $cart = $stmtCart->fetchAll();
    } catch (PDOException $e) {
        display_exception_msg($e, "158");
        exit();
    }

    if ($cart && $stmtCart->rowCount() > 0) {
        return true;
    }
    return false;
}

// return the number of cart items
function get_num_of_cart_items()
{
    $sql = "SELECT SUM(quantity) FROM `cart` WHERE `user_name` = :user_name";
    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['user_name' => $_SESSION['userName']]);
    } catch (PDOException $e) {
        display_exception_msg($e, "159");
        exit();
    }

    return $stmt->fetchColumn();
}

// display the product search results
function show_search_results($searchTerm, $productsPerRow)
{
    $sql = "SELECT `prod_id`, `prod_title`, `prod_description`, `price`, "
        . "`img_path` FROM `products` WHERE `prod_title` LIKE :needle";
    try {
        $stmt = get_shop_db()->prepare($sql);
        $needle = "%" . $searchTerm . "%";
        $stmt->bindValue(':needle', $needle, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        display_exception_msg($e, "160");
        exit();
    }

    if ($stmt->rowCount() <= 0) {

        echo '<div class="con-center con-search">Sorry, it seems '
            . 'like we have no products that match your search request :(<br>';


        $pos1 = strpos($searchTerm, "document.cookie");
        if ($pos1 !== false) {
            $btn = '<button type="button" class="btn btn-link btn-sm" '
                . 'data-toggle="modal" data-target="#xss-solution">Challenge '
                . 'Cookie</button>';
            $msg = 'Do you want to enter the' . $btn . '?';

            echo $msg;
        }

        echo '</div>';
    }


    $done = false; // is used in product_preview.php
    while ($row = $stmt->fetch()) {
        echo '<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">';

        $i = $productsPerRow;
        while ($i > 0) {
            // don't load a new prod if the first hasn't been displayed yet
            if ($i != $productsPerRow) {
                $row = $stmt->fetch();
            }
            include(INCL . "product_preview.php");
            $i--;
        }
        echo "</div>";
    }
}

// empty the current cart of the user
function empty_cart($username)
{

    $sql = "DELETE FROM `cart` WHERE `user_name`=?";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$username]);
    } catch (PDOException $e) {
        display_exception_msg($e, "164");
        exit();
    }
}
