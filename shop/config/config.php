<?php

define("DS", DIRECTORY_SEPARATOR); // OS independent dir separator
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . DS); // Web Server root dir

// Locations on app level
define("SRC", ROOT .  ".." . DS . "src" . DS); // Source dir
define("RES", ROOT .  ".." . DS . "resources" . DS); // Resources dir
define("DOC", ROOT .  ".." . DS . "docs" . DS); // Documentation dir
define("LOG", ROOT .  ".." . DS . "docs" . DS); // Log dir
define("BIN", ROOT .  ".." . DS . "docs" . DS); // Command Line Utility dir (currently empty)

// Relative path to important pages
define("MAIN_PAGE", "shop" . DS . "main.php");
define("LOGIN_PAGE", "index.php");
