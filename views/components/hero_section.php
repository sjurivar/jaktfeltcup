<?php
/**
 * Universal Hero Section Component
 * 
 * Usage: include_hero_section($page_key, $section_key, $default_title, $default_subtitle, $buttons, $icon)
 * 
 * @param string $page_key - Page identifier for content management
 * @param string $section_key - Section identifier for content management  
 * @param string $default_title - Default title if no content exists
 * @param string $default_subtitle - Default subtitle if no content exists
 * @param array $buttons - Array of button configurations
 * @param string $icon - Font Awesome icon class
 */
function include_hero_section($page_key, $section_key, $default_title, $default_subtitle, $buttons = [], $icon = 'fas fa-bullseye') {
    // Get editable content
    $hero_content = render_editable_content($page_key, $section_key, $default_title, $default_subtitle);
    ?>
    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <?php if (can_edit_inline() && !empty($hero_content['editor_html'])): ?>
                        <?= $hero_content['editor_html'] ?>
                    <?php else: ?>
                        <h1 class="display-4 fw-bold mb-4"><?= htmlspecialchars($hero_content['title']) ?></h1>
                        <p class="lead mb-4"><?= htmlspecialchars($hero_content['content']) ?></p>
                    <?php endif; ?>
                    
                    <?php if (!empty($buttons)): ?>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <?php foreach ($buttons as $button): ?>
                                <a href="<?= htmlspecialchars($button['url']) ?>" class="btn <?= htmlspecialchars($button['class'] ?? 'btn-light') ?> btn-lg">
                                    <?php if (!empty($button['icon'])): ?>
                                        <i class="<?= htmlspecialchars($button['icon']) ?> me-2"></i>
                                    <?php endif; ?>
                                    <?= htmlspecialchars($button['text']) ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
    <?php
}
?>
