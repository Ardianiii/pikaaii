<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/controllers/ProjectController.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Not authenticated']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$project_id = $data['project_id'] ?? null;
$layout = $data['layout'] ?? null;

if (!$project_id || !is_array($layout)) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>'Invalid payload']);
    exit;
}

$projectController = new ProjectController($pdo);
$userId = $_SESSION['user_id'];

// verify ownership
$project = $projectController->getProject($project_id, $userId);
if (!$project) {
    http_response_code(403);
    echo json_encode(['success'=>false,'message'=>'Project not found or access denied']);
    exit;
}

// sanitize / limit layout size if you want — minimal example:
$layoutJson = json_encode($layout, JSON_UNESCAPED_UNICODE);

$ok = $projectController->saveLayout($project_id, $userId, $layoutJson);

if ($ok) {
    echo json_encode(['success'=>true,'message'=>'Layout saved']);
} else {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Failed to save layout']);
}
exit;
