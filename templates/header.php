<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'PikaAi' ?></title>
    <link rel="stylesheet" href="./assets/css/main.css">
</head>
<body>
<header>
    <nav>
        <a href="?page=home">PikaAi</a>
        <?php if (!empty($_SESSION['user_id'])): ?>
            <a href="?page=dashboard">Dashboard</a>
            <a href="?page=logout">Logout</a>
        <?php else: ?>
            <a href="?page=login">Login</a>
            <a href="?page=register">Register</a>
        <?php endif; ?>
    </nav>
</header>
<main>
