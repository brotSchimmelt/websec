<?php
session_start(); // to store global values

use PHPUnit\Framework\TestCase;

// load configurations and functions to test
require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . CONF_DB_LOGIN); // DB credentials
require_once(dirname(__FILE__) . FUNC_LOGIN); // login functions
// require_once(dirname(__FILE__) . FUNC_BASE); // basic functions
// require_once(dirname(__FILE__) . ERROR_HANDLING); // error functions


final class LoginFunctionsTest extends TestCase
{

    /**
     * Set up the database for the test runs.
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
        array_push($_SESSION['testUsers'], "elliot");

        // add an entry to the POST array
        $_POST['test_var_set'] = "test";
        $_POST['test_var_empty'] = "";
        // add an entry to the GET array
        $_GET['test_var_set'] = "test";
        $_GET['test_var_empty'] = "";
    }

    /**
     * Delete all test users from the database after test completion.
     */
    public static function tearDownAfterClass(): void
    {

        // delete all test users to the login database
        foreach ($_SESSION['testUsers'] as $e) {
            $deleteSQL = "DELETE FROM `users` WHERE `user_name`='" . $e . "'";
            get_login_db()->query($deleteSQL);
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
            "No user" => array(0, 302),
            "One user" => array(1, true),
            "Duplicate user" => array(2, 302)
        ];
    }

    /**
     * Test the login function 'check_user_exists()'.
     * 
     * @test
     * @dataProvider providertestCheckUserExists
     * @runInSeparateProcess
     */
    public function testCheckUserExists($input, $expected): void
    {

        if ($input === 1) {
            // check if user was found
            $result = check_user_exists($input);
            $this->assertEquals($expected, $result);
        } else {
            // get http response code for error cases
            $returnVal = check_user_exists($input);
            $this->assertEquals($expected, http_response_code());
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
            array($pwdWrong, $hashCorrect, 302, 1),
            "Correct password wrong hash" =>
            array($pwdCorrect, $hashWrong, 302, 1)
        ];
    }


    /**
     * Test the login function 'verify_pwd()'.
     * 
     * @test
     * @dataProvider providerTestVerifyPassword
     * @runInSeparateProcess
     */
    public function testVerifyPassword($input1, $input2, $expected, $flag): void
    {

        if ($flag === 0) {

            // check if correct login credentials are detected
            $result = verify_pwd($input1, $input2);
            $this->assertEquals($expected, $result);
        } else {

            // check if wrong credentials lead to redirect
            $returnVal = verify_pwd($input1, $input2);
            $this->assertEquals($expected, http_response_code());
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
        // to run this cases, hard code the allowed domains
        return [
            // "Correct WWU mail" =>
            // array("user@uni-muenster.de", true, 0),
            // "Correct WI WWU mail" =>
            // array("user@wi.uni-muenster.de", true, 0),
            // "Wrong domain" =>
            // array("user@hotmail.com", false, 0),
            "No mail address format" =>
            array("norealmail", 302, 1)
        ];
    }

    /**
     * Test the login function 'validate_mail()'.
     * 
     * @test
     * @dataProvider providerTestValidateMail
     * @runInSeparateProcess
     */
    public function testValidateMail($input, $expected, $flag): void
    {
        if ($flag === 0) {

            $result = validate_mail($input);
            $this->assertEquals($expected, $result);
        } else {

            $returnVal = validate_mail($input);
            $this->assertEquals($expected, http_response_code());
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
     */
    public function testHashUserPassword(): void
    {
        $result = hash_user_pwd("12345678");
        $this->assertEquals(!empty($result), true);
    }
}
