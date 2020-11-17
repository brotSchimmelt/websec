<?php

/* General configurations */
// OS independent dir separator
define('DS', DIRECTORY_SEPARATOR);
// Web server root dir
define('ROOT', $_SERVER['DOCUMENT_ROOT'] . DS);
// Current URL to the website
define('SITE_URL', 'localhost');
// The hidden directory for PHPMyAdmin
// this dir is only used if phpMyAdmin runs directly in the apache container
define('PMA', 'e6rs82wdj94qsfgb' . DS);
// Token to access phpMyAdmin without being an admin user already
define('PMA_TOKEN', 'ux6vjfhsxqg3tfp6x9s7q6uggfdj3whm');
// Number of challenges in the shop
// currently XSS (2x), SQLi, CSRF
define('NUM_CHALLENGES', 4);


/* Locations on app level */
// Source dir
define('SRC', ROOT .  '..' . DS . 'src' . DS);
// Resources dir
define('RES', ROOT .  '..' . DS . 'resources' . DS);
// Documentation dir
define('DOC', ROOT .  '..' . DS . 'docs' . DS);
// Log dir
define('LOG', ROOT .  '..' . DS . 'docs' . DS);
// Command Line Utility dir
define('BIN', ROOT .  '..' . DS . 'bin' . DS);
// Configuration dir
define('CON', ROOT .  '..' . DS . 'config' . DS);
// Database dir for the sql injection challenge
define('DAT', ROOT .  '..' . DS . 'data' . DS);
// Test dir
define('TES', ROOT . '..' . DS . 'tests' . DS);

/* Database configurations and settings */
// Login database credentials
define('CONF_DB_LOGIN', CON . 'db_login.php');
// Shop database credentials
define('CONF_DB_SHOP', CON . 'db_shop.php');
// Settings file for difficulty and login/registration
// define('SETTINGS', CON . 'settings.json');
define('SETTINGS', dirname(__FILE__) . DS . 'settings.json');

/* Subfolders in src/ */
// Includes dir
define('INCL', SRC . 'includes' . DS);

/* Common includes */
// Header location
define('HEADER_SHOP', INCL . 'shop_header.php');
// Footer location
define('FOOTER_SHOP', INCL . 'shop_footer.php');
// JavaScript for the shop section
define('JS_SHOP', INCL . 'shop_js.php');
// Admin header location
define('HEADER_ADMIN', INCL . 'admin_header.php');
// Admin sidebar location
define('SIDEBAR_ADMIN', INCL . 'admin_sidebar.php');
// JavaScript for the admin section
define('JS_ADMIN', INCL . 'admin_js.php');
// Default JavaScript for bootstrap
define('JS_BOOTSTRAP', INCL . 'util_bootstrap_js.php');

/* Instructions for the challenges */
// general remarks for the challenges
define('INST_GENERAL', INCL . 'instruction_general.php');
// instructions for the stored and the reflective XSS challenges
define('INST_XSS', INCL . 'instruction_xss.php');
// instruction for the SQL injection challenge
define('INST_SQLI', INCL . 'instruction_sqli.php');
// instruction for the contact form challenge
define('INST_CSRF', INCL . 'instruction_csrf.php');

/* Functions */
// Basic functions for the site
define('FUNC_BASE', SRC . 'basic_functions.php');
// Functions for login, registration etc.
define('FUNC_LOGIN', SRC . 'login_functions.php');
// Functions for user management etc. 
define('FUNC_ADMIN', SRC . 'admin_functions.php');
// Functions for products, cart etc.
define('FUNC_SHOP', SRC . 'shop_functions.php');
// Functions for websec challenges
define('FUNC_WEBSEC', SRC . 'websec_functions.php');

/* Errors */
// Functions for error handling
define('ERROR_HANDLING', SRC . 'error_functions.php');
// Error messages, user notifications etc.
define('MESSAGES', INCL . 'util_messages.php');

/* Path for frequent redirects */
define('MAIN_PAGE', '/shop/main.php');
define('LOGIN_PAGE', '/index.php');
define('REGISTER_PAGE', '/registration.php');
define('SCORE', '/user/scorecard.php');
define('EXPORT', '/admin/export_file.php');
