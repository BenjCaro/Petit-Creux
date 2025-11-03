<?php

use Carbe\Petitcreuxv2\Controllers\HomeController;

$router->map('GET', '/', function() {
    
    $home = new HomeController();
    $home->viewHomePage();

});