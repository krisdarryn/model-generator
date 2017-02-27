<?php

/**
* Congiguration Constants
*/
$basePath = array_diff(explode(SEPARATOR, __DIR__), array(' '));
for($i = 0; $i < 1; $i++) {
   array_pop($basePath);
}
$basePath = implode(SEPARATOR, $basePath);

// Local base path
define('BASE_PATH', $basePath . SEPARATOR);
define('VIEW_PATH', BASE_PATH . SEPARATOR . 'source' . SEPARATOR . 'Views' . SEPARATOR);

// System configuration constants
define('CONFIG_URI', '/model-gen');
define('CONTROLLER_NAMESPACE', '\Generator\Controllers\\');
define('HTML_TITLE', 'Model Generator');

// Web path
define('ASSETS_PATH', CONFIG_URI . '/assets/');
define('PUBLIC_PATH', CONFIG_URI . '/public/');
define('CSS_PATH', PUBLIC_PATH . 'css/');
define('JS_PATH', PUBLIC_PATH . 'js/');
define('IMAGES_PATH', PUBLIC_PATH . 'images/');
define('BOWER_PATH', ASSETS_PATH . 'bower_components/');