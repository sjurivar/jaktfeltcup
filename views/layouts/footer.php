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
            <div class="col-md-6">
                <h5>Jaktfeltcup</h5>
                <p class="text-muted">Administrasjonssystem for skyteøvelse</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">&copy; 2024 Jaktfeltcup. Alle rettigheter forbeholdt.</p>
            </div>
        </div>
    </div>
</footer>
<?php endif; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- Additional JavaScript -->
<?= $additional_js ?>

</body>
</html>
