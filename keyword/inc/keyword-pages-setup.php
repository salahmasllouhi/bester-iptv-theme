<?php
/**
 * Keyword landing pages — one-time provisioning
 *
 * Creates the eight pages, stamps each with its keyword and writes its Rank
 * Math title, description and focus keyword.
 *
 * Idempotent. It matches on template + keyword_slug before creating anything,
 * so re-running repairs rather than duplicates. To re-run after editing the
 * copy table, bump KEYWORD_PAGES_BUILD.
 *
 * Unlike the plan pages, these are created published: they only make sense as
 * live entry points, and the footer column that links them drops any that is
 * missing anyway.
 *
 * SEO meta is written once, on creation. Re-running never overwrites it, so an
 * editor who rewrites a title in Rank Math keeps it.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

// Bump to re-run after adding a keyword to the table. Re-running is safe:
// existing pages are matched and reused, and only the keyword stamp is
// rewritten — never the status, the content or an edited SEO field.
//
// It is also the only lever in this theme that purges LiteSpeed, which caches
// these pages for a week. A template change alone is invisible to visitors
// until that cache expires, so bump this whenever one ships.
//   2 — removed the body band from the landing pages.
define('KEYWORD_PAGES_BUILD', 2);

if (!function_exists('iptv_keyword_existing_pages')) {
    /**
     * Every page already using the keyword template, indexed by keyword slug.
     *
     * Straight to postmeta rather than through WP_Query so nothing filtering on
     * parse_query can narrow the result. Pages whose keyword_slug was lost fall
     * back to their post slug, which is the same string by construction.
     *
     * @return array<string,int>
     */
    function iptv_keyword_existing_pages()
    {
        global $wpdb;

        $found = array();

        $ids = $wpdb->get_col($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta}
             WHERE meta_key = '_wp_page_template' AND meta_value = %s",
            IPTV_KEYWORD_TEMPLATE
        ));

        foreach ($ids as $id) {
            $post = get_post((int) $id);

            if (!$post || $post->post_type !== 'page' || $post->post_status === 'trash') {
                continue;
            }

            $slug = (string) get_post_meta($post->ID, 'keyword_slug', true);

            if (!$slug) {
                $slug = $post->post_name;
            }

            $found[$slug] = $post->ID;
        }

        return $found;
    }
}

if (!function_exists('iptv_keyword_build_pages')) {
    /**
     * Create anything missing.
     *
     * @return array Summary, for the admin notice.
     */
    function iptv_keyword_build_pages()
    {
        $existing = iptv_keyword_existing_pages();
        $summary  = array('created' => 0, 'reused' => 0);

        foreach (iptv_keyword_definitions() as $slug => $definition) {

            $post_id = isset($existing[$slug]) ? $existing[$slug] : 0;

            // A page may already exist on this slug without the template — the
            // slug is the keyword, so a collision is plausible. Adopt it rather
            // than creating a second page competing for the same URL.
            if (!$post_id) {
                $found = get_page_by_path($slug, OBJECT, 'page');
                if ($found && $found->post_status !== 'trash') {
                    $post_id = $found->ID;
                }
            }

            if ($post_id) {
                $summary['reused']++;
            } else {
                $post_id = wp_insert_post(array(
                    'post_title'   => $definition['title'],
                    'post_name'    => $slug,
                    'post_type'    => 'page',
                    'post_status'  => 'publish',
                    'post_content' => '',
                    'meta_input'   => array(
                        '_wp_page_template' => IPTV_KEYWORD_TEMPLATE,
                    ),
                ), true);

                if (is_wp_error($post_id) || !$post_id) {
                    continue;
                }

                $summary['created']++;
            }

            update_post_meta($post_id, '_wp_page_template', IPTV_KEYWORD_TEMPLATE);
            update_post_meta($post_id, 'keyword_slug', $slug);

            // Written only where the field is still empty, so a rewrite in the
            // Rank Math box survives the next build.
            $seo = array(
                'rank_math_title'         => $definition['seo_title'],
                'rank_math_description'   => $definition['seo_desc'],
                'rank_math_focus_keyword' => mb_strtolower($definition['keyword']),
            );

            foreach ($seo as $meta_key => $meta_value) {
                if (get_post_meta($post_id, $meta_key, true) === '') {
                    update_post_meta($post_id, $meta_key, $meta_value);
                }
            }
        }

        return $summary;
    }
}

/**
 * Run once per KEYWORD_PAGES_BUILD, on the first request after deploy.
 *
 * The flag is written before the work rather than after, so two requests
 * arriving together cannot both start building. If a run does fail part way,
 * bumping KEYWORD_PAGES_BUILD re-runs it — and because the whole thing matches
 * on existing pages first, the retry repairs instead of duplicating.
 */
add_action('init', function () {
    if ((int) get_option('iptv_keyword_pages_built') === KEYWORD_PAGES_BUILD) {
        return;
    }

    update_option('iptv_keyword_pages_built', KEYWORD_PAGES_BUILD, false);

    $summary = iptv_keyword_build_pages();

    update_option('iptv_keyword_pages_report', $summary, false);

    // Same reason as the plan build: LiteSpeed would otherwise serve the old
    // footer, which is where these pages are linked from.
    do_action('litespeed_purge_all');
}, 20);
