<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

class RequestTest extends PHPUnit_Framework_TestCase {
    /**
    * Test request class
    */
    public function testRequest() {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '?q=/';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/';

        $request = new Powerstack\Core\Request();
        $this->assertEquals($request->request_method, 'get');
        $this->assertEquals($request->request_uri, '/');
        $this->assertFalse($request->https);
        $this->assertEquals($request->remote_address, '127.0.0.1');
        $this->assertEquals($request->http_referer, 'test');
        $this->assertEquals($request->user_agent, 'test');
        $this->assertEquals($request->query_string, '?q=/');
        $this->assertEquals($request->base_uri, 'php.unit');
    }
}
?>
