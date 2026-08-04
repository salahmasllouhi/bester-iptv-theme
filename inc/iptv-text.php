<?php
/**
 * iptv_text() – front page copy lookup
 *
 * Every string on the front page (and in the header and footer, which render on
 * every template) goes through this function.
 *
 * Resolution order:
 *   1. ACF field on the front page, which is what makes the whole page editable
 *      from the page editor.
 *   2. The same key as plain post meta, for fields added to acf-json/ but not
 *      yet synced into the database.
 *   3. The English default written into the template.
 *
 * This replaces IPTV_Content_Settings::get_text(), which also consulted an
 * `iptv_content` option keyed by the site slugs of the old multisite install
 * (se/no/dk/fi/is) — a layer that never matched and always fell through to the
 * default.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_text')) {
    /**
     * Get the current language's copy for a front page key.
     *
     * @param string $key     Field name on the front page ACF group.
     * @param string $default English fallback.
     * @return string
     */
    function iptv_text($key, $default = '')
    {
        // Keys backed by an ACF link field (an array). Templates read those
        // directly, so the lookup here would only ever return the wrong shape.
        static $acf_skip_keys = array('hero_cta');

        // NOTE: the old get_text() mapped 'hero_title_span' to a field named
        // 'hero_title_gradient_text'. No such field exists — the ACF field is
        // named 'hero_title_span' and only its *label* says "Gradient Text" — so
        // the lookup always missed and the hero's second line silently fell back
        // to the template default, ignoring whatever was typed in the editor.

        $front_page_id = get_option('page_on_front');

        $value = null;

        if (function_exists('get_field') && !in_array($key, $acf_skip_keys, true)) {
            $found = $front_page_id
                ? get_field($key, $front_page_id)
                : get_field($key);

            if ($found !== null && $found !== '' && !is_array($found)) {
                $value = $found;
            }
        }

        // get_field() resolves nothing for a field ACF has not registered, which
        // is the case for any field added to acf-json/ but not yet synced into
        // the database. The value is still plain post meta under the same key, so
        // read it directly rather than falling through to the English default.
        if ($value === null && $front_page_id) {
            $meta = get_post_meta($front_page_id, $key, true);
            if (is_string($meta) && $meta !== '') {
                $value = $meta;
            }
        }

        if ($value === null) {
            $value = $default;
        }

        /**
         * Last word on any front-page string.
         *
         * The front page's sections are also the body of every keyword landing
         * page, which needs the same layout with its own wording — so those
         * pages hook this and answer for the handful of keys where the keyword
         * belongs, leaving the rest to fall through to the front page. See
         * keyword/inc/keyword-text.php.
         *
         * @param string $value   Resolved copy.
         * @param string $key     Field name.
         * @param string $default Template fallback.
         */
        return apply_filters('iptv_text', $value, $key, $default);
    }
}
