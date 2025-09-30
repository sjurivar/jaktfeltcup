<?php
/**
 * Inline Edit Helper
 * Helper functions for inline content editing
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/ViewHelper.php';
require_once __DIR__ . '/ContentHelper.php';

/**
 * Check if user can edit content inline
 */
function can_edit_inline() {

    if (!isset($_SESSION['user_id'])) {
        return false;
    }

    try {
        // Initialize database connection
        global $db_config;
        $database = new \Jaktfeltcup\Core\Database($db_config);
        
        $user_roles = $database->queryAll(
            "SELECT r.role_name FROM jaktfelt_user_roles ur 
             JOIN jaktfelt_roles r ON ur.role_id = r.id 
             WHERE ur.user_id = ?", 
            [$_SESSION['user_id']]
        );
        $user_roles = array_column($user_roles, 'role_name');
        
        return in_array('contentmanager', $user_roles) || in_array('admin', $user_roles);
    } catch (Exception $e) {
        error_log("can_edit_inline error: " . $e->getMessage());
        return false;
    }
}

/**
 * Render editable content
 */
function render_editable_content($page_key, $section_key, $default_title = '', $default_content = '') {
    // TEMPORARY: Database calls disabled for evaluation
    // TODO: Re-enable after deciding what should be editable
    
    /*
    try {
        // Initialize database connection
        global $db_config;
        $database = new \Jaktfeltcup\Core\Database($db_config);
        $contentHelper = new \Jaktfeltcup\Helpers\ContentHelper($database);
        $content = $contentHelper->getPageContent($page_key, $section_key);
        
        if (empty($content['title']) && empty($content['content'])) {
            $content = ['title' => $default_title, 'content' => $default_content];
        }
        if (can_edit_inline()) {
            $editor_data = render_inline_editor($page_key, $section_key, $content['title'], $content['content']);
            return [
                'title' => $content['title'],
                'content' => $content['content'],
                'editor_html' => $editor_data['editor_html']
            ];
        } else {
            return [
                'title' => $content['title'],
                'content' => $content['content'],
                'editor_html' => null
            ];
        }
    } catch (Exception $e) {
        error_log("render_editable_content error: " . $e->getMessage());
        return [
            'title' => $default_title,
            'content' => $default_content,
            'editor_html' => null
        ];
    }
    */
    
    // TEMPORARY: Return default content without database calls
    return [
        'title' => $default_title,
        'content' => $default_content,
        'editor_html' => null
    ];
}

/**
 * Render inline editor
 */
function render_inline_editor($page_key, $section_key, $title, $content) {
    $editor_id = 'editor_' . $page_key . '_' . $section_key;
    
    return [
        'title' => $title,
        'content' => $content,
        'editor_html' => '
        <div class="content-editor" data-page="' . htmlspecialchars($page_key) . '" data-section="' . htmlspecialchars($section_key) . '" data-title="' . htmlspecialchars($title) . '" data-content="' . htmlspecialchars($content) . '">
            <div class="content-display">
                <h5 class="card-title">' . htmlspecialchars($title) . '</h5>
                <p class="card-text">' . htmlspecialchars($content) . '</p>
            </div>
            <div class="edit-trigger">
                <button class="btn btn-sm btn-outline-primary edit-btn" title="Rediger innhold">
                    <i class="fas fa-edit me-1"></i>Rediger
                </button>
            </div>
        </div>'
    ];
}

/**
 * Get inline edit CSS
 */
function get_inline_edit_css() {
    return '
    <style>
    .inline-editor {
        position: relative;
        border: 2px dashed transparent;
        padding: 10px;
        margin: 5px 0;
        transition: all 0.3s ease;
    }
    
    .inline-editor:hover {
        border-color: #007bff;
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    .inline-editor.editing {
        border-color: #28a745;
        background-color: rgba(40, 167, 69, 0.1);
    }
    
    .edit-indicator {
        position: absolute;
        top: 5px;
        right: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .inline-editor:hover .edit-indicator {
        opacity: 1;
    }
    
    .edit-controls {
        margin-top: 10px;
        padding: 10px;
        background-color: #f8f9fa;
        border-radius: 5px;
    }
    
    .edit-title[contenteditable="true"]:focus,
    .edit-content[contenteditable="true"]:focus {
        outline: none;
        background-color: #fff;
        border: 1px solid #007bff;
        border-radius: 3px;
        padding: 5px;
    }
    
    .edit-title {
        font-weight: bold;
        margin-bottom: 10px;
    }
    
    .edit-content {
        line-height: 1.6;
    }
    </style>';
}

/**
 * Get inline edit JavaScript
 */
function get_inline_edit_js() {
    return '
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Add edit indicators to all inline editors
        document.querySelectorAll(".inline-editor").forEach(function(editor) {
            const indicator = editor.querySelector(".edit-indicator");
            const controls = editor.querySelector(".edit-controls");
            const titleField = editor.querySelector(".edit-title");
            const contentField = editor.querySelector(".edit-content");
            const saveBtn = editor.querySelector(".save-edit");
            const cancelBtn = editor.querySelector(".cancel-edit");
            
            let originalTitle = titleField.textContent;
            let originalContent = contentField.textContent;
            
            // Show controls on click
            editor.addEventListener("click", function(e) {
                if (!editor.classList.contains("editing")) {
                    editor.classList.add("editing");
                    controls.style.display = "block";
                    indicator.style.display = "none";
                }
            });
            
            // Save changes
            saveBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                
                const page = editor.dataset.page;
                const section = editor.dataset.section;
                const newTitle = titleField.textContent.trim();
                const newContent = contentField.textContent.trim();
                
                // Send AJAX request
                fetch("' . base_url('admin/content/handlers/save_inline_edit.php') . '", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: "page_key=" + encodeURIComponent(page) + 
                          "&section_key=" + encodeURIComponent(section) + 
                          "&title=" + encodeURIComponent(newTitle) + 
                          "&content=" + encodeURIComponent(newContent)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        editor.classList.remove("editing");
                        controls.style.display = "none";
                        indicator.style.display = "block";
                        originalTitle = newTitle;
                        originalContent = newContent;
                        
                        // Show success message
                        showNotification("Endringer lagret!", "success");
                    } else {
                        showNotification("Feil ved lagring: " + data.message, "error");
                    }
                })
                .catch(error => {
                    showNotification("Feil ved lagring: " + error.message, "error");
                });
            });
            
            // Cancel changes
            cancelBtn.addEventListener("click", function(e) {
                e.stopPropagation();
                titleField.textContent = originalTitle;
                contentField.textContent = originalContent;
                editor.classList.remove("editing");
                controls.style.display = "none";
                indicator.style.display = "block";
            });
        });
    });
    
    function showNotification(message, type) {
        const notification = document.createElement("div");
        notification.className = "alert alert-" + (type === "success" ? "success" : "danger") + " alert-dismissible fade show";
        notification.style.position = "fixed";
        notification.style.top = "20px";
        notification.style.right = "20px";
        notification.style.zIndex = "9999";
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }
    </script>';
}
