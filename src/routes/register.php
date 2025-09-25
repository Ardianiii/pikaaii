<?php
// No session_start() here because it's already started in index.php

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $result = $auth->register($name, $email, $password);

    if ($result['status']) {
        // Registration successful, redirect to login with message
        header('Location: index.php?page=login&msg=registered');
        exit;
    } else {
        $error = $result['message'];
    }
}

// Include the register form template
include __DIR__ . '/../../templates/register.php';