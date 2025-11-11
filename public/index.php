<?php

use Carbe\Petitcreuxv2\Core\Database;

session_start();

require __DIR__ . '/../vendor/autoload.php';
define('VIEW_PATH', dirname(__DIR__) . '/src/Templates');

$dotenv = Dotenv\Dotenv::createUnsafeImmutable(dirname(__DIR__));
$dotenv->load();

$router= new AltoRouter();

require __DIR__ . '/../routes/web.php';


$match = $router->match();

if( is_array($match) && is_callable( $match['target'] ) ) {
	call_user_func_array( $match['target'], $match['params'] );
} else {
	
	header( $_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
}

