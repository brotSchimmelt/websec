<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

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

    <title>Websec | Products</title>
</head>

<body>
    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <h2>The Greatest Banana Slicer of All Time</h2>

    <h4>Product Description</h4>
    <table border="0" width="500" cellpadding="5">
        <tr>
            <td>
                Here it comes: The world's most famous banana slicer!<br><br>
                You will never need any other banana slicer once you have one of these. It is amazing! Consisting of the findest plastic pieces and absolutely non-sharp, child-proof, never-cutting razors, it does exactly what you want it for.<br><br>
                <button type="button"><strong>BUY NOW</strong></button><br>
                <font size="small">only 5.879 in stock</font><br><br><br>
            </td>
            <td><img src="../assets/img/bananaslicer.jpg" width="250"></td>
        </tr>
    </table>

    <h4>Write a Review</h4>
    <form id="reviewform">
        your name:
        <input type="text" name="username" value="TestUser" disabled><br>
        <input type="hidden" name="uname" value="username">
        your comment:<br>
        <input type="text" name="ucomment" size="50"><br>
        <input type="submit" value="Submit">
    </form>
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