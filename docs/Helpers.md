### Helpers

Helper functions and utility classes used by views and controllers.

---

### ViewHelper (`src/Helpers/ViewHelper.php`)

Global functions:
- `base_url(string $path = ''): string` — Builds absolute URL using global `$app_config['base_url']`.
- `include_header(string $header_file = 'header.php'): void` — Includes `views/layouts/{header_file}` if present.
- `include_footer(string $footer_file = 'footer.php'): void` — Includes `views/layouts/{footer_file}` if present.
- `getDataService()` — Returns `JsonDataService` when `data_source=json`, else returns `Database`.

Example:
```php
include_header();
echo base_url('assets/css/app.css');
include_footer();
```

---

### ImageHelper (`Jaktfeltcup\Helpers\ImageHelper`)

Static API:
- `getImageUrl($path, $fallback = null)` — Returns URL for existing file or `$fallback`.
- `getLogoUrl()` — Convenience for site logo.
- `getSponsorImages()` — Returns sponsors with resolved `logo_url` from DB or filesystem.
- `getBackgroundStyle($imageUrl, $opacity = 0.1)` — Inline CSS string for background image.

Example:
```php
$logo = Jaktfeltcup\Helpers\ImageHelper::getLogoUrl();
echo '<img src="' . htmlspecialchars($logo) . '" alt="Logo" />';
```

---

### ContentHelper (`Jaktfeltcup\Helpers\ContentHelper`)

Public API:
- `__construct(Database $database)`
- `getPageContent(string $page_key, string $section_key, string $lang = 'no'): array`
- `getPageAllContent(string $page_key, string $lang = 'no'): array`
- `updatePageContent(string $page_key, string $section_key, string $title, string $content, int $updated_by, string $lang = 'no'): bool`

Example:
```php
$helper = new Jaktfeltcup\Helpers\ContentHelper($db);
$hero = $helper->getPageContent('public/home', 'hero');
```

---

### Inline Edit Helper (`src/Helpers/InlineEditHelper.php`)

Global functions:
- `can_edit_inline(): bool` — Returns true when current session user has `contentmanager` or `admin` role.
- `render_editable_content($page_key, $section_key, $default_title = '', $default_content = ''): array` — Returns `['title','content','editor_html']` with optional inline editor when allowed.
- `get_inline_edit_css(): string` — Returns `<style>` block for inline editor.
- `get_inline_edit_js(): string` — Returns `<script>` block with JS to persist edits via `admin/content/handlers/save_inline_edit.php`.

Example:
```php
$data = render_editable_content('public/home', 'hero', 'Velkommen', 'Intro...');
echo '<h1>' . htmlspecialchars($data['title']) . '</h1>';
echo $data['editor_html'] ?? '';
```

