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
                                    <h5 class="display-5">Number of registered students</h5>
                                </div>
                                <div class="card-body">
                                    <p><strong><?= $numOfStudents ?></strong> registered students</p>
                                    <p><strong><?= get_num_of_unlocked_students() ?></strong> unlocked students</p>
                                    <p><strong><?= get_num_of_admins() ?></strong> admin users</p>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Total progress of all registered students</h5>
                                </div>
                                <div class="card-body"><strong><?= get_total_progress($numOfStudents, 4) . "%" ?></strong>
                                    of all challenges solved
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Global Challenge Difficulty</h5>
                                </div>
                                <div class="card-body">
                                    Level of difficulty: <strong><?= get_global_difficulty() ?></strong>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">List of all students that need to solve at least one remaining challenge</h5>
                                </div>
                                <div class="card-body">
                                    <input type="checkbox" class="tablefilter" name="is-admin" id="is-admin" unchecked />
                                    <label for="is-admin" id="checkbox-admin-label">Hide Admin User</label>
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