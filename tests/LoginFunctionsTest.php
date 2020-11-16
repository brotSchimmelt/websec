<?php

namespace test\login;

session_start(); // to store global values

use Exception;
use PHPUnit\Framework\TestCase;
use PDO;

// load configurations and functions to test
require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . CONF_DB_LOGIN); // DB credentials
require_once(dirname(__FILE__) . CONF_DB_SHOP); // DB credentials
require_once(dirname(__FILE__) . FUNC_LOGIN); // login functions
require_once(dirname(__FILE__) . TES . "login_mocked_functions.php");


/**
 * Move to README when done.
 * 
 * NOTES:
 * Every test with header() must be run as a separat process
 * The same applies to tests that include SQL queries
 * 
 * --> WHY:
 * The fixtures are set before every test that runs in a separat process.
 * And also deleted afterwards. So, if a test (A) runs normally (in the main process)
 * after a test (B) that ran in a separat process, the fixtures will be deleted 
 * after B and NOT set up again before A runs. Solution: A needs to run in a 
 * separat process as well.
 */


/**
 * Test Class for the login functions.
 */
final class LoginFunctionsTest extends TestCase
{
    /*
    * Set up and tear down fixtures.
    */

    public static function setUpBeforeClass(): void
    {
        // initialize test arrays
        $_POST = [];
        $_GET = [];
        $_COOKIE = [];
        $_SESSION['testUsers'] = [];

        // add test user to the 'users' database
        // 'elliot' can not be used as a user name for normal users, so there
        // are no conflicts with production data
        $insertUser = "INSERT IGNORE INTO users (user_id, user_name, "
            . "user_wwu_email, user_pwd_hash, is_unlocked, is_admin, "
            . "timestamp, last_login) VALUE (NULL, :user, :mail, :pwd_hash, "
            . "'0', '0', :timestamp, NULL)";
        $insertChallenge = "INSERT IGNORE INTO challengeStatus VALUE (DEFAULT,"
            . ":user,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,DEFAULT,"
            . "DEFAULT,DEFAULT,DEFAULT)";
        $insertFakeCookie = "INSERT IGNORE INTO fakeCookie VALUE (DEFAULT, "
            . ":user,DEFAULT,DEFAULT,DEFAULT)";
        $insertRequest = "INSERT IGNORE INTO resetPwd VALUE (DEFAULT, :mail,"
            . "'abc', 'abc', 0)";
        get_login_db()->prepare($insertUser)->execute([
            'user' =>
            "elliot",
            'mail' =>
            "fake@mail.example",
            'pwd_hash' =>
            // 'fakehash' is the corresponding password
            '$2y$13$iPY//1niofP6MBJooRBWN.OMP1RgUaFIQZZIojUv2r8MQ28GVPL06',
            'timestamp' =>
            date("Y-m-d H:i:s")
        ]);
        get_login_db()->prepare($insertUser)->execute([
            'user' =>
            "testUser",
            'mail' =>
            "test@uni-muenster.de",
            'pwd_hash' =>
            // 'fakehash' is the corresponding password
            '$2y$13$iPY//1niofP6MBJooRBWN.OMP1RgUaFIQZZIojUv2r8MQ28GVPL06',
            'timestamp' =>
            date("Y-m-d H:i:s")
        ]);
        get_login_db()->prepare($insertChallenge)->execute([
            'user' =>
            "testUser",
        ]);
        get_login_db()->prepare($insertFakeCookie)->execute([
            'user' =>
            "testUser",
        ]);
        get_login_db()->prepare($insertRequest)->execute([
            'mail' =>
            "test@test.test"
        ]);

        array_push($_SESSION['testUsers'], "elliot");
        array_push($_SESSION['testUsers'], "testUser");
        array_push($_SESSION['testUsers'], "newUser");
        array_push($_SESSION['testUsers'], "test@test.test");
        array_push($_SESSION['testUsers'], "test@uni-muenster.de");

        // add an entry to the POST array
        $_POST['test_var_set'] = "test";
        $_POST['test_var_empty'] = "";
        // add an entry to the GET array
        $_GET['test_var_set'] = "test";
        $_GET['test_var_empty'] = "";
    }

