<?php
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(HEADER_DASH);
$here = basename($_SERVER['PHP_SELF'], ".php");
?>

<!doctype html>
<html lang="en">

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include(SIDEBAR_DASH); ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                <div class="jumbotron shadow">
                    <h1>User Feedback</h1>
                    <hr>

                </div>
            </main>
        </div>
    </div>


    <?php include(JS_DASHBOARD); ?>
</body>

</html>