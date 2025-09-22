<?php
require_once __DIR__ . '/User.php';

class Project {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Create a new project
    public function create($user_id, $title) {
        $stmt = $this->pdo->prepare("INSERT INTO projects (user_id, title, layout, status, created_at, updated_at) VALUES (?, ?, ?, 'draft', NOW(), NOW())");
        $layout = json_encode([]); // empty layout for now
        if ($stmt->execute([$user_id, $title, $layout])) {
            return $this->pdo->lastInsertId();
        }
        return false;
    }

    // Get all projects for a user
    public function getByUser($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM projects WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateLayout($project_id, $layout) {
    $stmt = $this->pdo->prepare("UPDATE projects SET layout = :layout, updated_at = NOW() WHERE id = :id");
    $success = $stmt->execute([
        ':layout' => json_encode($layout),
        ':id' => $project_id
    ]);

    if ($success) {
        return ['status' => true, 'message' => 'Layout saved successfully'];
    }
    return ['status' => false, 'message' => 'Failed to save layout'];
}

}
