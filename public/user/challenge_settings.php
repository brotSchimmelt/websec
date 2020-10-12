<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);
require_once(CONF_DB_SHOP);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check admin status
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

if (isset($_POST['simplexss']) && isset($_POST['doit-simplexss'])) {
    $resetReflectiveXSSModal = reset_reflective_xss_db($username);
}
if (isset($_POST['storedxss']) && isset($_POST['doit-storedxss'])) {
    $resetStoredXSSModal = reset_stored_xss_db($username);
}
if (isset($_POST['sqli']) && isset($_POST['doit-sqli'])) {
    $resetSQLiModal = reset_sqli_db($username);
}
if (isset($_POST['csrf']) && isset($_POST['doit-csrf'])) {
    $resetCSRFModal = reset_csrf_db($username);
}
if (isset($_POST['all']) && isset($_POST['doit-all'])) {
    $resetAllModal = reset_all_challenges($username);
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

    <title>Websec | Challenge Settings</title>
</head>

<body>

    <?php
    // Load navbar
    require(HEADER_SHOP);
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <div class="page-container">

        <h1 class="display-4">Challenge Settings</h1>
        <br>
        <p>Lorem, ipsum dolor sit amet consectetur adipisicing elit. Dolores sapiente sit animi obcaecati aspernatur rerum distinctio voluptas a voluptate ipsa atque, deleniti quibusdam est nesciunt.</p>
        <br><br>

        <div class="flex-row mb-5" id="settings-row">
            <div class="list-group list-group-horizontal" id="list-tab-settings" role="tablist">
                <a class="list-group-item list-group-item-action active" id="list-reflective-xss-list" data-toggle="list" href="#list-reflective-xss" role="tab" aria-controls="reflective-xss">Reflective XSS</a>
                <a class="list-group-item list-group-item-action" id="list-stored-xss-list" data-toggle="list" href="#list-stored-xss" role="tab" aria-controls="stored-xss">Stored XSS</a>
                <a class="list-group-item list-group-item-action" id="list-sqli-list" data-toggle="list" href="#list-sqli" role="tab" aria-controls="sqli">SQLi</a>
                <a class="list-group-item list-group-item-action" id="list-csrf-list" data-toggle="list" href="#list-csrf" role="tab" aria-controls="csrf">CSRF</a>
                <a class="list-group-item list-group-item-action" id="list-all-list" data-toggle="list" href="#list-all" role="tab" aria-controls="all">All Challenges</a>
            </div>
        </div>
        <div class="flex-row">
            <div class="tab-content" id="nav-tabContent">
                <!-- Reflective XSS -->
                <div class="tab-pane fade show active" id="list-reflective-xss" role="tabpanel" aria-labelledby="list-home-list">
                    <h3 class="display-5">RESET REFLECTIVE XSS CHALLENGE</h3>
                    <br>
                    <p><strong class="text-danger">Warning: </strong>This will delete your progress for this challenge and set new cookies.</p>
                    <form action="challenge_settings.php" method="post">
                        <div class="form-group">
                            <label for="username-reflective-xss"><b>Your Username:</b></label>
                            <input type="text" name="username-reflective-xss" id="username-reflective-xss" class="form-control setting-form" aria-describedby="username-reflective-xss" value="<?= $_SESSION['userName'] ?>" disabled>
                        </div>
                        <input type="hidden" name="doit-simplexss" value="1">
                        <input type="hidden" name="simplexss" value="1">
                        <input class="btn btn-danger" type="submit" value="RESET REFLECTIVE XSS CHALLENGE">
                    </form>
                </div>
                <!-- Stored XSS -->
                <div class="tab-pane fade" id="list-stored-xss" role="tabpanel" aria-labelledby="list-profile-list">
                    <h3 class="display-5">RESET STORED XSS CHALLENGE</h3>
                    <br>
                    <p>This will <strong class="text-danger">delete all your achievements</strong>!</p>
                    <form action="challenge_settings.php" method="post">
                        <div class="form-group">
                            <label for="username-stored-xss"><b>Your Username:</b></label>
                            <input type="text" name="username-stored-xss" id="username-stored-xss" class="form-control setting-form" aria-describedby="username-stored-xss" value="<?= $_SESSION['userName'] ?>" disabled>
                        </div>
                        <input type="hidden" name="doit-storedxss" value="1">
                        <input type="hidden" name="storedxss" value="1">
                        <input class="btn btn-danger" type="submit" value="RESET STORED XSS CHALLENGE">
                    </form>
                </div>
                <!-- SQLi -->
                <div class="tab-pane fade" id="list-sqli" role="tabpanel" aria-labelledby="list-messages-list">
                    <h3 class="display-5">RESET SQLi CHALLENGE</h3>
                    <br>
                    <p>This will <strong class="text-danger">delete all your achievements</strong>!</p>
                    <form action="challenge_settings.php" method="post">
                        <div class="form-group">
                            <label for="username-sqli"><b>Your Username:</b></label>
                            <input type="text" name="username-sqli" id="username-sqli" class="form-control setting-form" aria-describedby="username-sqli" value="<?= $_SESSION['userName'] ?>" disabled>
                        </div>
                        <input type="hidden" name="doit-sqli" value="1">
                        <input type="hidden" name="sqli" value="1">
                        <input class="btn btn-danger" type="submit" value="RESET SQL DATABASE">
                    </form>
                </div>
                <!-- CSRF -->
                <div class="tab-pane fade" id="list-csrf" role="tabpanel" aria-labelledby="list-settings-list">
                    <h3 class="display-5">RESET CSRF CHALLENGE</h3>
                    <br>
                    <p>This will <strong class="text-danger">delete all your achievements</strong>!</p>
                    <form action="challenge_settings.php" method="post">
                        <div class="form-group">
                            <label for="username-csrf"><b>Your Username:</b></label>
                            <input type="text" name="username-csrf" id="username-csrf" class="form-control setting-form" aria-describedby="username-csrf" value="<?= $_SESSION['userName'] ?>" disabled>
                        </div>
                        <input type="hidden" name="doit-csrf" value="1">
                        <input type="hidden" name="csrf" value="1">
                        <input class="btn btn-danger" type="submit" value="RESET SUPPORT CONTACT">
                    </form>
                </div>
                <!-- All Challenge Settings -->
                <div class="tab-pane fade" id="list-all" role="tabpanel" aria-labelledby="list-settings-list">
                    <h3 class="display-5">RESET ALL CHALLENGES</h3>
                    <br>
                    <p>This will <strong class="text-danger">delete all your achievements</strong>!</p>
                    <form action="challenge_settings.php" method="post">
                        <div class="form-group">
                            <label for="username-all"><b>Your Username:</b></label>
                            <input type="text" name="username-all" id="username-all" class="form-control setting-form" aria-describedby="username-all" value="<?= $_SESSION['userName'] ?>" disabled>
                        </div>
                        <input type="hidden" name="doit-all" value="1">
                        <input type="hidden" name="all" value="1">
                        <input class="btn btn-danger" type="submit" value="RESET ALL CHALLENGES">
                    </form>
                </div>
            </div>
        </div>
        <br><br>
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