<?php
namespace Jaktfeltcup\Helpers;

require_once __DIR__ . '/../Core/Database.php';

use Jaktfeltcup\Core\Database;

class ContentHelper {
    private $database;

    public function __construct(Database $database) {
        $this->database = $database;
    }

    public function getPageContent(string $page_key, string $section_key, string $lang = 'no'): array {
        try {
            $content = $this->database->queryOne(
                "SELECT title, content FROM jaktfelt_page_content WHERE page_key = ? AND section_key = ? AND language = ?",
                [$page_key, $section_key, $lang]
            );
            return $content ?: ['title' => '', 'content' => ''];
        } catch (\Exception $e) {
            error_log("Error fetching page content for $page_key/$section_key: " . $e->getMessage());
            return ['title' => '', 'content' => ''];
        }
    }

    public function getPageAllContent(string $page_key, string $lang = 'no'): array {
        try {
            $contents = $this->database->queryAll(
                "SELECT section_key, title, content FROM jaktfelt_page_content WHERE page_key = ? AND language = ?",
                [$page_key, $lang]
            );
            $result = [];
            foreach ($contents as $item) {
                $result[$item['section_key']] = ['title' => $item['title'], 'content' => $item['content']];
            }
            return $result;
        } catch (\Exception $e) {
            error_log("Error fetching all page content for $page_key: " . $e->getMessage());
            return [];
        }
    }

    public function updatePageContent(string $page_key, string $section_key, string $title, string $content, int $updated_by, string $lang = 'no'): bool {
        try {
            // Validate parameters
            if (empty($page_key) || empty($section_key) || empty($title) || empty($content)) {
                error_log("ContentHelper::updatePageContent - Invalid parameters: page_key=$page_key, section_key=$section_key, title=$title, content=$content");
                return false;
            }
            
            if (!$updated_by || $updated_by <= 0) {
                error_log("ContentHelper::updatePageContent - Invalid updated_by: $updated_by");
                return false;
            }
            
            $existing = $this->database->queryOne(
                "SELECT id FROM jaktfelt_page_content WHERE page_key = ? AND section_key = ? AND language = ?",
                [$page_key, $section_key, $lang]
            );

            if ($existing) {
                $this->database->execute(
                    "UPDATE jaktfelt_page_content SET title = ?, content = ?, updated_at = NOW(), updated_by = ? WHERE id = ?",
                    [$title, $content, $updated_by, $existing['id']]
                );
                error_log("ContentHelper::updatePageContent - Updated existing record for $page_key/$section_key");
            } else {
                $this->database->execute(
                    "INSERT INTO jaktfelt_page_content (page_key, section_key, title, content, language, updated_by) VALUES (?, ?, ?, ?, ?, ?)",
                    [$page_key, $section_key, $title, $content, $lang, $updated_by]
                );
                error_log("ContentHelper::updatePageContent - Inserted new record for $page_key/$section_key");
            }
            return true;
        } catch (\Exception $e) {
            error_log("ContentHelper::updatePageContent - Error for $page_key/$section_key: " . $e->getMessage());
            return false;
        }
    }
}
