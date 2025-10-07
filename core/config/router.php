<?php
$current_path = current_path();

$routes_list = $routes->getRoutes();

$current_method = method_request();

$route = find_matching_route($routes_list, $current_path, $current_method);

$extracted_params = [];

if ($route) {
    $extracted_params = $route['extracted_params'] ?? [];
    $params = array_values($extracted_params); 

    $controllerView = explode('::', $route['controller']);
    if(count($controllerView) != 2) {
        error_404(6);
        exit();
    }

    $route['controller'] = $controllerView[0];
    $route['view'] = $controllerView[1];
    
    $controller = array(
        'controller' => $route['controller'], 
        'view' => $route['view'],
        'auth' => $route['auth'],
        'params' => $params, 
        'extracted_params' => $extracted_params,
        'private' => $route['private'] ?? false
    );

} else {
    error_404();
    exit();
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

        if(!method_exists($controller_instance, $controller['view'])) {
            error_404(4);
        }

        if($controller['auth']) {
            $params_auth = explode('.', $controller['auth']);
            $auth_class =  "\\Auth\\" . ucfirst($params_auth[0]);
            if(!class_exists($auth_class)) {
                header('Location: ' . BASE_URL );
                exit();
            }

            unset($params_auth[0]);
            $auth_instance = new $auth_class();
            if(!$auth_instance->canAccess($params_auth)) {
                header('Location: ' . BASE_URL );
                exit();
            }
        }

        $controller_instance->{$controller['view']}($controller['params']);

    } else {
        error_404(2);
    }
} else {
    error_404(3);
}

function find_matching_route($routes_list, $current_path, $current_method) {

    global $routes;
    $isForbidden = $routes->isForbidden($current_path);
    if($isForbidden) {
        charge_forbidden($current_path);
        exit();
    }
    
    if (!isset($routes_list[$current_method])) {
        return null;
    }
    
    $method_routes = $routes_list[$current_method];
    if (isset($method_routes[$current_path])) {
        $route = $method_routes[$current_path];
        $route['extracted_params'] = [];
        return $route;
    }
    
    foreach ($method_routes as $route_pattern => $route_config) {
        $match_result = match_route_pattern($route_pattern, $current_path);
        
        if ($match_result !== false) {
            $route_config['extracted_params'] = $match_result;
            return $route_config;
        }
    }
    
    return null;
}


function match_route_pattern($pattern, $path) {
    $pattern_segments = explode('/', trim($pattern, '/'));
    $path_segments = explode('/', trim($path, '/'));
    
    if (count($pattern_segments) !== count($path_segments)) {
        return false;
    }
    
    $extracted_params = [];
    
    for ($i = 0; $i < count($pattern_segments); $i++) {
        $pattern_segment = $pattern_segments[$i];
        $path_segment = $path_segments[$i];
        
        
        if (strpos($pattern_segment, ':') === 0) {
            $param_name = substr($pattern_segment, 1);
            $extracted_params[$param_name] = $path_segment;
        }
        else if ($pattern_segment !== $path_segment) {
            return false;
        }
    }
    
    return $extracted_params;
}


function pattern_to_regex($pattern) {
    $regex = preg_replace('/:([a-zA-Z_][a-zA-Z0-9_]*)/', '([^/]+)', $pattern);
    return '/^' . str_replace('/', '\/', $regex) . '$/';
}

function match_route_with_regex($pattern, $path) {
    $regex = pattern_to_regex($pattern);
    
    if (preg_match($regex, $path, $matches)) {
        return match_route_pattern($pattern, $path); 
    }
    
    return false;
}

function charge_forbidden($route) {
    $route = explode('/', $route);
    $controller = $route[0] ?? 'unknown';
    $method = $route[1] ?? 'index';
    $params = [];

    if(count($route) > 2) {
        $params = array_slice($route, 2);
    }

    if(count($params) == 1) {
        $params = $params[0];
    }

    $class = 'core\classes\\' . ucfirst($controller);
    if(class_exists($class)) {
        $instance = new $class();
        if(!method_exists($instance, $method)) {
            error_404(4);
            exit();
        }
        $instance->{$method}($params);
    }

}