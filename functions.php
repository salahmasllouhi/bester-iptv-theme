<?php
/**
 * My IPTV Theme - Functions and definitions
 */

// Enqueue theme styles
function my_iptv_enqueue_styles()
{
    wp_enqueue_style('my-iptv-style', get_stylesheet_uri(), array(), '1.0.0');
}
add_action('wp_enqueue_scripts', 'my_iptv_enqueue_styles');

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

    // Add WooCommerce support
    add_theme_support('woocommerce');
}
add_action('after_setup_theme', 'my_iptv_theme_setup');

// Include Geo-Redirect System for Multisite
require_once get_template_directory() . '/inc/geo-redirect.php';

// SEO Manager disabled - Using Rank Math Pro instead
// require_once get_template_directory() . '/inc/seo-manager.php';

// Include DeepL Translation Service
require_once get_template_directory() . '/inc/deepl-translator.php';

// Include OpenAI Translation Service (secure API key in WordPress options)
require_once get_template_directory() . '/inc/openai-translator.php';

// Include Network Cloner utility (uses DeepL for auto-translation)
require_once get_template_directory() . '/inc/network-cloner.php';

// Include Multi-Currency Pricing Settings
require_once get_template_directory() . '/inc/currency-settings.php';

// Include Front Page Content Settings with DeepL Translation
require_once get_template_directory() . '/inc/content-settings.php';

// Include User Guide Shortcode (displays posts from user-guide category)
require_once get_template_directory() . '/inc/user-guide-shortcode.php';
