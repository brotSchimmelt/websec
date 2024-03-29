<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);
require_once(CONF_DB_SHOP);

// load functions
require(FUNC_BASE);
require(FUNC_SHOP);
require(FUNC_LOGIN);
require(FUNC_WEBSEC);
require(ERROR_HANDLING);

// check admin status
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
$thisPage = basename(__FILE__);

// check if a reset was requested
if (isset($_POST['simplexss']) && isset($_POST['doit-simplexss'])) {
    reset_reflective_xss_db($username);
    header("location: " . basename(__FILE__) . "?reset=xss1");
} else if (isset($_POST['storedxss']) && isset($_POST['doit-storedxss'])) {
    reset_stored_xss_db($username);
    header("location: " . basename(__FILE__) . "?reset=xss2");
} else if (isset($_POST['sqli']) && isset($_POST['doit-sqli'])) {
    reset_sqli_db($username);
    header("location: " . basename(__FILE__) . "?reset=sqli");
} else if (isset($_POST['csrf']) && isset($_POST['doit-csrf'])) {
    reset_csrf_db($username);
    header("location: " . basename(__FILE__) . "?reset=csrf");
} else if (isset($_POST['all']) && isset($_POST['doit-all'])) {
    reset_all_challenges($username);
    header("location: " . basename(__FILE__) . "?reset=all");
} else if (isset($_POST['comment']) && isset($_POST['doit-comment'])) {
    remove_comment($username);
    header("location: " . basename(__FILE__) . "?reset=comment");
}

