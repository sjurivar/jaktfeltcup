<?php
/**
 * Common Footer Layout
 */

$show_footer = $show_footer ?? true;
$additional_js = $additional_js ?? '';
?>

<?php if ($show_footer): ?>
<!-- Footer -->
<footer class="bg-dark text-light py-4 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <h5>Nasjonal 15m Jaktfeltcup</h5>
                <p class="text-muted">Administrasjonssystem for skyteøvelse</p>
            </div>
            <div class="col-md-4 mb-3 mb-md-0">
                <h6 class="mb-3">Lenker</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <a href="<?= base_url('om-oss') ?>" class="text-light text-decoration-none">
                            <i class="fas fa-users me-2"></i>Om
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('dokumentasjon') ?>" class="text-light text-decoration-none">
                            <i class="fas fa-book me-2"></i>Dokumentasjon
                        </a>
                    </li>
                    <li class="mb-2">
                        <a href="<?= base_url('about') ?>" class="text-light text-decoration-none">
                            <i class="fas fa-info-circle me-2"></i>Teknisk info
                        </a>
                    </li>
                </ul>
            </div>
            <div class="col-md-4 text-md-end">
                <p class="text-muted mb-0">&copy; 2024 Nasjonal 15m Jaktfeltcup. Alle rettigheter forbeholdt.</p>
            </div>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Additional JavaScript -->
<?= $additional_js ?>

<!-- Content Edit JavaScript -->
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Add edit buttons to all content editors
    document.querySelectorAll(".content-editor").forEach(function(editor) {
        const editBtn = editor.querySelector(".edit-btn");
        
        // Show edit button on hover
        editor.addEventListener("mouseenter", function() {
            editBtn.style.opacity = "1";
        });
        
        editor.addEventListener("mouseleave", function() {
            editBtn.style.opacity = "0";
        });
        
        // Open modal on edit button click
        editBtn.addEventListener("click", function(e) {
            e.stopPropagation();
            openEditModal(editor);
        });
    });
});

function openEditModal(editor) {
    const page = editor.dataset.page;
    const section = editor.dataset.section;
    const currentTitle = editor.dataset.title;
    const currentContent = editor.dataset.content;
    
    // Create modal HTML
    const modalHtml = `
        <div class="modal fade content-edit-modal" id="contentEditModal" tabindex="-1" aria-labelledby="contentEditModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="contentEditModalLabel">
                            <i class="fas fa-edit me-2"></i>Rediger innhold
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="contentEditForm">
                            <input type="hidden" name="page_key" value="${page}">
                            <input type="hidden" name="section_key" value="${section}">
                            
                            <div class="mb-3">
                                <label for="editTitle" class="form-label fw-bold">Tittel</label>
                                <input type="text" class="form-control" id="editTitle" name="title" value="${currentTitle}" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="editContent" class="form-label fw-bold">Innhold</label>
                                <textarea class="form-control" id="editContent" name="content" rows="4" required>${currentContent}</textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>Avbryt
                        </button>
                        <button type="button" class="btn btn-success" id="saveContentBtn">
                            <i class="fas fa-save me-1"></i>Lagre endringer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('contentEditModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('contentEditModal'));
    modal.show();
    
    // Handle save button
    document.getElementById('saveContentBtn').addEventListener('click', function() {
        const form = document.getElementById('contentEditForm');
        const formData = new FormData(form);
        
        // Send AJAX request
        fetch("<?= base_url('admin/content/handlers/save_inline_edit.php') ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded",
            },
            body: new URLSearchParams(formData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the content display
                const titleElement = editor.querySelector('h1, h2, h5');
                const contentElement = editor.querySelector('p');
                
                if (titleElement) titleElement.textContent = formData.get('title');
                if (contentElement) contentElement.textContent = formData.get('content');
                
                // Update data attributes
                editor.dataset.title = formData.get('title');
                editor.dataset.content = formData.get('content');
                
                // Close modal
                modal.hide();
                
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
    
    // Clean up modal when hidden
    document.getElementById('contentEditModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

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
</script>

</body>
</html>
