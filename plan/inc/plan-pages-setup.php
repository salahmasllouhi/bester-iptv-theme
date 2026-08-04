<?php
/**
 * Plan pages — one-time provisioning
 *
 * Creates the four plan pages and stamps each with its length.
 *
 * Idempotent. It matches on template + plan_months before creating anything, so
 * re-running repairs rather than duplicates. To re-run after editing the table
 * below, bump PLAN_PAGES_BUILD.
 *
 * Pages are created as drafts. Publishing to a live site is the site owner's
 * call, not a migration's. Note that the compare table only links plans that are
 * published (iptv_plan_url() queries post_status=publish), so the cross-links
 * light up as you publish them.
 *
 * This used to create 24 pages — the same four in each of six languages — and
 * link them as Polylang translation groups. The site is English-only now, so
 * only the English four are provisioned. Trashed pages are deliberately not
 * adopted (see iptv_plan_existing_pages()), but they are also not recreated
 * while PLAN_PAGES_BUILD is unchanged: bumping it would recreate the four
 * English pages only if they had been deleted outright.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

// Bump to re-run after changing the titles or slugs below. Re-running is safe:
// existing pages are matched and reused, so only the plan length is rewritten —
// never the status or the content.
define('PLAN_PAGES_BUILD', 3);

if (!function_exists('iptv_plan_page_definitions')) {
    /**
     * Title and slug for every plan.
     *
     * Slugs are the keyword-bearing ones set for Rank Math, not descriptions
     * of the plan length — that is deliberate: the primary focus keyword has
     * to appear in the URL.
     *
     * Only applied when a page is created. Existing pages keep whatever slug
     * they have, so editing this table never moves a live URL.
     *
     * @return array<int,array{title:string,slug:string,label:string}>
     */
    function iptv_plan_page_definitions()
    {
        return array(
            1  => array('title' => '1 Month IPTV Subscription', 'slug' => 'iptv-service-provider', 'label' => '1 Month'),
            3  => array('title' => '3 Months IPTV Subscription', 'slug' => 'ip-tv-subscription', 'label' => '3 Months'),
            6  => array('title' => '6 Months IPTV Subscription', 'slug' => 'iptv-service-usa', 'label' => '6 Months'),
            12 => array('title' => '12 Months IPTV Subscription', 'slug' => 'best-iptv-providers-reddit', 'label' => '12 Months'),
        );
    }
}

if (!function_exists('iptv_plan_existing_pages')) {
    /**
     * Every page already using the plan template, indexed by length:
     * [months] => post ID.
     *
     * Straight to postmeta rather than through WP_Query so nothing filtering on
     * parse_query can narrow the result.
     *
     * @return array<int,int>
     */
    function iptv_plan_existing_pages()
    {
        global $wpdb;

        $found = array();

        $ids = $wpdb->get_col($wpdb->prepare(
            "SELECT post_id FROM {$wpdb->postmeta}
             WHERE meta_key = '_wp_page_template' AND meta_value = %s",
            'template-plan.php'
        ));

        foreach ($ids as $id) {
            $post = get_post((int) $id);

            if (!$post || $post->post_type !== 'page' || $post->post_status === 'trash') {
                continue;
            }

            $months = (int) get_post_meta($post->ID, 'plan_months', true);

            $found[$months] = $post->ID;
        }

        return $found;
    }
}

if (!function_exists('iptv_plan_build_pages')) {
    /**
     * Create anything missing.
     *
     * @return array Summary, for the admin notice.
     */
    function iptv_plan_build_pages()
    {
        $definitions = iptv_plan_page_definitions();
        $existing    = iptv_plan_existing_pages();
        $summary     = array('created' => 0, 'reused' => 0);

        foreach ($definitions as $months => $def) {

            $post_id = isset($existing[$months]) ? $existing[$months] : 0;

            if ($post_id) {
                $summary['reused']++;
            } else {
                $post_id = wp_insert_post(array(
                    'post_title'   => $def['title'],
                    'post_name'    => $def['slug'],
                    'post_type'    => 'page',
                    'post_status'  => 'draft',
                    'post_content' => '',
                    'meta_input'   => array(
                        '_wp_page_template' => 'template-plan.php',
                    ),
                ), true);

                if (is_wp_error($post_id) || !$post_id) {
                    continue;
                }

                $summary['created']++;
            }

            // Length. update_field() writes the _plan_months field-key
            // reference too, which plain post meta would not.
            if (function_exists('update_field')) {
                update_field('field_plan_months', (string) $months, $post_id);
            } else {
                update_post_meta($post_id, 'plan_months', (string) $months);
            }
        }

        return $summary;
    }
}

/**
 * Run once per PLAN_PAGES_BUILD, on the first request after deploy.
 *
 * The flag is written before the work rather than after, so two requests
 * arriving together cannot both start building. If a run does fail part way,
 * bumping PLAN_PAGES_BUILD re-runs it — and because the whole thing matches on
 * existing pages first, the retry repairs instead of duplicating.
 */
add_action('init', function () {
    if ((int) get_option('iptv_plan_pages_built') === PLAN_PAGES_BUILD) {
        return;
    }

    update_option('iptv_plan_pages_built', PLAN_PAGES_BUILD, false);

    $summary = iptv_plan_build_pages();

    update_option('iptv_plan_pages_report', $summary, false);

    // LiteSpeed caches these pages the first time they are hit, so a template
    // or copy change that ships alongside a build bump would otherwise not be
    // visible until the cache expired on its own. Same call the price table
    // uses after a rebuild — see IPTV_Currency_Settings::rebuild_price_table().
    do_action('litespeed_purge_all');
}, 20);
