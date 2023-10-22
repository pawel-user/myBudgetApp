<?php


/**
 * Front controller
 *
 * PHP version 7.4
 */

 ini_set('session.cookie_lifetime', '864000');  // ten days in seconds

/**
 * Composer
 */
require '../vendor/autoload.php';


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Sessions
 */
session_start();


/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('contact', ['controller' => 'Home', 'action' => 'contact']);
$router->add('newpassword', ['controller' => 'Home', 'action' => 'newpassword']);
$router->add('start', ['controller' => 'Start', 'action' => 'home']);
$router->add('create', ['controller' => 'Login', 'action' => 'create']);
$router->add('login', ['controller' => 'Login', 'action' => 'new']);
$router->add('logout', ['controller' => 'Login', 'action' => 'destroy']);

$router->add('password/reset/{token:[\da-f]+}', ['controller' => 'Password', 'action' => 'reset']);

$router->add('incomes', ['controller' => 'Start', 'action' => 'incomes']);
$router->add('expenses', ['controller' => 'Start', 'action' => 'expenses']);
$router->add('balance', ['controller' => 'Start', 'action' => 'balance']);
$router->add('{controller}/{action}');
//$router->add('{controller}/{id:\d+}/{action}');
//$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
    
$router->dispatch($_SERVER['QUERY_STRING']);