    public static function tearDownAfterClass(): void
    {
        // delete all test users to the login database
        foreach ($_SESSION['testUsers'] as $e) {
            $deleteUser = "DELETE FROM `users` WHERE `user_name`='" . $e . "'";
            $deleteChallenge = "DELETE FROM `challengeStatus` WHERE "
                . "`user_name`='" . $e . "'";
            $deleteCookie = "DELETE FROM `fakeCookie` WHERE `user_name`='"
                . $e . "'";
            $deleteSolutions = "DELETE FROM `challenge_solutions` WHERE "
                . "`user_name`='" . $e . "'";
            $deleteRequest = "DELETE FROM `resetPwd` WHERE "
                . "`user_wwu_email`='" . $e . "'";
            get_login_db()->query($deleteUser);
            get_login_db()->query($deleteChallenge);
            get_login_db()->query($deleteCookie);
            get_login_db()->query($deleteRequest);
            get_shop_db()->query($deleteSolutions);
        }

        // remove the Session variable that was used to store all changes to the
        // login database
        unset($_SESSION['testUsers']);
        session_destroy();

        // remove all remaining variables set by the setUp() method
        unset($_GET);
        unset($_POST);
        unset($_COOKIE);
    }


    /*
    * Tests and data provider for the login functions.
    */

    /**
     * Test the login function 'get_login_db()'.
     * 
     * @test
     */
    public function testGetLoginDbConnection(): void
    {

        // test if a PDO connection can be established
        $conn = get_login_db();
        $connTest = ($conn instanceof PDO) ? true : false;
        $this->assertEquals(true, $connTest);

        // check if sql queries and the setUp() method work
        $sql = "SELECT `user_name` FROM `users` WHERE `user_name`='elliot'";
        $stmt = get_login_db()->query($sql);
        $sqlTest = $stmt->fetch();
        $this->assertEquals("elliot", $sqlTest['user_name']);
    }

    /**
     * Generates data for the 'testPostVarSet()' and 'testGetVarSet()' method.
     */
    public function providerPostGetVarSet()
    {
        return [
            "Variable is set" => array("test_var_set", true),
            "Variable is empty" => array("test_var_empty", false),
            "Variable is not set" => array("test_var_unset", false)
        ];
    }

