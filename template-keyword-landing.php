<?php
/**
 * Template Name: Keyword Landing Page
 * Description: The front page's section stack, rendered for one keyword. Assign
 *              this template to a page whose slug matches an entry in
 *              keyword/inc/keyword-data.php and the headlines, body band, FAQ
 *              and schema all follow from that entry.
 *
 * The eight pages are the same file. Nothing here is hard-coded to a keyword —
 * iptv_keyword_context() tells iptv_text() which page is being rendered, and
 * every section below is the front page's own, unchanged.
 *
 * Section order — identical to front-page.php except for the two marked ones:
 *   1. header
 *   2. hero              (keyword headline)
 *   3. content-showcase
 *   4. sports
 *   5. cta-bar
 *   6. features
 *   7. pricing
 *   8. steps
 *   9. keyword-content   ← the part that only exists here
 *  10. unlock
 *  11. reviews
 *  12. faq               (answers this page's questions — see faq.php)
 *  13. contact
 *  14. footer
 *  15. keyword-schema    ← FAQPage JSON-LD
 */

get_header();

$theme_dir     = get_template_directory();
$front_dir     = $theme_dir . '/front-page';
$keyword_dir   = $theme_dir . '/keyword';
$sections_dir  = $front_dir . '/sections';
?>

<style>
    <?php
    // The front page's layers in the front page's order — design-v2 must stay
    // last so it remaps the older tokens — plus the body band's own sheet.
    $css_files = array(
        $front_dir   . '/css/variables.css',
        $front_dir   . '/css/base.css',
        $front_dir   . '/css/header.css',
        $front_dir   . '/css/pricing.css',
        $front_dir   . '/css/reviews.css',
        $front_dir   . '/css/contact.css',
        $front_dir   . '/css/footer.css',
        $front_dir   . '/css/responsive.css',
        $front_dir   . '/css/redesign-theme.css',
        $front_dir   . '/css/cta.css',
        $front_dir   . '/css/activity-ticker.css',
        $front_dir   . '/css/design-v2.css',
        $front_dir   . '/css/design-v2-sections.css',
        $keyword_dir . '/css/keyword.css',
    );

    foreach ($css_files as $path) {
        if (file_exists($path)) {
            include $path;
        }
    }
    ?>
</style>

<?php // Page-wide backdrop, as on the front page. See .dv2-grid-wash. ?>
<div class="dv2-grid-wash" aria-hidden="true"></div>

<?php
include $sections_dir . '/header.php';

while (have_posts()) :
    the_post();

    $kw_slug = iptv_keyword_slug_for_post(get_post());
    $kw      = $kw_slug ? iptv_keyword_definition($kw_slug) : null;

    // Nothing to render this page as. Falling back to the front page's own copy
    // would publish a second, wordless home page, so bail to the body field
    // instead — which is what a mis-stamped page should look like.
    if (!$kw) {
        echo '<section class="dv2-section"><div class="container">';
        the_content();
        echo '</div></section>';
        continue;
    }

    // From here on every iptv_text() call answers for this page. Cleared after
    // the footer so nothing outside the loop inherits it.
    iptv_keyword_context($kw_slug);

    include $sections_dir  . '/hero.php';
    include $sections_dir  . '/content-showcase.php';
    include $sections_dir  . '/sports.php';
    include $sections_dir  . '/cta-bar.php';
    include $sections_dir  . '/features.php';
    include $sections_dir  . '/pricing.php';
    include $sections_dir  . '/steps.php';
    include $keyword_dir   . '/sections/keyword-content.php';
    include $sections_dir  . '/unlock.php';
    include $sections_dir  . '/reviews.php';
    include $sections_dir  . '/faq.php';
    include $sections_dir  . '/contact.php';
    include $sections_dir  . '/footer.php';
    include $keyword_dir   . '/sections/keyword-schema.php';

    include $front_dir . '/partials/activity-ticker.php';

    iptv_keyword_context(false);

endwhile;
?>

<script>
    <?php
    // The same set the front page loads: the pricing configurator, the sport
    // and brand carousels and the hero animation are all on this page too.
    $js_files = array(
        $front_dir . '/js/header.js',
        $front_dir . '/js/currency.js',
        $front_dir . '/js/carousels.js',
        $front_dir . '/js/pricing.js',
        $front_dir . '/js/hero-animation.js',
        $front_dir . '/js/activity-ticker.js',
    );

    foreach ($js_files as $path) {
        if (file_exists($path)) {
            include $path;
        }
    }
    ?>
</script>

<?php get_footer(); ?>
