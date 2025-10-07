<?php

class Routes
{

    private $routes = [];
    private $forbidden_routes = ['migrations/', 'seeders/'];

    public function get($path, $controller, $auth = null, $private = false)
    {
        $this->addRoute('GET', $path, $controller, $auth, $private);
    }

    public function post($path, $controller, $auth = null, $private = false)
    {
        $this->addRoute('POST', $path, $controller, $auth, $private);
    }

    public function put($path, $controller, $auth = null, $private = false)
    {
        $this->addRoute('PUT', $path, $controller, $auth, $private);
    }

    public function delete($path, $controller, $auth = null, $private = false)
    {
        $this->addRoute('DELETE', $path, $controller, $auth, $private);
    }

    private function addRoute($method, $path, $controller, $auth, $private)
    {
        $param_names = [];
        
        if (strpos($path, ':') !== false) {
            $parts = explode('/', $path);
            foreach ($parts as $part) {
                if (strpos($part, ':') === 0) {
                    $param_names[] = substr($part, 1); 
                }
            }
        }

        
        $this->routes[$method][$path] = [
            'controller' => $controller,
            'auth' => $auth,
            'private' => $private,
            'method' => $method,
            'param_names' => $param_names, 
            'pattern' => $path
        ];
    }

    public function getRoutes()
    {
        $check = $this->checkRoutes();
        if(count($check) === 0) {
            return $this->routes;
        } else {
            echo "Rutas mal definidas:";
            foreach($check as $bad) {
                echo "<br>- " . $bad;
            }
            die();
        }
    }

    public function checkRoutes() {
        $routes = $this->routes;
        $bad_routes = [];

        foreach($routes as $methodRoutes) {
            foreach($methodRoutes as $route => $routeData) {
                foreach($this->forbidden_routes as $forbidden_route) {
                    $check = str_starts_with($route, $forbidden_route);
                    if($check) {
                        // e($route);
                        $bad_routes[] = $route;
                    }
                }
            }
        }

        return $bad_routes;
    }

    public function isForbidden($route) {
        foreach($this->forbidden_routes as $forbidden_route) {
            if(str_starts_with($route, $forbidden_route)) {
                return true;
            }
        }
        return false;
    }

}

global $routes;
$routes = new Routes();