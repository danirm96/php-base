<?php

namespace Controllers;
use Config\Controller;

class Auth extends Controller {
    public $private = false;

    public function login($params) {
        if($this->checkSesion()) {
            header('Location: /dashboard');
            exit();
        }
        charge_view('auth/login', [], false);
    }

    public function access($params) {
        if(method_request() !== 'POST') {
            error_404('Method Not Allowed');
        }

        $data = $_POST;
        if(empty($data['email']) || empty($data['password'])) {
            charge_view('auth/login', ['error' => 'Por favor, completa todos los campos.', 'email' => $data['email'] ?? '', 'password' => $data['password'] ?? ''], false);
            return;
        }

        $user = new \User();
        $login = $user->login($data['email'], $data['password']);

        if(!$login) {
            charge_view('auth/login', ['error' => 'Credenciales incorrectas.', 'email' => $data['email'] ?? '', 'password' => $data['password'] ?? ''], false);
            return;
        } else {
            header('Location: /dashboard');
            exit();
        }
    }

    private function checkSesion() {
        if(isset($_SESSION['user'])) {
            if(time() > $_SESSION['user']['expires']) {
                session_unset();
                session_destroy();
                charge_view('auth/login', ['error' => 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.'], false);
                exit();
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}