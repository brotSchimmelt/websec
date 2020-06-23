<?php
session_start(); // Needs to be called first on every page

// Include default config file
require_once("$_SERVER[DOCUMENT_ROOT]/../config/config.php");


if (isset($_POST['register-submit'])) {
    require(SRC . "registration_script.php");
} else if (isset($_POST['login-submit'])) {
    require(SRC . "login_script.php");
} else {
    header("location: " . LOGIN_PAGE . "?message=Nope");
    exit();
}
