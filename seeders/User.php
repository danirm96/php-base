<?php

$seed = array(
    array(
        'name' => 'Admin User',
        'email' => 'e@e.com',
        'role_id' => 1,
        'status' => '1',
        'password' => password_hash('123', PASSWORD_BCRYPT)
    )
);