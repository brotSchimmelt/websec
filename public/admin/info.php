<?php
session_start(); // needs to be called first on every page

// load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);
require_once(CONF_DB_SHOP);

// load functions
require(FUNC_BASE);
require(FUNC_ADMIN);
require(FUNC_LOGIN);
require(FUNC_SHOP);
require(FUNC_WEBSEC);
require(ERROR_HANDLING);

// check admin status
if (!is_user_admin()) {
    // redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// variables
$username = $_SESSION['userName'];
$here = basename($_SERVER['PHP_SELF'], ".php"); // Get script name for sidebar highlighting
$numOfStudents = get_num_of_students();
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

    <title>Admin | Dashboard</title>

    <!-- Link to favicon -->
    <link rel="shortcut icon" type="image/png" href="/assets/img/favicon.png">

    <!-- Additional Scripts for iframe -->
    <script>
        // source: https://www.sitepoint.com/community/t/auto-height-iframe-content-script/67843/4
        function resizeIframe(obj) {
            obj.style.height = obj.contentWindow.document.documentElement.scrollHeight + 'px';
        }
    </script>
</head>

<body>

    <?php
    // load navbar and sidebar
    require(HEADER_ADMIN);
    // load error messages, user notifications etc.
    require(MESSAGES);
    ?>


    <!-- HTML Content BEGIN -->
    <div class="container-fluid">
        <div class="row">
            <?php include(SIDEBAR_ADMIN); ?>

            <main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-md-4">

                <iframe src="info_content.php" frameborder="0" width="100%" scrolling="no" onload="resizeIframe(this)" />

            </main>
        </div>
    </div>
    <!-- HTML Content END -->


    <?php
    // load JavaScript
    require_once(JS_BOOTSTRAP); // default Bootstrap JavaScript
    require_once(JS_ADMIN); // custom JavaScript
    ?>
</body>

</html>