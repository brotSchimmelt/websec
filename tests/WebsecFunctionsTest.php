<?php

namespace test\websec;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use Exception;
use SQLite3;

// load SetupHelper
use test\helper\SetupHelper;

if (session_status() == PHP_SESSION_NONE) {
    // session has not started
    session_start();
}

// load configurations and functions to test
require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . CONF_DB_LOGIN); // DB credentials
require_once(CONF_DB_SHOP); // DB credentials
require_once(dirname(__FILE__) . FUNC_WEBSEC); // websec functions
require_once(dirname(__FILE__) . TES . "websec_mocked_functions.php");
require_once(dirname(__FILE__) . TES . "SetupHelper.php");


/**
 * Test class for the WebSec functions.
 */
final class WebsecFunctionsTest extends TestCase
{
    /*
    * Set up and tear down fixtures.
    */

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        // initialize sqlite catalog
        $_SESSION['sqliteDBs'] = [];

        // insert test user
        SetupHelper::insertUser("testWebSec1", "web@sec.test");
        SetupHelper::insertUser("testWebSec2", "web2@sec.test");
        SetupHelper::insertFakeCookie("testWebSec1");
        SetupHelper::insertFakeCookie("testWebSec2");
        SetupHelper::insertAllChallenges("testWebSec1");
        SetupHelper::insertAllChallenges("testWebSec2");
        SetupHelper::insertSolutions("testWebSec1", "-", "-", "-");
        SetupHelper::insertSolutions("testWebSec2", "-", "-", "-");
        SetupHelper::insertComment("testWebSec2");

        // test user catalog
        $_SESSION['websecTestUser'] = ["testWebSec1"];
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {
        // delete all SQLite test databases
        foreach ($_SESSION['sqliteDBs'] as $db) {
            unlink($db);
        }

        // delete all WebSec test users
        foreach ($_SESSION['websecTestUser'] as $user) {
            SetupHelper::deleteDbEntries($user);
        }

        // clean up SESSION
        unset($_SESSION['sqliteDBs']);
    }


    /*
    * Tests and data provider for the login functions.
    */

    /**
     * Generates data for the 'testSlug()' method.
     */
    public function providerSlug()
    {
        return [
            "String with umlaut" => array("töst", "tst"),
            "String with special characters" => array("_{(#!*.test,;", "test"),
            "String with whitespace" => array(" t e s t   ", "t-e-s-t"),
            "String with multiple whitespace" => array("t e s  t", "t-e-s--t"),
            "Empty String" => array("", ""),
            "Only whitespace" => array(" ", ""),
            "Upper case" => array("Upper Case", "Upper-Case"),
            "WWU mail" =>
            array("m_must01@uni-muenster.de", "mmust01uni-muensterde"),
            "All combined" => array(" -t #_饺子e st4_@2_-01}  ", "t-e-st42-01"),
            "Non ASCII characters" => array("Hi 大家好！", "Hi"),
            "Valid string" => array("Test123", "Test123")
        ];
    }

    /**
     * Test the websec function 'slug()'.
     * 
     * @test
     * @dataProvider providerSlug
     */
    public function testSlug($input, $expected): void
    {
        $result = slug($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the websec function 'create_sqli_db()'.
     * 
     * @test
     */
    public function testCreateSqliDb(): void
    {
        $user = "testWebSec1";
        $mail = "web@sec.test";
        $path = dirname(__FILE__) . "/";
        create_sqli_db($user, $mail, $path);

        $file = $path . $user . ".sqlite";
        $this->assertTrue(file_exists($file));

        // check if DB can be opened
        if (file_exists($file)) {
            $countUser = "SELECT COUNT(*) FROM `users`;";
            $database = new SQLite3($file);
            $result = $database->querySingle($countUser);

            $this->assertEquals(4, $result);
        }

        // clean up
        array_push($_SESSION['sqliteDBs'], $file);
    }

    /**
     * Generates data for the 'testQuerySqliDb()' method.
     */
    public function providerQuerySqliDb()
    {
        return [
            "Search user (default)" =>
            array(
                "elliot", 0,
                "Looks like we found your friend!", 4
            ),
            "Empty search term" =>
            array("", 1, 4),
            "Select query" =>
            array(
                "'; SELECT * FROM users;--", 0,
                "Looks like we found your friend!", 4
            ),
            "Insert user" =>
            array(
                "';INSERT INTO users (username,password,email,"
                    . "wishlist, user_status) VALUES ('foo','bar',"
                    . "'foo', 'bar', 'standard');--",
                1, 2
            ),
            "Delete user" =>
            array("';DELETE users WHERE username='foo';--", 1, 4),
            "Update a user to premium" =>
            array(
                "';UPDATE users SET user_status='premium' WHERE username="
                    . "'admin';--",
                1, 1
            ),
            "Update own user to premium" =>
            array("';UPDATE users SET user_status='premium';--", 1, 0)
        ];
    }

    /**
     * Test the websec function 'query_sqli_db()'.
     * 
     * @test
     * @dataProvider providerQuerySqliDb
     */
    public function testQuerySqliDb(
        $query,
        $queryType,
        $expected,
        $code = -1
    ): void {

        // set user name
        $_SESSION['userName'] = "testWebSec1";

        // call function
        $path = dirname(__FILE__) . "/";
        $result = query_sqli_db($query, $path);

        if ($queryType == 0) {

            // check output
            $this->expectOutputRegex("/" . $expected . "/");

            // check exit code
            if ($code != -1) {
                $this->assertEquals($code, $result);
            }
        } else {

            // check return value
            $this->assertEquals($expected, $result);
        }

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Test the websec function 'show_xss_comments()'.
     * 
     * @test
     */
    public function testShowXssComments(): void
    {
        // set user name
        $_SESSION['userName'] = "testWebSec1";

        // call function
        $path = dirname(__FILE__) . "/../src/includes/";
        show_xss_comments($path);
        $this->expectOutputRegex("/Totally useless!!1! I would never buy/");

        // clean up
        unset($_SESSION['userName']);
    }


    /**
     * Test the websec function 'check_user_comment_exists()'.
     * 
     * @test
     */
    public function testCheckUserCommentExists(): void
    {
        // call function
        $user = "testWebSec2";
        check_user_comment_exists($user);

        // check result
        $sql = "SELECT * FROM xss_comments WHERE author=?";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$user]);
        $result = $stmt->fetch();
        $this->assertFalse($result);
    }

    /**
     * Test the websec function 'add_comment_to_db()'.
     * 
     * @test
     */
    public function testAddCommentToDb(): void
    {
        // call function
        $user = "testWebSec2";
        $expected = "second";
        add_comment_to_db($expected, $user);

        // check result
        $sql = "SELECT * FROM xss_comments WHERE author=?";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$user]);
        $result = $stmt->fetch();
        $this->assertEquals($expected, $result['text']);
    }

    // process_csrf NEXT
}
