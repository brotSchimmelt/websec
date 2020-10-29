<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check login status
if (!is_user_logged_in()) {
    // Redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// check if user is unlocked
if (!is_user_unlocked()) {
    header("location: " . MAIN_PAGE);
    exit();
}

// initialize links to separate help sections
$general = "";
$xss = "";
$sqli = "";
$csrf = "";

// Load POST or GET variables and sanitize input BELOW this comment
if (isset($_GET['help'])) {

    $section = filter_input(INPUT_GET, 'help', FILTER_SANITIZE_STRING);

    // check for sections
    $xss = ($section == "xss") ? "active" : "";
    $sqli = ($section == "sqli") ? "active" : "";
    $csrf = ($section == "csrf") ? "active" : "";
}

// set default to list-group
if (empty($xss) && empty($sqli) && empty($csrf)) {
    $general = "active";
}
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/shop.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Websec | Help</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>
    <div class="page-container">
        <!-- HTML Content BEGIN -->
        <h1 class="display-4">User Instructions</h1>
        <hr>
    </div>

    <div class="row justify-content-around d-none d-lg-block help-container-flex mx-auto">
        <div class="col">
            <div class="row">
                <div class="offset-xl-1 col-xl-auto offset-lg-0 col-lg-auto offset-md-0 mb-5 justify-content-around">
                    <div class="list-group help-list-group mr-3" id="list-tab" role="tablist">
                        <a class="list-group-item list-group-item-action <?= $general ?>" id="list-general-list" data-toggle="list" href="#list-general" role="tab" aria-controls="general">Genral Instructions</a>
                        <a class="list-group-item list-group-item-action <?= $xss ?>" id="list-xss-list" data-toggle="list" href="#list-xss" role="tab" aria-controls="xss">Cross-Site Scripting</a>
                        <a class="list-group-item list-group-item-action <?= $sqli ?>" id="list-sqli-list" data-toggle="list" href="#list-sqli" role="tab" aria-controls="sqli">SQL Injections</a>
                        <a class="list-group-item list-group-item-action <?= $csrf ?>" id="list-csrf-list" data-toggle="list" href="#list-csrf" role="tab" aria-controls="csrf">Contact Form Challenge</a>
                    </div>
                </div>
                <div class="col-xl-8 col-lg-8">
                    <div class="tab-content help-text-list-group" id="nav-tabContent">
                        <!-- General Instructions -->
                        <div class="tab-pane fade show <?= $general ?>" id="list-general" role="tabpanel" aria-labelledby="list-general-list">
                            <?php
                            require(INST_GENERAL);
                            ?>
                        </div>
                        <!-- XSS -->
                        <div class="tab-pane fade show <?= $xss ?>" id="list-xss" role="tabpanel" aria-labelledby="list-xss-list">
                            <?php
                            require(INST_XSS);
                            ?>
                        </div>
                        <!-- SQLi -->
                        <div class="tab-pane fade show <?= $sqli ?>" id="list-sqli" role="tabpanel" aria-labelledby="list-sqli-list">
                            <?php
                            require(INST_SQLI);
                            ?>
                        </div>
                        <!-- CSRF -->
                        <div class="tab-pane fade show <?= $csrf ?>" id="list-csrf" role="tabpanel" aria-labelledby="list-csrf-list">
                            <?php
                            require(INST_CSRF);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center d-lg-none">
        <div class="col justify-content-center help-container-flex">
            <div class="row">
                <div class="col">
                    <!-- General -->
                    <div id="general_sm"></div>
                    <?php
                    require(INST_GENERAL);
                    ?>
                    <!-- XSS -->
                    <div id="xss_sm"></div>
                    <?php
                    require(INST_XSS);
                    ?>
                    <!-- SQLi -->
                    <div id="sqli_sm"></div>
                    <?php
                    require(INST_SQLI);
                    ?>
                    <!-- CSRF -->
                    <div id="csrf_sm"></div>
                    <?php
                    require(INST_CSRF);
                    ?>
                </div>
            </div>
        </div>
    </div>
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