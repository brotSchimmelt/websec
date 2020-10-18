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

    <style type="text/css">
        @media print {
            body {
                margin-left: 2cm;
                margin-right: 2cm;
                margin-top: 3cm;
                margin-bottom: 1.8cm;
            }
        }
    </style>

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <title>Admin | Export Data</title>
</head>

<body>

    <?php
    // Load navbar and sidebar
    require(HEADER_ADMIN);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>

    <div class="d-none d-print-block">
        <!-- only visible in print mode -->
        <div class="row">
            <div class="col justify-content-center">
                <h1 class="display-4">Student Results</h1>
                <hr>
                <br>
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
                    <tbody>
                        <?php show_solved_challenges() ?>
                    </tbody>
                </table>
                <p id="export-table-bottom">
                    <small>
                        The <strong>*</strong> indicates that a post request was made in the CSRF challenge, but the referrer does not match.
                    </small>
                </p>
                <hr>
                <p>
                    <strong>Semester:</strong> <?php get_semester() ?>
                    <br>
                    <strong>Export Date:</strong> <?= date(DATE_RFC850) ?>
                </p>
            </div>
        </div>
    </div>

    <!-- HTML Content BEGIN -->
    <div class="container-fluid d-print-none">
        <div class="row">
            <?php include(SIDEBAR_ADMIN); ?>
            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">
                <div class="jumbotron shadow">
                    <h1 class="display-4">Export Data</h1>
                    <hr>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h4 class="display-5">Download Results</h4>
                                </div>
                                <div class="card-body">
                                    <p class="lead">
                                        You can either download the results of the students as a JSON file or print them directly as a PDF.
                                        The JSON file has the format <strong class="text-info">WWU_Mail: {username, [challenges], difficulty}</strong>.
                                    </p>
                                    <br>
                                    <div class="row">
                                        <div class="col-xl-3 col-md-12">
                                            <form action="export_file.php" method="post">
                                                <input type="hidden" name="exportJSON" value="1">
                                                <!-- <input class="btn btn btn-info" type="submit" value="Download JSON File"> -->
                                                <button class="btn btn btn-info" type="submit">
                                                    Download JSON File
                                                    <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-download mr-1 ml-1 btn-icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z" />
                                                        <path fill-rule="evenodd" d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                        <br><br><br>
                                        <div class="col-xl-3 col-md-12">
                                            <button class="btn btn btn-info" onclick="window.print()">
                                                Download PDF File
                                                <svg width="1.2em" height="1.2em" viewBox="0 0 16 16" class="bi bi-file-earmark-arrow-down mr-1 ml-1 btn-icon" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M4 0h5.5v1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5h1V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2z" />
                                                    <path d="M9.5 3V0L14 4.5h-3A1.5 1.5 0 0 1 9.5 3z" />
                                                    <path fill-rule="evenodd" d="M8 6a.5.5 0 0 1 .5.5v3.793l1.146-1.147a.5.5 0 0 1 .708.708l-2 2a.5.5 0 0 1-.708 0l-2-2a.5.5 0 1 1 .708-.708L7.5 10.293V6.5A.5.5 0 0 1 8 6z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                    <br>
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