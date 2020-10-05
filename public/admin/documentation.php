<?php
session_start();

// include config and basic functions
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
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
    <!-- Simple Navbar -->
    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm" id="top">
        <h5 class="my-0 mr-md-auto font-weight-normal">WebSec Documentation</h5>
        <nav class="my-2 my-md-0 mr-md-3">
            <a class="p-2 text-dark" href="../shop/main.php">Back to the Shop</a>
            <a class="p-2 text-dark" href="dashboard.php">Back to the Dashboard</a>
        </nav>
        <a class="btn btn-outline-light" href="/logout.php?token=<?= $_SESSION['userToken'] ?>">Logout</a>
    </div>

    <!-- Headline -->
    <div class="doc-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
        <h1 class="display-4">WebSec Documentation</h1>
        <p class="lead">Here is the HTML version of the project documentation.</p>
        <a class="doc-link" href="#dashboard"> · Dashboard</a>
        <a class="doc-link" href="#shop"> · Shop</a>
        <a class="doc-link" href="#docker"> · Docker</a>
        <a class="doc-link" href="#vagrant"> · Vagrant</a>

    </div>

    <br><br>

    <!-- Content Container -->
    <div class="container">

        <!-- Include Documentations -->
        <div id="dashboard">
            <?php include(DOC . "dashboard.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <div id="dashboard">
            <?php include(DOC . "shop.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <div id="dashboard">
            <?php include(DOC . "docker.html"); ?>
        </div>
        <a href="#top">Back to the Top</a>
        <br>

        <div id="dashboard">
            <?php include(DOC . "vagrant.html"); ?>
        </div>
        <!-- End  Documentation -->
    </div>

    <!-- Simple Footer -->
    <br><br>
    <div class="container">
        <footer class="pt-4 my-md-5 pt-md-5 border-top">
            <a href="#top">Back to the Top</a>
        </footer>
    </div>
</body>

</html>