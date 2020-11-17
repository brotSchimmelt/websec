<?php

namespace test\basic;

use PDO;
use PDOException;

if (session_status() == PHP_SESSION_NONE) {
    // session has not started
    session_start();
}

use PHPUnit\Framework\TestCase;


// load configurations and functions to test
require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . CONF_DB_LOGIN); // DB credentials
require_once(dirname(__FILE__) . FUNC_BASE); // basic functions
require_once(dirname(__FILE__) . TES . "basic_mocked_functions.php");

/**
 * Test class for the basic functions.
 */
final class BasicFunctionsTest extends TestCase
{

    /*
    * Set up and tear down fixtures.
    */

    public static function setUpBeforeClass(): void
    {
        // set up test user
        $sql = "INSERT IGNORE INTO users (user_id, user_name, "
            . "user_wwu_email, user_pwd_hash, is_unlocked, is_admin, "
            . "timestamp, last_login) VALUE (NULL, :user, :mail, :pwd_hash, "
            . "'0', '0', :timestamp, NULL)";
        get_login_db()->prepare($sql)->execute([
            'user' => "loginTest",
            'mail' => "login@test.de",
            'pwd_hash' =>
            '$2y$13$iPY//1niofP6MBJooRBWN.OMP1RgUaFIQZZIojUv2r8MQ28GVPL06',
            'timestamp' => date("Y-m-d H:i:s")
        ]);
    }

    public static function tearDownAfterClass(): void
    {
        // delete test user
        $sql = "DELETE FROM users WHERE user_name='loginTest'";
        get_login_db()->query($sql);
    }

    /*
    * Tests and data provider for the basic functions.
    */

    /**
     * Generates data for the 'testIsUserLoggedIn()' method.
     */
    public function providerTestIsUserLoggedIn()
    {
        return [
            "Session variable unset" =>
            array("", "", false),
            "Session variable invalid value" =>
            array("userLoginStatus", 0, false),
            "Session variable set" =>
            array("userLoginStatus", 1, true)
        ];
    }

    /**
     * Test the basic function 'is_user_logged_in()'.
     * 
     * @test
     * @dataProvider providerTestIsUserLoggedIn
     */
    public function testIsUserLoggedIn($sessionVar, $sessionVal, $expected): void
    {
        // mock session array
        $_SESSION[] = [];

        if (!empty($sessionVar)) {
            $_SESSION[$sessionVar] = $sessionVal;
        }

        $result = is_user_logged_in();
        $this->assertEquals($expected, $result, "Result failed!");

        // clean up 
        unset($_SESSION);
    }

    /**
     * Generates data for the 'testIsUserAdmin()' method.
     */
    public function providerTestIsUserAdmin()
    {
        return [
            "Session variable unset" =>
            array("", "", "", false),
            "Session variable invalid value" =>
            array("userIsAdmin", 0, "", false),
            "Session variable set and not logged in" =>
            array("userIsAdmin", 1, "", false),
            "Session variable set and logged in" =>
            array("userIsAdmin", 1, 1, true)
        ];
    }

    /**
     * Test the basic function 'is_user_admin()'.
     * 
     * @test
     * @dataProvider providerTestIsUserAdmin
     */
    public function testIsUserAdmin(
        $sessionVar,
        $sessionVal,
        $loggedIn,
        $expected
    ): void {

        // mock session array
        $_SESSION[] = [];

        if (!empty($sessionVar)) {
            $_SESSION[$sessionVar] = $sessionVal;
        }
        if (!empty($loggedIn)) {
            $_SESSION['userLoginStatus'] = $sessionVal;
        }

        $result = is_user_admin();
        $this->assertEquals($expected, $result, "Result failed!");

        // clean up 
        unset($_SESSION);
    }

    /**
     * Generates data for the 'testIsUserUnlocked()' method.
     */
    public function providerTestIsUserUnlocked()
    {
        return [
            "Session variable unset" =>
            array("", "", "", false),
            "Session variable invalid value" =>
            array("userIsUnlocked", 0, "", false),
            "Session variable set and not logged in" =>
            array("userIsUnlocked", 1, "", false),
            "Session variable set and logged in" =>
            array("userIsUnlocked", 1, 1, true)
        ];
    }

    /**
     * Test the basic function 'is_user_unlocked()'.
     * 
     * @test
     * @dataProvider providerTestIsUserUnlocked
     */
    public function testIsUserUnlocked(
        $sessionVar,
        $sessionVal,
        $loggedIn,
        $expected
    ): void {

        // mock session array
        $_SESSION[] = [];

        if (!empty($sessionVar)) {
            $_SESSION[$sessionVar] = $sessionVal;
        }
        if (!empty($loggedIn)) {
            $_SESSION['userLoginStatus'] = $sessionVal;
        }

        $result = is_user_unlocked();
        $this->assertEquals($expected, $result, "Result failed!");

        // clean up 
        unset($_SESSION);
    }

    /**
     * Test the basic function 'log_user_out()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testLogUserOut(): void
    {
        // mock session array
        $_SESSION['test'] = "abc";

        log_user_out();

        $this->assertEquals(true, empty($_SESSION), "Result failed!");
    }

    /**
     * Test the basic function 'get_semester()'.
     * 
     * @test
     */
    public function testGetSemester(): void
    {
        $result = get_semester();
        $expected = " VM Web Security Winter Term 2020";
        $this->assertEquals($result, $expected);
    }

