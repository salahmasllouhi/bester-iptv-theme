<?php
/**
 * Single Series Template
 * Displays individual series landing pages for programmatic SEO.
 * Post type key: series (registered via ACF Pro)
 *
 * Section order:
 *   1. header
 *   2. series-hero    (two-column, featured image + content)
 *   3. series-features (6 cards)
 *   4. steps           (direct reuse from front page)
 *   5. pricing         (unchanged)
 *   6. series-faq     (Polylang for all text)
 *   7. series-cta     (dark CTA)
 *   8. series-schema  (JSON-LD)
 *   9. footer
 */

get_header();
?>

<?php
// ── Shared CSS (colors, buttons, layout, pricing, faq, header/footer) ────────
$shared_css = [
    'variables.css',
    'redesign-theme.css',
    'base.css',
    'header.css',
    'footer.css',
    'pricing.css',
    'faq.css',
    'cta.css',
];

echo '<style>';
foreach ($shared_css as $file) {
    $path = get_template_directory() . '/front-page/css/' . $file;
    if (file_exists($path)) {
        echo file_get_contents($path);
    }
}

// ── Series-specific CSS (hero layout, overrides) ──────────────────────────────
$series_css = get_template_directory() . '/series/css/series.css';
if (file_exists($series_css)) {
    echo file_get_contents($series_css);
}
echo '</style>';
?>

<!-- Universal Header -->
<?php include get_template_directory() . '/front-page/sections/header.php'; ?>

<?php while (have_posts()):
    the_post(); ?>
    <?php
    // Make series name available to all sections
    $series_name = get_the_title();
    ?>

    <!-- 1. Hero Section -->
    <?php include get_template_directory() . '/series/sections/series-hero.php'; ?>

    <!-- 2. Features Section (6 cards) -->
    <div style="background:var(--bg-section);width:100%">
        <?php include get_template_directory() . '/series/sections/series-features.php'; ?>
    </div>

    <!-- 3. Steps Section (direct reuse from front page) -->
    <div style="background:var(--bg-page);width:100%">
        <?php include get_template_directory() . '/front-page/sections/steps.php'; ?>
    </div>

    <!-- 4. Pricing Section -->
    <div style="background:var(--bg-section);width:100%">
        <?php
        $pricing_title_override = sprintf(
            srs_str('Start Watching <span class="gradient-text">%s</span> Today'),
            esc_html($series_name)
        );
        $pricing_subtitle_override = sprintf(
            srs_str('Choose your plan and start streaming %s in minutes'),
            esc_html($series_name)
        );
        include get_template_directory() . '/front-page/sections/pricing.php';
        ?>
    </div>

    <!-- 5. FAQ Section -->
    <div style="background:var(--bg-page);width:100%">
        <?php include get_template_directory() . '/series/sections/series-faq.php'; ?>
    </div>

    <!-- 6. CTA Section -->
    <?php include get_template_directory() . '/series/sections/series-cta.php'; ?>

    <!-- 7. JSON-LD Schema -->
    <?php include get_template_directory() . '/series/sections/series-schema.php'; ?>

<?php endwhile; ?>

<!-- Footer Section -->
<?php include get_template_directory() . '/front-page/sections/footer.php'; ?>

<?php
// Load JavaScript files inline
$js_files = [
    'header.js',
    'pricing.js',
    'currency.js',
    'hero-animation.js',
];

echo '<script>';
foreach ($js_files as $file) {
    $path = get_template_directory() . '/front-page/js/' . $file;
    if (file_exists($path)) {
        echo file_get_contents($path);
    }
}
echo '</script>';
?>

<?php get_footer(); ?>