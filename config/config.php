<?php

define('BASE_URL', 'https://erprojects.dot/');
define('APP_NAME', 'ERProjects');
define('DB_HOST', 'localhost');
define('DB_NAME', 'erprojects_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('IPS_ALLOW', ['85.60.114.248', '127.0.0.1']);
define('EXPIRED_SESSION', 3600);

require_once 'config/Db.php';
require_once 'config/Model.php';
require_once 'config/Controller.php';
require_once 'config/routes.php';

function include_classes($class)
{
    $file_path = 'models/' . $class . '.php';
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}

spl_autoload_register('include_classes');

function e($e) {
    echo "<pre>";
    var_dump($e);
    echo "</pre>";
}

function charge_controller() {
    
    $segments = current_url();

    $controller = !empty($segments[0]) ? $segments[0] : 'home';
    $view = !empty($segments[1]) ? $segments[1] : 'index';

    unset($segments[0]);
    unset($segments[1]);

    $params = count($segments) === 1 ? $segments[2] : array_values($segments);

    return array(
        'controller' => $controller,
        'view' => $view,
        'params' => $params
    );
}

function error_404($msg = '') {
    http_response_code(404);
    charge_view('errors/404', ['msg' => $msg, 'title' => '404 Not Found'], false, false);
    exit();
}

function current_url(): array {
    $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $actual_link = str_replace(BASE_URL, '', $actual_link);
    $actual_link = trim($actual_link, '/');
    $segments = explode('/', $actual_link);
    return $segments;
}

function method_request(): string {
    return $_SERVER['REQUEST_METHOD'];
}


function charge_view($view, $data = [], $header = true, $footer = true) {
    $view_file = 'views/' . $view . '.php';

    if(empty($data) || !isset($data['title']) || $data['title'] == '') {
        $data['title'] = APP_NAME;
    }
    
    require_once 'views/partials/head.php';
    if (file_exists($view_file)) {
        if($header) {
            require_once 'views/partials/header.php';
        }
        require_once $view_file;

        if($footer) {
            require_once 'views/partials/footer.php';
        }
    } else {
        error_404();
    }
}
