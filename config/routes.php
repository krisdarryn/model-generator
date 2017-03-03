<?php
/**
* This files is where to register the routes and its corresponding controller
*/

// Index page
$klein->respond('/', function($request, $response, $service, $app) use ($klein) {
   
    $controller = new \Generator\Controllers\IndexController(array(
        'klein' => $klein,
        'request' => $request,
        'response' => $response,
        'service' => $service,
        'app' => $app,
    ));
    
    return $controller->index();
}); 

// Generic route for URI pattern: <controller|controller/action>
$klein->respond('/[:controller]?/[:action]?', function($request, $response, $service, $app) use ($klein) {
    $controller = CONTROLLER_NAMESPACE . str_replace(' ', '', ucwords(str_replace('-', ' ', $request->controller))) . 'Controller';

    if (($request->controller && ($request->controller !== 'page-not-found')) && !class_exists($controller)) {
        return $response->redirect(PAGE_NOT_FOUND_URI);
    }
    
    $controllerObj = new $controller(array(
        'klein' => $klein,
        'request' => $request,
        'response' => $response,
        'service' => $service,
        'app' => $app,
    ));

    return $request->action ? $controllerObj->{$request->action}() : $controllerObj->index();
}); 