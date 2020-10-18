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
$numOfStudents = get_num_of_students();
$registrationStatus = is_registration_enabled() ? "enabled" : "disabled";
$loginStatus = is_login_enabled() ? "enabled" : "disabled";

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

    <title>Admin | Dashboard</title>
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

                <div class="jumbotron shadow-sm">
                    <h1 class="display-4">Dashboard</h1>
                    <hr><br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Number of Registered Students</h5>
                                </div>
                                <div class="card-body lead">
                                    <p><strong class="mr-3 text-info"><?= $numOfStudents ?></strong> registered students</p>
                                    <p><strong class="mr-3 text-info"><?= get_num_of_unlocked_students() ?></strong> unlocked students</p>
                                    <p><strong class="mr-3 text-info"><?= get_num_of_admins() ?></strong> admin users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Total Progress of All Registered Students</h5>
                                </div>
                                <div class="card-body lead"><strong class="mr-3 text-info"><?= get_total_progress($numOfStudents, 4) . "%" ?></strong>
                                    of all challenges solved
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Global Settings</h5>
                                </div>
                                <div class="card-body lead">
                                    Level of difficulty: <strong class="ml-3 text-info"><?= get_global_difficulty() ?></strong><br>
                                    Registration is currently: <strong class="ml-3 text-info"><?= $registrationStatus ?></strong><br>
                                    Login is currently: <strong class="ml-3 text-info"><?= $loginStatus ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">List of All Users That Need to Solve at Least One Remaining Challenge</h5>
                                </div>
                                <div class="card-body">
                                    <input type="checkbox" class="tablefilter" name="is-admin" id="is-admin" unchecked />
                                    <label for="is-admin" id="checkbox-admin-label">Hide Admin User</label>
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-striped filteredtable">
                                            <thead>
                                                <tr>
                                                    <td><strong>Pos.</strong></td>
                                                    <td><strong>Username</strong></td>
                                                    <td><strong>Mail</strong> <i>(with mailto link)</i></td>
                                                    <td><strong>Open Challenges</strong></td>
                                                    <td><strong>Admin</strong></td>
                                                    <td><strong>Unlocked</strong></td>
                                                    <td><strong>Last Activity</strong></td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php show_students_with_open_challenges() ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <small>The <strong>*</strong> indicates that a post request was made in the CSRF challenge, but the referrer does not match.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
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