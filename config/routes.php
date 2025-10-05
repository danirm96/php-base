<?php

$routes = [
    'login' => ['controller' => 'auth', 'view' => 'login', 'private' => false, 'method' => 'GET'],
    'access' => ['controller' => 'auth', 'view' => 'access', 'private' => false, 'method' => 'POST'],
    'dashboard' => ['controller' => 'dashboard', 'view' => 'index', 'private' => true, 'method' => 'GET'],
    'projects' =>
    [
        'new' => ['controller' => 'projects', 'view' => 'new', 'private' => true, 'method' => 'GET'],
        'edit' => ['controller' => 'projects', 'view' => 'edit', 'private' => true, 'method' => 'GET'],
    ]
];
