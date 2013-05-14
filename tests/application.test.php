<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

class ApplicationTest extends PHPUnit_Framework_TestCase {
    /**
    * Test get route
    */
    public function testGet() {
        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '?q=/';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/';

        $conf = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $app = new Powerstack\Core\Application($conf);

        $app->get('/', function($request, $params) {
            echo "index";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage);
        }

        $this->assertEquals("index", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->get('/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '?q=hello/jim';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello/jim';

        $app = new Powerstack\Core\Application($conf);

        $app->get('/hello/:name', function($request, $params) {
            echo "hello, " . $params->name;
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals('hello, jim', $response);
    }

    /**
    * Test post route
    */
    public function testPost() {
        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '?q=/';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/';

        $conf = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $app = new Powerstack\Core\Application($conf);

        $app->post('/', function($request, $params) {
            echo "index";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage);
        }

        $this->assertEquals("index", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->post('/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '?q=hello/jim';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello/jim';

        $app = new Powerstack\Core\Application($conf);

        $app->post('/hello/:name', function($request, $params) {
            echo "hello, " . $params->name;
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals('hello, jim', $response);
    }

    /**
    * Test put route
    */
    public function testPut() {
        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['QUERY_STRING'] = '?q=/';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/';

        $conf = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $app = new Powerstack\Core\Application($conf);

        $app->put('/', function($request, $params) {
            echo "index";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage);
        }

        $this->assertEquals("index", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->put('/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['QUERY_STRING'] = '?q=hello/jim';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello/jim';

        $app = new Powerstack\Core\Application($conf);

        $app->put('/hello/:name', function($request, $params) {
            echo "hello, " . $params->name;
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals('hello, jim', $response);
    }

    /**
    * Test delete route
    */
    public function testDelete() {
        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['QUERY_STRING'] = '?q=/';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/';

        $conf = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $app = new Powerstack\Core\Application($conf);

        $app->delete('/', function($request, $params) {
            echo "index";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage);
        }

        $this->assertEquals("index", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->delete('/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        unset($_SERVER);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['QUERY_STRING'] = '?q=hello/jim';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello/jim';

        $app = new Powerstack\Core\Application($conf);

        $app->delete('/hello/:name', function($request, $params) {
            echo "hello, " . $params->name;
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals('hello, jim', $response);
    }

    /**
    * Test any route
    */
    public function testAny() {
        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $conf = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $app = new Powerstack\Core\Application($conf);

        $app->any(array('GET', 'POST', 'PUT', 'DELETE'), '/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->any(array('GET', 'POST', 'PUT', 'DELETE'), '/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'PUT';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->any(array('GET', 'POST', 'PUT', 'DELETE'), '/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);

        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'DELETE';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $app = new Powerstack\Core\Application($conf);

        $app->any(array('GET', 'POST', 'PUT', 'DELETE'), '/hello', function($request, $params) {
            echo "hello";
        });

        try {
            ob_start();
            $app->run();
            $response = ob_get_clean();
        } catch (Powerstack\Core\NotFoundException $e) {
            $this->fail($e->getMessage());
        }

        $this->assertEquals("hello", $response);
    }

    /**
    * Test 404
    * @expectedException    Powerstack\Core\NotFoundException
    */
    public function test404() {
        // Mock Server vars for request
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = '?q=hello';
        $_SERVER['HTTP_REFERER'] = 'test';
        $_SERVER['HTTP_USER_AGENT'] = 'test';
        $_SERVER['HTTPS'] = false;
        $_SERVER['REMOTE_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = 'php.unit';
        $_SERVER['REQUEST_URI'] = '/hello';

        $conf = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
        $app = new Powerstack\Core\Application($conf);
        $app->run();
    }
}
?>