    /**
     * Test the login function 'post_var_set()'.
     * 
     * @test
     * @dataProvider providerPostGetVarSet
     */
    public function testPostVariableCheck($input, $expected): void
    {
        $result = post_var_set($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the login function 'get_var_set()'.
     * 
     * @test
     * @dataProvider providerPostGetVarSet
     */
    public function testGetVariableCheck($input, $expected): void
    {
        $result = post_var_set($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Generates data for the 'testCheckUserExists()' method.
     */
    public function providertestCheckUserExists()
    {
        return [
            "No user" => array(0, false, 302),
            "One user" => array(1, true, 0),
            "Duplicate user" => array(2, false, 302)
        ];
    }

    /**
     * Test the login function 'check_user_exists()'.
     * 
     * @test
     * @dataProvider providertestCheckUserExists
     * @runInSeparateProcess
     */
    public function testCheckUserExists($input, $expected, $code): void
    {

        // check if user was found
        $result = check_user_exists($input);

        if ($code === 0) {
            $this->assertEquals($expected, $result);
        } else {

            // get http response code for error cases
            $this->assertEquals($code, http_response_code());
            $this->assertEquals($expected, $result);
        }
    }

    /**
     * Generates data for the 'testVerifyPassword()' method.
     */
    public function providerTestVerifyPassword()
    {

        $hashCorrect['user_pwd_hash'] = '$2y$13$iPY//1niofP6MBJooRBWN.OMP1RgUa'
            . 'FIQZZIojUv2r8MQ28GVPL06';
        $hashWrong['user_pwd_hash'] = "wrongHash";

        $pwdCorrect = "fakehash";
        $pwdWrong = "wrongPassword";

        return [
            "Correct password and hash" =>
            array($pwdCorrect, $hashCorrect, true, 0),
            "Wrong password correct hash" =>
            array($pwdWrong, $hashCorrect, false, 302),
            "Correct password wrong hash" =>
            array($pwdCorrect, $hashWrong, false, 302)
        ];
    }


    /**
     * Test the login function 'verify_pwd()'.
     * 
     * @test
     * @dataProvider providerTestVerifyPassword
     * @runInSeparateProcess
     */
    public function testVerifyPassword($input1, $input2, $expected, $code): void
    {

        // verify the password
        $result = verify_pwd($input1, $input2);

        if ($code === 0) {
            // check if correct login credentials are detected
            $this->assertEquals($expected, $result);
        } else {

            // check if wrong credentials lead to redirect
            $this->assertEquals($code, http_response_code());
            $this->assertEquals($expected, $result);
        }
    }

    /**
     * Generates data for the 'testVerifyPassword()' method.
     */
    public function providerTestValidateUsername()
    {
        return [
            "Correct user name" =>
            array("ellioT42", true),
            "Too short user name" =>
            array("u", false),
            "Too long user name" =>
            array("loremloremloremlorem12345", false),
            "Lower limit user name length" =>
            array("us", true),
            "Upper limit user name length" =>
            array("loremloremloremlorem1234", true),
            "Empty user name" =>
            array("", false),
            "Not allowed characters" =>
            array("user_+123", false),
            "Non-Latin characters" =>
            array("漂浪的名字", false)
        ];
    }

    /**
     * Test the login function 'validate_username()'.
     * 
     * @test
     * @dataProvider providerTestValidateUsername
     */
    public function testValidateUsername($input, $expected): void
    {
        $result = validate_username($input);
        $this->assertEquals($expected, $result);
    }


    /**
     * Generates data for the 'testValidateMail()' method.
     */
    public function providerTestValidateMail()
    {
        return [
            "Correct WWU mail" =>
            array("user@uni-muenster.de", true, 0),
            "Correct WI WWU mail" =>
            array("user@wi.uni-muenster.de", true, 0),
            "Wrong domain" =>
            array("user@fake.test", false, 0),
            "No mail address format" =>
            array("norealmail", false, 302)
        ];
    }

    /**
     * Test the login function 'validate_mail()'.
     * 
     * Note:
     * Hard code the 'allowed domains' for phpUnit in 'validate_mail()'.
     * See the keyword 'TESTING' in the source code.
     * 
     * @test
     * @dataProvider providerTestValidateMail
     * @runInSeparateProcess
     */
    public function testValidateMail($input, $expected, $code): void
    {

        $result = validate_mail($input);

        if ($code === 0) {
            $this->assertEquals($expected, $result);
        } else {
            $this->assertEquals($expected, $result);
            $this->assertEquals($code, http_response_code());
        }
    }


    /**
     * Generates data for the 'testValidatePassword()' method.
     */
    public function providerTestValidatePassword()
    {
        return [
            "Too short password" => array("1234", false),
            "Empty password" => array("", false),
            "Correct password" => array("12345678", true)
        ];
    }

    /**
     * Test the login function 'validate_pwd()'.
     * 
     * @test
     * @dataProvider providerTestValidatePassword
     */
    public function testValidatePassword($input, $expected): void
    {
        $result = validate_pwd($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the login function 'hash_user_pwd()'.
     * 
     * @test
     */
    public function testHashUserPassword(): void
    {
        $result = hash_user_pwd("password123");
        $this->assertEquals(!empty($result), true);
    }

    /**
     * Generates data for the 'testCheckEntryExits()' method.
     */
    public function providerTestCheckEntryExits()
    {

        $mailSQL = "SELECT 1 FROM `users` WHERE `user_wwu_email` = ?";
        $userSQL = "SELECT 1 FROM `users` WHERE `user_name` = ?";

        return [
            "Forbidden user name" => array("admin", $userSQL, true),
            "Existing user name" => array("elliot", $userSQL, true),
            "Existing mail" => array("fake@mail.example", $mailSQL, true),
            "New mail" => array("test@test.test", $mailSQL, false)
        ];
    }

    /**
     * Test the login function 'check_entry_exists()'.
     * 
     * Note:
     * Hard code the 'fake user' array for phpUnit in 'check_entry_exists()'.
     * See the keyword 'TESTING' in the source code.
     * 
     * @test
     * @dataProvider providerTestCheckEntryExits
     * @runInSeparateProcess
     */
    public function testCheckEntryExits($input1, $input2, $expected): void
    {
        $result = check_entry_exists($input1, $input2);
        $this->assertEquals($expected, $result);
    }


    /**
     * Generates data for the 'testValidateRegistrationInput()' method.
     */
    public function providerValidateRegistrationInput()
    {

        return [
            "Correct input" => array(
                "test",
                "test@uni-muenster.de",
                "password123",
                "password123",
                0, true
            ),
            "Invalid password format" => array(
                "test",
                "test@uni-muenster.de",
                "123",
                "123",
                302, false
            ),
            "Invalid user and mail format" => array(
                "t",
                "test@test.de",
                "password123",
                "password123",
                302, false
            ),
            "Invalid user format" => array(
                "t",
                "test@uni-muenster.de",
                "password123",
                "password123",
                302, false
            ),
            "Invalid mail format" => array(
                "test",
                "test@test.test",
                "password123",
                "password123",
                302, false
            ),
            "Password missmatch" => array(
                "test",
                "test@uni-muenster.de",
                "password123",
                "123password",
                302, false
            ),
            "Empty input" => array(
                "",
                "",
                "",
                "",
                302, false
            )
        ];
    }


    /**
     * Test the login function 'validate_registration_input()'.
     * 
     * Note:
     * Hard code the 'fake user' array for phpUnit in 'check_entry_exists()'.
     * Hard code the 'allowed domains' for phpUnit in 'validate_mail()'.
     * See the keyword 'TESTING' in the source code.
     * 
     * @test
     * @dataProvider providerValidateRegistrationInput
     * @runInSeparateProcess
     */
    public function testValidateRegistrationInput(
        $input1,
        $input2,
        $input3,
        $input4,
        $responseCode,
        $expected
    ): void {

        $result = validate_registration_input($input1, $input2, $input3, $input4);

        if ($responseCode === 0) {
            $this->assertEquals($expected, $result);
        } else {
            $this->assertEquals($expected, $result);
            $this->assertEquals($responseCode, http_response_code());
        }
    }

    /**
     * Test the login function 'do_login()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testDoLogin(): void
    {

        // set valid parameter
        $username = "testUser";
        $mail = "test@uni-muenster.de";
        $adminFlag = "1";
        $unlockedFlag = "0";

        // call do_login() with parameters
        do_login($username, $mail, $adminFlag, $unlockedFlag);

        // test if session was started
        $this->assertEquals(PHP_SESSION_ACTIVE, session_status());

        // test if session variables were set
        $this->assertContains($username, $_SESSION);
        $this->assertContains($mail, $_SESSION);
        $this->assertContains($adminFlag, $_SESSION);
        $this->assertNotContains($unlockedFlag, $_SESSION);
        $this->assertArrayHasKey("fakeCSRFToken", $_SESSION);
        $this->assertContains("elliot", $_SESSION);

        // get last login
        $sql = "SELECT last_login FROM users WHERE user_name = ?";
        $stmt = get_login_db()->prepare($sql);
        $stmt->execute([$username]);
        $result = $stmt->fetch();

        // check if last login is not NULL
        $this->assertNotEquals(NULL, $result['last_login']);
    }

    /**
     * Generates data for the 'testRegistration()' method.
     */
    public function providerTestRegistration()
    {
        return [
            "User name already exists" => array(
                "elliot", "newMail@uni-muenster.de", "password123", false, 302
            ),
            "Mail already exists" => array(
                "newUser", "test@uni-muenster.de", "password123", false, 302
            ),
            "Challenge status and fake cookie present" => array(
                "testUser", "test@uni-muenster.de", "password123", false, 302
            ),
            "Valid registration attempt" => array(
                "newUser", "newMail@uni-muenster.de", "password123", true, 302
            )
        ];
    }

    /**
     * Test the login function 'try_registration()'.
     * Test implicitly the 'do_registration()' function.
     * 
     * @test
     * @dataProvider providerTestRegistration
     * @runInSeparateProcess
     */
    public function testRegistration(
        $input1,
        $input2,
        $input3,
        $expected,
        $code
    ): void {
        $result = try_registration($input1, $input2, $input3);
        $this->assertEquals($expected, $result);
        $this->assertEquals($code, http_response_code());
    }


    /**
     * Generates data for the 'testGetRandomToken()' method.
     */
    public function providerTestGetRandomToken()
    {
        return [
            "Token with 42 characters" => array(42, 42),
            "Token with -1 characters" => array(
                -1,
                "Code Error: Token length cannot be 0 or negative!"
            )
        ];
    }

    /**
     * Test the login function 'get_random_token()'.
     * 
     * @test
     * @dataProvider providerTestGetRandomToken
     */
    public function testGetRandomToken($input, $expected): void
    {
        if ($input > 0) {
            $tokenLength = strlen(get_random_token($input));
            $this->assertEquals($expected, $tokenLength);
        } else {
            try {
                $token = get_random_token(-1);
            } catch (Exception $e) {
                $error = $e->getMessage();
            }
            $this->assertEquals($expected, $error);
        }
    }
    /**
     * Test the login function 'send_pwd_reset_mail()'.
     * 
     * @test
     */
    public function testSendPwdResetMail(): void
    {

        $mail = "This is a test mail.";
        $resetUrl = "index.php";
        $result = send_pwd_reset_mail($mail, $resetUrl);
        $this->assertEquals(true, $result);
    }

    /**
     * Generates data for the 'testCheckPwdRequestStatus()' method.
     */
    public function providerTestCheckPwdRequestStatus()
    {
        return [
            "Empty input" => array("", false),
            "Mail address exists" => array("test@test.test", true),
            "Mail address does not exits" => array("fake@test.test", false)
        ];
    }

    /**
     * Test the login function 'check_pwd_request_status()'.
     * 
     * @test
     * @dataProvider providerTestCheckPwdRequestStatus
     * @runInSeparateProcess
     */
    public function testCheckPwdRequestStatus($input, $expected, $flag = 0): void
    {
        $result = check_pwd_request_status($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the login function 'delete_pwd_request()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testDeletePwdRequest(): void
    {

        // insert request to DB
        $sql = "INSERT IGNORE INTO resetPwd VALUE (DEFAULT, "
            . "'test@delete.request','selector_test','validator_test','42')";
        get_login_db()->query($sql);

        // delete request from DB
        $mail = "test@delete.request";
        delete_pwd_request($mail);

        // test result
        $sql = "SELECT 1 FROM resetPwd WHERE user_wwu_email='" . $mail . "'";
        $stmt = get_login_db()->query($sql);
        $result = $stmt->fetch();
        $this->assertEquals(false, $result);
    }

    /**
     * Test the login function 'add_pwd_request()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testAddPwdRequest(): void
    {

        // insert request to DB
        $mail = "test@add.request";
        add_pwd_request($mail, "add", "test", "3.1415");

        // test result
        $sql = "SELECT 1 FROM resetPwd WHERE user_wwu_email='" . $mail . "'";
        $stmt = get_login_db()->query($sql);
        $result = $stmt->fetch();
        $this->assertNotEquals(false, $result);

        // delete request from DB
        $sql = "DELETE FROM `resetPwd` WHERE `user_wwu_email`='" . $mail . "'";
        get_login_db()->query($sql);
    }


    /**
     * Generates data for the 'testDoPwdReset()' method.
     */
    public function providerTestDoPwdReset()
    {
        return [
            "Mail address exists" => array("fake@mail.example", true, 302),
            "Mail address does not exits" => array("non@exist.ing", false, 302),
            "Request already exists" => array("test@uni-muenster.de", true, 302)
        ];
    }

    /**
     * Test the login function 'do_pwd_reset()'.
     * 
     * @test
     * @dataProvider providerTestDoPwdReset
     * @runInSeparateProcess
     */
    public function testDoPwdReset($input, $expected, $code): void
    {
        $result = do_pwd_reset($input);
        $this->assertEquals($expected, $result, "Reset failed!");
        $this->assertEquals($code, http_response_code(), "No redirect!");

        // clean up DB
        $sql = "DELETE FROM `resetPwd` WHERE `user_wwu_email`='" . $input . "'";
        get_login_db()->query($sql);
    }


    /**
     * Generates data for the 'testValidateNewPwd()' method.
     */
    public function providerTestValidateNewPwd()
    {
        return [
            "Empty password" => array("", "password123", false),
            "Empty password confirm" => array("password123", "", false),
            "Too short password" => array("test", "test", false),
            "Valid password" => array("password123", "password123", true)
        ];
    }

    /**
     * Test the login function 'validate_new_pwd()'.
     * 
     * @test
     * @dataProvider providerTestValidateNewPwd
     */
    public function testValidateNewPwd($input1, $input2, $expected): void
    {
        $result = validate_new_pwd($input1, $input2);
        $this->assertEquals($expected, $result);
    }

    /**
     * Generates data for the 'testVerifyToken()' method.
     */
    public function providerTestVerifyToken()
    {
        // set up test requests
        $mail = "mailAdress@test.manual";
        $mail2 = "mailAdress2@test.manual";
        $mail3 = "mailAdress3@test.manual";
        $selector = get_random_token(16);
        $selector2 = get_random_token(16);
        $selector3 = get_random_token(16);
        $validator = get_random_token(32);
        $expires = date('U') + 1200;
        $expires2 = date('U') - 42;

        // check if an old request exists and delete it
        if (check_pwd_request_status($mail)) {
            delete_pwd_request($mail);
        }
        if (check_pwd_request_status($mail2)) {
            delete_pwd_request($mail2);
        }
        if (check_pwd_request_status($mail3)) {
            delete_pwd_request($mail3);
        }

        // add request to database
        add_pwd_request($mail, $selector, $validator, $expires);
        add_pwd_request($mail2, $selector2, $validator, $expires2);
        add_pwd_request($mail3, $selector3, $validator, $expires);
        add_pwd_request($mail3, $selector3, $validator, $expires);

        return [
            "Selector not hexadecimal" => array("qwerty", $validator, false, 0),
            "Both token not hexadecimal" => array("qwerty", "qwerty", false, 0),
            "Selector empty" => array("", $validator, false, 0),
            "Both token empty" => array("", "", false, 0),
            "Expired Request" => array($selector2, $validator, false, 0, $mail2),
            "Double Request" => array($selector3, $validator, false, 302, $mail3),
            "Invalid token" => array("56789f", "0123f", false, 0),
            "Validator mismatch" => array("abcdef", "foobar", false, 0),
            "Correct request tokens" => array(
                $selector, $validator, true, 0, $mail
            )
        ];
    }

    /**
     * Test the login function 'verify_token()'.
     * 
     * @test
     * @dataProvider providerTestVerifyToken
     * @runInSeparateProcess
     */
    public function testVerifyToken(
        $input1,
        $input2,
        $expected,
        $code,
        $mail = "1"
    ): void {

        $result = verify_token($input1, $input2, "url");
        $this->assertEquals($expected, $result, "Result failed!");

        if ($code > 0) {
            $this->assertEquals($code, http_response_code(), "Redirect failed!");
        }

        // remove test requests from the database
        if ($mail != "1") {
            delete_pwd_request($mail);
        }
    }

    /**
     * Generates data for the 'testGetUserMail()' method.
     */
    public function providerTestGetUserMail()
    {
        return [
            "Invalid selector" => array("123", false),
            "Valid selector" => array("abc", "test@test.test")
        ];
    }

    /**
     * Test the login function 'get_user_mail()'.
     * 
     * @test
     * @dataProvider providerTestGetUserMail
     * @runInSeparateProcess
     */
    public function testGetUserMail($input, $expected)
    {
        $result = get_user_mail($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Generates data for the 'testSetNewPwd()' method.
     */
    public function providerTestSetNewPwd()
    {
        // set up test requests
        $mail = "utterNonsense";
        $selector = get_random_token(16);
        $validator = get_random_token(32);
        $expires = date('U') + 1200;

        // check if an old request exists and delete it
        if (check_pwd_request_status($mail)) {
            delete_pwd_request($mail);
        }

        // add request to database
        add_pwd_request($mail, $selector, $validator, $expires);

        return [
            "Too short password" =>
            array(
                $selector, $validator, "12", "12", "url", false, 302
            ),
            "Empty password" =>
            array(
                $selector, $validator, "", "", "url", false, 302
            ),
            "Password mismatch" =>
            array(
                $selector,
                $validator, "password123", "password321", "url", false, 302
            ),
            "Invalid token" =>
            array(
                "invalid",
                $validator,
                "password123", "password123", "url", false, 302
            ),
            "Valid Request" =>
            array(
                $selector,
                $validator,
                "password123", "password123", "url", true, 302, $mail
            ),
        ];
    }

    /**
     * Test the login function 'set_new_pwd()'.
     * 
     * @test
     * @dataProvider providerTestSetNewPwd
     * @runInSeparateProcess
     */
    public function testSetNewPwd(
        $input1,
        $input2,
        $input3,
        $input4,
        $input5,
        $expected,
        $code,
        $mail = "1"
    ) {

        $result = set_new_pwd($input1, $input2, $input3, $input4, $input5);
        $this->assertEquals($expected, $result, "Result failed!");
        $this->assertEquals($code, http_response_code(), "Redirect failed!");

        // remove test requests from the database
        if ($mail != "1") {
            delete_pwd_request($mail);
        }
    }

    /**
     * Generates data for the 'testChangePassword()' method.
     */
    public function providerTestChangePassword()
    {
        return [
            "Too short password" =>
            array("elliot", "fakehash", "12", "12", false, 302),
            "Empty new password" =>
            array("elliot", "fakehash", "", "", false, 302),
            "New password mismatch" =>
            array("elliot", "fakehash", "password1", "password321", false, 302),
            "Old password mismatch" =>
            array("elliot", "invalid", "password1", "password1", false, 302),
            "Non existing user" =>
            array("no", "fakehash", "password1", "password1", false, 302),
            "Valid request" =>
            array("elliot", "fakehash", "password1", "password1", true, 302),
        ];
    }

    /**
     * Test the login function 'change_password()'.
     * 
     * @test
     * @dataProvider providerTestChangePassword
     * @runInSeparateProcess
     */
    public function testChangePassword(
        $input1,
        $input2,
        $input3,
        $input4,
        $expected,
        $code
    ) {
        $result = change_password($input1, $input2, $input3, $input4);
        $this->assertEquals($expected, $result);
        $this->assertEquals($code, http_response_code());
    }

    /**
     * Test the login function 'update_last_login()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testUpdateLastLogin()
    {
        $sql = "SELECT last_login FROM users WHERE user_name='elliot';";
        $stmt = get_login_db()->query($sql);
        $resultBefore = $stmt->fetch();

        update_last_login("elliot");

        $sql = "SELECT last_login FROM users WHERE user_name='elliot';";
        $stmt = get_login_db()->query($sql);
        $resultAfter = $stmt->fetch();

        $this->assertNotEquals(
            $resultBefore['last_login'],
            $resultAfter['last_login']
        );
    }

    /**
     * Generates data for the 'testSetChangePwdReminder()' method.
     */
    public function providerTestSetChangePwdReminder()
    {
        return [
            "Admin user" => array("administrator", true),
            "Non-admin user" => array("elliot", false),
        ];
    }

    /**
     * Test the login function 'set_change_pwd_reminder()'.
     * 
     * @test
     * @dataProvider providerTestSetChangePwdReminder
     */
    public function testSetChangePwdReminder($input, $expected)
    {
        $result = set_change_pwd_reminder($input);
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the login function 'get_ast_login()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testGetLastLogin()
    {
        $result = get_last_login("elliot");
        $this->assertEquals(true, is_null($result));
    }

    /**
     * Test the login function 'set_user_cookies()'.
     * 
     * @test
     * @runInSeparateProcess
     */
    public function testSetUserCookies()
    {
        set_user_cookies("testUser");
        $_COOKIE['XSS_YOUR_SESSION'] = $_SESSION['reflectiveXSS'];

        $this->assertEquals(true, isset($_SESSION['reflectiveXSS']));
        $this->assertEquals(true, !empty($_SESSION['reflectiveXSS']));
        $this->assertEquals(true, isset($_SESSION['storedXSS']));
        $this->assertEquals(true, !empty($_SESSION['storedXSS']));
        $this->assertArrayHasKey("XSS_YOUR_SESSION", $_COOKIE);
        $this->assertEquals(
            $_SESSION['reflectiveXSS'],
            $_COOKIE['XSS_YOUR_SESSION']
        );
    }
}
