<?php
if (session_status() === PHP_SESSION_NONE) session_start();
header('Content-Type: application/json');
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/controllers/TemplateController.php';

if (empty($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success'=>false,'message'=>'Not authenticated']);
    exit;
}

$theme = $_POST['theme'] ?? '';
$prompt = $_POST['prompt'] ?? '';
if (!preg_match('/^[a-zA-Z0-9_\-]+$/', $theme) || empty($prompt)) {
    http_response_code(400);
    echo json_encode(['success'=>false,'message'=>'Invalid input']);
    exit;
}

$templatesPath = realpath(__DIR__ . '/../../templates');
$themeDir = $templatesPath . '/' . basename($theme);
if (!is_dir($themeDir)) {
    http_response_code(404);
    echo json_encode(['success'=>false,'message'=>'Theme not found']);
    exit;
}

// generate a slug
$slug = 'ai-generated-' . time();
$filename = $themeDir . '/' . $slug . '.php';

// STUB: generate HTML from prompt (replace with real AI call)
function ai_generate_html_from_prompt($prompt, $theme) {
    $heading = htmlspecialchars(substr($prompt, 0, 50));
    return "<section class=\"$theme-ai-generated\">\n  <div class=\"container\">\n    <h2 contenteditable=\"true\">{$heading}</h2>\n    <p contenteditable=\"true\">AI-generated content for: ".htmlspecialchars($prompt)."</p>\n    <a href=\"#\" class=\"btn\" contenteditable=\"true\">Call to action</a>\n  </div>\n</section>\n";
}

$html = ai_generate_html_from_prompt($prompt, basename($theme));

// write file safely
if (file_put_contents($filename, $html) === false) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Failed to write file']);
    exit;
}

echo json_encode(['success'=>true,'theme'=>basename($theme),'section'=>$slug]);
exit;
