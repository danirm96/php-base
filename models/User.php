<?php

namespace Models;
use core\classes\BaseModel;
use Models\Role;

class User extends BaseModel
{
    private $isLogged;

    public $table = 'users';
    public $primaryKey = 'id';
    public $timestamps = true;
    
    public $searchable = ['name', 'email'];

    public $head = ['ID', 'Nombre', 'Email', 'Rol', 'Estado'];

    public $label = 'Usuario';

    public $plural = 'Usuarios';

    public $canCreate = true;

    public $actions = [
        'view' => ['Ver', '/users/view'],
        'edit' => ['Editar', '/users/edit'],
        'delete' => ['Eliminar', '/users/delete'],
    ];


    public function isLogged()
    {
        return $this->isLogged;
    }

    public function createUser($name, $email, $role, $status, $password) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $userData = [
            'name' => $name,
            'email' => $email,
            'role_id' => $role,
            'status' => $status,
            'password' => $hashedPassword
        ];

        // check if user exists
        $existingUser = $this->findBy('email', $email);
        if ($existingUser) {
            return false;
        }
        return $this->create($userData);
    }

    public function login($email, $password)
    {
        $user = $this->findBy('email', $email);
        if ($user) {
            if (password_verify($password, $user['password'])) {
                $roleName = (new Role())->findBy('id', $user['role_id'])['name'] ?? 'user';
                $token = $this->generate_token();
                $_SESSION['user'] = array(
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $roleName,
                    'status' => $user['status'],
                    'token' => $token,
                    'expires' => time() + EXPIRED_SESSION
                );
                $this->isLogged = true;
                $this->update($user['id'], ['token' => $token]);
                return true;
            }
        }
        return false;
    }

    public function generate_token()
    {
        return bin2hex(random_bytes(16));
    }

    public function columns(): array
    {
        return array(
            'id' => array(
                'type' => 'int',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => false,
                'auto_increment' => true,
                'label' => 'ID'
            ),
            'name' => array(
                'type' => 'varchar',
                'max_length' => 100,
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Nombre',
                'type_input' => 'text'

            ),
            'email' => array(
                'type' => 'varchar',
                'max_length' => 100,
                'required' => true,
                'unique' => true,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Email',
                'type_input' => 'email'
            ),
            'role_id' => array(
                'type' => 'int',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Rol',
                'type_input' => 'select',
                'foreign_key' => array(
                    'table' => 'roles',
                    'column' => 'id',
                    'display_column' => 'description'
                )
            ),
            'status' => array(
                'type' => 'int',
                'required' => true,
                'show_in_list' => true,
                'show_in_form' => true,
                'label' => 'Estado',
                'type_input' => 'select',
                'select_options' => [
                    0 => 'Activo',
                    1 => 'Inactivo'
                ]
            ),
            'password' => array(
                'type' => 'varchar',
                'max_length' => 255,
                'required' => true,
                'show_in_list' => false,
                'encrypted' => true,
                'show_in_form' => true,
                'type_input' => 'password',
                'label' => 'Contraseña'
            ),
            'token' => array(
                'type' => 'varchar',
                'max_length' => 255,
                'required' => false,
                'show_in_list' => false,
                'encrypted' => true,
                'show_in_form' => false,
                'label' => 'Token'
            )
        );
    }
}
