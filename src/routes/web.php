<?php
session_start();

require_once dirname(__DIR__) . '/config/database.php';
require_once dirname(__DIR__) . '/controllers/AuthController.php';

$auth = new AuthController($pdo);
$page = $_GET['page'] ?? 'home';

// Routing
switch ($page) {
    case 'register':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $auth->register($_POST['name'], $_POST['email'], $_POST['password']);
            if ($result['status']) {
                header('Location: index.php?page=login&msg=registered');
                exit;
            }
            $error = $result['message'];
        }
        include dirname(__DIR__, 2) . '/public/register.php';
        break;

    case 'login':
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $result = $auth->login($_POST['email'], $_POST['password']);
            if ($result['status']) {
                header('Location: index.php?page=dashboard');
                exit;
            }
            $error = $result['message'];
        }
        include dirname(__DIR__, 2) . '/public/login.php';
        break;

    case 'logout':
        $auth->logout();
        header('Location: index.php?page=login');
        exit;

    case 'dashboard':
        if (!$auth->isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }
        include dirname(__DIR__, 2) . '/public/dashboard.php';
        break;

    default:
        include dirname(__DIR__, 2) . '/public/index.php';
        break;
}
