<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

$title = 'Create New Project - PikaAi';

require_once __DIR__ . '/../controllers/ProjectController.php';
$projectController = new ProjectController($pdo);

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titleInput = trim($_POST['title'] ?? '');
    if (!$titleInput) {
        $error = 'Project title is required.';
    } else {
        // Create project
        $project_id = $projectController->createProject($_SESSION['user_id'], $titleInput);

        if ($project_id) {
            // Redirect to builder with the new project ID
            header("Location: index.php?page=builder&project_id=" . $project_id);
            exit;
        } else {
            $error = 'Failed to create project. Try again.';
        }
    }
}

include 'templates/header.php';
?>

<div class="create-project-container">
    <h1>Create New Project</h1>
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <input type="text" name="title" placeholder="Project Title" required>
        <button type="submit">Create Project</button>
    </form>

    <a href="index.php?page=dashboard" class="btn">Back to Dashboard</a>
</div>

<style>
.create-project-container {
    max-width: 600px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.create-project-container input {
    width: 100%;
    padding: 10px;
    margin-bottom: 15px;
    box-sizing: border-box;
}
.create-project-container button,
.create-project-container .btn {
    padding: 10px 15px;
    margin-top: 5px;
    text-decoration: none;
    border: none;
    background: #007bff;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
}
.create-project-container .btn {
    background: #6c757d;
}
</style>

<?php include 'templates/footer.php'; ?>
