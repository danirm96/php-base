<?php

// Sintaxis: $routes->get(path, controller::method, authClass, private)

// Ruta de ejemplo con parámetros dinámicos
// $routes->get('user/list/:id/:name', 'users::list', 'auth.admin', true);

// Accesos
$routes->get('', 'AuthController::login', false, false);
$routes->get('login', 'AuthController::login',false, false);
$routes->post('access', 'AuthController::access',false, false);
$routes->get('logout', 'AuthController::logout', false, true);

// Dashboard
$routes->get('dashboard', 'DashboardController::index', 'authUser.admin.user', false);

$routes->get('users/list', 'UsersController::list', 'authUser.admin', false);
$routes->get('users/view/:id', 'UsersController::view', 'authUser.admin.user', false);
$routes->get('users/edit/:id', 'UsersController::edit', 'authUser.admin', false);
$routes->post('users/update', 'UsersController::update', 'authUser.admin', false);
$routes->post('users/create', 'UsersController::create', 'authUser.admin', false);
$routes->get('users/new', 'UsersController::new', 'authUser.admin', false);

$routes->get('projects/list', 'ProjectsController::list', 'authUser', false);
$routes->get('projects/view/:id', 'ProjectsController::view', 'authUser', false);
$routes->get('projects/edit/:id', 'ProjectsController::edit', 'authUser', false);
$routes->post('projects/update', 'ProjectsController::update', 'authUser', false);
$routes->post('projects/create', 'ProjectsController::create', 'authUser', false);
$routes->get('projects/new', 'ProjectsController::new', 'authUser', false);