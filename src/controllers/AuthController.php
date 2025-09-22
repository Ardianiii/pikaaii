<?php
require_once dirname(__DIR__) . '/models/User.php';

class AuthController {
    private $userModel;

    public function __construct($pdo) {
        $this->userModel = new User($pdo);
    }

    public function register($name, $email, $password) {
        // Basic validation
        $name = trim($name);
        $email = trim($email);
        $password = trim($password);

        if (strlen($name) < 2 || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            return ['status' => false, 'message' => 'Invalid input'];
        }

        return $this->userModel->create($name, $email, $password);
    }

    public function login($email, $password) {
        $user = $this->userModel->verifyPassword($email, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            return ['status' => true, 'message' => 'Logged in successfully'];
        }
        return ['status' => false, 'message' => 'Invalid credentials'];
    }

    public function logout() {
        session_destroy();
    }

    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}
