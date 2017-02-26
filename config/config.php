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

// Always include trailing slash
define('CONFIG_URI', '/model-gen');
define('CONTROLLER_NAMESPACE', '\Generator\Controllers\\');
define('HTML_TITLE', 'Model Generator');