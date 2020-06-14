<?php
session_start();

// include config and basic functions
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE);

// check if user is logged in
if (!is_user_logged_in()) {
    header("location: " . LOGIN_PAGE . "?login=accessDenied");
    exit();
}

// check if user is admin
if (!is_user_admin()) {
    header("location: " . MAIN_PAGE);
    exit();
}

// include header
require(HEADER_DASH);
$here = basename($_SERVER['PHP_SELF'], ".php"); // get script name
?>

<!doctype html>
<html lang="en">

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include(SIDEBAR_DASH); ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                <div class="jumbotron shadow">
                    <h1>User Management</h1>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">Quick Metrics</div>
                                <div class="card-body">
                                    <p>Number of Users: 42</p>
                                    <p>Number of Admin Users: 42</p>
                                    <p>Last Activity: 01.01.1970</p>
                                    <p></p>
                                </div>
                            </div>
                        </div>
                        <div class="col">

                        </div>
                        <div class="col">

                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">List of all users</div>
                                <div class="card-body">[Table]</div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>


    <?php include(JS_DASHBOARD); ?>
</body>

</html>