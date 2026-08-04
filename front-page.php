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

    // The body band's prose styles. Shared with the keyword landing pages, so
    // the sheet lives with them rather than in this folder.
    $keyword_css = get_template_directory() . '/keyword/css/keyword.css';
    if (file_exists($keyword_css)) {
        include $keyword_css;
    }
    ?>
</style>

<?php
// Page-wide backdrop: a fixed, masked grid that sits behind every section.
// Rendered once here rather than inside a section, since it belongs to the page
// rather than to any one band of it. See .dv2-grid-wash in design-v2.css.
?>
<div class="dv2-grid-wash" aria-hidden="true"></div>

<?php
// Load all sections in order
// Order follows the "IPTV Anbieter Light Purple" design.
$sections = array(
    'header',           // Front-page header (source of truth for all headers)
    'hero',             // Split hero with Trustpilot badge
    'content-showcase', // Channels panel + VOD panel
    'sports',           // Sports panel with mosaic
    'cta-bar',          // Full-width savings bar
    // Features sits directly above pricing on purpose: it is the argument, and
    // the prices are the ask. It closes with a CTA into #pricing so the reader
    // does not have to go looking for the thing they were just sold on.
    'features',         // Eight capability cards
    'pricing',          // Plan cards + sticky screen picker (WooCommerce)
    'steps',            // Onboarding panel - sits directly under pricing
    // The reading material sits below the buying path rather than inside it:
    // everything above this line is the offer, everything below is the case for
    // it. Same position the keyword landing pages give their band.
    'body',             // Long-form copy on "IPTV Anbieter"
    'unlock',           // Supported device chips
    'reviews',          // Score card + two-row review marquee
    'faq',              // Accordion
    'contact',          // Support cards
    'footer'
    // Not in this list (files and styles still exist - add the slug back to
    // render them again):
    //   'comparison'  - IPTV Anbieter vs. traditional services
    //   'dashboard'   - Member area promo
    //   'dark-cta'    - second CTA block; the journey panel closes this design
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