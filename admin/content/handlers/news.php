<?php
/**
 * News Handler
 * Handles CRUD operations for news items
 */

session_start();

require_once __DIR__ . '/../../../config/config.php';
require_once __DIR__ . '/../../../src/Core/Database.php';
require_once __DIR__ . '/../../../src/Helpers/ViewHelper.php';

// Initialize database connection
global $db_config;
$database = new \Jaktfeltcup\Core\Database($db_config);

// Handle different actions
$action = $_POST['action'] ?? 'create';

// If news_id is provided, it's an update
if (!empty($_POST['news_id'])) {
    $action = 'update';
}

try {
    switch ($action) {
        case 'create':
        case 'update':
            $news_id = $_POST['news_id'] ?? null;
            $title = trim($_POST['title'] ?? '');
            $excerpt = trim($_POST['excerpt'] ?? '');
            $content = trim($_POST['content'] ?? '');
            $image_url = trim($_POST['image_url'] ?? '');

            // Validation
            if (empty($title) || empty($excerpt) || empty($content)) {
                throw new Exception('Alle felter må fylles ut.');
            }

            if ($action === 'create') {
                // Create new news item
                $database->execute(
                    "INSERT INTO jaktfelt_news (title, excerpt, content, image_url) VALUES (?, ?, ?, ?)",
                    [$title, $excerpt, $content, $image_url ?: null]
                );
                $_SESSION['success'] = 'Nyhet opprettet!';
            } else {
                // Update existing news item
                if (!$news_id) {
                    throw new Exception('Nyhet ID mangler.');
                }
                
                $database->execute(
                    "UPDATE jaktfelt_news SET title = ?, excerpt = ?, content = ?, image_url = ?, updated_at = NOW() WHERE id = ?",
                    [$title, $excerpt, $content, $image_url ?: null, $news_id]
                );
                $_SESSION['success'] = 'Nyhet oppdatert!';
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception('Nyhet ID mangler.');
            }

            $database->execute("DELETE FROM jaktfelt_news WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Nyhet slettet!';
            break;

        default:
            throw new Exception('Ugyldig handling.');
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    error_log("News handler error: " . $e->getMessage());
}

// Redirect back to content management
header('Location: ' . base_url('admin/content'));
exit;
