<?php
// require_once("../config/config.php");
// require_once(FUNC_LOGIN);

use PHPUnit\Framework\TestCase;

require_once(dirname(__FILE__) . "/../config/config.php");
require_once(dirname(__FILE__) . "/../src/login_functions.php");

final class LoginFunctionsTest extends TestCase
{
    // public function testGetLoginDB(): void
    // {
    // }

    // /**
    //  * @dataProvider additionProvider
    //  */
    public function testAdd()
    {

        $_POST = array('1' => 'eins');

        $expected = "eins";
        $this->assertEquals($expected, $_POST['1']);
    }

    // public function additionProvider()
    // {
    //     return [
    //         'adding zeros'  => [0, 0, 0],
    //         'zero plus one' => [0, 1, 1],
    //         'one plus zero' => [1, 0, 1],
    //         'one plus one'  => [1, 1, 3]
    //     ];
    // }
}
