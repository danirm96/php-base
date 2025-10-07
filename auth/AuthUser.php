<?php
namespace Auth;
use core\classes\Auth;
use Models\User;

class AuthUser extends Auth {

    public function canAccess($roles = []): bool {
        
        global $user;
        if(!$user) {
            return false;
        }

        if(!in_array($user['role'], $roles)) {
            return false;
        }

        return true;
    }

    public function login() {
        if($this->checkSesion()) {
            charge_layout('general', ['dashboard/index'], ['title' => 'Dashboard']);
            exit();
        }
        charge_layout('nouser', ['auth/login'], ['title' => 'Login']);
    }

    private function checkSesion() {
        if(isset($_SESSION['user']) && $_SESSION['user'] !== null && is_array($_SESSION['user']) && isset($_SESSION['user']['expires'])) {
            if(time() > $_SESSION['user']['expires']) {
                session_unset();
                session_destroy();
                charge_layout('nouser', ['auth/login'], ['title' => 'Login', 'error' => 'Tu sesión ha expirado. Por favor, inicia sesión de nuevo.']);
                exit();
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function access() {
        if(method_request() !== 'POST') {
            error_404();
        }

        $data = $_POST;
        if(empty($data['email']) || empty($data['password'])) {
            charge_layout('nouser', ['auth/login'], ['title' => 'Login', 'error' => 'Por favor, completa todos los campos.', 'email' => $data['email'] ?? '', 'password' => $data['password'] ?? '']);
            return;
        }

        $user = new User();
        $login = $user->login($data['email'], $data['password']);


        if(!$login) {
            charge_layout('nouser', ['auth/login'], ['title' => 'Login', 'error' => 'Credenciales incorrectas.', 'email' => $data['email'] ?? '', 'password' => $data['password'] ?? '']);
            return;
        } else {
            header('Location: ' . HOME_URL);
            exit();
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
    }
}