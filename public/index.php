<?php


/**
 * Front controller
 *
 * PHP version 7.4
 */

/**
 * Composer
 */
require '../vendor/autoload.php';

/**
 * Twig
 */
//Twig_Autoloader::register();
//$loader = new Twig_Loader_Filesystem('../app/views');


/**
 * Error and Exception handling
 */
error_reporting(E_ALL);
set_error_handler('Core\Error::errorHandler');
set_exception_handler('Core\Error::exceptionHandler');

/**
 * Routing
 */
$router = new Core\Router();

// Add the routes
$router->add('', ['controller' => 'Home', 'action' => 'index']);
$router->add('index', ['controller' => 'Home', 'action' => 'index']);
$router->add('Start', ['controller' => 'Start', 'action' => 'home']);
$router->add('{controller}/{action}');
//$router->add('{controller}/{id:\d+}/{action}');
//$router->add('admin/{controller}/{action}', ['namespace' => 'Admin']);
    
$router->dispatch($_SERVER['QUERY_STRING']);
