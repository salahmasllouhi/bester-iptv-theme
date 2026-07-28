<?php
/**
 * Single Channel Template
 * Displays individual channel landing pages for programmatic SEO.
 * Post type key: channel (registered via ACF Pro)
 *
 * Section order:
 *   1. header
 *   2. channel-hero    (centered, live viewer dot + counter)
 *   3. channel-features (6 cards, cloned from front page)
 *   4. steps           (direct reuse from front page)
 *   5. pricing         (unchanged)
 *   6. channel-faq     (Polylang for all text)
 *   7. channel-cta     (dark CTA)
 *   8. channel-schema  (JSON-LD)
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
    'design-v2.css',
    'design-v2-sections.css',
];

echo '<style>';
foreach ($shared_css as $file) {
    $path = get_template_directory() . '/front-page/css/' . $file;
    if (file_exists($path)) {
        echo file_get_contents($path);
    }
}

// ── Channel-specific CSS (hero centered layout, live counter, overrides) ──────
$channel_css = get_template_directory() . '/channel/css/channel.css';
if (file_exists($channel_css)) {
    echo file_get_contents($channel_css);
}
echo '</style>';
?>

<!-- Universal Header -->
<?php include get_template_directory() . '/front-page/sections/header.php'; ?>

<?php while (have_posts()):
    the_post(); ?>
    <?php
    // Make channel name available to all sections
    $channel_name = get_the_title();
    ?>

    <!-- 1. Hero Section (centered, live viewer dot + counter) -->
    <?php include get_template_directory() . '/front-page/sections/channel-hero.php'; ?>

    <!-- 2. Features Section (6 cards) -->
    <div style="background:var(--bg-section);width:100%">
        <?php include get_template_directory() . '/front-page/sections/channel-features.php'; ?>
    </div>

    <!-- 3. Steps Section (direct reuse from front page) -->
    <div style="background:var(--bg-page);width:100%">
        <?php include get_template_directory() . '/front-page/sections/steps.php'; ?>
    </div>

    <!-- 4. Pricing Section -->
    <div style="background:var(--bg-section);width:100%">
        <?php
        $pricing_title_override = sprintf(
            tpl_str('Start Watching <span class="gradient-text">%s</span> Today'),
            esc_html($channel_name)
        );
        $pricing_subtitle_override = sprintf(
            tpl_str('Choose your plan and start streaming %s in minutes'),
            esc_html($channel_name)
        );
        include get_template_directory() . '/front-page/sections/pricing.php';
        ?>
    </div>

    <!-- 5. FAQ Section -->
    <div style="background:var(--bg-page);width:100%">
        <?php include get_template_directory() . '/front-page/sections/channel-faq.php'; ?>
    </div>

    <!-- 6. CTA Section -->
    <?php include get_template_directory() . '/front-page/sections/channel-cta.php'; ?>

    <!-- 7. JSON-LD Schema -->
    <?php include get_template_directory() . '/front-page/sections/channel-schema.php'; ?>

<?php endwhile; ?>

<!-- Footer Section -->
<?php include get_template_directory() . '/front-page/sections/footer.php'; ?>

<?php
// Load JavaScript files inline
$js_files = [
    'header.js',
    'pricing.js',
    'currency.js',
    'hero-animation.js', // Live viewer counter
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