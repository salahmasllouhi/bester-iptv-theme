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
        'redesign-theme', // NEW REDESIGN (Overrides + New Sections)
        'cta',            // CTA Section Styles
        'activity-ticker' // Social proof notifications
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
$sections = array(
    'header',      // Front-page header (source of truth for all headers)
    'hero',        // Redesigned
    'content-showcase', // New Section
    // 'brands' removed per user request
    'features',    // Redesigned
    'sports',      // Redesigned
    'comparison',  // Redesigned (Price Comparison)
    'steps',       // Redesigned
    'unlock',      // Redesigned (Devices)
    'pricing',     // Existing (User said: "Your Existing Pricing Section Goes Here")
    'reviews',     // Existing
    'faq',         // New
    'dark-cta',    // Redesigned (CTA)
    'footer'       // Existing
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