<?php

define("DS", DIRECTORY_SEPARATOR); // OS independent dir separator
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . DS); // Web Server root dir

// Locations on app level
define("SRC", ROOT .  ".." . DS . "src" . DS); // Source dir
define("RES", ROOT .  ".." . DS . "resources" . DS); // Resources dir
define("DOC", ROOT .  ".." . DS . "docs" . DS); // Documentation dir
define("LOG", ROOT .  ".." . DS . "docs" . DS); // Log dir
define("BIN", ROOT .  ".." . DS . "docs" . DS); // Command Line Utility dir (currently empty)
define("CON", ROOT .  ".." . DS . "config" . DS); // Log dir

// Subfolders in src/
define("INCL", SRC . "includes" . DS);  // Includes dir

// Common includes
define("HEADER_SHOP", INCL . "shop_header.php"); // Header location
define("FOOTER_SHOP", INCL . "shop_footer.php");  // Footer location
define("HEADER_DASH", INCL . "dashboard_header.php"); // Dashboard header location
define("SIDEBAR_DASH", INCL . "dashboard_sidebar.php"); // Dashboard sidebar location
define("JS_DASHBOARD", INCL . "dashboard_js.php"); // JavaScript for the admin section
define("JS_SHOP", INCL . "shop_js.php"); // JavaScript for the shop section

// Functions
define("FUNC_BASE", INCL . "basic_functions.php"); // Basic functions for the site
define("FUNC_LOGIN", INCL . "login_functions.php"); // Functions for login, registration etc.

// Relative path to frequent redirect destinations
define("MAIN_PAGE", "shop" . DS . "main.php");
define("LOGIN_PAGE", "index.php");
