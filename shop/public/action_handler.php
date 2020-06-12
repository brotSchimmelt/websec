<?php
session_start();

require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");


if (isset($_POST['register-submit'])) {
    require(SRC . "registration_script.php");
} else if (isset($_POST['login-submit'])) {
    require(SRC . "login_script.php");
} else {
    header("location: " . "index.php");
}
