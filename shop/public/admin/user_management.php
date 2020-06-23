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

    <title>Admin | User Management</title>
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
    <!-- HTML Content END -->


    <?php
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_ADMIN); // Custom JavaScript
    ?>
</body>

</html>