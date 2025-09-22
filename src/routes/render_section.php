<?
session_start();
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    echo "Not logged in";
    exit;
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/TemplateController.php';

$templateName = $_GET['template'] ?? null;
$sectionFile = $_GET['section'] ?? null;

// Optional: get default content variables
$data = $_GET['data'] ?? [];

$templateController = new TemplateController($pdo);

if ($templateName && $sectionFile) {
    echo $templateController->renderSection($templateName, $sectionFile, $data);
} else {
    http_response_code(400);
    echo "Template or section missing";
}
