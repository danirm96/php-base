<?php

$includes_files = [
    'core/config/globals.php',
    'config/config.php',
    'config/menu.php',
    'core/config/functions.php',
    'core/config/helpers.php',
    'core/config/charges.php',
    'core/config/autoload.php',
    'config/routes.php'
];

foreach ($includes_files as $file) {
    if (file_exists($file)) {
        require_once $file;
    } else {
        die("Error: No se pudo cargar el archivo '$file'.");
    }
}

$components = get_files_in_directory('core/components');

foreach ($components as $component) {
    if (file_exists('core/components/' . $component)) {
        require_once 'core/components/' . $component;
    }
}

$helpers = get_files_in_directory('core/helpers');

foreach ($helpers as $helper) {
    if (file_exists('core/helpers/' . $helper)) {
        require_once 'core/helpers/' . $helper;
    }
}

require_once 'core/config/router.php';
