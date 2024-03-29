<?php
session_start(); // needs to be called first on every page

// include config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// basic functions
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
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap core CSS -->
    <link href="../assets/css/vendor/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="../assets/css/doc.css" rel="stylesheet">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>WebSec | Documentation</title>
</head>

<body>
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm" id="top">
        <h5 class="my-0 mr-md-auto font-weight-normal">WebSec Documentation</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="../shop/main.php">Back to the Shop</a>
            <a class="p-2 text-dark" href="dashboard.php">Back to the Dashboard</a>
        </nav>
        <a class="btn btn-outline-warning" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
    </div>

    <div class="doc-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">WebSec Documentation</h1>
        <p class="lead">Here is the HTML version of the project documentation.</p>
        <a class="doc-link" href="#settings"> · Settings</a>
        <a class="doc-link" href="#challenges"> · Challenges</a>
        <a class="doc-link" href="#errors"> · Error Codes</a>
        <a class="doc-link" href="#shop"> · Shop</a>
        <a class="doc-link" href="#docker"> · Docker</a>
        <a class="doc-link" href="#vagrant"> · Test Environment</a>
    </div>
    <br><br>

    <!-- Content Container -->
    <div class="container">

        <!-- Begin Documentation Body -->

        <!-- Settings Section From The WebSec README -->
        <div id="settings">
            <?php include(DOC . "settings.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <!-- Challenge Solutions Cheat Sheet -->
        <div id="challenges">
            <?php include(DOC . "challenges.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <!-- Error Codes From WebSec README -->
        <div id="errors">
            <?php include(DOC . "errors.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <!-- WebSec README Without Error And Setting Section-->
        <div id="shop">
            <?php include(DOC . "shop.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <!-- Docker README -->
        <div id="docker">
            <?php include(DOC . "docker.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <!-- Vagrant README -->
        <div id="vagrant">
            <?php include(DOC . "test_environment.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <!-- End Documentation Body -->
    </div>
    <br><br>

    <!-- Footer -->
    <div class="container">
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <a href="#top">Back to the Top</a>
        </footer>
    </div>
</body>

</html>