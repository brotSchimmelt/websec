<?php
session_start(); // Needs to be called first on every page

// Load config files
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");
// Load custom libraries
require(FUNC_BASE);

// check if user is admin before showing info.php
if (is_user_admin()) {
    phpinfo();
} else {
    exit();
}
