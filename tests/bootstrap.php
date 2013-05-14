<?php
define('BASEDIR', dirname(dirname(__FILE__)) . '/');
define('APPDIR', BASEDIR . 'app/');
define('VENDORDIR', BASEDIR . 'vendor/');

require_once(BASEDIR . 'autoloader.php');
require_once(VENDORDIR . 'autoload.php');
require_once(dirname(__FILE__) . '/utils.php');

// Mock Server vars for request
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['QUERY_STRING'] = '?q=/';
$_SERVER['HTTP_REFERER'] = 'test';
$_SERVER['HTTP_USER_AGENT'] = 'test';
$_SERVER['HTTPS'] = false;
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';
$_SERVER['SERVER_NAME'] = 'php.unit';
$_SERVER['REQUEST_URI'] = '/';

$registry = Powerstack\Core\Registry::getInstance();
$config = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
$application = new Powerstack\Core\Application($config);
registry('config', $config);
registry('app', $application);
?>
