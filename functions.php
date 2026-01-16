<?php
/**
 * My IPTV Theme - Functions and definitions
 */

// Enqueue theme styles
function my_iptv_enqueue_styles()
{
    wp_enqueue_style('my-iptv-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'my_iptv_enqueue_styles', 20);



// Theme setup
function my_iptv_theme_setup()
{
    // Add title tag support
    add_theme_support('title-tag');

    // Add HTML5 support
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register Navigation Menus
    register_nav_menus(array(
        'primary' => esc_html__('Header Menu', 'my-iptv'),
        'footer_1' => esc_html__('Footer - Quick Links', 'my-iptv'),
        'footer_2' => esc_html__('Footer - Support', 'my-iptv'),
        'footer_3' => esc_html__('Footer - Legal', 'my-iptv'),
    ));

    // Add WooCommerce support
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'my_iptv_theme_setup');

// Include Geo-Redirect System for Multisite
require_once get_template_directory() . '/inc/geo-redirect.php';

// SEO Manager disabled - Using Rank Math Pro instead
// require_once get_template_directory() . '/inc/seo-manager.php';

// Include OpenAI Translation Service (secure API key in WordPress options)
require_once get_template_directory() . '/inc/openai-translator.php';

// Include Network Cloner utility
require_once get_template_directory() . '/inc/network-cloner.php';

// Include Multi-Currency Pricing Settings
require_once get_template_directory() . '/inc/currency-settings.php';

// Include Front Page Content Settings with OpenAI Translation
require_once get_template_directory() . '/inc/content-settings.php';

// Include User Guide Shortcode (displays posts from user-guide category)
require_once get_template_directory() . '/inc/user-guide-shortcode.php';

// Include Product Setup Utility (Ensures WooCommerce products exist)
require_once get_template_directory() . '/inc/product-setup.php';

// Include Bulk Product Editor
require_once get_template_directory() . '/inc/admin-bulk-editor.php';