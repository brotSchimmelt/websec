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

// process setting requests
if (isset($_POST['update-login'])) {
    if ($_POST['loginRadios'] == "enable") {
        set_login_status(true);
    } else {
        set_login_status(false);
    }
}
if (isset($_POST['update-registration'])) {
    if ($_POST['registrationRadios'] == "enable") {
        set_registration_status(true);
    } else {
        set_registration_status(false);
    }
}
if (isset($_POST['update-difficulty'])) {
    if ($_POST['diffRadios'] == "normal") {
        set_global_difficulty("normal");
    } else {
        set_global_difficulty("hard");
    }
}
if (isset($_POST['update-usernames'])) {
    $nameList = make_clean_array($_POST['input-usernames']);
    set_blocked_usernames($nameList);
}
if (isset($_POST['update-domains'])) {
    $domainList = make_clean_array($_POST['input-domains']);
    set_allowed_domains($domainList);
}
if (isset($_POST['update-badge'])) {
    $learnweb = (filter_var($_POST['input-learnweb'], FILTER_VALIDATE_URL)) ? $_POST['input-learnweb'] : "https://www.uni-muenster.de/LearnWeb/learnweb2/";
    $reflectiveXSS = (filter_var($_POST['input-reflective-xss'], FILTER_VALIDATE_URL)) ? $_POST['input-reflective-xss'] : "https://en.wikipedia.org/wiki/Cross-site_scripting#Non-persistent_(reflected)";
    $storedXSS = (filter_var($_POST['input-stored-xss'], FILTER_VALIDATE_URL)) ? $_POST['input-stored-xss'] : "https://en.wikipedia.org/wiki/Cross-site_scripting#Persistent_(or_stored)";
    $sqli = (filter_var($_POST['input-sqli'], FILTER_VALIDATE_URL)) ? $_POST['input-sqli'] : "https://en.wikipedia.org/wiki/SQL_injection";
    $csrf = (filter_var($_POST['input-csrf'], FILTER_VALIDATE_URL)) ? $_POST['input-csrf'] : "https://en.wikipedia.org/wiki/Cross-site_request_forgery";
    set_setting("learnweb", "link", $learnweb);
    set_badge_link("reflective_xss", $reflectiveXSS);
    set_badge_link("stored_xss", $storedXSS);
    set_badge_link("sqli", $sqli);
    set_badge_link("csrf", $csrf);
}

