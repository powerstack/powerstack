<?php
define('BASEDIR', dirname(dirname(__FILE__)) . '/');
define('APPDIR', BASEDIR . 'app/');
define('VENDORDIR', BASEDIR . 'vendor/');

require_once(BASEDIR . 'autoloader.php');
require_once(VENDORDIR . 'autoload.php');
require_once(APPDIR . 'includes/utils.php');

$registry = Powerstack\Core\Registry::getInstance();
$config = new Powerstack\Core\Config(dirname(__FILE__) . '/config.yml');
registry('config', $config);
?>
