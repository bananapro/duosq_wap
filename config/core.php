<?php
//该文件不可修改，如有需要定义常量，请改env.php
require_once "env.php";

/**
 * Set debug level here:
 * - 0: production
 * - 1: development
 * - 2: full debug with sql
 * - 3: full debug with sql and dump of the current object
 */

//支持命令行调试
if (isset($_SERVER['argv']) && $_SERVER['argc'] >= 2) {
	$ac = 1;
	while ($ac < (count($_SERVER['argv']))) {
		$arg = $_SERVER['argv'][$ac];

		if ($arg == '-debug') {
			if(!defined('DEBUG'))define('DEBUG', 2);
			break;
		}
		$ac++;
	}
}
if (!defined('DEBUG')){
	if (isset($_GET['debug'])) {

		if ($_GET['debug'] == 'bpro')
			define('DEBUG', 2);
		elseif ($_GET['debug'] == 'false')
			define('DEBUG', 0);

	}else {
		define('DEBUG', 0);
	}
}

if (!defined('CACHE_CHECK'))define('CACHE_CHECK', false);
/**
 * Error constant. Used for differentiating error logging and debugging.
 * Currently PHP supports LOG_DEBUG
 */
define('LOG_ERROR', 2);

define('MAX_SQL_LOG', 1000);

define('CAKE_SESSION_SAVE', 'php');

define('CAKE_SESSION_TABLE', 'duosq_sess');

define('CAKE_SESSION_STRING', 'DYhG93b0qyJfIxfs2guVoUubWwvniR2G0FgaC9mi');

define('CAKE_SESSION_COOKIE', 'duosq_sess');

define('CAKE_SECURITY', 'low');

define('CAKE_SESSION_TIMEOUT', '120');

define('CAKE_SESSION_DOMAIN', '');

/**
 *  The define below is used to turn cake built webservices
 *  on or off. Default setting is off.
 */
define('WEBSERVICES', 'off');
/**
 * Compress output CSS (removing comments, whitespace, repeating tags etc.)
 * This requires a/var/cache directory to be writable by the web server (caching).
 * To use, prefix the CSS link URL with '/ccss/' instead of '/css/' or use Controller::cssTag().
 */
define('COMPRESS_CSS', false);
/**
 * If set to true, helpers would output data instead of returning it.
 */
define('AUTO_OUTPUT', false);
/**
 * If set to false, session would not automatically be started.
 */
define('AUTO_SESSION', false);
/**
 * Set the max size of file to use md5() .
 */
define('MAX_MD5SIZE', (5 * 1024) * 1024);
?>