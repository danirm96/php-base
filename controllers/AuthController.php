<?php
namespace Controllers;

class AuthController {

    public function login() {
        $auth = new \Auth\AuthUser();
        $auth->login();
    }

    public function access() {
        $auth = new \Auth\AuthUser();
        $auth->access();
    }

    public function logout() {
        session_unset();
        session_destroy();
        header('Location: ' . BASE_URL );
        exit();
    }
}