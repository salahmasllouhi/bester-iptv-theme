<?php
/**
 * Keyword landing pages — runtime
 *
 * Two jobs:
 *   1. Know which keyword page is being rendered (iptv_keyword_context()).
 *   2. Answer iptv_text() for the keys that page overrides, so the front page's
 *      own sections render with this page's wording and everything else falls
 *      through to the front page unchanged.
 *
 * The context is set explicitly by the template rather than derived from
 * is_page(), because the Rank Math digest has to build a page's copy from
 * wp-admin, where there is no such thing as "the current page".
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

define('IPTV_KEYWORD_TEMPLATE', 'template-keyword-landing.php');

if (!function_exists('iptv_keyword_context')) {
    /**
     * Get, set or clear the keyword page being rendered.
     *
     * @param string|false|null $slug Slug to switch to, false to clear, null to read.
     * @return string Active slug, or '' when none.
     */
    function iptv_keyword_context($slug = null)
    {
        static $current = '';

        if ($slug === false) {
            $current = '';
        } elseif (is_string($slug)) {
            $current = iptv_keyword_definition($slug) ? $slug : '';
        }

        return $current;
    }
}

if (!function_exists('iptv_keyword_slug_for_post')) {
    /**
     * The keyword slug stamped on a post, if it is a keyword page.
     *
     * Falls back to the post slug: the two are the same by construction, so a
     * page whose meta was lost still resolves.
     *
     * @param int|WP_Post|null $post
     * @return string
     */
    function iptv_keyword_slug_for_post($post = null)
    {
        $post = get_post($post);

        if (!$post || $post->post_type !== 'page') {
            return '';
        }

        if (get_post_meta($post->ID, '_wp_page_template', true) !== IPTV_KEYWORD_TEMPLATE) {
            return '';
        }

        $slug = (string) get_post_meta($post->ID, 'keyword_slug', true);

        if (!$slug || !iptv_keyword_definition($slug)) {
            $slug = $post->post_name;
        }

        return iptv_keyword_definition($slug) ? $slug : '';
    }
}

if (!function_exists('iptv_keyword_page_url')) {
    /**
     * Permalink of a keyword page, by slug. '' when it is not published yet.
     *
     * @param string $slug
     * @return string
     */
    function iptv_keyword_page_url($slug)
    {
        return function_exists('iptv_page_url') ? iptv_page_url($slug) : '';
    }
}

/**
 * Answer iptv_text() for the active keyword page.
 *
 * Only the keys in the page's `text` table are answered; everything else keeps
 * whatever the front page resolved, which is the entire point — one set of
 * prices, reviews and device chips, eight sets of headlines.
 */
add_filter('iptv_text', function ($value, $key) {
    $slug = iptv_keyword_context();

    if (!$slug) {
        return $value;
    }

    $definition = iptv_keyword_definition($slug);

    return isset($definition['text'][$key]) ? $definition['text'][$key] : $value;
}, 10, 2);
