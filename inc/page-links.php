<?php
/**
 * Page links
 *
 * Resolves a page by slug and returns the current language's translation of it.
 *
 * The footer used to hardcode `href="#"` for every legal link, so About Us,
 * Privacy Policy, Terms of Service and the refund policy all pointed nowhere on
 * every language of the site — despite all four pages existing. The other footer
 * column built its URLs with home_url(), which is not language-aware, so the
 * Swedish footer linked to the English blog and setup guide.
 *
 * iptv_page_url() fixes both: it finds the page whatever language it was created
 * in, then hands the ID to Polylang to get this language's version. When a
 * translation does not exist yet it returns the page it did find, because a link
 * to the English page is more use than a dead one.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_page_url')) {
    /**
     * Permalink of a page in the current language, looked up by slug.
     *
     * @param string $slug     Page slug in any language — Polylang maps it across.
     * @param string $fallback Returned when no such page exists.
     * @return string Permalink, or $fallback, or '' if there is neither.
     */
    function iptv_page_url($slug, $fallback = '')
    {
        static $cache = array();

        $lang = function_exists('pll_current_language') ? pll_current_language('slug') : '';
        $key  = $slug . '|' . $lang;

        if (isset($cache[$key])) {
            return $cache[$key];
        }

        $url = '';

        // 'lang' => '' tells Polylang not to restrict the query to the current
        // language. Which translation this finds does not matter: every member of
        // a translation group maps to the same set, so pll_get_post() below
        // resolves the right one either way.
        $query = new WP_Query(array(
            'post_type'              => 'page',
            'name'                   => $slug,
            'post_status'            => 'publish',
            'posts_per_page'         => 1,
            'lang'                   => '',
            'no_found_rows'          => true,
            'update_post_term_cache' => false,
            'update_post_meta_cache' => false,
        ));

        if (!empty($query->posts)) {
            $id = $query->posts[0]->ID;

            if (function_exists('pll_get_post')) {
                $translated = pll_get_post($id);
                if ($translated) {
                    $id = $translated;
                }
            }

            $url = get_permalink($id);
        }

        if (!$url) {
            $url = $fallback;
        }

        $cache[$key] = $url;

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
