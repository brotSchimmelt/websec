<?php
session_start();

// includes
require("$_SERVER[DOCUMENT_ROOT]/../config/config.php");

session_unset($_SESSION['userName']);
session_destroy();

header("location: " . LOGIN_PAGE);
