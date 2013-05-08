<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

class FilesystemTest extends PHPUnit_Framework_TestCase {
    /**
    * Test making a directory
    */
    public function testMkdir() {
        Powerstack\Core\Filesystem::mkdir('test');
        $this->assertFileExists('test');
    }

    /**
    * Test reading, wrtitng and appending files
    *
    * @depends testMkdir
    */
    public function testFileOps() {
        $write = Powerstack\Core\Filesystem::writeFile('test/test.txt', "test\n");
        $this->assertTrue($write);

        $data = Powerstack\Core\Filesystem::readFile('test/test.txt');
        $this->assertEquals("test\n", $data);

        $append = Powerstack\Core\Filesystem::appendFile('test/test.txt', 'hello');
        $this->assertTrue($append);

        $data = Powerstack\Core\Filesystem::readFile('test/test.txt');
        $this->assertEquals("test\nhello", $data);
    }

    /**
    * Test listAll
    *
    * @depends testFileOps
    */
    public function testList() {
        $list = Powerstack\Core\Filesystem::listAll('test/');
        $this->assertArrayHasKey('files', $list);
        $this->assertArrayHasKey('dirs', $list);
        $this->assertContains(dirname(dirname(__FILE__)) . '/test/test.txt', $list['files']);
    }

    /**
    * Test find
    *
    * @depends testFileOps
    */
    public function testFind() {
        $file = Powerstack\Core\Filesystem::find('test/', '*.txt');
        $this->assertContains('test/test.txt', $file);
    }

    /**
    * Test removing directories and files
    *
    * @depends testFileOps
    */
    public function testRemove() {
        $removefile = Powerstack\Core\Filesystem::remove('test/test.txt');
        $this->assertTrue($removefile);

        $removedir = Powerstack\Core\Filesystem::remove('test', true);
        $this->assertTrue($removedir);
    }
}
?>
