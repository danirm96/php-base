<?php

// Muestra la página de error 404
function error_404($msg = '') {
    if($msg !== '') {
        e($msg);
        die();
    }
    $view_file = 'views/errors/404.php';
    require_once $view_file;
    exit();
}

