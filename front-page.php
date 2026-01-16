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
    // Load all CSS files in order
    $css_files = array(
        'variables',
        'base',
        'header',
        'hero',
        'sports',
        'brands',
        'features',
        'pricing',
        'steps',
        'cta',
        'reviews',
        'contact',
        'footer',
        'responsive'
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
    'header',
    'hero',
    'sports',
    'brands',
    'features',
    'pricing',
    'steps',
    'dark-cta',
    'comparison',
    'reviews',
    'contact',
    'footer'
);

foreach ($sections as $section) {
    $path = $sections_dir . '/' . $section . '.php';
    if (file_exists($path)) {
        include $path;
    }
}
?>

<script>
    <?php
    // Load all JS files in order
    $js_files = array(
        'header',
        'currency',
        'carousels',
        'pricing'
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