// show corresponding success modal for resetting
if (isset($_GET['reset'])) {
    if ($_GET['reset'] == "xss1") {
        $resetReflectiveXSSModal = true;
    } else if ($_GET['reset'] == "xss2") {
        $resetStoredXSSModal = true;
    } else if ($_GET['reset'] == "sqli") {
        $resetSQLiModal = true;
    } else if ($_GET['reset'] == "csrf") {
        $resetCSRFModal = true;
    } else if ($_GET['reset'] == "all") {
        $resetAllModal = true;
    } else if ($_GET['reset'] == "comment") {
        $removeCommentModal = true;
    }
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
    // load navbar
    require(HEADER_SHOP);
    // load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <div class="page-container">

        <h1 class="display-4">Challenge Settings</h1>
        <hr>
        <br>
        <p class="lead">Here you can reset all challenges and your current achievements. If you already passed a challenge and decide to reset it, you have to solve it again in order to get your solution graded.</p>
        <br><br>

        <div class="row mb-5">
            <div class="col-auto mb-5 d-none d-lg-block">
                <div class=" list-group" id="list-tab-settings" role="tablist">
                    <a class="list-group-item list-group-item-action active" id="list-reflective-xss-list" data-toggle="list" href="#list-reflective-xss" role="tab" aria-controls="reflective-xss">Reflective XSS</a>
                    <a class="list-group-item list-group-item-action" id="list-stored-xss-list" data-toggle="list" href="#list-stored-xss" role="tab" aria-controls="stored-xss">Stored XSS</a>
                    <a class="list-group-item list-group-item-action" id="list-comment-list" data-toggle="list" href="#list-comment" role="tab" aria-controls="comment">Product Comment</a>
                    <a class="list-group-item list-group-item-action" id="list-sqli-list" data-toggle="list" href="#list-sqli" role="tab" aria-controls="sqli">SQLi</a>
                    <a class="list-group-item list-group-item-action" id="list-csrf-list" data-toggle="list" href="#list-csrf" role="tab" aria-controls="csrf">CSRF</a>
                    <a class="list-group-item list-group-item-action" id="list-all-list" data-toggle="list" href="#list-all" role="tab" aria-controls="all">All Challenges</a>
                </div>
            </div>
            <div class="col-xl-7 col-lg-7 col-md-auto col-sm-auto col-xs-auto mb-5 d-none d-lg-block">
                <div class="tab-content" id="nav-tabContent">
                    <!-- Reflective XSS -->
                    <div class="tab-pane fade show active" id="list-reflective-xss" role="tabpanel" aria-labelledby="list-home-list">
                        <h3 class="display-5">Reset Reflective XSS Challenge</h3>
                        <br>
                        <p class="lead"><strong class="text-danger">Warning: </strong>This will delete your progress for this challenge and set new cookies.</p>
                        <form action="<?= $thisPage ?>" method="post">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group fl_icon">
                                        <div class="icon">
                                            <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            </svg>
                                        </div>
                                        <input name="username-reflective-xss" id="username-reflective-xss" aria-describedby="username-reflective-xss" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                                    </div>
                                    <input type="hidden" name="doit-simplexss" value="1">
                                    <input type="hidden" name="simplexss" value="1">
                                    <input class="btn btn-danger shadow" type="submit" value="RESET REFLECTIVE XSS CHALLENGE">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Stored XSS -->
                    <div class="tab-pane fade" id="list-stored-xss" role="tabpanel" aria-labelledby="list-profile-list">
                        <h3 class="display-5">Reset Stored XSS Challenge</h3>
                        <br>
                        <p class="lead"><strong class="text-danger">Warning: </strong>This will delete your progress for this challenge and set new cookies.</p>
                        <form action="<?= $thisPage ?>" method="post">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group fl_icon">
                                        <div class="icon">
                                            <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            </svg>
                                        </div>
                                        <input name="username-stored-xss" id="username-stored-xss" aria-describedby="username-stored-xss" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                                    </div>
                                    <input type="hidden" name="doit-storedxss" value="1">
                                    <input type="hidden" name="storedxss" value="1">
                                    <input class="btn btn-danger shadow" type="submit" value="RESET STORED XSS CHALLENGE">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Product Comment -->
                    <div class="tab-pane fade" id="list-comment" role="tabpanel" aria-labelledby="list-profile-list">
                        <h3 class="display-5">Remove Comment from Product Page</h3>
                        <br>
                        <p class="lead"><strong class="text-wwu-green">Note: </strong>This will remove your current comment from the product page. This action has no effect on your progress.</p>
                        <form action="<?= $thisPage ?>" method="post">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group fl_icon">
                                        <div class="icon">
                                            <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            </svg>
                                        </div>
                                        <input name="username-comment" id="username-comment" aria-describedby="username-comment" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                                    </div>
                                    <input type="hidden" name="doit-comment" value="1">
                                    <input type="hidden" name="comment" value="1">
                                    <input class="btn btn-wwu-cart shadow" type="submit" value="DELETE PRODUCT COMMENT">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- SQLi -->
                    <div class="tab-pane fade" id="list-sqli" role="tabpanel" aria-labelledby="list-messages-list">
                        <h3 class="display-5">Reset SQLi Challenge</h3>
                        <br>
                        <p class="lead"><strong class="text-danger">Warning:</strong> This will completely reset your fake challenge user databse. The changes you might made will not persist!</p>

                        <form action="<?= $thisPage ?>" method="post">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group fl_icon">
                                        <div class="icon">
                                            <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            </svg>
                                        </div>
                                        <input name="username-sqli" id="username-sqli" aria-describedby="username-sqli" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                                    </div>
                                    <input type="hidden" name="doit-sqli" value="1">
                                    <input type="hidden" name="sqli" value="1">
                                    <input class="btn btn-danger shadow" type="submit" value="RESET SQLi DATABASE">
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- CSRF -->
                    <div class="tab-pane fade" id="list-csrf" role="tabpanel" aria-labelledby="list-settings-list">
                        <h3 class="display-5">Reset Contact Form Challenge</h3>
                        <br>
                        <p class="lead"><strong class="text-danger">Warning:</strong> This will delete every post you have made to the contact form!</p>
                        <form action="<?= $thisPage ?>" method="post">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group fl_icon">
                                        <div class="icon">
                                            <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            </svg>
                                        </div>
                                        <input name="username-csrf" id="username-csrf" aria-describedby="username-csrf" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                                    </div>
                                    <input type="hidden" name="doit-csrf" value="1">
                                    <input type="hidden" name="csrf" value="1">
                                    <input class="btn btn-danger shadow" type="submit" value="RESET SUPPORT CONTACT">
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- All Challenge Settings -->
                    <div class="tab-pane fade" id="list-all" role="tabpanel" aria-labelledby="list-settings-list">
                        <h3 class="display-5">Reset all Challenges</h3>
                        <br>
                        <p class="lead"><strong class="text-danger">Warning:</strong> This will delete <b>all</b> your achievements!</p>
                        <form action="<?= $thisPage ?>" method="post">
                            <div class="row">
                                <div class="col-auto">
                                    <div class="form-group fl_icon">
                                        <div class="icon">
                                            <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                            </svg>
                                        </div>
                                        <input name="username-all" id="username-all" aria-describedby="username-all" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                                    </div>
                                    <input type="hidden" name="doit-all" value="1">
                                    <input type="hidden" name="all" value="1">
                                    <input class="btn btn-danger shadow" type="submit" value="RESET ALL CHALLENGES">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="col d-lg-none">

                <!-- Reflective XSS -->
                <h3 class="display-5">Reset Reflective XSS Challenge</h3>
                <br>
                <p class="lead"><strong class="text-danger">Warning: </strong>This will delete your progress for this challenge and set new cookies.</p>
                <form action="<?= $thisPage ?>" method="post">
                    <div class="row">
                        <div class="col-auto">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username-reflective-xss" id="username-reflective-xss" aria-describedby="username-reflective-xss" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                            <input type="hidden" name="doit-simplexss" value="1">
                            <input type="hidden" name="simplexss" value="1">
                            <input class="btn btn-danger shadow" type="submit" value="RESET REFLECTIVE XSS CHALLENGE">
                        </div>
                    </div>
                </form>
                <br><br><br>

                <!-- Stored XSS -->
                <h3 class="display-5">Reset Stored XSS Challenge</h3>
                <br>
                <p class="lead"><strong class="text-danger">Warning: </strong>This will delete your progress for this challenge and set new cookies.</p>
                <form action="<?= $thisPage ?>" method="post">
                    <div class="row">
                        <div class="col-auto">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username-stored-xss" id="username-stored-xss" aria-describedby="username-stored-xss" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                            <input type="hidden" name="doit-storedxss" value="1">
                            <input type="hidden" name="storedxss" value="1">
                            <input class="btn btn-danger shadow" type="submit" value="RESET STORED XSS CHALLENGE">
                        </div>
                    </div>
                </form>
                <br><br><br>

                <!-- Product Comment-->
                <h3 class="display-5">Remove Comment from Product Page</h3>
                <br>
                <p class="lead"><strong class="text-wwu-green">Note: </strong>This will remove your current comment from the product page. This action has no effect on your progress.</p>
                <form action="<?= $thisPage ?>" method="post">
                    <div class="row">
                        <div class="col-auto">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username-comment" id="username-comment" aria-describedby="username-comment" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                            <input type="hidden" name="doit-comment" value="1">
                            <input type="hidden" name="comment" value="1">
                            <input class="btn btn-wwu-cart shadow" type="submit" value="DELETE PRODUCT COMMENT">
                        </div>
                    </div>
                </form>
                <br><br><br>

                <!-- SQLi -->
                <h3 class="display-5">Reset SQLi Challenge</h3>
                <br>
                <p class="lead"><strong class="text-danger">Warning:</strong> This will completely reset your fake challenge user databse. The changes you might made will not persist!</p>

                <form action="<?= $thisPage ?>" method="post">
                    <div class="row">
                        <div class="col-auto">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username-sqli" id="username-sqli" aria-describedby="username-sqli" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                            <input type="hidden" name="doit-sqli" value="1">
                            <input type="hidden" name="sqli" value="1">
                            <input class="btn btn-danger shadow" type="submit" value="RESET SQLi DATABASE">
                        </div>
                    </div>
                </form>
                <br><br><br>

                <!-- CSRF -->
                <h3 class="display-5">Reset Contact Form Challenge</h3>
                <br>
                <p class="lead"><strong class="text-danger">Warning:</strong> This will delete every post you have made to the contact form!</p>
                <form action="<?= $thisPage ?>" method="post">
                    <div class="row">
                        <div class="col-auto">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username-csrf" id="username-csrf" aria-describedby="username-csrf" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                            <input type="hidden" name="doit-csrf" value="1">
                            <input type="hidden" name="csrf" value="1">
                            <input class="btn btn-danger shadow" type="submit" value="RESET SUPPORT CONTACT">
                        </div>
                    </div>
                </form>
                <br><br><br>

                <!-- All Challenges -->
                <h3 class="display-5">Reset all Challenges</h3>
                <br>
                <p class="lead"><strong class="text-danger">Warning:</strong> This will delete <b>all</b> your achievements!</p>
                <form action="<?= $thisPage ?>" method="post">
                    <div class="row">
                        <div class="col-auto">
                            <div class="form-group fl_icon">
                                <div class="icon">
                                    <svg width="1.7em" height="1.7em" viewBox="0 0 16 16" class="bi bi-person-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z" />
                                    </svg>
                                </div>
                                <input name="username-all" id="username-all" aria-describedby="username-all" type="text" class="form-input form-disabled-input" value="<?= $_SESSION['userName'] ?>" disabled>
                            </div>
                            <input type="hidden" name="doit-all" value="1">
                            <input type="hidden" name="all" value="1">
                            <input class="btn btn-danger shadow" type="submit" value="RESET ALL CHALLENGES">
                        </div>
                    </div>
                </form>
                <br>

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