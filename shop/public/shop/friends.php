<?php
// includes
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(HEADER_SHOP);
?>

<!doctype html>
<html lang="en">

<body>
    <h4>Find your friends</h4>
    You want to know what your friends bought?<br>
    No problemo! Just use the following form:
    <br><br>
    <form action="sqlinjection.php" method="post">
        search a username:
        <input type="text" name="searchuser" size="50" value="">
        <input type="submit" value="Search User">
    </form>
    <br>
    <font size="-1">Info: We value our users' privacy. If you entered a username in the search field and there is no corresponding user then nothing is displayed.</font>
    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>

</html>