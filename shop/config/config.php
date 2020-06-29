<?php

define("DS", DIRECTORY_SEPARATOR); // OS independent dir separator
define("ROOT", $_SERVER['DOCUMENT_ROOT'] . DS); // Web Server root dir

// Locations on app level
define("SRC", ROOT .  ".." . DS . "src" . DS); // Source dir
define("RES", ROOT .  ".." . DS . "resources" . DS); // Resources dir
define("DOC", ROOT .  ".." . DS . "docs" . DS); // Documentation dir
define("LOG", ROOT .  ".." . DS . "docs" . DS); // Log dir
define("BIN", ROOT .  ".." . DS . "docs" . DS); // Command Line Utility dir (currently empty)
define("CON", ROOT .  ".." . DS . "config" . DS); // Configuration dir
define("DAT", ROOT .  ".." . DS . "data" . DS); // Database dir for the sql injection challenge

// Database configurations
define("CONF_DB_LOGIN", CON . "db_login.php"); // Login database credentials
define("CONF_DB_SHOP", CON . "db_shop.php"); // Shop database credentials

// Subfolders in src/
define("INCL", SRC . "includes" . DS);  // Includes dir

// Common includes
define("HEADER_SHOP", INCL . "shop_header.php"); // Header location
define("FOOTER_SHOP", INCL . "shop_footer.php");  // Footer location
define("JS_SHOP", INCL . "shop_js.php"); // JavaScript for the shop section
define("HEADER_ADMIN", INCL . "admin_header.php"); // Admin header location
define("SIDEBAR_ADMIN", INCL . "admin_sidebar.php"); // Admin sidebar location
define("JS_ADMIN", INCL . "admin_js.php"); // JavaScript for the admin section
define("JS_BOOTSTRAP", INCL . "bootstrap_js.php"); // Default JavaScript for bootstrap

// Functions
define("FUNC_BASE", SRC . "basic_functions.php"); // Basic functions for the site
define("FUNC_LOGIN", SRC . "login_functions.php"); // Functions for login, registration etc.
define("FUNC_ADMIN", SRC . "admin_functions.php"); // Functions for user management etc.
define("FUNC_SHOP", SRC . "shop_functions.php"); // Functions for products, cart etc.
define("FUNC_WEBSEC", SRC . "websec_functions.php"); // Functions for websec challenges

// Errors
define("ERROR_HANDLING", SRC . "error_handling.php"); // Functions for error handling
define("MESSAGES", SRC . "messages.php"); // Error messages, user notifications etc.

// Path for frequent redirects
define("MAIN_PAGE", "/shop" . DS . "main.php");
define("LOGIN_PAGE", "/index.php");
define("REGISTER_PAGE", "/registration.php");
