<?php

define("DS", DIRECTORY_SEPARATOR); // OS independent dir separator
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . DS); // Web Server root dir

// Locations on app level
define("SRC", ROOT .  ".." . DS . "src" . DS); // Source dir
define("RES", ROOT .  ".." . DS . "resources" . DS); // Resources dir
define("DOC", ROOT .  ".." . DS . "docs" . DS); // Documentation dir
define("LOG", ROOT .  ".." . DS . "docs" . DS); // Log dir
define("BIN", ROOT .  ".." . DS . "docs" . DS); // Command Line Utility dir (currently empty)


// Locations in public/
define("ADMIN_DIR", ROOT . "admin" . DS); // Admin dir
define("SHOP_DIR", ROOT . "shop" . DS); // Shop dir

// Location of assets
define("CSS", ROOT . "assets" . DS . "css" . DS); // CSS
define("IMG", ROOT . "assets" . DS . "img" . DS); // Images
define("JSC", ROOT . "assets" . DS . "js" . DS . "vendor" . DS); // JavaScript


define("LOGIN_PAGE", ROOT . "index.php"); // Login page
define("MAIN_PAGE", ROOT .  "shop" . "main.php"); // Main page
