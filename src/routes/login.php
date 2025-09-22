<?php
// No session_start() here because it's already started in index.php

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    // Use $auth from index.php
    $result = $auth->login($email, $password);

    if ($result['status']) {
        // Login successful
        $_SESSION['user_id'] = $result['user']['id'];
        $_SESSION['user_name'] = $result['user']['name'];
        header('Location: index.php?page=dashboard');
        exit;
    } else {
        $error = $result['message'];
    }
}

// Include the login form template
include __DIR__ . '/../../templates/login.php';
