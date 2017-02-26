<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

function debug_r($a = null) {
    echo '<pre>';
    print_r($a);
    echo '</pre>';
}

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

// Register the routes
$klein->with(CONFIG_URI, function() use ($klein) {
    $klein->service()
          ->sharedData()
          ->set('htmlTitle', HTML_TITLE);
    
    require_once __DIR__ . SEPARATOR . 'config' . SEPARATOR . 'routes.php';
});

// Serve the pages
$klein->dispatch(); 