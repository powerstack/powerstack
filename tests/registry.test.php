<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

class RegistryTest extends PHPUnit_Framework_TestCase {
    /**
    * Test registry class
    */
    public function testRegistry() {
        $registry = Powerstack\Core\Registry::getInstance();
        $registry->set('test', 'hello');
        $test = $registry->get('test');
        $exists = $registry->exists('test');
        $delete = $registry->delete('test');

        $this->assertEquals('hello', $test);
        $this->assertTrue($exists);
        $this->assertTrue($delete);

        $exists = $registry->exists('test');
        $this->assertFalse($exists);
    }
}
?>
