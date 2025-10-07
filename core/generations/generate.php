<?php

function generateController($name) {
    $controller_name = ucfirst($name) . 'Controller';
    $model_name = ucfirst($name);

    $template = file_get_contents('core/generations/templates/Controller.php');
    $template = str_replace('{{model_name}}', $model_name, $template);
    $template = str_replace('{{controller_name}}', $controller_name, $template);
    $template = "<?php\n\n" . $template;

    $file = fopen("controllers/{$controller_name}.php", 'w');
    if(fwrite($file, $template)) {
        echo "Controlador {$controller_name} creado con éxito.\n";
    } else {
        echo "Error al crear el controlador {$controller_name}.\n";
    }
    fclose($file);
}

function generateModel($name) {
    $model_name = ucfirst($name);
    $table_name = strtolower($name);
    $label_name = $model_name;

    $template = file_get_contents('core/generations/templates/Model.php');
    $template = str_replace('{{model_name}}', $model_name, $template);
    $template = str_replace('{{table_name}}', $table_name, $template);
    $template = "<?php\n\n" . $template;

    // create file
    $file = fopen("models/{$model_name}.php", 'w');
    if(fwrite($file, $template)) {
        echo "Modelo {$model_name} creado con éxito.\n";
    } else {
        echo "Error al crear el modelo {$model_name}.\n";
    }
    fclose($file);
}

function generateRoutes($name, $auth = 'authUser') {
    $controller_name = ucfirst($name) . 'Controller';

    $template = file_get_contents('core/generations/templates/route.php');
    $template = str_replace('{{controller}}', $controller_name, $template);
    $template = str_replace('{{name}}', $name, $template);
    $template = str_replace('{{auth}}', $auth, $template);

    // append to routes file
    $file = fopen("config/routes.php", 'a');
    if(fwrite($file, "\n" . $template)) {
        echo "Rutas para {$name} creadas con éxito.\n";
    } else {
        echo "Error al crear rutas para {$name}.\n";
    }
    fclose($file);
}