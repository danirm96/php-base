<?php

/* 
    Estructura:
    $menu = [
        'Nombre del ítem' => [
            'url' => 'URL del ítem',
            'icon' => 'Icono (opcional)',
            'private' => true/false (si es un ítem privado o público),
            'roles' => ['rol1', 'rol2', ...] (roles que pueden ver el ítem, '*' para todos los roles),
            'submenus' => [ (submenús opcionales)
                'Nombre del subítem' => [
                    'url' => 'URL del subítem',
                    'icon' => 'Icono (opcional)',
                    'private' => true/false,
                    'roles' => ['rol1', 'rol2', ...]
                ],
                ...
            ]
        ],
        ...
    ];
*/

global $menu, $user;

$menu = [
    'Inicio' => [
        'url' => '/dashboard',
        'icon' => 'home-outline', 
        'private' => true
    ],
    'Usuarios' => [
        'url' => '/users/list',
        'icon' => 'person-outline', 
        'private' => true
    ]
];
