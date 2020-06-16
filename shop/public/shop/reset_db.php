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
    <h4>Reseting your DataBase ...</h4>
    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>


</html>