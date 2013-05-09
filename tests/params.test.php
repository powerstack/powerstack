<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

class ParamsTest extends PHPUnit_Framework_TestCase {
    /**
    * Test params class
    */
    public function testParams() {
        $_GET = array(
            'test' => 'hi',
            'hello' => 'world',
        );

        $_POST = array(
            'username' => 'test',
            'password' => 'test',
        );

        $params = new Powerstack\Core\Params();
        $this->assertEquals('hi', $params->test);
        $this->assertEquals('world', $params->hello);
        $this->assertEquals('test', $params->username);
        $this->assertEquals('test', $params->password);
    }
}
?>
