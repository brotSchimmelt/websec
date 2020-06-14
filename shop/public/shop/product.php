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
    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>


</html>