// Other php variables
$here = basename($_SERVER['PHP_SELF'], ".php"); // Get script name
$checkDifficulty = (get_global_difficulty() == "normal") ? true : false;
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
                                    <p>
                                        Here you can disable or enable the user login and registration system.
                                        The users will be redirected to an error page with an appropriated message.
                                    </p>
                                    <div class="row">
                                        <div class="col">
                                            <form action="shop_settings.php" method="post">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="loginRadios" id="enLoginRadio" value="enable" <?= is_login_enabled() ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="enLoginRadio">
                                                        Enable Login
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="loginRadios" id="disLoginRadio" value="disable" <?= is_login_enabled() ? "" : "checked" ?>>
                                                    <label class="form-check-label" for="disLoginRadio">
                                                        Disable Login
                                                    </label>
                                                </div>
                                                <br>
                                                <input type="hidden" name="update-login" value="1">
                                                <button type="submit" class="btn btn-sm btn-info">Update</button>
                                            </form>
                                        </div>
                                        <div class="col">
                                            <form action="shop_settings.php" method="post">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="registrationRadios" id="enRegistrationRadio" value="enable" <?= is_registration_enabled() ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="enRegistrationRadio">
                                                        Enable Registration
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="registrationRadios" id="disRegistrationRadio" value="disable" <?= is_registration_enabled() ? "" : "checked" ?>>
                                                    <label class="form-check-label" for="disRegistrationRadio">
                                                        Disable Registration
                                                    </label>
                                                </div>
                                                <br>
                                                <input type="hidden" name="update-registration" value="1">
                                                <button type="submit" class="btn btn-sm btn-info">Update</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Level of Difficulty</h5>
                                </div>
                                <div class="card-body">
                                    <p>
                                        Here you can set the global difficulty for the challenges.<br>
                                    </p>
                                    <div class="row">
                                        <div class="col"></div>
                                        <div class="col">
                                            <form action="shop_settings.php" method="post">
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="diffRadios" id="normalRadio" value="normal" <?= $checkDifficulty ? "checked" : "" ?>>
                                                    <label class="form-check-label" for="normalRadio">
                                                        Normal Difficulty
                                                    </label>
                                                </div>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="diffRadios" id="hardRadio" value="hard" <?= $checkDifficulty ? "" : "checked" ?>>
                                                    <label class="form-check-label" for="hardRadio">
                                                        Hard Difficulty
                                                    </label>
                                                </div>
                                                <br>
                                                <input type="hidden" name="update-difficulty" value="1">
                                                <button type="submit" class="btn btn-sm btn-info" title="Attention!" data-content="You should reset the hole shop system when you change the difficulty in order to avoid unexpected behaviour for the users. You can do so by running the 'docker-compose down -v' command followed by 'docker-compose up -d'." data-toggle="popover" data-trigger="hover" data-placement="top">
                                                    Update
                                                </button>
                                            </form>
                                        </div>
                                        <div class="col"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col">
                            <div class="card shadow-sm">
                                <div class="card-header">
                                    <h5 class="display-5">Blocked Usernames and Allowed Mail Addresses</h5>
                                </div>
                                <div class="card-body">
                                    <p>
                                        Here are all usernames that are blocked during registration. You can add new ones by appending them in the field below. The names will be processed case insensitive.
                                    </p>
                                    <div class="text-center">
                                        <form action="shop_settings.php" method="post">
                                            <input type="text" class="form-control" name="input-usernames" value="<?= implode(', ', get_blocked_usernames()) ?>">
                                            <input type="hidden" name="update-usernames" value="1">
                                            <br>
                                            <button class="btn btn-info" type="submit">Update</button>
                                        </form>
                                    </div>
                                    <br><br>
                                    <p>
                                        This list contains all domains that are allowed for the mail addresses during registration. You can add new ones by appending them in the field below.
                                    </p>
                                    <div class="text-center">
                                        <form action="shop_settings.php" method="post">
                                            <input type="text" class="form-control" name="input-domains" value="<?= implode(', ', get_allowed_domains()) ?>">
                                            <input type="hidden" name="update-domains" value="1">
                                            <br>
                                            <button class="btn btn-info" type="submit">Update</button>
                                        </form>
                                    </div>
                                    <br>
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
                                        Here you can set the links for the challenge badges and the current learnweb course. By default the links are set to the corresponding wikipedia articles or the learnweb landing page.
                                        Please remember to put <b>http</b> or <b>https</b> in front of the link. Otherwise it is not registered as a valid URL. I was too lazy to program another string filter, sorry :/
                                    </p>
                                    <br>
                                    <form action="shop_settings.php" method="post">
                                        <label for="input-learnweb"><strong>Link Current Learnweb Course:</strong></label>
                                        <input type="text" class="form-control" name="input-learnweb" value="<?= get_setting("learnweb", "link") ?>"><br>
                                        <label for="input-reflective-xss"><strong>Link Reflective XSS:</strong></label>
                                        <input type="text" class="form-control" name="input-reflective-xss" value="<?= get_challenge_badge_link("reflective_xss") ?>"><br>
                                        <label for="input-stored-xss"><strong>Link Stored XSS:</strong></label>
                                        <input type="text" class="form-control" name="input-stored-xss" value="<?= get_challenge_badge_link("stored_xss") ?>"><br>
                                        <label for="input-sqli"><strong>Link SQLi:</strong></label>
                                        <input type="text" class="form-control" name="input-sqli" value="<?= get_challenge_badge_link("sqli") ?>"><br>
                                        <label for="input-csrf"><strong>Link CSRF:</strong></label>
                                        <input type="text" class="form-control" name="input-csrf" value="<?= get_challenge_badge_link("csrf") ?>">
                                        <br>
                                        <input type="hidden" name="update-badge" value="1">
                                        <div class="text-center">
                                            <button class="btn btn-info" type="submit">Update</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <?php
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    require_once(JS_ADMIN); // Custom JavaScript
    ?>
    <script>
        $(document).ready(function() {
            $('[data-toggle="popover"]').popover();
        });
    </script>
    <!-- HTML Content END -->
</body>

</html>