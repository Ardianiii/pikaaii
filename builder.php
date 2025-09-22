<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php?page=login');
    exit;
}

require_once __DIR__ . '/src/controllers/ProjectController.php';

$projectId = $_GET['project_id'] ?? null;
if (!$projectId) {
    header('Location: index.php?page=dashboard');
    exit;
}

$title = 'Website Builder - PikaAi';
include 'templates/header.php';
?>

<div class="builder-container">
    <!-- Toolbar -->
    <div class="builder-toolbar">
        <button id="add-block" class="btn">+ Add Block</button>
        <button id="save-layout" class="btn btn-save">💾 Save Layout</button>
        <a href="index.php?page=dashboard" class="btn btn-exit">⬅ Back to Dashboard</a>
    </div>

    <!-- Drag & Drop Area -->
    <div id="blocks-area" class="blocks-area" data-project-id="<?= htmlspecialchars($projectId) ?>">
        <div class="block">Welcome to your website</div>
    </div>
</div>

<!-- JS for draggable blocks -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/Draggable.min.js"></script>
<script src="assets/js/builder.js"></script>

<style>
.builder-container {
    max-width: 1000px;
    margin: 30px auto;
    padding: 20px;
}

.builder-toolbar {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.builder-toolbar .btn {
    padding: 10px 15px;
    background: #007bff;
    color: white;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
}
.builder-toolbar .btn-save { background: #28a745; }
.builder-toolbar .btn-exit { background: #6c757d; }

.blocks-area {
    min-height: 500px;
    padding: 20px;
    background: #f8f9fa;
    border: 2px dashed #ccc;
    border-radius: 10px;
}

.block {
    padding: 20px;
    margin-bottom: 10px;
    background: #f1f1f1;
    border-radius: 6px;
    cursor: grab;
}
</style>

<?php include 'templates/footer.php'; ?>
