<?php

namespace test\admin;

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
require_once(CONF_DB_SHOP); // DB credentials
require_once(dirname(__FILE__) . FUNC_ADMIN); // admin functions
require_once(dirname(__FILE__) . TES . "admin_mocked_functions.php");


/**
 * Test class for the admin functions.
 */
final class AdminFunctionsTest extends TestCase
{

    /*
    * Set up and tear down fixtures.
    */

    /**
     * This method is called before the first test of this test class is run.
     */
    public static function setUpBeforeClass(): void
    {
        // add test students
        self::insertUser("testUser1", "user1@test.fake", "hash");
        self::insertUser("testUser2", "user2@test.fake", "hash", $unlocked = 1);
        self::setAllChallenges("testUser1");
        self::setAllChallenges("testUser2", 1, 1, 1, 1);
        self::insertCSRFPost("testUser2", "pwned", "example.php");
        self::insertSolutions("testUser1", "-", "-", "-");
        self::insertSolutions("testUser2", "test", "test", "test");

        // add test admins
        self::insertUser("testAdmin1", "admin1@test.fake", "hash", 0, 1);
        self::insertUser("testAdmin2", "admin2@test.fake", "hash", 1, 1);
        self::setAllChallenges("testAdmin1");
        self::setAllChallenges("testAdmin2");

        // save all test user names in SESSION array
        $_SESSION['adminTestUser'] =
            ["testUser1", "testUser2", "testAdmin1", "testAdmin2"];
        $_SESSION['adminNumOfStudents'] = 2;
        $_SESSION['adminNumOfAdmins'] = 2 + 1; // +1 for default administrator
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {
        // SQL statements for the databases
        $deleteUsers = "DELETE FROM users WHERE user_name=?";
        $deleteChallenges = "DELETE FROM challengeStatus WHERE user_name=?";
        $deleteCSRF = "DELETE FROM csrf_posts WHERE user_name=?";
        $deleteCart = "DELETE FROM cart WHERE user_name=?";
        $deleteSolutions = "DELETE FROM challenge_solutions WHERE user_name=?";

        // remove test users
        foreach ($_SESSION['adminTestUser'] as $user) {
            get_login_db()->prepare($deleteUsers)->execute([$user]);
            get_login_db()->prepare($deleteChallenges)->execute([$user]);
            get_shop_db()->prepare($deleteCSRF)->execute([$user]);
            get_shop_db()->prepare($deleteCart)->execute([$user]);
            get_shop_db()->prepare($deleteSolutions)->execute([$user]);
        }

        // empty SESSION array
        unset($_SESSION['adminTestUser']);
        unset($_SESSION['adminNumOfStudents']);
        unset($_SESSION['adminNumOfAdmins']);
    }

    /**
     * Insert a user into the 'users' database.
     */
    public static function insertUser(
        $name,
        $mail,
        $hash,
        $unlocked = 0,
        $admin = 0
    ): void {
        $insertUser = "INSERT IGNORE INTO users (user_id, user_name, "
            . "user_wwu_email, user_pwd_hash, is_unlocked, is_admin, "
            . "timestamp, last_login) VALUE (NULL, :user, :mail, :pwd_hash, "
            . ":unlocked, :admin, DEFAULT, DEFAULT)";

        $stmt = get_login_db()->prepare($insertUser);
        $stmt->execute([
            'user' => $name,
            'mail' => $mail,
            'pwd_hash' => $hash,
            'unlocked' => $unlocked,
            'admin' => $admin
        ]);
    }

    /**
     * Set all challenges for a given user.
     */
    public static function setAllChallenges(
        $name,
        $reflective_xss = 0,
        $stored_xss = 0,
        $sqli = 0,
        $csrf = 0
    ): void {
        $insertChallenge = "INSERT IGNORE INTO challengeStatus "
            . "(id,user_name,reflective_xss,stored_xss, sqli,csrf,csrf_referrer"
            . ") VALUE (NULL,:user,:rxss,:sxss,:sqli,:csrf,:csrfr)";

        // set test referrer
        $csrfReferrer = ($csrf === 1) ? "referrer" : "";

        $stmt = get_login_db()->prepare($insertChallenge);
        $stmt->execute([
            'user' => $name,
            'rxss' => $reflective_xss,
            'sxss' => $stored_xss,
            'sqli' => $sqli,
            'csrf' => $csrf,
            'csrfr' => $csrfReferrer
        ]);
    }

    /**
     * Insert a test CSRF post for a given user into the database.
     */
    public static function insertCSRFPost($name, $msg, $referrer): void
    {
        $insertCSRF = "INSERT IGNORE INTO csrf_posts (post_id,user_name,"
            . "message,referrer,timestamp) VALUE (NULL,:user,:msg,:referrer,"
            . ":time)";

        $stmt = get_shop_db()->prepare($insertCSRF);
        $stmt->execute([
            'user' => $name,
            'msg' => $msg,
            'referrer' => $referrer,
            'time' => date("Y-m-d H:i:s")
        ]);
    }

    /**
     * Insert challenge solutions for a given user.
     */
    public static function insertSolutions($name, $rxss, $sxss, $sqli)
    {
        $insertSolution = "INSERT IGNORE INTO challenge_solutions (id, "
            . "user_name, reflective_xss, stored_xss, sqli) VALUE (NULL, :user,"
            . " :rxss, :sxss, :sqli)";

        $stmt = get_shop_db()->prepare($insertSolution);
        $stmt->execute([
            'user' => $name,
            'rxss' => $rxss,
            'sxss' => $sxss,
            'sqli' => $sqli
        ]);
    }


    /*
    * Tests and data provider for the login functions.
    */


    /**
     * Test the admin function 'get_num_of_students()'.
     * 
     * @test
     */
    public function testGetNumOfStudents(): void
    {
        $result = get_num_of_students();
        $this->assertEquals($_SESSION['adminNumOfStudents'], $result);
    }

    /**
     * Test the admin function 'get_num_of_admins()'.
     * 
     * @test
     */
    public function testGetNumOfAdmins(): void
    {
        $result = get_num_of_admins();
        $this->assertEquals($_SESSION['adminNumOfAdmins'], $result);
    }

    /**
     * Test the admin function 'get_num_of_unlocked_students()'.
     * 
     * @test
     */
    public function testGetNumOfUnlockedStudents(): void
    {
        $result = get_num_of_unlocked_students();
        $this->assertEquals(1, $result);
    }

    /**
     * Generates data for the 'testIsUserUnlockedInDb()' method.
     */
    public function providerIsUserUnlockedInDb()
    {
        return [
            "Locked user" => array("testUser1", false),
            "Unlocked user" => array("testUser2", true)
        ];
    }

    /**
     * Test the admin function 'is_user_unlocked_in_db()'.
     * 
     * @test
     * @dataProvider providerIsUserUnlockedInDb
     */
    public function testIsUserUnlockedInDb($input, $expected): void
    {
        $result = is_user_unlocked_in_db($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Generates data for the 'testIsUserAdminInDb()' method.
     */
    public function providerIsUserAdminInDb()
    {
        return [
            "Student" => array("testUser1", false),
            "Admin" => array("testAdmin1", true)
        ];
    }

    /**
     * Test the admin function 'is_user_admin_in_db()'.
     * 
     * @test
     * @dataProvider providerIsUserAdminInDb
     */
    public function testIsUserAdminInDb($input, $expected): void
    {
        $result = is_user_admin_in_db($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the admin function 'get_total_progress()'.
     * 
     * @test
     */
    public function testGetTotalProgress(): void
    {
        $result = get_total_progress();
        $this->assertEquals(50.0, $result);
    }

    /**
     * Generates data for the 'testGetIndividualProgress()' method.
     */
    public function providerGetIndividualProgress()
    {
        return [
            "0 solved challenges" => array("testUser1", 0),
            "All challenges solved" => array("testUser2", 4)
        ];
    }

    /**
     * Test the admin function 'get_individual_progress()'.
     * 
     * @test
     * @dataProvider providerGetIndividualProgress
     */
    public function testGetIndividualProgress($input, $expected): void
    {
        $result = get_individual_progress($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the admin function 'show_students_with_open_challenges()'.
     * 
     * @test
     */
    public function testShowStudentsWithOpenChallenges(): void
    {
        show_students_with_open_challenges();

        $this->expectOutputRegEx("/testUser1/");
    }

    /**
     * Test the admin function 'show_solved_challenges()'.
     * 
     * @test
     */
    public function testShowSolvedChallenges(): void
    {
        show_solved_challenges();
        $this->expectOutputRegEx("/user2@test.fake/");
    }

    /**
     * Generates data for the 'testGetSolvedChallenges()' method.
     */
    public function providerGetSolvedChallenges()
    {
        return [
            "0 solved challenges" =>
            array("testUser1", "-"),
            "All challenges solved" =>
            array("testUser2", "Reflective XSS, Stored XSS, SQLi, Crosspost*")
        ];
    }

    /**
     * Test the admin function 'get_solved_challenges()'.
     * 
     * @test
     * @dataProvider providerGetSolvedChallenges
     */
    public function testGetSolvedChallenges($input, $expected): void
    {
        $result = get_solved_challenges($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Generates data for the 'testGetOpenChallenges()' method.
     */
    public function providerGetOpenChallenges()
    {
        return [
            "0 open challenges" =>
            array("testUser2", "Crosspost*"),
            "All challenges open" =>
            array("testUser1", "Reflective XSS, Stored XSS, SQLi, Crosspost")
        ];
    }

    /**
     * Test the admin function 'get_open_challenges()'.
     * 
     * @test
     * @dataProvider providerGetOpenChallenges
     */
    public function testGetOpenChallenges($input, $expected): void
    {
        $result = get_open_challenges($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Generates data for the 'testGetCsrfChallengeData()' method.
     */
    public function providerGetCsrfChallengeData()
    {
        return [
            "User with no posts" =>
            array("testUser1", ["referrer" => "-", "message" => "-"]),
            "User with CSRF post" =>
            array(
                "testUser2", ["referrer" => "example.php", "message" => "pwned"]
            )
        ];
    }

    /**
     * Test the admin function 'get_csrf_challenge_data()'.
     * 
     * @test
     * @dataProvider providerGetCsrfChallengeData
     */
    public function testGetCsrfChallengeData($input, $expected): void
    {
        $result = get_csrf_challenge_data($input);
        $this->assertTrue(is_array($result));
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the admin function 'set_global_difficulty()'.
     * 
     * @test
     */
    public function testSetGlobalDifficulty(): void
    {
        set_global_difficulty("normal");
        $this->assertEquals(false, $_SESSION['difficulty']['hard']);
        set_global_difficulty("hard");
        $this->assertEquals(true, $_SESSION['difficulty']['hard']);

        // clean up
        unset($_SESSION['difficulty']);
    }

    /**
     * Test the admin function 'set_login_status()'.
     * 
     * @test
     */
    public function testSetLoginStatus(): void
    {
        set_login_status(true);
        $this->assertEquals(false, $_SESSION['login']['disabled']);
        set_login_status(false);
        $this->assertEquals(true, $_SESSION['login']['disabled']);

        // clean up
        unset($_SESSION['login']);
    }

    /**
     * Test the admin function 'set_registration_status()'.
     * 
     * @test
     */
    public function testSetRegistrationStatus(): void
    {
        set_registration_status(true);
        $this->assertEquals(false, $_SESSION['registration']['disabled']);
        set_registration_status(false);
        $this->assertEquals(true, $_SESSION['registration']['disabled']);

        // clean up
        unset($_SESSION['registration']);
    }

    /**
     * Test the admin function 'get_results_as_array()'.
     * 
     * @test
     */
    public function testGetResultsAsArray(): void
    {
        $expected = array(
            array(
                "wwu_mail", "user_name", "difficulty",
                "reflective_xss", "stored_xss", "sqli", "csrf",
                "csrf_referrer_match", "reflective_xss_solution",
                "stored_xss_solution", "sqli_solution", "csrf_solution",
                "csrf_referrer", "csrf_msg"
            ),
            array(
                "user1@test.fake", "testUser1", "normal",
                0, 0, 0, 0, 0, "-", "-", "-", "-", "-", "-"
            ),
            array(
                "user2@test.fake", "testUser2", "normal", 1, 1, 1, 1, 0, "test", "test",
                "test", "-", "-", "-"
            )
        );
        $result = get_results_as_array();
        $this->assertEquals($expected, $result, "Result array does not match!");
    }

    /**
     * Test the admin function 'get_challenge_status()'.
     * 
     * @test
     */
    public function testGetChallengeStatus(): void
    {
        // nothing solved
        $result1 = get_challenge_status("testUser1");
        $this->assertEquals([0, 0, 0, 0, 0], $result1, "Nothing solved failed!");

        // everything solved
        $result2 = get_challenge_status("testUser2");
        $this->assertEquals([1, 1, 1, 1, 0], $result2, "All solved failed!");
    }

    /**
     * Test the admin function 'set_blocked_usernames()'.
     * 
     * @test
     */
    public function testSetBlockedUsernames(): void
    {
        set_blocked_usernames("test1, test2");
        $this->assertEquals("test1, test2", $_SESSION['usernames']['deny_list']);

        // clean up
        unset($_SESSION['usernames']);
    }

    /**
     * Test the admin function 'set_allowed_domains()'.
     * 
     * @test
     */
    public function testSetAllowedDomains(): void
    {
        set_allowed_domains("test1, test2");
        $this->assertEquals("test1, test2", $_SESSION['domains']['allow_list']);

        // clean up
        unset($_SESSION['domains']);
    }

    /**
     * Test the admin function 'set_badge_link()'.
     * 
     * @test
     */
    public function testSetBadgeLink(): void
    {
        set_badge_link("reflective", "test.domain");
        $this->assertEquals(
            "test.domain",
            $_SESSION['badge_links']['reflective']
        );
        set_badge_link("sqli", "test2.domain");
        $this->assertEquals(
            "test2.domain",
            $_SESSION['badge_links']['sqli']
        );

        // clean up
        unset($_SESSION['badge_links']);
    }

    /**
     * Test the admin function 'show_challenge_solutions()'.
     * 
     * @test
     */
    public function testShowChallengeSolutions(): void
    {
        show_challenge_solutions();
        $this->expectOutputRegEx("/"
            . "\<tr\>\<td\>testUser1\<\/td\>\<td\>-\<\/td\>\<td\>-\<\/td\>\<"
            . "td\>-\<\/td\>\<td\>-\<\/td\>\<\/tr\>\<tr\>\<td\>testUser2\<\/"
            . "td\>\<td\>test\<\/td\>\<td\>test\<\/td\>\<td\>test\<\/td\>"
            . "\<td\>-\<\/td\>\<\/tr\>/");
    }
}
