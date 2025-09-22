<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/src/config/database.php';
require_once __DIR__ . '/src/controllers/ProjectController.php';
require_once __DIR__ . '/src/controllers/TemplateController.php';

$project_id = $_GET['project_id'] ?? null;
if (!$project_id) { echo "No project id"; exit; }

$projCtrl = new ProjectController($pdo);
$templateCtrl = new TemplateController($pdo);

$project = $projCtrl->getProject($project_id, $_SESSION['user_id'] ?? null);
if (!$project) { echo "Project not found"; exit; }

$layout = json_decode($project['layout'] ?? '[]', true);
?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>Preview - <?= htmlspecialchars($project['title'] ?? 'Project') ?></title>
<link rel="stylesheet" href="public/assets/css/main.css">
</head>
<body>
<?php
foreach ($layout as $block) {
    // if block has direct html (override), render that
    if (!empty($block['html'])) {
        echo $block['html'];
        continue;
    }
    // else if theme + section present, render from files
    $theme = $block['theme'] ?? null;
    $section = $block['section'] ?? null;
    if ($theme && $section) {
        $html = $templateCtrl->renderSection($theme, $section);
        if ($html !== null) {
            echo $html;
            continue;
        }
    }
    // fallback: if block.type exists and content structure, render minimal
    if (!empty($block['type']) && !empty($block['content'])) {
        // simple render: hero/features/cta
        switch ($block['type']) {
            case 'hero':
                echo '<section><h1>'.htmlspecialchars($block['content']['heading'] ?? '').'</h1><p>'.htmlspecialchars($block['content']['subheading'] ?? '').'</p></section>';
                break;
            case 'features':
                echo '<section>';
                foreach ($block['content'] as $f) {
                    echo '<div><h3>'.htmlspecialchars($f['title'] ?? '').'</h3><p>'.htmlspecialchars($f['desc'] ?? '').'</p></div>';
                }
                echo '</section>';
                break;
            case 'cta':
                echo '<section><a href="'.htmlspecialchars($block['content']['link'] ?? '#').'">'.htmlspecialchars($block['content']['text'] ?? 'CTA').'</a></section>';
                break;
            default:
                echo $block['html'] ?? '';
        }
    }
}
?>
</body>
</html>
