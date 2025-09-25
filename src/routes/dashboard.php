<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once dirname(__DIR__) . '/controllers/ProjectController.php';
$projectController = new ProjectController($pdo);
$projects = $projectController->getUserProjects($_SESSION['user_id']);

$title = 'Dashboard - PikaAi';
include 'templates/header.php';
?>

<div class="dashboard-container">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['user_name']) ?>!</h1>
    <p>Manage your projects and use AI tools.</p>

    <!-- Quick actions -->
    <div class="dashboard-actions">
        <a href="index.php?page=create_project" class="btn">+ Create New Project</a>
        <a href="index.php?page=logout" class="btn btn-logout">Logout</a>
    </div>

    <!-- Projects list -->
    <div class="projects-list">
        <h2>Your Projects</h2>
        <?php if (empty($projects)): ?>
            <p>No projects yet. Click "Create New Project" to start.</p>
        <?php else: ?>
            <ul>
                <?php foreach ($projects as $project): ?>
                    <li>
                        <strong><?= htmlspecialchars($project['title']) ?></strong>
                        <a href="index.php?page=builder&project_id=<?= $project['id'] ?>" class="btn btn-edit">Edit</a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background: #fff;
    border-radius: 10px;
    box-shadow: 0 0 15px rgba(0,0,0,0.1);
}
.dashboard-actions {
    margin: 20px 0;
}
.dashboard-actions .btn {
    display: inline-block;
    padding: 10px 15px;
    margin-right: 10px;
    background: #007bff;
    color: #fff;
    border-radius: 5px;
    text-decoration: none;
}
.dashboard-actions .btn-logout {
    background: #dc3545;
}
.projects-list ul {
    list-style: none;
    padding: 0;
}
.projects-list li {
    padding: 10px;
    border-bottom: 1px solid #eee;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.projects-list .btn-edit {
    background: #28a745;
    padding: 5px 10px;
    color: #fff;
    border-radius: 4px;
    text-decoration: none;
}
</style>

<?php include 'templates/footer.php'; ?>
