<?php
session_start();

// include config and basic functions
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
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

    <div>
        <h1 class="display-3">Here is an overview of your progress:</h1>
        <p class="lead">Lorem ipsum dolor sit amet consectetur adipisicing elit. Cum explicabo id repellat sint minima fugiat excepturi tempore atque aliquid accusantium, expedita quaerat molestiae nostrum. Voluptate!</p>
        <hr class="my-4">
        <p>This scorecard is just an <em>indicator</em> of your challenges' status!<br>
            The final judgement whether or not a challenge was solved correctly is done by your lecturer.</p>
    </div>
    <?php
    require(FOOTER_SHOP);
    require(JS_SHOP);
    ?>
</body>


</html>