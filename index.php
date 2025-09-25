<?php
session_start();

// Database + Auth shared across routes
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/controllers/AuthController.php';

// Create AuthController instance once here
$auth = new AuthController($pdo);ss

$page = $_GET['page'] ?? 'home';

switch ($page) {
    // ========== AUTH ==========
    case 'login':
        // $auth is already defined above and available here
        include __DIR__ . '/src/routes/login.php';
        break;

    case 'register':
        include __DIR__ . '/src/routes/register.php';
        break;

    case 'logout':
        include __DIR__ . '/src/routes/logout.php';
        break;

    // ========== DASHBOARD / PROJECTS ==========
    case 'dashboard':
        include __DIR__ . '/src/routes/dashboard.php';
        break;

    case 'create_project':
        include __DIR__ . '/src/routes/create_project.php';
        break;

    case 'builder':
        include __DIR__ . '/src/routes/builder.php';
        break;

    // ========== API / ACTION ROUTES ==========
    case 'save_layout':
        include __DIR__ . '/src/routes/save_layout.php';
        break;

    case 'render_section':
        include __DIR__ . '/src/routes/render_section.php';
        break;

    case 'preview':
        include __DIR__ . '/src/routes/preview.php';
        break;

    case 'admin_templates':
        include __DIR__ . '/src/routes/admin_templates.php';
        break;

    case 'generate_ai_section':
        include __DIR__ . '/src/routes/generate_ai_section.php';
        break;

    // ========== DEFAULT ==========
    default:
        echo "<h1>Welcome to PikaAi</h1>
              <p><a href='index.php?page=login'>Login</a> or 
              <a href='index.php?page=register'>Register</a></p>";
        break;
}
