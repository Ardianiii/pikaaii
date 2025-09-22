<?
class TemplateController {
    private $pdo;
    private $templatesPath;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->templatesPath = __DIR__ . '/../../templates';
    }

    // Get all templates from DB
    public function getAllTemplates() {
        $stmt = $this->pdo->query("SELECT * FROM templates");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Get all sections of a template from folder
    public function getTemplateSections($templateName) {
        $folder = $this->templatesPath . '/' . $templateName;
        $sections = [];
        if (is_dir($folder)) {
            foreach (glob($folder . '/*.php') as $file) {
                $sections[] = basename($file); // e.g., hero.php
            }
        }
        return $sections;
    }

    // Render a section
    public function renderSection($templateName, $sectionFile, $data = []) {
        $file = $this->templatesPath . "/$templateName/$sectionFile";
        if (file_exists($file)) {
            extract($data); // makes variables like $heading, $subheading available
            ob_start();
            include $file;
            return ob_get_clean();
        }
        return '';
    }
}
