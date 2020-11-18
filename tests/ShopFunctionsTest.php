<?php

namespace test\shop;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use Exception;

if (session_status() == PHP_SESSION_NONE) {
    // session has not started
    session_start();
}

// load configurations and functions to test
require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . CONF_DB_LOGIN); // DB credentials
require_once(dirname(__FILE__) . CONF_DB_SHOP); // DB credentials
require_once(dirname(__FILE__) . FUNC_SHOP); // shop functions


/**
 * Test class for the shop functions.
 */
final class ShopFunctionsTes extends TestCase
{
}