    /**
     * Test the basic function 'unlock_user()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testUnlockUser(): void
    {
        // get status before
        $sql = "SELECT is_unlocked FROM users WHERE user_name =?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute(["loginTest"]);
        $resultBefore = $stmt->fetch();

        // call function
        unlock_user("loginTest");

        // compare results
        $stmt2 = get_login_db()->prepare($sql);
        $stmt2->execute(["loginTest"]);
        $resultAfter = $stmt2->fetch();
        $this->assertNotEquals(
            $resultBefore['is_unlocked'],
            $resultAfter['is_unlocked'],
            "Unlocked status in DB was not changed!"
        );

        // check SESSION variable
        $this->assertEquals(1, $_SESSION['userIsUnlocked'], "Session failed!");
    }

    /**
     * Test the basic function 'delete_all_challenge_cookies()' and 
     * delete_all_cookies().
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testDeleteCookies(): void
    {
        delete_all_challenge_cookies();
        delete_all_cookies();

        $this->assertEquals(true, empty($_COOKIE));
    }

    /**
     * Test the basic function 'get_global_difficulty()'.
     * 
     * @test
     */
    public function testGetGlobalDifficulty(): void
    {
        $result = get_global_difficulty();
        $this->assertEquals("normal", $result);
    }

    /**
     * Test the basic function 'is_registration_enabled()'.
     * 
     * @test
     */
    public function testIsRegistrationEnabled(): void
    {
        $result = is_registration_enabled();
        $this->assertEquals(true, $result);
    }

    /**
     * Test the basic function 'is_login_enabled()'.
     * 
     * @test
     */
    public function testIsLoginEnabled(): void
    {
        $result = is_login_enabled();
        $this->assertEquals(true, $result);
    }

    /**
     * Test the basic function 'get_challenge_badge_link()'.
     * 
     * @test
     */
    public function testGetChallengeBadgeLink(): void
    {
        $result = get_challenge_badge_link('reflective_xss');
        $expected = "https://sso.uni-muenster.de/LearnWeb/"
            . "learnweb2/course/view.php?id=47537";
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the basic function 'get_allowed_domains()'.
     * 
     * @test
     */
    public function testGetAllowedDomains(): void
    {
        $result = get_allowed_domains();
        $expected = [
            "@uni-muenster.de",
            "@wi.uni-muenster.de",
            "@gmail.com"
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the basic function 'get_blocked_usernames()'.
     * 
     * @test
     */
    public function testGetBlockedUsernames(): void
    {
        $result = get_blocked_usernames();
        $expected = [
            "admin",
            "elliot",
            "l337_h4ck3r",
            "administrator"
        ];
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the basic function 'get_setting()'.
     * 
     * @test
     */
    public function testGetSetting(): void
    {
        $result = get_setting("difficulty", "hard");
        $this->assertEquals(false, $result);
    }

    /**
     * Test the basic function 'set_setting()'.
     * 
     * @test
     */
    public function testSetSetting(): void
    {
        // open test JSON file
        $file = dirname(__FILE__) . "/testSettings.json";

        // set new value
        $oldVal = get_setting("difficulty", "hard", $file);
        $newVal = !$oldVal;
        set_setting("difficulty", "hard", $newVal, $file);

        // test setting
        $result = get_setting("difficulty", "hard", $file);
        $this->assertEquals($newVal, $result);

        // clean up
        set_setting("difficulty", "hard", $oldVal, $file);
    }

    /**
     * Test the basic function 'read_json_file()'.
     * 
     * @test
     */
    public function testReadJsonFile(): void
    {
        // open test JSON file
        $file = dirname(__FILE__) . "/testSettings.json";

        $fileContent = read_json_file($file);
        $result = is_array($fileContent) && !empty($fileContent);

        $this->assertEquals(true, $result);
    }

    /**
     * Generates data for the 'testMakeCleanArray()' method.
     */
    public function providerMakeCleanArray()
    {
        return [
            "Empty input" =>
            array("", []),
            "String with 1 element" =>
            array("string", ["string"]),
            "Only whitespace" =>
            array(" ", []),
            "String with semicolon" =>
            array("element;element, element", ["element;element", "element"]),
            "String with extra whitespace" =>
            array("string   string", ["stringstring"]),
            "Valid list" =>
            array("string,string", ["string", "string"])
        ];
    }

    /**
     * Test the basic function 'make_clean_array()'.
     * 
     * @test
     * @dataProvider providerMakeCleanArray
     */
    public function testMakeCleanArray($input, $expected): void
    {
        $result = make_clean_array($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the basic function 'get_file_size()'.
     * 
     * @test
     */
    public function testGetFileSize(): void
    {
        $result = get_file_size(__FILE__);
        $this->assertTrue($result > 0);
    }

    /**
     * Generates data for the 'testGetUserName()' method.
     */
    public function providerGetUserName()
    {
        return [
            "User exists" =>
            array("login@test.de", "loginTest"),
            "User does not exits" =>
            array("fake@mail.test", false)
        ];
    }

    /**
     * Test the basic function 'get_user_name()'.
     * 
     * @test
     * @dataProvider providerGetUserName
     * @runInSeparateProcess
     */
    public function testGetUserName($input, $expected): void
    {
        $result = get_user_name($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the basic function 'export_json()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testExportJSON(): void
    {
        $content = [['test'], ['test'], ['test']];
        $result = export_json($content, "test.csv");

        $this->assertTrue(!empty($result));
    }
}
