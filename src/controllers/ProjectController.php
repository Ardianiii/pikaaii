<?php
require_once __DIR__ . '/../models/Project.php';

class ProjectController {
    private $pdo;
    private $projectModel;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->projectModel = new Project($pdo);
    }

    // Create a new project and return its ID
    public function createProject($user_id, $title) {
        return $this->projectModel->create($user_id, $title);
    }

    // Get all projects of a user
    public function getUserProjects($user_id) {
        return $this->projectModel->getByUser($user_id);
    }

    // Optional: get a single project by ID
    public function getProjectById($project_id, $user_id) {
        $projects = $this->getUserProjects($user_id);
        foreach ($projects as $project) {
            if ($project['id'] == $project_id) {
                return $project;
            }
        }
        return null;
    }
    public function saveLayout($user_id, $project_id, $layout) {
    // Fetch project
    $projects = $this->getUserProjects($user_id);
    $project = null;
    foreach ($projects as $p) {
        if ($p['id'] == $project_id) {
            $project = $p;
            break;
        }
    }

    if (!$project) {
        return ['status' => false, 'message' => 'Project not found'];
    }

    // Save JSON layout
    return $this->projectModel->updateLayout($project_id, $layout);
}

}
