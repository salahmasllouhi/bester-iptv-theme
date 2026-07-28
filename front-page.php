<?php
/**
 * Template Name: Front Page
 * Description: Modular IPTV Landing Page - Each section is in a separate file
 */
get_header();

// Define file paths
$front_page_dir = get_template_directory() . '/front-page';
$css_dir = $front_page_dir . '/css';
$js_dir = $front_page_dir . '/js';
$sections_dir = $front_page_dir . '/sections';
?>

<style>
    <?php
    // Load CSS files
    $css_files = array(
        'variables',      // Old vars (Keep for Pricing/Reviews)
        'base',           // Old base (Keep for layout safety)
        'header',         // Existing Header
        'pricing',        // Existing Pricing
        'reviews',        // Existing Reviews
        'contact',        // Existing Contact form styling
        'footer',         // Existing Footer
        'responsive',     // Existing Responsive
        'redesign-theme', // Previous redesign (Overrides + New Sections)
        'cta',            // CTA Section Styles
        'activity-ticker', // Social proof notifications
        'design-v2',      // DESIGN V2 tokens (light purple) - must come after the old layers
        'design-v2-sections' // DESIGN V2 section components
    );

    foreach ($css_files as $file) {
        $path = $css_dir . '/' . $file . '.css';
        if (file_exists($path)) {
            include $path;
        }
    }
    ?>
</style>

<?php
// Load all sections in order
// Order follows the "NordicTV Light Purple" design.
$sections = array(
    'header',           // Front-page header (source of truth for all headers)
    'hero',             // Split hero with Trustpilot badge
    'features',         // Eight capability chips
    'content-showcase', // Channels panel + VOD panel
    'sports',           // Sports panel with mosaic
    'cta-bar',          // Full-width savings bar
    'comparison',       // NordicTV vs. traditional services
    'reviews',          // Score card + review grid
    'pricing',          // Device/duration configurator (WooCommerce)
    'unlock',           // Supported device chips
    'dashboard',        // Member Dashboard
    'faq',              // Accordion
    'steps',            // Journey panel - closing CTA in this design
    'contact',          // Support cards
    'footer'
    // 'dark-cta' is intentionally not in this list: the journey panel is the
    // closing CTA in this design. The section and its styles still exist -
    // add it back here if you want a second CTA block.
);

foreach ($sections as $section) {
    $path = $sections_dir . '/' . $section . '.php';
    if (file_exists($path)) {
        include $path;
    }
}

// Activity Ticker (Social Proof)
include $front_page_dir . '/partials/activity-ticker.php';
?>

<script>
    <?php
    // Load all JS files in order
    $js_files = array(
        'header',
        'currency',
        'carousels',
        'pricing',
        'hero-animation',   // New Hero Animation
        'activity-ticker'   // Social proof notifications
    );

    foreach ($js_files as $file) {
        $path = $js_dir . '/' . $file . '.js';
        if (file_exists($path)) {
            include $path;
        }
    }
    ?>
</script>

<?php get_footer(); ?>