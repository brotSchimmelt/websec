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
                                                <button type="submit" class="btn btn-sm btn-info" title="Attention!" data-content="You should reset the hole shop system in order to avoid unexpected behaviour for the users. You can do so by running the 'docker-compose down -v' command followed by 'docker-compose up -d'." data-toggle="popover" data-trigger="hover" data-placement="top">
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