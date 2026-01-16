<?php
/**
 * The template for displaying all WooCommerce pages
 * 
 * Fully styled to match the Nordic IPTV theme with proper logo sizing
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/variables.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/base.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/header.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/footer.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/front-page/css/responsive.css">


</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <!-- Universal Header -->
    <?php include get_template_directory() . '/inc/universal-header.php'; ?>

    <!-- Page Header -->
    <div class="wc-page-header">
        <div class="container">
            <h1><?php woocommerce_page_title(); ?></h1>
        </div>
    </div>

    <!-- Page Content -->
    <main class="wc-content">
        <div class="container">
            <?php woocommerce_content(); ?>
        </div>
    </main>

    <!-- Footer -->
    <?php include get_template_directory() . '/front-page/sections/footer.php'; ?>

    <!-- Include Currency JS -->
    <script src="<?php echo get_template_directory_uri(); ?>/front-page/js/currency.js"></script>

    <?php wp_footer(); ?>
</body>

</html>