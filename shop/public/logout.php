<?php
session_start();

$loginPage = "index.php";

session_unset($_SESSION['userName']);
session_destroy();

header("location: " . $loginPage);
