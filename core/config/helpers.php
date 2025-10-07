<?php

function e($e) {
    echo "<pre>";
    $backtrace = debug_backtrace();
    $caller = array_shift($backtrace);
    echo "{$caller['file']} {$caller['line']}:\n";
    var_dump($e);
    echo "</pre>";
}

// Devuelve la URL actual completa
function current_url(): string {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $current_url = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    return $current_url;
}

// Devuelve los segmentos de la URL actual como un array
function current_path(): string {
    $path = $_SERVER['REQUEST_URI'];
    $path = trim($path, '/');
    return $path;
}

// Devuelve el método de la solicitud HTTP actual
function method_request(): string {
    return $_SERVER['REQUEST_METHOD'];
}

// conseguir todos los archivos dentro del directorio
function get_files_in_directory($directory) {
    $files = [];
    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                if ($file != '.' && $file != '..') {
                    $files[] = $file;
                }
            }
            closedir($dh);
        }
    }
    return $files;
}

// Devuelve todos los segments de la URL actual como un array
function current_segments(): array {
    $path = $_SERVER['REQUEST_URI'];
    $path = trim($path, '/');
    $path = parse_url($path, PHP_URL_PATH);
    $segments = explode('/', $path);
    return $segments;
}