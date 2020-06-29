<?php

// return a pdo connection to the shop db
function get_shop_db()
{
    static $dbShop;

    if ($dbShop instanceof PDO) {
        return $dbShop;
    }

    try {
        $dbShop = new PDO(DSN_SHOP, DB_USER_SHOP, DB_PWD_SHOP, OPTIONS_SHOP);
    } catch (PDOException $e) {
        exit("Unable to connect to the database :(");
    }
    return $dbShop;
}

function get_number_of_cart_items()
{
    $sql = "SELECT SUM(`quantity`) FROM `cart` WHERE user_name=?";
    $stmt = get_shop_db()->prepare($sql);
    $stmt->execute([$_SESSION['userName']]);

    return $stmt->fetchColumn();
}

function add_product_to_cart($productID, $quantity)
{

    // add existing product to cart
    if (is_product_in_cart($productID)) {

        $quantityQuery = "SELECT `quantity` FROM `cart` WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        $stmtQuantity = get_shop_db()->prepare($quantityQuery);
        $stmtQuantity->execute([
            'prod_id' => $productID,
            'user_name' => $_SESSION['userName']
        ]);
        $result = $stmtQuantity->fetch();

        if (($result['quantity'] + $quantity) >= 3) {
            $quantity = 3;
            $sql = "UPDATE `cart` SET `quantity` = :quantity, `timestamp` = :date WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        } else if (($result['quantity'] + $quantity) <= 0) {
            $quantity = 1;
            $sql = "UPDATE `cart` SET `quantity` = :quantity, `timestamp` = :date WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        } else {
            $sql = "UPDATE `cart` SET `quantity` = `quantity` + :quantity, `timestamp` = :date WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        }

        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            'prod_id' => $productID,
            'user_name' => $_SESSION['userName'],
            'quantity' => $quantity,
            'date' => date("Y-m-d H:i:s")
        ]);
        // add new product to cart
    } else {
        // check if quantity sent by user is not greater than 3
        $quantity = $quantity > 3 ? 3 : $quantity;
        $sql = "INSERT INTO `cart` (`position_id`, `prod_id`, `user_name`, `quantity`, `timestamp`) VALUES (NULL, :prod_id, :user_name, :quantity, :date)";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            'prod_id' => $productID,
            'user_name' => $_SESSION['userName'],
            'quantity' => $quantity,
            'date' => date("Y-m-d H:i:s")
        ]);
    }
}


function is_product_in_cart($productID)
{
    $sql = "SELECT * FROM `cart` WHERE user_name=:user_name AND prod_id=:prod_id";
    $stmt = get_shop_db()->prepare($sql);
    $stmt->execute(['user_name' => $_SESSION['userName'], 'prod_id' => $productID]);

    $num = $stmt->rowCount();

    if ($num > 0) {
        return true;
    } else {
        return false;
    }
}


function show_products($productsPerRow)
{
    $sql = "SELECT prod_id, prod_title, prod_description, price, img_path FROM products";
    $result = get_shop_db()->query($sql);


    $done = false; // is used in product_preview.php
    while ($row = $result->fetch()) {
        echo '<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">';

        $i = $productsPerRow;
        while ($i > 0) {
            if ($i != $productsPerRow) { // don't load a new prod if the first hasn't been displayed yet
                $row = $result->fetch();
            }
            include(INCL . "product_preview.php");
            $i--;
        }
        echo "</div>";
    }
}


function show_cart_content()
{

    $sqlCart = "SELECT `prod_id`, `quantity`, `timestamp` FROM `cart` WHERE `user_name` = :user_name";
    $stmtCart = get_shop_db()->prepare($sqlCart);
    $stmtCart->execute(['user_name' => $_SESSION['userName']]);
    $cart = $stmtCart->fetchAll();

    $i = 0;
    $totalPrice = 0;
    foreach ($cart as $row) {

        $prodID = $row['prod_id'];
        $sqlProducts = "SELECT `prod_title`, `price`, `img_path` FROM `products` WHERE `prod_id` = :prod_id";
        $stmtProd = get_shop_db()->prepare($sqlProducts);
        $stmtProd->execute(['prod_id' => $prodID]);
        $product = $stmtProd->fetch();

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
    echo '<tr><th scope="row">Total</th>' . str_repeat("<td></td>", 3) . "<td><strong>" . $totalPrice . " &euro;</strong></td></tr>";
}


function is_cart_empty()
{
    $sqlCart = "SELECT `prod_id`, `quantity`, `timestamp` FROM `cart` WHERE `user_name` = :user_name";
    $stmtCart = get_shop_db()->prepare($sqlCart);
    $stmtCart->execute(['user_name' => $_SESSION['userName']]);
    $cart = $stmtCart->fetchAll();

    if ($cart && $stmtCart->rowCount() > 0) {
        return true;
    }
    return false;
}


function get_num_of_cart_items()
{
    $sql = "SELECT SUM(quantity) FROM `cart` WHERE `user_name` = :user_name";
    $stmt = get_shop_db()->prepare($sql);
    $stmt->execute(['user_name' => $_SESSION['userName']]);

    return $stmt->fetchColumn();
}



function show_search_results($searchTerm, $productsPerRow)
{
    $sql = "SELECT `prod_id`, `prod_title`, `prod_description`, `price`, `img_path` FROM `products` WHERE `prod_title` LIKE :needle";
    $stmt = get_shop_db()->prepare($sql);
    $needle = "%" . $searchTerm . "%";
    $stmt->bindValue(':needle', $needle, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() <= 0) {
        echo "sorry, seems like we have no products that match your search request :(";
    }


    $done = false; // is used in product_preview.php
    while ($row = $stmt->fetch()) {
        echo '<div class="d-md-flex flex-md-equal w-100 my-md-3 pl-md-3">';

        $i = $productsPerRow;
        while ($i > 0) {
            if ($i != $productsPerRow) { // don't load a new prod if the first hasn't been displayed yet
                $row = $stmt->fetch();
            }
            include(INCL . "product_preview.php");
            $i--;
        }
        echo "</div>";
    }
}
