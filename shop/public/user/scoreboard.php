<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP);
require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);

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

// Load POST or GET variables and sanitize input BELOW this comment
$username = $_SESSION['userName'];

// Challenge variables
$solvedXSS = check_xss_challenge($username);
$solvedSQLi = check_sqli_challenge($username);
$solvedCrosspost = check_crosspost_challenge($username);
$solvedCrosspostDoubleCheck = check_crosspost_challenge_double($username);

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
    <link rel="stylesheet" href="/assets/css/shop.css">

    <title>Websec | Scoreboard</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <div class="con-search">
        <h1 class="display-4">Your Scorecard</h1>
        <br>
        <p>This scorecard is just an <em>indicator</em> of your challenges' status!<br>
            The final judgement whether or not a challenge was solved correctly is done by your lecturer.</p>
        <br>
        <table class="table table-striped shadow">
            <thead class="my-head">
                <tr>
                    <th scope="col">Challenge</th>
                    <th scope="col">Current Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>XSS (combined)</td>
                    <td> <?php if ($solvedXSS) {
                                echo "Solved";
                            } else {
                                echo "Still Open";
                            } ?>
                    </td>
                </tr>
                <tr>
                    <td>SQLi</td>
                    <td> <?php if ($solvedSQLi) {
                                echo "Solved";
                            } else {
                                echo "Still Open";
                            } ?>
                    </td>
                </tr>
                <tr>
                    <td>Support Form Hack</td>
                    <td> <?php if ($solvedCrosspost && $solvedCrosspostDoubleCheck) {
                                echo "Solved";
                            } elseif ($solvedCrosspost || $solvedCrosspostDoubleCheck) {
                                echo "probably solved";
                            } else {
                                echo "Still Open";
                            } ?>
                    </td>
                </tr>
            </tbody>
        </table>
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