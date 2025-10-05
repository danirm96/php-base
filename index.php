<?php

include 'config/config.php';

$current_url = current_url();
$count = count($current_url);

session_start();

$route = $routes;
foreach ($current_url as $segment) {
    if (isset($route[$segment])) {
        $route = $route[$segment];
    } else {
        $route = null;
        break;
    }
}

if($route) {
    if(isset($route['method']) && $route['method'] !== method_request()) {
        error_404('Method Not Allowed');
    }
    $controller = array('controller' =>$route['controller'], 'view' => $route['view'], 'params' => []);
} else {
    $controller = charge_controller();
}


$controller_file = 'controllers/' . ucfirst($controller['controller']) . '.php';

if (file_exists($controller_file)) {
    require_once $controller_file;
    $controller_class = 'Controllers\\' . ucfirst($controller['controller']);

    if (class_exists($controller_class)) {
        $controller_instance = new $controller_class();
        if($controller_instance->private) {
            if(!in_array($_SERVER['REMOTE_ADDR'], IPS_ALLOW)) {
                error_404(1);
            }
        }
        $controller_instance->{$controller['view']}($controller['params']);
    } else {
        error_404(2);
    }
}
else {
    error_404(3);
}