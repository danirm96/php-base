<?php

// Carga un partial (componente reutilizable) con los datos proporcionados
function charge_partial($partial, $data = []) {
    $partial_file = 'views/partials/' . $partial . '.php';
    
    if (file_exists($partial_file)) {
        require_once $partial_file;
    } else {
        error_404(16);
    }
}

// Carga la vista especificada con los datos proporcionados
function charge_view($view, $data = []) {
    
    if (file_exists($view)) {
        require_once $view;
    } else {
        error_404(17);
    }
}

// Carga el layout especificado con las vistas y datos proporcionados
function charge_layout($layout, $views = [], $data = []) {
    require_once 'views/partials/head.php';
    $layout_file = 'views/layouts/' . $layout . '.php';

    // Check views existence
    $dirs = ['views/pages/', 'views/partials/', 'core/views/'];
    $found = false;
    $final_views = [];
    
    foreach ($views as $view) {
        foreach ($dirs as $dir) {
            if (file_exists($dir . $view . '.php')) {
                $found = true;
                $final_views[] = $dir . $view . '.php';
                break;
            }
        }
    }

    $views = $final_views;

    if (!$found) {
        error_404(18);
    }

    if(empty($data) || !isset($data['title']) || $data['title'] == '') {
        $data['title'] = APP_NAME;
    }

    if (file_exists($layout_file)) {
        require_once $layout_file;
    } else {
        error_404(19);
    }
}
