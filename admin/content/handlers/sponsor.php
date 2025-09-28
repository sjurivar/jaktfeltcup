<?php
/**
 * Sponsor Handler
 * Handles CRUD operations for sponsors
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

// If sponsor_id is provided, it's an update
if (!empty($_POST['sponsor_id'])) {
    $action = 'update';
}

try {
    switch ($action) {
        case 'create':
        case 'update':
            $sponsor_id = $_POST['sponsor_id'] ?? null;
            $name = trim($_POST['name'] ?? '');
            $description = trim($_POST['description'] ?? '');
            $sponsor_level = $_POST['sponsor_level'] ?? 'bronze';
            $website_url = trim($_POST['website_url'] ?? '');

            // Validation
            if (empty($name) || empty($description)) {
                throw new Exception('Navn og beskrivelse må fylles ut.');
            }

            // Handle file upload
            $logo_filename = null;
            $logo_url = null;
            
            if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = __DIR__ . '/../../../assets/images/sponsors/';
                
                // Create directory if it doesn't exist
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }

                $file_info = pathinfo($_FILES['logo']['name']);
                // Use sponsor-friendly filename to avoid ad-blocker issues
                $logo_filename = 'sponsor_' . time() . '_' . strtolower(str_replace(' ', '_', $name)) . '.' . $file_info['extension'];
                $upload_path = $upload_dir . $logo_filename;
                
                // Validate file type
                $allowed_types = ['png', 'jpg', 'jpeg', 'gif', 'svg'];
                if (!in_array(strtolower($file_info['extension']), $allowed_types)) {
                    throw new Exception('Kun PNG, JPG, JPEG, GIF og SVG filer er tillatt.');
                }

                // Validate file size (2MB max)
                if ($_FILES['logo']['size'] > 2 * 1024 * 1024) {
                    throw new Exception('Filen er for stor. Maksimal størrelse er 2MB.');
                }

                if (move_uploaded_file($_FILES['logo']['tmp_name'], $upload_path)) {
                    // Use relative path that works with the application
                    $logo_url = base_url('assets/images/sponsors/' . $logo_filename);
                } else {
                    throw new Exception('Kunne ikke laste opp filen.');
                }
            }

            if ($action === 'create') {
                // Create new sponsor
                $database->execute(
                    "INSERT INTO jaktfelt_sponsors (name, description, sponsor_level, website_url, logo_filename, logo_url) VALUES (?, ?, ?, ?, ?, ?)",
                    [$name, $description, $sponsor_level, $website_url ?: null, $logo_filename, $logo_url]
                );
                $_SESSION['success'] = 'Sponsor opprettet!';
            } else {
                // Update existing sponsor
                if (!$sponsor_id) {
                    throw new Exception('Sponsor ID mangler.');
                }

                // If new logo uploaded, update logo fields
                if ($logo_filename) {
                    $database->execute(
                        "UPDATE jaktfelt_sponsors SET name = ?, description = ?, sponsor_level = ?, website_url = ?, logo_filename = ?, logo_url = ?, updated_at = NOW() WHERE id = ?",
                        [$name, $description, $sponsor_level, $website_url ?: null, $logo_filename, $logo_url, $sponsor_id]
                    );
                } else {
                    $database->execute(
                        "UPDATE jaktfelt_sponsors SET name = ?, description = ?, sponsor_level = ?, website_url = ?, updated_at = NOW() WHERE id = ?",
                        [$name, $description, $sponsor_level, $website_url ?: null, $sponsor_id]
                    );
                }
                $_SESSION['success'] = 'Sponsor oppdatert!';
            }
            break;

        case 'delete':
            $id = $_POST['id'] ?? null;
            if (!$id) {
                throw new Exception('Sponsor ID mangler.');
            }

            // Get sponsor info to delete logo file
            $sponsor = $database->queryOne("SELECT logo_filename FROM jaktfelt_sponsors WHERE id = ?", [$id]);
            if ($sponsor && $sponsor['logo_filename']) {
                $logo_path = __DIR__ . '/../../../assets/images/sponsors/' . $sponsor['logo_filename'];
                if (file_exists($logo_path)) {
                    unlink($logo_path);
                }
            }

            $database->execute("DELETE FROM jaktfelt_sponsors WHERE id = ?", [$id]);
            $_SESSION['success'] = 'Sponsor slettet!';
            break;

        default:
            throw new Exception('Ugyldig handling.');
    }

} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    error_log("Sponsor handler error: " . $e->getMessage());
}

// Redirect back to content management
header('Location: ' . base_url('admin/content'));
exit;
