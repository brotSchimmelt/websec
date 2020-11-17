<?php

namespace test\error;

if (session_status() == PHP_SESSION_NONE) {
    // session has not started
    session_start();
}

use PHPUnit\Framework\TestCase;

// load configurations and functions to test
require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . ERROR_HANDLING); // Error functions

/**
 * Test class for the error functions.
 */
final class ErrorFunctionsTest extends TestCase
{
    /*
    * Tests and data provider for the error functions.
    */


    /**
     * Generates data for the 'testFormatMsg()' method.
     */
    public function providerTestFormatMsg()
    {
        return [
            "Info message" => array("info", "This is a info message"),
            "Success message" => array("success", "This is a success message"),
            "Error message" => array("error", "This is a error message"),
            "Empty message type" => array("", "This is a empty message"),
            "Empty message" => array("", "")
        ];
    }

    /**
     * Test the error function 'format_msg()'.
     * 
     * @test
     * @dataProvider providerTestFormatMsg
     */
    public function testFormatMsg($format, $message): void
    {
        // build string to compare
        $format = (empty($format)) ? "info" : $format;
        $rawString =
            '<div class="alert alert-%s shadow" role="alert">%s</div>';
        $expectedString = sprintf(
            $rawString,
            ($format == "error") ? "danger" : $format,
            $message
        );

        // compare output string with expected string
        $this->expectOutputString($expectedString);

        // generate output
        echo format_msg($message, $format);
    }

    /**
     * Generates data for the 'testGetErrorMessage()' method.
     */
    public function providerTestGetErrorMessage()
    {
        return [
            "SQL error" =>
            array("sqlError", "0", "Oh no! There was an error in the database"),
            "Wrong credentials" =>
            array("wrongCredentials", "0", "Wrong credentials"),
            "Internal Error" =>
            array("internalError", "0", "Oh no! There was an internal error"),
            "Invalid name and mail" =>
            array("invalidNameAndMail", "0", "your user name \<b\>and\<\/b\>"),
            "Invalid username" =>
            array("invalidUsername", "0", "It seems like your user name does"),
            "Invalid mail format" =>
            array("invalidMailFormat", "0", "you are not using a valid e-mail"),
            "Invalid mail address" =>
            array("invalidMail", "0", "not using your WWU e-mail account"),
            "Invalid password" =>
            array("invalidPassword", "0", "does not fulfill the requirements"),
            "Password mismatch" =>
            array("passwordMismatch", "0", "Your password does not match"),
            "Name error" =>
            array("nameError", "0", "Please use a different user name!"),
            "Mail already taken" =>
            array("mailTaken", "0", "e-mail address is already taken"),
            "Double entry" =>
            array("doubleEntry", "0", "state of the database is corrupted"),
            "Invalid token" =>
            array("invalidToken", "0", "like your reset link is not working"),
            "Missing token" =>
            array("missingToken", "0", "your reset link does not contain"),
            "Default message" =>
            array("default", "0", "An unknown \<b\>error\<\/b\> occurred")
        ];
    }

    /**
     * Test the error function 'get_error_msg()'.
     * 
     * @test
     * @dataProvider providerTestGetErrorMessage
     */
    public function testGetErrorMessage($error, $errorCode, $expectedMsg): void
    {
        // compare output string with expected string
        $this->expectOutputRegEx("/" . $expectedMsg . "/");

        // generate output
        echo get_error_msg($error, $errorCode);
    }

    /**
     * Generates data for the 'testGetSuccessMessage()' method.
     */
    public function providerTestGetSuccessMessage()
    {
        return [
            "Logout success" =>
            array("logout", "You were successfully \<b\>logged out\<\/b\>!"),
            "Signup success" =>
            array("signup", "You successfully signed up. Try the "),
            "Reset password success" =>
            array("resetPwd", "password was successfully \<b\>reset\<\/b\>"),
            "Request processed success" =>
            array("requestProcessed", "If your e-mail address is linked to an"),
            "Password changed success" =>
            array("pwdChanged", " was successfully \<b\>changed\<\/b\>!"),
            "Default message" =>
            array("default", "Your operation was successful!")
        ];
    }

    /**
     * Test the error function 'get_success_msg()'.
     * 
     * @test
     * @dataProvider providerTestGetSuccessMessage
     */
    public function testGetSuccessMessage($success, $expectedMsg): void
    {
        // compare output string with expected string
        $this->expectOutputRegEx("/" . $expectedMsg . "/");

        // generate output
        echo get_success_msg($success);
    }

    /**
     * Generates data for the 'testGetMessage()' method.
     */
    public function providerTestGetMessage()
    {
        return [
            "Get variable not set and empty" =>
            array("", "", ""),
            "Get variable set and empty" =>
            array("success", "", "")
        ];
    }

    /**
     * Test the error function 'get_message()'.
     * 
     * @test
     * @dataProvider providerTestGetMessage
     */
    public function testGetMessage($getVarName, $getVarVal, $expectedMsg): void
    {
        // initialize mock array for GET variables
        $_GET = [];

        // set GET variables
        if (!empty($getVarName)) {
            $_GET[$getVarName] = $getVarVal;
        }

        // compare output string with expected string
        $this->expectOutputString("");

        // generate output
        echo get_message();

        // clean up
        unset($_GET);
    }
}
