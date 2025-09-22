<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require_once __DIR__ . '/../../src/controllers/TemplateController.php';
require_once __DIR__ . '/../../src/config/database.php';

$templateController = new TemplateController($pdo ?? null);

// add your own admin check here
if (empty($_SESSION['user_id']) /* || !is_admin($_SESSION['user_id']) */) {
    echo "Access denied";
    exit;
}

$themes = $templateController->getThemesAndSections();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['delete'])) {
    $theme = basename($_POST['theme']);
    $section = basename($_POST['section']);
    $file = realpath(__DIR__ . '/../../templates/' . $theme . '/' . $section . '.php');
    if ($file && strpos($file, realpath(__DIR__ . '/../../templates/' . $theme)) === 0) {
        unlink($file);
        header('Location: '.$_SERVER['REQUEST_URI']);
        exit;
    }
}
?>
<!doctype html><html><head><meta charset="utf-8"><title>Admin Templates</title></head><body>
<h1>Templates on disk</h1>
<?php foreach ($themes as $theme => $sections): ?>
  <h2><?=htmlspecialchars($theme)?></h2>
  <ul>
    <?php foreach ($sections as $section): ?>
      <li>
        <?=htmlspecialchars($section)?>
        <form method="post" style="display:inline">
          <input type="hidden" name="theme" value="<?=htmlspecialchars($theme)?>">
          <input type="hidden" name="section" value="<?=htmlspecialchars($section)?>">
          <button type="submit" name="delete" value="1" onclick="return confirm('Delete file?')">Delete</button>
        </form>
      </li>
    <?php endforeach; ?>
  </ul>
<?php endforeach; ?>
</body></html>
