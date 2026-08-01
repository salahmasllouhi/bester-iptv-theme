<?php
/**
 * Plan pages — one-time provisioning
 *
 * Creates the four plan pages in each of the six languages (24 in total),
 * assigns each its Polylang language and links the six versions of each plan
 * as translations of one another.
 *
 * Why this lives in the theme rather than being done over the REST API: a
 * Polylang translation group is a term in the `post_translations` taxonomy
 * whose *description* is a serialized lang => post_id map. Writing that by hand
 * over an API is fragile — anything that sanitises the description corrupts the
 * group silently. pll_set_post_language() and pll_save_post_translations() are
 * Polylang's own API for it, and they can only be called from inside WordPress.
 *
 * Idempotent. It matches on template + plan_months + language before creating
 * anything, so re-running repairs rather than duplicates — including adopting
 * the English 1-month page that already existed before this ran. To re-run
 * after editing the table below, bump PLAN_PAGES_BUILD.
 *
 * Pages are created as drafts. Publishing 24 pages to a live site is the site
 * owner's call, not a migration's. Note that the compare table only links plans
 * that are published (iptv_plan_url() queries post_status=publish), so the
 * cross-links light up as you publish them.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

// Bump to re-run after changing the titles or slugs below. Re-running is safe:
// existing pages are matched and reused, so only the language, translation
// group and plan length are rewritten — never the status or the content.
define('PLAN_PAGES_BUILD', 2);

if (!function_exists('iptv_plan_page_definitions')) {
    /**
     * Title and slug for every plan in every language.
     *
     * Slugs are deliberately distinct across languages even where the titles
     * collide (Norwegian and Danish are the same phrase), so WordPress never
     * has to disambiguate one with a -2 suffix.
     *
     * @return array<string,array<int,array{title:string,slug:string}>>
     */
    function iptv_plan_page_definitions()
    {
        return array(
            'en' => array(
                1  => array('title' => '1 Month IPTV Subscription',   'slug' => '1-month-iptv-subscription'),
                3  => array('title' => '3 Months IPTV Subscription',  'slug' => '3-months-iptv-subscription'),
                6  => array('title' => '6 Months IPTV Subscription',  'slug' => '6-months-iptv-subscription'),
                12 => array('title' => '12 Months IPTV Subscription', 'slug' => '12-months-iptv-subscription'),
            ),
            'sv' => array(
                1  => array('title' => 'IPTV-abonnemang 1 månad',    'slug' => 'iptv-abonnemang-1-manad'),
                3  => array('title' => 'IPTV-abonnemang 3 månader',  'slug' => 'iptv-abonnemang-3-manader'),
                6  => array('title' => 'IPTV-abonnemang 6 månader',  'slug' => 'iptv-abonnemang-6-manader'),
                12 => array('title' => 'IPTV-abonnemang 12 månader', 'slug' => 'iptv-abonnemang-12-manader'),
            ),
            'no' => array(
                1  => array('title' => 'IPTV-abonnement 1 måned',    'slug' => 'iptv-abonnement-1-maned'),
                3  => array('title' => 'IPTV-abonnement 3 måneder',  'slug' => 'iptv-abonnement-3-maneder'),
                6  => array('title' => 'IPTV-abonnement 6 måneder',  'slug' => 'iptv-abonnement-6-maneder'),
                12 => array('title' => 'IPTV-abonnement 12 måneder', 'slug' => 'iptv-abonnement-12-maneder'),
            ),
            'dk' => array(
                1  => array('title' => 'IPTV-abonnement 1 måned',    'slug' => 'iptv-abonnement-1-maaned'),
                3  => array('title' => 'IPTV-abonnement 3 måneder',  'slug' => 'iptv-abonnement-3-maaneder'),
                6  => array('title' => 'IPTV-abonnement 6 måneder',  'slug' => 'iptv-abonnement-6-maaneder'),
                12 => array('title' => 'IPTV-abonnement 12 måneder', 'slug' => 'iptv-abonnement-12-maaneder'),
            ),
            'fi' => array(
                1  => array('title' => 'IPTV-tilaus 1 kuukausi',   'slug' => 'iptv-tilaus-1-kuukausi'),
                3  => array('title' => 'IPTV-tilaus 3 kuukautta',  'slug' => 'iptv-tilaus-3-kuukautta'),
                6  => array('title' => 'IPTV-tilaus 6 kuukautta',  'slug' => 'iptv-tilaus-6-kuukautta'),
                12 => array('title' => 'IPTV-tilaus 12 kuukautta', 'slug' => 'iptv-tilaus-12-kuukautta'),
            ),
            'is' => array(
                1  => array('title' => 'IPTV áskrift 1 mánuður', 'slug' => 'iptv-askrift-1-manudur'),
                3  => array('title' => 'IPTV áskrift 3 mánuðir', 'slug' => 'iptv-askrift-3-manudir'),
                6  => array('title' => 'IPTV áskrift 6 mánuðir', 'slug' => 'iptv-askrift-6-manudir'),
                12 => array('title' => 'IPTV áskrift 12 mánuðir', 'slug' => 'iptv-askrift-12-manudir'),
            ),
        );
    }
}

if (!function_exists('iptv_plan_existing_pages')) {
    /**
     * Every page already using the plan template, indexed by language and
     * length: [lang][months] => post ID.
     *
     * Straight to postmeta rather than through WP_Query: Polylang filters on
     * parse_query, which 'suppress_filters' does not stop, so a normal query
     * would narrow to the current language — and, worse, would drop the pages
     * that have no language assigned yet, which are exactly the ones this has
     * to find and adopt.
     *
     * @return array<string,array<int,int>>
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
            $lang   = function_exists('pll_get_post_language')
                ? pll_get_post_language($post->ID, 'slug')
                : '';

            // A page with no language yet is parked under '' and claimed by
            // its definition below — that is the English 1-month page.
            $found[$lang ? $lang : ''][$months] = $post->ID;
        }

        return $found;
    }
}

if (!function_exists('iptv_plan_build_pages')) {
    /**
     * Create anything missing, then wire the translation groups.
     *
     * @return array Summary, for the admin notice.
     */
    function iptv_plan_build_pages()
    {
        $definitions = iptv_plan_page_definitions();
        $existing    = iptv_plan_existing_pages();
        $summary     = array('created' => 0, 'reused' => 0, 'linked' => 0);

        // months => [lang => post_id], built as we go and handed to Polylang.
        $groups = array();

        foreach ($definitions as $lang => $plans) {
            foreach ($plans as $months => $def) {

                $post_id = isset($existing[$lang][$months]) ? $existing[$lang][$months] : 0;

                // Adopt a page that has the right template and length but no
                // language assigned — that is the English 1-month page created
                // before this migration existed.
                if (!$post_id && $lang === 'en' && isset($existing[''][$months])) {
                    $post_id = $existing[''][$months];
                    unset($existing[''][$months]);
                }

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

                if (function_exists('pll_set_post_language')) {
                    pll_set_post_language($post_id, $lang);
                }

                $groups[$months][$lang] = $post_id;
            }
        }

        // Link each plan's six versions as translations of one another. This is
        // the step that cannot be done safely from outside WordPress.
        if (function_exists('pll_save_post_translations')) {
            foreach ($groups as $translations) {
                pll_save_post_translations($translations);
                $summary['linked']++;
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

    // Polylang has to be up: without it every page would be created with no
    // language and the groups could not be linked.
    if (!function_exists('pll_set_post_language') || !function_exists('pll_save_post_translations')) {
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
