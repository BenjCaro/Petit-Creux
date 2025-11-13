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


$router->map('POST', '/login', function() use ($container) {

    $login = $container->get(UserController::class);
    $login->connexion();
});

$router->map('GET', '/register', function() use ($container) {

    $register = $container->get(UserController::class);
    $register->registerForm();
});

$router->map('POST', '/register', function() use ($container) {

    $register = $container->get(UserController::class);
    $register->register();
});

$router->map('GET', '/logout', function() use ($container) {

    $deconnexion = $container->get(UserController::class);
    $deconnexion->deconnexion();
});