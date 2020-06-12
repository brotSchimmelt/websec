<?php
session_start();


if (isset($_POST['register-submit'])) {
    require("$_SERVER[DOCUMENT_ROOT]/../src/registration_script.php");
} else if (isset($_POST['login-submit'])) {
    require("$_SERVER[DOCUMENT_ROOT]/../src/login_script.php");
} else {
    // TODO: add redirect to login page!
    echo "<b>Nope.</b>";
}
