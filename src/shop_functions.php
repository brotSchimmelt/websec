<?php

/**
 * This file contains all functions that are relevant for the shop functionality
 * of the hacking platform.
 */

/**
 * Get the PDO connection for the shop DB.
 * 
 * Uses the credentials defined in the config.php file.
 *
 * @return \PDO The shop database connection.
 */
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

/**
 * Get the number of cart items.
 * 
 * Return the numbers of cart items in the current session.
 * 
 * @return int Number of cart items.
 */
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

/**
 * Add product to shopping cart.
 * 
 * Add a product with a given quantity to the cart of the current session and 
 * save it in the shop database.
 * 
 * @param int $prodID Product ID.
 * @param int $quantity Quantity of the product.
 */
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

/**
 * Check if product is in cart.
 * 
 * Check if a given product is in the cart of the current session.
 * 
 * @param int $prodID Product ID.
 * @return bool Product status.
 */
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
    return $stmt->rowCount() > 0 ? true : false;
}

/**
 * Display all products.
 * 
 * Load all products from the shop database to the screen.
 */
function show_products()
{

    $sql = "SELECT prod_id, prod_title, prod_description, "
        . "price, img_path FROM products";

    try {
        $result = get_shop_db()->query($sql);
    } catch (PDOException $e) {
        display_exception_msg($e, "155");
        exit();
    }

    echo '<div class="row justify-content-start mx-auto">';
    while ($row = $result->fetch()) {

        echo '<div class="col-xl-4 col-lg-6 col-md-6 col-sm-auto">';

        include(INCL . "shop_product_preview.php");

        echo "</div>";
    }
    echo "</div>";
}

/**
 * Show cart content.
 * 
 * Load all cart items from the shop database and show them in a table.
 */
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

    // check if user has premium discount
    $premium = (lookup_challenge_status("sqli", $_SESSION['userName']))
        ? true : false;

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

        // calculate product price
        if ($premium) {
            // add premium discount
            $price = round((0.5 * (float)$product['price'] / 100), 2);
        } else {
            $price = isset($product['price']) ? $product['price'] / 100 : 42;
        }

        $rowPrice = $row['quantity'] * $price;
        $i++;
        $totalPrice += $rowPrice;

        echo "<tr>";
        echo '<th scope="row">' . $i . '.</th>';
        echo '<td>' . $product['prod_title'] . '</td>';
        echo '<td>' . number_format($price, 2) . ' &euro;</td>';
        echo '<td>' . $row['quantity'] . '</td>';
        echo '<td>' . number_format($rowPrice, 2) . ' &euro;</td>';
        echo "</tr>";
    }
    echo '<tr><th scope="row">Total</th>' . str_repeat("<td></td>", 3)
        . "<td><strong>" . number_format($totalPrice, 2) . " &euro;</strong></td></tr>";
}

/**
 * Check if the cart is empty.
 * 
 * Check if the cart from the current session is empty.
 * 
 * @return bool Cart status.
 */
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

/**
 * Get the number of items in the cart.
 * 
 * Get the number of items in the cart for the current session from the shop 
 * database.
 * 
 * @return int Number of items in cart.
 */
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

/**
 * Show search results.
 * 
 * Display all products from the shop database that match the search term.
 * 
 * @param string $searchTerm Search term.
 */
function show_search_results($searchTerm)
{
    $sql = "SELECT `prod_id`, `prod_title`, `prod_description`, `price`, "
        . "`img_path` FROM `products` WHERE `prod_title` COLLATE utf8mb4_0900_as_ci LIKE :needle";
    try {
        $stmt = get_shop_db()->prepare($sql);
        $needle = "%" . $searchTerm . "%";

        // bind value since LIKE statements do not work out of the box
        // with prepared statements
        $stmt->bindValue(':needle', $needle, PDO::PARAM_STR);
        $stmt->execute();
    } catch (PDOException $e) {
        display_exception_msg($e, "160");
        exit();
    }

    if ($stmt->rowCount() <= 0) {

        echo '<div class="page-center page-container lead">Sorry, it seems '
            . 'like we have no products that match your search request &#128533;<br>';

        // check if XSS was tried
        $pos1 = strpos($searchTerm, "document.cookie");
        if ($pos1 !== false) {
            $btn = '<button type="button" class="btn btn-link btn" '
                . 'data-toggle="modal" data-target="#xss-solution">Challenge '
                . 'Cookie</button>';
            $msg = 'Do you want to enter the' . $btn . '?';

            echo $msg;
        }
        echo "</div>";
    } else {

        echo '<div class="row justify-content-center mx-auto">';
        while ($row = $stmt->fetch()) {

            echo '<div class="col-xl-4 col-lg-6 col-md-6 col-sm-auto">';

            include(INCL . "shop_product_preview.php");

            echo "</div>";
        }
        echo "</div>";
    }
}

/**
 * Empty the cart.
 * 
 * Remove all products from the cart for the current session by deleting the 
 * cart entries in the shop database.
 * 
 * @param string $username User name.
 */
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

/**
 * Get all product data.
 * 
 * Get all data for a given product from the shop database.
 * 
 * @param int $prodID Product ID.
 */
function get_product_data($prodID)
{

    $sql = "SELECT `prod_title`, `prod_description`, `price`, `img_path` FROM "
        . "`products` WHERE `prod_id` = :prod_id";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute(['prod_id' => $prodID]);
    } catch (PDOException $e) {
        display_exception_msg($e, "168");
        exit();
    }

    return $stmt->fetch();
}

/**
 * Save the current challenge solution.
 * 
 * Write the user input that solved a given challenge to the shop database.
 * 
 * @param string $username User name.
 * @param string $solution User input that solved the challenge.
 * @param string $challenge Challenge name.
 */
function save_challenge_solution($username, $solution, $challenge)
{

    $sql = "UPDATE `challenge_solutions` SET " . $challenge . " = :solution "
        . "WHERE `user_name` = :user";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            "solution" => $solution,
            "user" => $username
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "169");
        exit();
    }
}

/**
 * Get the challenge solution.
 * 
 * Get the user input for a given challenge from the database that solved it.
 * 
 * @param string $username User name.
 * @param string $challenge Name of the challenge.
 * @return string User input.
 */
function get_challenge_solution($username, $challenge)
{
    $sql = "SELECT " . $challenge . " FROM `challenge_solutions` WHERE "
        . "`user_name`=:user";

    try {
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            "user" => $username
        ]);
    } catch (PDOException $e) {
        display_exception_msg($e, "170");
        exit();
    }

    $result = $stmt->fetch();
    return $result[$challenge];
}
