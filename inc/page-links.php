<?php
/**
 * Page links
 *
 * Resolves a page by slug and returns its permalink.
 *
 * The footer used to hardcode `href="#"` for every legal link, so About Us,
 * Privacy Policy, Terms of Service and the refund policy all pointed nowhere,
 * despite all four pages existing. iptv_page_url() fixes that.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_page_url')) {
    /**
     * Permalink of a page, looked up by slug.
     *
     * @param string $slug     Page slug.
     * @param string $fallback Returned when no such page exists.
     * @return string Permalink, or $fallback, or '' if there is neither.
     */
    function iptv_page_url($slug, $fallback = '')
    {
        static $cache = array();

        if (isset($cache[$slug])) {
            return $cache[$slug];
        }

        $url = '';

        $args = array(
            'post_type'              => 'page',
            'name'                   => $slug,
            'post_status'            => 'publish',
            'posts_per_page'         => 1,
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        );

        $query = new WP_Query($args);

        if (!empty($query->posts)) {
            $url = get_permalink($query->posts[0]->ID);
        }

        if (!$url) {
            $url = $fallback;
        }

        $cache[$slug] = $url;

        return $url;
    }
}

if (!function_exists('iptv_footer_links')) {
    /**
     * Render a list of footer links, skipping any whose page is missing.
     *
     * Printing a link to '' would point at the current page, which is worse than
     * the dead '#' it replaces, so unresolved entries are dropped entirely.
     *
     * @param array $links Each entry: ['slug' => …, 'key' => …, 'label' => …]
     *                     or ['url' => …, 'key' => …, 'label' => …] for an
     *                     external destination.
     */
    function iptv_footer_links(array $links)
    {
        echo '<div>';

        foreach ($links as $link) {
            $url = isset($link['url'])
                ? $link['url']
                : iptv_page_url($link['slug'], isset($link['fallback']) ? $link['fallback'] : '');

            if (!$url) {
                continue;
            }

            printf(
                '<a href="%s">%s</a>',
                esc_url($url),
                esc_html(iptv_text($link['key'], $link['label']))
            );
        }

        echo '</div>';
    }
}
