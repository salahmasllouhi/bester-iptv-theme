<?php
/**
 * Keyword landing pages — runtime
 *
 * Three jobs:
 *   1. Know which keyword page is being rendered (iptv_keyword_context()).
 *   2. Answer iptv_text() for the keys that page overrides, so the front page's
 *      own sections render with this page's wording and everything else falls
 *      through to the front page unchanged.
 *   3. Turn the %placeholder% links in the copy table into real anchors.
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

if (!function_exists('iptv_keyword_link_map')) {
    /**
     * Placeholder → [url, label] for one keyword page.
     *
     * Built per page because %kw:…% must not link a page to itself, and because
     * the external reference differs by topic.
     *
     * @param string $slug Page being rendered.
     * @return array<string,array{0:string,1:string}>
     */
    function iptv_keyword_link_map($slug)
    {
        $definition = iptv_keyword_definition($slug);
        $wiki       = isset($definition['wiki'])
            ? $definition['wiki']
            : array(
                'https://de.wikipedia.org/wiki/Internet_Protocol_Television',
                'Wikipedia-Artikel zu IPTV',
            );

        $map = array(
            '%home%'    => array(home_url('/'), 'Startseite'),
            '%guide%'   => array(iptv_page_url('iptv-guide-setup-apps-devices-tips'), 'Einrichtungsanleitung'),
            '%m3u%'     => array(iptv_page_url('m3u-playlist-convert-your-m3u-url'), 'M3U-Konverter'),
            '%faq%'     => array(iptv_page_url('iptv-services-faq-everything-you-need-to-know'), 'FAQ-Seite'),
            '%contact%' => array(iptv_page_url('contact-us'), 'Kontaktseite'),
            '%wiki%'    => $wiki,
        );

        foreach (array(1, 3, 6, 12) as $months) {
            $map['%plan' . $months . '%'] = array(
                function_exists('iptv_plan_url') ? iptv_plan_url($months) : '',
                $months . '-Monats-Zugang',
            );
        }

        foreach (iptv_keyword_definitions() as $other => $other_definition) {
            $map['%kw:' . $other . '%'] = array(
                $other === $slug ? '' : iptv_keyword_page_url($other),
                $other_definition['keyword'],
            );
        }

        return $map;
    }
}

if (!function_exists('iptv_keyword_links')) {
    /**
     * Resolve %placeholder% into anchors.
     *
     * A placeholder whose target does not resolve — an unpublished plan page, a
     * self-reference — degrades to its label as plain text rather than to a link
     * that goes nowhere.
     *
     * @param string $text
     * @param string $slug Page being rendered.
     * @return string
     */
    function iptv_keyword_links($text, $slug)
    {
        static $maps = array();

        if (strpos($text, '%') === false) {
            return $text;
        }

        if (!isset($maps[$slug])) {
            $maps[$slug] = iptv_keyword_link_map($slug);
        }

        foreach ($maps[$slug] as $token => $target) {
            if (strpos($text, $token) === false) {
                continue;
            }

            list($url, $label) = $target;

            $replacement = $url
                ? sprintf('<a href="%s">%s</a>', esc_url($url), esc_html($label))
                : esc_html($label);

            $text = str_replace($token, $replacement, $text);
        }

        return $text;
    }
}
