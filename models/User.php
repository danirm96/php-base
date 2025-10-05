<?php

use Config\Model;

class User extends Model {

    private $name;
    private $email;
    private $password;
    private $role;
    private $status;
    private $isLogged;

    public $table = 'users';


    public function __construct()
    {

        if (isset($_SESSION['user'])) {
            $this->name = $_SESSION['user']['name'];
            $this->email = $_SESSION['user']['email'];
            $this->role = $_SESSION['user']['role'];
            $this->status = $_SESSION['user']['status'];
            $this->isLogged = true;
        } else {
            $this->name = null;
            $this->email = null;
            $this->role = null;
            $this->status = null;
            $this->isLogged = false;
        }   

    }


    public function isLogged()
    {
        return $this->isLogged;
    }

    public function createUser($name, $email, $role, $status, $password) {
        
    }

    
    public function columns() {
        return array('name' => array(
            'type' => 'varchar',
            'max_length' => 100,
            'required' => true,
            'show_in_list' => true

        ),
         'email' => array(
            'type' => 'varchar',
            'max_length' => 100,
            'required' => true,
            'show_in_list' => true
        ),
         'role_id' => array(
            'type' => 'int',
            'required' => true,
            'show_in_list' => true,
            'foreign_key' => array(
                'table' => 'roles',
                'column' => 'id',
                'display_column' => 'name'
            )
        ),
         'status' => array(
            'type' => 'int',
            'required' => true,
            'show_in_list' => true
        ),
         'password' => array(
            'type' => 'varchar',
            'max_length' => 255,
            'required' => true,
            'show_in_list' => false,
            'encrypted' => true
         ),
         'token' => array(
            'type' => 'varchar',
            'max_length' => 255,
            'required' => false,
            'show_in_list' => false,
            'encrypted' => true
         )
    );

}

    public function login($email, $password) {
        $user = $this->findBy('email', $email);
        if($user) {
            if(password_verify($password, $user['password'])) {
                $token = $this->generate_token();
                $_SESSION['user'] = array(
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'email' => $user['email'],
                    'role' => $user['role_id'],
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

    public function generate_token() {
        return bin2hex(random_bytes(16));
    }

}