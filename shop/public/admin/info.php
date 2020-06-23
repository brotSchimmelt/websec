<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

// Load custom libraries
require(FUNC_BASE);
require(FUNC_ADMIN);


if (is_user_admin()) {
    phpinfo();
} else {
    header("location: " . MAIN_PAGE);
    exit();
}
