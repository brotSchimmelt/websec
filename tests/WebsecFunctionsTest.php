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
        $_SESSION['websecTestFiles'] = [];

        // insert test user
        SetupHelper::insertUser("testWebSec1", "web@sec.test");
        SetupHelper::insertUser("testWebSec2", "web2@sec.test");
        SetupHelper::insertUser("testWebSec3", "web3@sec.test");
        SetupHelper::insertUser("testWebSec4", "web4@sec.test");
        SetupHelper::insertUser("testWebSec5", "web5@sec.test");
        SetupHelper::insertFakeCookie("testWebSec1");
        SetupHelper::insertFakeCookie("testWebSec2");
        SetupHelper::insertFakeCookie("testWebSec3");
        SetupHelper::insertFakeCookie("testWebSec4");
        SetupHelper::insertFakeCookie("testWebSec5");
        SetupHelper::insertAllChallenges("testWebSec1");
        SetupHelper::insertAllChallenges("testWebSec2");
        SetupHelper::insertAllChallenges("testWebSec3");
        SetupHelper::insertAllChallenges("testWebSec4", 1, 1, 1, 1);
        SetupHelper::insertAllChallenges("testWebSec5", 1, 1, 1, 1);
        SetupHelper::insertSolutions("testWebSec1", "-", "-", "-");
        SetupHelper::insertSolutions("testWebSec2", "-", "-", "-");
        SetupHelper::insertSolutions("testWebSec3", "-", "-", "-");
        SetupHelper::insertSolutions("testWebSec4", "-", "-", "-");
        SetupHelper::insertComment("testWebSec2");
        SetupHelper::insertComment("testWebSec3", $comment = "contact.php");
        SetupHelper::insertComment("testWebSec4", $comment = "contact.php");
        SetupHelper::insertCSRFPost("testWebSec5", "pwned", "product.php");
        SetupHelper::insertProduct("testWebSec1", 1, 1);

        // test user catalog
        $_SESSION['websecTestUser'] =
            [
                "testWebSec1",
                "testWebSec2",
                "testWebSec3",
                "testWebSec4",
                "testWebSec5"
            ];
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {

        // delete all SQLite test databases
        foreach ($_SESSION['websecTestFiles'] as $file) {
            if (file_exists($file)) {
                unlink($file);
            }
        }

        // delete all WebSec test users
        foreach ($_SESSION['websecTestUser'] as $user) {
            SetupHelper::deleteDbEntries($user);
        }

        // clean up SESSION
        unset($_SESSION['websecTestFiles']);
        unset($_SESSION['websecTestUser']);
        session_destroy();
    }


    /*
    * Tests and data provider for the websec functions.
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
        array_push($_SESSION['websecTestFiles'], $file);
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

    /**
     * Generates data for the 'testProcessCsrf()' method.
     */
    public function providerProcessCsrf()
    {
        return [
            "Wrong message" =>
            array("elliot", "msg", "testWebSec1", "t", 1, "overview.php"),
            "Wrong user" =>
            array("admin", "pwned", "testWebSec2", "t", 2, "overview.php"),
            "Wrong token, hard" =>
            array("elliot", "msg", "testWebSec2", "t", 2, "overview.php", true),
            "Already a post in the databse" =>
            array("elliot", "pwned", "testWebSec1", "t", 3, "overview.php"),
            "Wrong referrer" =>
            array("elliot", "pwned", "testWebSec2", "t", 4, "example.php"),
            "correct user, message and referrer" =>
            array("elliot", "pwned", "testWebSec3", "t", 0, "overview.php"),
            "Correct token, hard" =>
            array(
                "elliot", "pwned", "testWebSec4", "csrf_token", 0,
                "overview.php", true
            )

        ];
    }

    /**
     * Test the websec function 'process_csrf()'.
     * 
     * @test
     * @dataProvider providerProcessCsrf
     */
    public function testProcessCsrf(
        $uname,
        $post,
        $user,
        $userToken,
        $expected,
        $referrer,
        $difficulty = false
    ): void {

        // set difficulty
        if ($difficulty == true) {
            $_SESSION['hard'] = true;
        }

        // set SESSION variables
        $_SESSION['userName'] = $user;
        $_SESSION['userMail'] = "process@csrf.test";
        $_SESSION['userCSRF'] = "elliot";

        // set referrer
        $_SERVER['HTTP_REFERER'] = $referrer;

        // set test path
        $path = dirname(__FILE__) . "/";

        // call function
        $result = process_csrf($uname, $post, $user, $userToken, $path);

        // compare results
        $this->assertEquals($expected, $result);

        // clean up
        unset($_SESSION['hard']);
        unset($_SERVER['HTTP_REFERER']);
        unset($_SESSION['userName']);
        unset($_SESSION['userMail']);
        unset($_SESSION['userCSRF']);
        $jsonFile = $path . $user . ".json";
        array_push($_SESSION['websecTestFiles'], $jsonFile);
    }

    /**
     * Test the websec function 'reset_reflective_xss_db()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testResetReflectiveXssDb(): void
    {
        // call function
        reset_reflective_xss_db("testWebSec4");

        // check SESSION
        $this->assertEquals(
            "newToken",
            $_SESSION['reflectiveXSS'],
            "Token was not saved to Session!"
        );

        // check database
        $sql = "SELECT * FROM challengeStatus WHERE user_name=?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['testWebSec4']);
        $this->assertEquals(
            0,
            $stmt->fetch()['reflective_xss'],
            "Database check failed!"
        );

        // clean up
        unset($_SESSION['reflectiveXSS']);
    }

    /**
     * Test the websec function 'reset_stored_xss_db()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testResetStoredXssDb(): void
    {
        // call function
        $_SESSION['reflectiveXSS'] = "foobar";
        reset_stored_xss_db("testWebSec4");

        // check SESSION
        $this->assertEquals(
            "newToken",
            $_SESSION['storedXSS'],
            "Token was not saved to Session!"
        );
        $this->assertFalse(
            isset($_SESSION['showStoredXSSModal']),
            "Modal flag was not removed!"
        );

        // check database
        $sql = "SELECT * FROM challengeStatus WHERE user_name=?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['testWebSec4']);
        $this->assertEquals(
            0,
            $stmt->fetch()['stored_xss'],
            "Database check failed!"
        );

        // clean up
        unset($_SESSION['storedXSS']);
        unset($_SESSION['reflectiveXSS']);
    }

    /**
     * Test the websec function 'reset_sqli_db()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testResetSqliDb(): void
    {
        // set SESSION variable
        $_SESSION['userMail'] = "web4@sec.test";

        // call function
        $user = "testWebSec4";
        $path = dirname(__FILE__) . "/";
        reset_sqli_db($user, $path);

        // compare results
        $sqliteDB = $path . $user . ".sqlite";
        $this->assertTrue(
            file_exists($sqliteDB),
            "SQLite file was not created!"
        );
        $sql = "SELECT * FROM challengeStatus WHERE user_name=?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(['testWebSec4']);
        $this->assertEquals(
            0,
            $stmt->fetch()['sqli'],
            "Database check failed!"
        );

        // clean up
        unset($_SESSION['userMail']);
        array_push($_SESSION['websecTestFiles'], $sqliteDB);
    }

    /**
     * Test the websec function 'reset_csrf_db()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testResetCsrfDb(): void
    {

        function get_posts($user)
        {
            $sql = "SELECT * FROM csrf_posts WHERE `user_name` = ?";
            $stmt = get_shop_db()->prepare($sql);
            $stmt->execute([$user]);
            return $stmt->fetch();
        }

        function get_status($user)
        {
            $sql = "SELECT * FROM challengeStatus WHERE `user_name` = ?";
            $stmt = get_login_db()->prepare($sql);
            $stmt->execute([$user]);
            return $stmt->fetch();
        }

        // get state before
        $user = "testWebSec5";
        $resultBefore = get_posts($user);

        // call function
        reset_csrf_db($user);

        // test results
        $this->assertNotEquals(get_posts($user), $resultBefore);
        $this->assertNotEquals(1, get_status($user)['csrf']);
        $this->assertNotEquals(1, get_status($user)['csrf_referrer']);
    }

    /**
     * Test the websec function 'reset_all_challenges()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testResetAllChallenges(): void
    {
        // set SESSION variables
        $_SESSION['reflectiveXSS'] = "foobar";
        $_SESSION['userMail'] = "web5@sec.test";

        // user settings
        $user = "testWebSec5";
        $path = dirname(__FILE__) . "/";
        $sqliteDB = $path . $user . ".sqlite";

        // call function
        reset_all_challenges($user, $path);

        // get csrf post
        $sql = "SELECT * FROM csrf_posts WHERE `user_name` = ?";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$user]);
        $csrfPost =  $stmt->fetch();

        // get challenge status from database
        $sql = "SELECT * FROM challengeStatus WHERE `user_name` = ?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$user]);
        $challengeResult =  $stmt->fetch();

        // tests
        $this->assertEquals(
            "newToken",
            $_SESSION['reflectiveXSS'],
            "Cookie creation failed (reflective)!"
        );
        $this->assertEquals(
            "newToken",
            $_SESSION['storedXSS'],
            "Cookie creation failed (stored)!"
        );
        $this->assertTrue(
            file_exists($sqliteDB),
            "SQLite file was not created!"
        );
        $this->assertFalse($csrfPost, "CSRF post was not deleted!");
        $this->assertEquals(
            0,
            $challengeResult['reflective_xss'],
            "DB reset failed (reflective)!"
        );
        $this->assertEquals(
            0,
            $challengeResult['stored_xss'],
            "DB reset failed (stored)!"
        );
        $this->assertEquals(
            0,
            $challengeResult['sqli'],
            "DB reset failed (sqli)!"
        );
        $this->assertEquals(
            0,
            $challengeResult['csrf'],
            "DB reset failed (csrf)!"
        );
        $this->assertEquals(
            0,
            $challengeResult['csrf_referrer'],
            "DB reset failed (csrf_referrer)!"
        );

        // clean up
        unset($_SESSION['reflectiveXSS']);
        unset($_SESSION['storedXSS']);
        unset($_SESSION['userMail']);
        array_push($_SESSION['websecTestFiles'], $sqliteDB);
    }

    /**
     * Generates data for the 'testCheckReflectiveXssChallenge()' method.
     */
    public function providerCheckReflectiveXssChallenge()
    {
        return [
            "Correct cookie (reflective)" => array("My1CooKie", true),
            "Correct cookie (stored)" => array("My2CooKie", true),
            "Wrong cookie" => array("foobar", false),
            "Empty cookie" => array("", false)
        ];
    }

    /**
     * Test the websec function 'check_reflective_xss_challenge()'.
     * 
     * @test
     * @runInSeparateProcess
     * @dataProvider providerCheckReflectiveXssChallenge
     */
    public function testCheckReflectiveXssChallenge($input, $expected): void
    {
        $_SESSION['reflectiveXSS'] = "My1CooKie";
        $_SESSION['storedXSS'] = "My2CooKie";

        $result = check_reflective_xss_challenge($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the websec function 'check_sqli_challenge()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testCheckSqliChallenge(): void
    {
        // create SQLi database
        $path = dirname(__FILE__) . "/";
        $user = "testWebSec5";
        $mail = "web5@sec.test";
        $pathToSQLiDB = $path . $user . ".sqlite";
        create_sqli_db($user, $mail, $path);

        // check status before solution
        $result = check_sqli_challenge($user, $path);
        $this->assertFalse($result, "Check before query failed!");

        // solve challenge
        $sql = "UPDATE `users` SET `user_status`='premium' WHERE "
            . "username='" . $user . "'";
        $database = new SQLite3($pathToSQLiDB);
        if ($database) {
            $database->querySingle($sql);
        }

        // call function after query
        $result = check_sqli_challenge($user, $path);
        $this->assertTrue($result, "Check after query failed!");

        // clean up
        array_push($_SESSION['websecTestFiles'], $pathToSQLiDB);
    }

    /**
     * Test the websec function 'check_crosspost_challenge()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testCheckCrosspostChallenge(): void
    {
        // user has solved the challenge
        $user = "testWebSec5";
        $result1 = check_crosspost_challenge($user);
        $this->assertTrue($result1);

        // user has not solved the challenge
        $user2 = "testWebSec4";
        $result2 = check_crosspost_challenge($user2);
        $this->assertFalse($result2);
    }

    /**
     * Test the websec function 'check_crosspost_challenge_double()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testCheckCrosspostChallengeDouble(): void
    {
        // set up environment
        SetupHelper::insertCSRFPost("testWebSec4", "pwned", "example.com");

        // user has solved the challenge correctly
        $user1 = "testWebSec5";
        $result1 = check_crosspost_challenge_double($user1);
        $this->assertTrue($result1);

        // user has solved the challenge with a wrong referrer
        $user2 = "testWebSec4";
        $result2 = check_crosspost_challenge_double($user2);
        $this->assertFalse($result2);

        // user has not solved the challenge
        $user3 = "testWebSec3";
        $result3 = check_crosspost_challenge_double($user3);
        $this->assertFalse($result3);
    }

    /**
     * Generates data for the 'testSetChallengeStatus()' method.
     */
    public function providerSetChallengeStatus()
    {
        return [
            "Set challenge to solved" => array(1, 1),
            "Set challenge to unsolved" => array(0, 0),
            "Wrong challenge status (!= 1)" => array(42, 0),
            "Empty challenge code" => array("", 0)
        ];
    }

    /**
     * Test the websec function 'set_challenge_status()'.
     * 
     * @test
     * @runInSeparateProcess
     * @dataProvider providerSetChallengeStatus
     */
    public function testSetChallengeStatus($input, $expected): void
    {
        $user = "testWebSec5";
        $challenge = "reflective_xss";
        set_challenge_status($challenge, $user, $status = $input);

        // check result
        $sql = "SELECT * FROM challengeStatus WHERE user_name=?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$user]);
        $result = $stmt->fetch()[$challenge];

        // compare results
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the websec function 'lookup_challenge_status()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testLookupChallengeStatus(): void
    {
        $result1 = lookup_challenge_status("reflective_xss", "testWebSec1");
        $this->assertEquals(false, $result1);
    }

    /**
     * Test the websec function 'update_cart()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testUpdateCart(): void
    {
        $user = "testWebSec1";
        update_cart($user);

        $sql = "SELECT * FROM cart WHERE user_name=?";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$user]);
        $result = $stmt->fetch()['quantity'];

        $this->assertTrue($result > 1);
        $this->assertTrue($_SESSION['fakeCart']);
    }


    /**
     * Test the websec function 'check_stored_xss_challenge()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testCheckStoredXssChallenge(): void
    {
        $user = "testWebSec1";
        SetupHelper::insertProduct($user, 2, 1);
        $_SESSION['userName'] = $user;

        check_stored_xss_challenge($user);

        $this->assertTrue(isset($_SESSION['fakeCart']));
        $this->assertTrue(isset($_SESSION['showSuccessModalXSS']));

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Generates data for the 'testCompareXssCookies()' method.
     */
    public function providerCompareXssCookies()
    {
        return [
            "Wrong cookie" => array("invalid_value", false),
            "Correct cookie" => array("value", true)
        ];
    }

    /**
     * Test the websec function 'compare_xss_cookies()'.
     * 
     * @test
     * @runInSeparateProcess
     * @dataProvider providerCompareXssCookies
     */
    public function testCompareXssCookies($input, $expected): void
    {
        $_COOKIE['compareTest'] = "value";
        $_SESSION['storedXSS'] = $input;
        $result = compare_xss_cookies();
        $this->assertEquals($expected, $result);

        // clean up
        unset($_COOKIE['compareTest']);
        unset($_SESSION['storedXSS']);
    }

    /**
     * Test the websec function 'set_stolen_session()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testSetStolenSession(): void
    {
        $user = "testWebSec1";
        unset($_SESSION['showStoredXSSModal']);
        set_stolen_session($user);

        // compare results
        $this->assertTrue(isset($_SESSION['showStoredXSSModal']));
        $this->assertEquals(0, $_SESSION['showStoredXSSModal']);

        // clean up
        unset($_SESSION['showStoredXSSModal']);
    }

    /**
     * Test the websec function 'get_fake_CSRF_token()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testGetFakeCsrfToken(): void
    {
        $user = "testWebSec1";
        $expected = "csrf_token";
        $result = get_fake_CSRF_token($user);

        $this->assertEquals($expected, $result);
    }

    /**
     * Test the websec function 'remove_comment()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testRemoveComment(): void
    {
        $user = "testWebSec2";
        remove_comment($user);

        // test results
        $sql = "SELECT 1 FROM xss_comments WHERE `author`=?";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$user]);
        $result = $stmt->fetch();

        $this->assertFalse($result);
    }

    /**
     * Test the websec function 'write_to_challenge_json()' and 
     * 'get_last_challenge_input()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testChallengeJson(): void
    {
        // json file
        $user = "testWebSec5";
        $path = dirname(__FILE__) . "/";
        $jsonFile = $path . $user . ".json";

        // write test file
        $mail = "web5@sec.test";
        $challenge = "sqli";
        $content = "foobar";
        write_to_challenge_json($user, $mail, $challenge, $content, $path);

        // test if JSON file exists
        $this->assertTrue(file_exists($jsonFile), "File was not created!");

        // get challenge results
        $resultXSS = get_last_challenge_input($user, "reflective_xss", $path);
        $resultSQLi = get_last_challenge_input($user, "sqli", $path);

        // test challenge results
        $this->assertEquals("-", $resultXSS);
        $this->assertEquals($content, $resultSQLi);

        // clean up
        array_push($_SESSION['websecTestFiles'], $jsonFile);
    }
}
