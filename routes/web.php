<?php

use Carbe\Petitcreuxv2\Core\Container;
use Carbe\Petitcreuxv2\Controllers\HomeController;
use Carbe\Petitcreuxv2\Controllers\UserController;

$container = new Container();


$router->map('GET', '/', function() {
    
    $home = new HomeController();
    $home->viewHomePage();

});

$router->map('GET', '/login', function() use ($container) {
    
    $login = $container->get(UserController::class);
    $login->loginForm();
});

$router->map('GET', '/register', function() use ($container) {

    $register = $container->get(UserController::class);
    $register->registerForm();
});