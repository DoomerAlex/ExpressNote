<?
// FRONT CONTROLLER
	define('ROOT', dirname(__FILE__)); // General position of file
	
	mb_internal_encoding("UTF-8");

	require_once(ROOT.'/config/router.php');
	require_once(ROOT.'/config/DataBase.php');

	//if (isset($_REQUEST[session_name()])) session_start();
	session_start();

	$router = new Router();
	$router->run();
?>