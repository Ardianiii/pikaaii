<?php
require_once dirname(__DIR__) . '/config/database.php';

class User {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new user
    public function create($name, $email, $password, $plan = 'free') {
        // Check if email already exists
        if ($this->findByEmail($email)) {
            return ['status' => false, 'message' => 'Email already exists'];
        }

        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare(
            "INSERT INTO users (name, email, password_hash, plan, created_at, updated_at) 
             VALUES (?, ?, ?, ?, NOW(), NOW())"
        );
        $result = $stmt->execute([$name, $email, $password_hash, $plan]);

        if ($result) {
            return ['status' => true, 'message' => 'User created successfully'];
        }
        return ['status' => false, 'message' => 'Failed to create user'];
    }

    public function findByEmail($email) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function verifyPassword($email, $password) {
        $user = $this->findByEmail($email);
        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }
}
