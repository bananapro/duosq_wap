<?php

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('PS')) {
	define('PS', PATH_SEPARATOR);
}

/**
 * These defines should only be edited if you have cake installed in
 * a directory layout other than the way it is distributed.
 * Each define has a commented line of code that explains what you would change.
 *
 */
if (!defined('ROOT')) {
	//define('ROOT', 'FULL PATH TO DIRECTORY WHERE APP DIRECTORY IS LOCATED DO NOT ADD A TRAILING DIRECTORY SEPARATOR';
	//You should also use the DS define to seperate your directories
	define('ROOT', dirname(dirname(dirname(__FILE__))));
}

if (!defined('APP_DIR')) {
	//define('APP_DIR', 'DIRECTORY NAME OF APPLICATION';
	define('APP_DIR', 'h5');
}

/**
 * This only needs to be changed if the cake installed libs are located
 * outside of the distributed directory structure.
 */
if (!defined('CAKE_CORE_INCLUDE_PATH')) {
	//define ('CAKE_CORE_INCLUDE_PATH', FULL PATH TO DIRECTORY WHERE CAKE CORE IS INSTALLED DO NOT ADD A TRAILING DIRECTORY SEPARATOR';
	//You should also use the DS define to seperate your directories
	define('CAKE_CORE_INCLUDE_PATH', ROOT . DS);

}

///////////////////////////////
//DO NOT EDIT BELOW THIS LINE//
///////////////////////////////
if (!defined('WEBROOT_DIR')) {
	define('WEBROOT_DIR', basename(dirname(__FILE__)));
}
if (!defined('WWW_ROOT')) {
	define('WWW_ROOT', dirname(__FILE__) . DS);
}
if (!defined('CORE_PATH')) {
	if (function_exists('ini_set')) {
		ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . CAKE_CORE_INCLUDE_PATH . PATH_SEPARATOR . ROOT . DS . APP_DIR . DS);
		define('APP_PATH', null);
		define('CORE_PATH', null);
	}
	else {
		define('APP_PATH', ROOT . DS . APP_DIR . DS);
		define('CORE_PATH', CAKE_CORE_INCLUDE_PATH . DS);
	}
}

require CORE_PATH . 'cake' . DS . 'bootstrap.php';

if (isset($_GET['url']) && $_GET['url'] === '/favicon.ico') {

}
else {
	$Dispatcher = new Dispatcher();
	$Dispatcher->dispatch($url);
}

?>
