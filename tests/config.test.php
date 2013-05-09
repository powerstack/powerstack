<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

class ConfigTest extends PHPUnit_Framework_TestCase {
    /*
    * Test that config class is working properly.
    */
    public function testConfig() {
        $config = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $yaml = Spyc::YAMLLoad(dirname(__FILE__) . '/config.yml');

        $this->assertEquals($config->application->name, $yaml['application']['name']);
        $this->assertEquals($config->template->engine, $yaml['template']['engine']);
    }
}
?>
