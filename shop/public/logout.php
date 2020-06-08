<?php

$loginPage = "index.php";

session_start();
session_unset($_SESSION['userName']);
session_destroy();

header("location: " . $loginPage);
