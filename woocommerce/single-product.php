<?php
/**
 * The template for displaying single WooCommerce products
 * 
 * This file overrides WooCommerce's default single-product.php
 * Styled to match the Nordic IPTV theme
 */

defined('ABSPATH') || exit;

get_header('shop');
?>

<link rel="stylesheet"
    href="<?php echo get_template_directory_uri(); ?>/front-page/css/variables.css?v=<?php echo time(); ?>">
<link rel="stylesheet"
    href="<?php echo get_template_directory_uri(); ?>/front-page/css/base.css?v=<?php echo time(); ?>">
<link rel="stylesheet"
    href="<?php echo get_template_directory_uri(); ?>/front-page/css/header.css?v=<?php echo time(); ?>">
<link rel="stylesheet"
    href="<?php echo get_template_directory_uri(); ?>/front-page/css/footer.css?v=<?php echo time(); ?>">
<link rel="stylesheet"
    href="<?php echo get_template_directory_uri(); ?>/front-page/css/responsive.css?v=<?php echo time(); ?>">
<link rel="stylesheet"
    href="<?php echo get_template_directory_uri(); ?>/front-page/css/product-page.css?v=<?php echo time(); ?>">

<style>
    /* Fix Header Logo Size */
    .site-header .site-logo img,
    .main-header .logo img,
    header .logo img,
    .header .logo img {
        max-width: 120px !important;
        height: auto !important;
    }
</style>

<!-- Universal Header -->
<?php include get_template_directory() . '/inc/universal-header.php'; ?>

<!-- Product Content -->
<main class="product-content">
    <?php
    while (have_posts()):
        the_post();
        wc_get_template_part('content', 'single-product');
    endwhile;
    ?>
</main>

<!-- Footer -->
<?php include get_template_directory() . '/front-page/sections/footer.php'; ?>

<script src="<?php echo get_template_directory_uri(); ?>/front-page/js/currency.js"></script>

<?php
get_footer('shop');
