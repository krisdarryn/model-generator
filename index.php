<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Prettify debugging message using print_r
 * 
 * @param  mixed $a
 * @return void
 */
function debug_r($a = null) {
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}

/**
 * Prettify debugging message using var_dump
 * 
 * @param  mixed $a
 * @return void
 */
function debug_dump($a = null) {
    echo '<pre>';
    var_dump($a);
    echo '</pre>';
}

// Define appropriate directory separator base on the current OS
PHP_OS == "Windows" || PHP_OS == "WINNT" ? define("SEPARATOR", "\\") : define("SEPARATOR", "/");

require_once __DIR__ . SEPARATOR . 'vendor' . SEPARATOR . 'autoload.php';
require_once __DIR__ . SEPARATOR . 'config' . SEPARATOR . 'config.php';

// Initialize $klein object
$klein = new \Klein\Klein();

// Register Session
$sessionFactory = new \Aura\Session\SessionFactory();
$session = $sessionFactory->newInstance($_COOKIE);
$segment = $session->getSegment('Generator');
$klein->service()
      ->sharedData()
      ->set('session', $segment);


// Register the routes
$klein->with(CONFIG_URI, function() use ($klein) {

    // Handling HTTP errors, e.g. 404, 405
    $klein->onHttpError(function ($code, $router) {
        switch ($code) {
            case 404:
            case 405:
            default:
                $router->response()
                       ->redirect(CONFIG_URI . '/page-not-found');
        }
    });

    // Register routes
    // Adding of routes will be in this file
    require_once __DIR__ . SEPARATOR . 'config' . SEPARATOR . 'routes.php';
});

// Serve the pages
$klein->dispatch(); 