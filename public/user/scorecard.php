<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_SHOP);
require_once(CONF_DB_LOGIN);

// load functions
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);
require(ERROR_HANDLING);

// check login status
if (!is_user_logged_in()) {
    // redirect to login page
    header("location: " . LOGIN_PAGE . "?login=false");
    exit();
}

// check if user is unlocked
if (!is_user_unlocked()) {
    header("location: " . MAIN_PAGE);
    exit();
}

// variables
$username = $_SESSION['userName'];
$difficulty = get_global_difficulty();

// get challenge variables
$solvedXSS = lookup_challenge_status("reflective_xss", $username);
$solvedStoredXSS = lookup_challenge_status("stored_xss", $username);
$solvedSQLi = lookup_challenge_status("sqli", $username);
$solvedCrosspost = lookup_challenge_status("csrf", $username);
$solvedCrosspostDoubleCheck = lookup_challenge_status("csrf_referrer", $username);

// check if all challenges were solved
$allChallengesSolved = ($solvedXSS && $solvedStoredXSS && $solvedSQLi
    && $solvedCrosspost && $solvedCrosspostDoubleCheck) ? true : false;


// challenge status for the user
$echoGreen = '<span style="color:green;">Solved</span>';
$echoRed = '<span style="color:red;">Still Open</span>';
$echoOrange = '<span style="color:orange;">Probably Solved</span>';
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

    <title>Websec | Scorecard</title>
</head>

<body>

    <?php
    // load navbar
    require(HEADER_SHOP);
    // load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <div class="page-container">
        <h1 class="display-4">Your Scorecard</h1>
        <hr>
        <br>
        <p class="lead">This scorecard is just an <em>indicator</em> of your challenges' status!<br>
            The final judgement whether or not a challenge was solved correctly is done by your lecturer.</p>
        <br>

        <?= $allChallengesSolved ? $alertscorecardAllSolved : "" ?>

        <table class="table table-striped shadow">
            <thead class="my-head">
                <tr>
                    <th scope="col">Challenge</th>
                    <th scope="col">Current Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><a class="text-muted" href="/shop/overview.php">Reflective XSS</a></td>
                    <td>
                        <?= $solvedXSS ? $echoGreen : $echoRed ?>
                    </td>
                </tr>
                <tr>
                    <td><a class="text-muted" href="/shop/product.php">Stored XSS</a></td>
                    <td>
                        <?= $solvedStoredXSS ? $echoGreen : $echoRed ?>
                    </td>
                </tr>
                <tr>
                    <td><a class="text-muted" href="/shop/friends.php">SQLi</a></td>
                    <td>
                        <?= $solvedSQLi ? $echoGreen : $echoRed ?>
                    </td>
                </tr>
                <tr>
                    <td><a class="text-muted" href="/shop/contact.php">CSRF</a></td>
                    <td> <?php
                            if ($solvedCrosspost && $solvedCrosspostDoubleCheck) {
                                echo $echoGreen;
                            } elseif ($solvedCrosspost || $solvedCrosspostDoubleCheck) {
                                echo $echoOrange;
                            } else {
                                echo $echoRed;
                            }
                            ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <div class="text-center">
            <small>
                Challenge Difficulty Level:
                <span title="Level of Difficulty" data-content="Only the lecturer can set the difficulty level for the challenges." data-toggle="popover" data-trigger="hover" data-placement="bottom">
                    <strong><?= $difficulty ?></strong>
                </span>
            </small>
        </div>
    </div>
    <!-- HTML Content END -->


    <?php
    // load shop footer
    require(FOOTER_SHOP);
    // load JavaScript
    require_once(JS_BOOTSTRAP); // default Bootstrap JavaScript
    require_once(JS_SHOP); // custom JavaScript
    ?>
</body>

</html>