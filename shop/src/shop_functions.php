<?php

// return a pdo connection to the shop db
function get_shop_db()
{
    static $dbShop;

    if ($dbShop instanceof PDO) {
        return $dbShop;
    }

    try {
        $dbShop = new PDO(DSN, DB_USER, DB_PWD, OPTIONS);
    } catch (PDOException $e) {
        exit("Unable to connect to the database :(");
    }
    return $dbShop;
}

// function get_number_of_cart_items()
// {
//     // $sql = "SELECT COUNT(id) FROM shopping_cart WHERE user_name=?";
//     $sql = "SELECT SUM(quantity) FROM shopping_cart WHERE user_name=?";
//     $stmt = get_shop_db()->prepare($sql);
//     $stmt->execute([$userID]);

//     return $stmt->fetchColumn();
// }

function add_product_to_cart($productID, $quantity)
{
    // check if quantity is not greater than 3
    $quantity = $quantity > 3 ? 3 : $quantity;

    if (is_product_in_cart($productID)) {


        $sql = "UPDATE `cart` SET `quantity` = `quantity` + :quantity, `timestamp` = :date WHERE `prod_id` = :prod_id AND `user_name` = :user_name";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            'quantity' => $quantity,
            'prod_id' => $productID,
            'user_name' => $_SESSION['userName'],
            'date' => date("Y-m-d H:i:s")
        ]);
    } else {
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


    $done = false;
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
