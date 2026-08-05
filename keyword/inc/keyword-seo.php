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
 * The hero comes first, which is what "keyword at the beginning of the content"
 * measures — every page's h1 opens with its keyword.
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

        // Restore rather than clear: the digest can be built from inside a
        // rendering page (the editor preview does exactly that).
        iptv_keyword_context($previous ? $previous : false);

        // The lead and blocks in the definition are no longer rendered — the
        // body band was removed from the template — so they are not digested.
        // Rank Math has to see the page, not the copy table.
        return implode("\n", $blocks);
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
