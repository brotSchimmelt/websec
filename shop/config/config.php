<?php

define("DS", DIRECTORY_SEPARATOR); // OS independent dir separator
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . DS); // Web Server root dir

// Locations on app level
define("SRC", ROOT .  ".." . DS . "src" . DS); // Source dir
define("RES", ROOT .  ".." . DS . "resources" . DS); // Resources dir
define("DOC", ROOT .  ".." . DS . "docs" . DS); // Documentation dir
define("LOG", ROOT .  ".." . DS . "docs" . DS); // Log dir
define("BIN", ROOT .  ".." . DS . "docs" . DS); // Command Line Utility dir (currently empty)

define("CART", ROOT .  ".." . DS . "src" . DS . "cart" . DS); // Cart dir
define("DASH", ROOT .  ".." . DS . "src" . DS . "dashboard" . DS); // Dashboard dir
define("PROD", ROOT .  ".." . DS . "src" . DS . "product" . DS); // Product dir
define("INCL", ROOT .  ".." . DS . "src" . DS . "includes" . DS);  // Includes dir

define("HEADER", INCL . "header.php"); // Header location
define("FOOTER", INCL . "footer.php");  // Footer location
define("HEADER_DASH", INCL . "dashboard_header.php"); // Dashboard header location
define("SIDEBAR", INCL . "sidebar.php"); // Dashboard sidebar location

// Relative path to frequent redirect destinations
define("MAIN_PAGE", "shop" . DS . "main.php");
define("LOGIN_PAGE", "index.php");
