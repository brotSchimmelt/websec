<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require_once(CONF_DB_LOGIN);

// Load custom libraries
require(FUNC_BASE);
require(FUNC_LOGIN);

// Load error handling and user messages
require(ERROR_HANDLING);

// Check login status
if (is_user_logged_in()) {
    // Redirect to shop main page
    header("location: " . MAIN_PAGE);
    exit();
}

// Load POST or GET variables and sanitize input BELOW this comment

?>
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="assets/css/bootstrap.css">

    <!-- Custom CSS to overwrite bootstrap.css -->
    <link rel="stylesheet" href="assets/css/login.css">

    <title>[DUMMY TITLE]</title>
</head>

<body>
    <?php
    // Load error messages, user notifications etc.
    require(MESSAGES);
    ?>

    <!-- HTML content BEGIN -->
    <!-- HTML content END -->

    <footer></footer>

    <?php
    // Load JavaScript
    require_once(JS_BOOTSTRAP); // Default Bootstrap JavaScript
    ?>
</body>

</html>