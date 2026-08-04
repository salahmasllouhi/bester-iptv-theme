<?php
/**
 * Keyword landing pages — Rank Math content analysis
 *
 * Same problem as the front page: post_content is empty and everything the
 * visitor reads comes from the template, so Rank Math had nothing to score.
 *
 * The digest is assembled with the page's keyword context switched on, which
 * makes iptv_text() answer with that page's headlines — so the shared builder
 * in inc/front-page-seo.php produces this page's copy, not the front page's.
 *
 * Order is deliberate: hero, then the body band, then the rest. The band is
 * where the keyword is explained, and "keyword at the beginning of the content"
 * looks at the first 10%.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_keyword_analysis_digest')) {
    /**
     * One keyword page's rendered copy, as HTML.
     *
     * @param string $slug
     * @return string
     */
    function iptv_keyword_analysis_digest($slug)
    {
        $definition = iptv_keyword_definition($slug);

        if (!$definition) {
            return '';
        }

        $previous = iptv_keyword_context();
        iptv_keyword_context($slug);

        $blocks = iptv_front_copy_blocks();
        $body   = iptv_prose_digest($definition, $slug);

        // Restore rather than clear: the digest can be built from inside a
        // rendering page (the editor preview does exactly that).
        iptv_keyword_context($previous ? $previous : false);

        $hero = isset($blocks['hero']) ? $blocks['hero'] : '';
        unset($blocks['hero']);

        return implode("\n", array_merge(
            array($hero, $body),
            $blocks
        ));
    }
}

/**
 * Give Rank Math the page rather than its empty body field.
 */
add_filter('rank_math/researches/post_content', function ($content, $post = null) {
    $slug = iptv_keyword_slug_for_post($post);

    if (!$slug) {
        return $content;
    }

    return $content . "\n" . iptv_keyword_analysis_digest($slug);
}, 10, 2);
