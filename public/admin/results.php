<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);
require_once(CONF_DB_SHOP);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_ADMIN);
require(FUNC_LOGIN);
require(FUNC_SHOP);
require(FUNC_WEBSEC);

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
    <link rel="stylesheet" href="/assets/css/vendor/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="/assets/css/admin.css">

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Admin | Results</title>
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
                    <h1 class="display-4">Student Results</h1>
                    <hr>

                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm mb-5">
                                <div class="card-header">
                                    <h5 class="display-5">Overview</h5>
                                    <i>(admins are hidden)</i>
                                </div>
                                <div class="card-body">
                                    <input class="form-control" id="user-search" type="text" placeholder="Search User Database ...">
                                    <br>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <td><strong>Pos.</strong></td>
                                                    <td><strong>User Name</strong></td>
                                                    <td><strong>Mail</strong></td>
                                                    <td><strong>Solved Challenges</strong></td>
                                                    <td><strong>CSRF: </strong>Referrer</td>
                                                    <td><strong>CSRF: </strong>Message</td>
                                                    <td><strong>Difficulty</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody id="user-table">
                                                <?php show_solved_challenges() ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small>The <strong>*</strong> indicates that a post request was made in the CSRF challenge, but the referrer does not match.</small>
                                </div>
                            </div>


                            <!-- Second Card -->
                            <div class="card shadow-sm mb-5">
                                <div class="card-header">
                                    <h5 class="display-5">Challenge Solutions</h5>
                                    <i>(admins are hidden)</i>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <td><strong>User Name</strong></td>
                                                    <td><strong>Reflective XSS</strong></td>
                                                    <td><strong>Stored XSS</strong></td>
                                                    <td><strong>SQLi</strong></td>
                                                    <td><strong>CSRF Script</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php show_challenge_solutions() ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small>If the CSRF challenge was solved by manipulating the form elements with the inspector, the <b>CSRF Script</b> field will be empty.</small>
                                </div>
                            </div>

                            <!-- Third Card -->
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">User Input File Size</h5>
                                    <i>(admins included)</i>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped">
                                            <thead class="thead-dark">
                                                <tr>
                                                    <td><strong>Pos.</strong></td>
                                                    <td><strong>User Name</strong></td>
                                                    <td><strong>File</strong></td>
                                                    <td><strong>Size</strong> (in KB)</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php show_file_sizes() ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small>A file size bigger than 20 KB is unusual.</small>
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