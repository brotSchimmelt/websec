<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_ADMIN);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
if (!is_user_admin()) {
    // Redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment
$username = $_SESSION['userName'];

// Other php variables
$here = basename($_SERVER['PHP_SELF'], ".php"); // Get script name
?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/admin.css">

    <title>Admin | Shop Settings</title>
</head>

<body>

    <?php
    // Load navbar and sidebar
    require(HEADER_ADMIN);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <div class="container-fluid">
        <div class="row">
            <?php include(SIDEBAR_ADMIN); ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                <div class="jumbotron shadow">
                    <h1 class="display-4">Shop Settings</h1>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Login / Registration</h5>
                                </div>
                                <div class="card-body">
                                    <?php
                                    $registrationStatus = is_registration_enabled() ? "enabled" : "disabled";
                                    $loginStatus = is_login_enabled() ? "enabled" : "disabled";
                                    ?>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Fugit, dolorem!</p>
                                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nemo, aliquam?</p>
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Placeat, iure.</p>
                                </div>
                                <p class=text-center>
                                    Registration is currently <strong><?= $registrationStatus ?></strong>.<br>
                                    Login is currently <strong><?= $loginStatus ?></strong>.
                                </p>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Level of Difficulty</h5>
                                </div>
                                <div class="card-body">
                                    <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Atque, saepe deleniti!</p>
                                </div>
                                <p class="text-center">
                                    Difficulty is currently set to <strong><?= get_global_difficulty() ?></strong>.
                                </p>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Blocked usernames and allowed domains</h5>
                                </div>
                                <div class="card-body">
                                    <p>
                                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Similique, voluptates. Placeat quasi harum, dignissimos ab vero error! Tempore saepe asperiores veritatis tempora eius. Dolore, eum!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Badge Links</h5>
                                </div>
                                <div class="card-body">
                                    <p>
                                        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Similique, voluptates. Placeat quasi harum, dignissimos ab vero error! Tempore saepe asperiores veritatis tempora eius. Dolore, eum!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <!-- HTML Content END -->


    <?php
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_ADMIN); // Custom JavaScript
    ?>
</body>

</html>