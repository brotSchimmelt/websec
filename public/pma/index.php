<?php
session_start(); // Needs to be called first on every page

// Load dependencies
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
require(FUNC_BASE); // Basic functions
require(ERROR_HANDLING); // Error handling

if (is_user_admin()) {
    header("location: " . PMA);
    exit();
} else if (isset($_GET['token']) && $_GET['token'] == "123") {
    header("location: " . PMA);
    exit();
} else {
    header("location: " . "/error.php?error=404");
    exit();
}
