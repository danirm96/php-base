<?php

function class_autoloader($class)
{
    $namespace_map = [
        'Models\\' => 'models/',
        'Controllers\\' => 'controllers/',
        'core\\classes\\' => 'core/classes/',
        'Seeders\\' => 'seeders/',
        'Auth\\' => 'auth/'
    ];

    foreach ($namespace_map as $namespace => $directory) {
        if (strpos($class, $namespace) === 0) {
            $class_name = str_replace($namespace, '', $class);
            $class_name = str_replace('\\', DIRECTORY_SEPARATOR, $class_name);
            $file_path = $directory . $class_name . '.php';
            
            if (file_exists($file_path)) {
                require_once $file_path;
                return true;
            }
        }
    }

    $class_path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file_path = strtolower($class_path) . '.php';
    
    if (file_exists($file_path)) {
        require_once $file_path;
        return true;
    }

    return false;
}

spl_autoload_register('class_autoloader');

// Cargar todas las clases del core después de registrar el autoloader
if (function_exists('get_files_in_directory')) {
    $classes_core = get_files_in_directory('core/classes');
    foreach ($classes_core as $class) {
        require_once 'core/classes/' . $class;
    }
}
