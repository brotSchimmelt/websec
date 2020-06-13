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

                <div class="jumbotron shadow-sm">
                    <h1>Dashboard</h1>
                    <p>Insert Tables and Charts with user data.</p>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Number of active users</div>
                            <div class="card-body">Some Number</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Total progress of registered students</div>
                            <div class="card-body">Some Chart</div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Current level of the callenges</div>
                            <div class="card-body">Bachelor/Master etc.</div>
                        </div>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col">
                        <div class="card shadow-sm">
                            <div class="card-header">Students behind schedule</div>
                            <div class="card-body">List of Mail Addresses</div>
                        </div>
                    </div>
                </div>


            </main>
        </div>
    </div>

    <?php include(JS_DASHBOARD); ?>
</body>

</html>