<?php

namespace test\shop;

use PHPUnit\Framework\TestCase;
use PDO;
use PDOException;
use Exception;

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
require_once(dirname(__FILE__) . FUNC_SHOP); // shop functions
require_once(dirname(__FILE__) . TES . "shop_mocked_functions.php");
require_once(dirname(__FILE__) . TES . "SetupHelper.php");


/**
 * Test class for the shop functions.
 */
final class ShopFunctionsTest extends TestCase
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
        SetupHelper::insertUser("testUser1", "user1@test.fake", "hash");
        SetupHelper::insertUser(
            "testUser2",
            "user2@test.fake",
            "hash",
            $unlocked = 1
        );
        SetupHelper::insertProduct("testUser1", "1", "41");
        SetupHelper::insertProduct("testUser1", "2", "1");
        SetupHelper::insertProduct("testUser2", "1", "4");
        SetupHelper::insertProduct("testUser2", "3", "6");
        SetupHelper::insertSolutions("testUser3", "get_test", "-", "-");

        // save all test user names in SESSION array
        $_SESSION['shopTestUser'] = ["testUser1", "testUser2", "testUser3"];
    }

    /**
     * This method is called after the last test of this test class is run.
     */
    public static function tearDownAfterClass(): void
    {
        // remove test users
        foreach ($_SESSION['shopTestUser'] as $user) {
            SetupHelper::deleteDbEntries($user);
        }

        // empty SESSION array
        unset($_SESSION['shopTestUser']);
        unset($_SESSION['shopNumOfStudents']);
    }


    /*
    * Tests and data provider for the shop functions.
    */


    /**
     * Test the shop function 'get_shop_db()'.
     * 
     * @test
     */
    public function testGetShopDbConnection(): void
    {
        // test if a PDO connection can be established
        $conn = get_shop_db();
        $connTest = ($conn instanceof PDO) ? true : false;
        $this->assertEquals(true, $connTest);
    }

    /**
     * Test the shop function 'get_number_of_cart_items()'.
     * 
     * @test
     */
    public function testGetNumberOfCartItems(): void
    {
        $_SESSION['userName'] = "testUser1";
        $result1 = get_number_of_cart_items();
        $this->assertEquals(42, $result1);
        $_SESSION['userName'] = "testUser2";
        $result2 = get_number_of_cart_items("testUser2");
        $this->assertEquals(10, $result2);

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Test the shop function 'is_product_in_cart()'.
     * 
     * @test
     */
    public function testIsProductInCart(): void
    {
        $_SESSION['userName'] = "testUser1";

        $result1 = is_product_in_cart(1);
        $this->assertTrue($result1);

        $result2 = is_product_in_cart(4);
        $this->assertFalse($result2);

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Generates data for the 'testAddProductToCart()' method.
     */
    public function providerAddProductToCart()
    {
        return [
            "Add 32 times product 4" => array("testUser1", 4, 32, 10),
            "Add -1 times product 5" => array("testUser1", 5, -1, 1),
            "Add 100 times product 5" => array("testUser1", 2, 99, 100),
            "Add 1025 times product 1" => array("testUser1", 1, 1025, 1024)
        ];
    }

    /**
     * Test the shop function 'add_product_to_cart()'.
     * 
     * @test
     * @dataProvider providerAddProductToCart
     */
    public function testAddProductToCart($user, $input1, $input2, $expected): void
    {
        $_SESSION['userName'] = $user;
        add_product_to_cart($input1, $input2);

        $sql = "SELECT quantity FROM cart WHERE user_name=:user AND "
            . "prod_id=:prod";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([
            'user' => $user,
            'prod' => $input1
        ]);
        $result = $stmt->fetch();

        $this->assertEquals($expected, $result['quantity']);

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Test the shop function 'show_cart_content()'.
     * 
     * @test
     */
    public function testShowCartContent(): void
    {
        $_SESSION['userName'] = "testUser1";

        show_cart_content();
        $this->expectOutputRegex("/Mug/");

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Test the shop function 'is_cart_empty()'.
     * 
     * @test
     */
    public function testIsCartEmpty(): void
    {

        // full cart
        $_SESSION['userName'] = "testUser1";
        $this->assertTrue(is_cart_empty());

        // empty cart
        $_SESSION['userName'] = "testUser3";
        $this->assertFalse(is_cart_empty());

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Test the shop function 'empty_cart()'.
     * 
     * @test
     */
    public function testEmptyCart(): void
    {
        $user = "testUser1";

        // empty cart
        empty_cart($user);
        $_SESSION['userName'] = $user;
        $this->assertFalse(is_cart_empty());

        // clean up
        unset($_SESSION['userName']);
    }

    /**
     * Test the shop function 'get_product_data()'.
     * 
     * @test
     */
    public function testGetProductData(): void
    {
        $result = get_product_data(2);
        $expected = "WebSec Banana Slicer S";
        $this->assertEquals($expected, $result['prod_title']);
    }

    /**
     * Test the shop function 'save_challenge_solution()'.
     * 
     * @test
     */
    public function testSaveChallengeSolution(): void
    {
        // save solution
        $user = "testUser3";
        $expected = "set_test";
        save_challenge_solution($user, $expected, "sqli");

        // get solution from the database
        $sql = "SELECT sqli FROM `challenge_solutions` WHERE "
            . "`user_name`=?";
        $stmt = get_shop_db()->prepare($sql);
        $stmt->execute([$user]);
        $result = $stmt->fetch();

        // compare results
        $this->assertEquals($expected, $result['sqli']);
    }

    /**
     * Test the shop function 'get_challenge_solution()'.
     * 
     * @test
     */
    public function testGetChallengeSolution(): void
    {
        $result = get_challenge_solution("testUser3", "reflective_xss");
        $expected = "get_test";
        $this->assertEquals($expected, $result);
    }
}
