<?php
/**
 * Front page and keyword pages — Rank Math content analysis
 *
 * Rank Math's content tests read post_content. The front page's post_content is
 * empty — every word on it comes from ACF and from front-page/sections/ — so
 * Rank Math scored content length, keyword-in-content, keyword-in-subheading,
 * image alt text and every link test against nothing at all.
 *
 * iptv_front_copy_blocks() hands it the copy a visitor actually reads, built
 * from the same iptv_text() lookups the sections render from rather than by
 * running the templates, which would mean prices, WooCommerce queries and a
 * carousel inside an admin request.
 *
 * It is keyword-aware by construction: iptv_text() answers for whichever
 * keyword page is in context, so the same builder produces the front page's
 * digest and each keyword page's. See keyword/inc/keyword-seo.php.
 *
 * Headings are emitted at the level the page really uses them, because
 * "focus keyword in subheading" only counts h2–h6.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_front_copy_blocks')) {
    /**
     * The front-page section stack as HTML, one entry per section.
     *
     * Keyed by section rather than concatenated so the order stays legible
     * against front-page.php, and so a section that stops being rendered is a
     * one-line deletion here rather than a hunt through a string.
     *
     * @return array<string,string>
     */
    function iptv_front_copy_blocks()
    {
        $blocks = array();

        // ── Hero ────────────────────────────────────────────────────────────
        $blocks['hero'] = '<h1>'
            . iptv_text('hero_title', '') . ' '
            . iptv_text('hero_title_span', '') . ' '
            . iptv_text('hero_title_3', '')
            . '</h1>'
            . '<p>' . iptv_text('hero_subtitle', '') . '</p>'
            . '<p>'
            . iptv_text('hero_trust_label', '') . ' '
            . iptv_text('hero_trust_score', '') . ' '
            . iptv_text('hero_trust_suffix', '') . ' · '
            . iptv_text('hero_primary_cta_label', '') . ' · '
            . iptv_text('hero_secondary_cta_label', '')
            . '</p>'
            . '<img src="' . esc_url(iptv_text('hero_image_url', '')) . '" alt="'
            . esc_attr(iptv_text('hero_image_alt', '')) . '" />';

        // ── Channels + VOD panels ───────────────────────────────────────────
        $blocks['showcase'] = '<h3>'
            . iptv_text('showcase_title', '') . ' '
            . iptv_text('showcase_title_span', '') . ' '
            . iptv_text('showcase_title_3', '')
            . '</h3>'
            . '<p>' . iptv_text('showcase_subtitle', '') . '</p>'
            . '<ul>'
            . '<li>' . iptv_text('showcase_f1', '') . '</li>'
            . '<li>' . iptv_text('showcase_f2', '') . '</li>'
            . '<li>' . iptv_text('showcase_f3', '') . '</li>'
            . '<li>' . iptv_text('showcase_f4', '') . '</li>'
            . '</ul>'
            . '<h3>'
            . iptv_text('vod_title', '') . ' '
            . iptv_text('vod_title_span', '') . ' '
            . iptv_text('vod_title_3', '')
            . '</h3>'
            . '<p>' . iptv_text('vod_subtitle', '') . '</p>'
            . '<p>' . iptv_text('showcase_channel_more', '') . '</p>'
            . '<img src="" alt="' . esc_attr(iptv_text('vod_image_alt', '')) . '" />';

        // ── Sport ───────────────────────────────────────────────────────────
        // No sport_N_name list: the six-tile mosaic was replaced by a single
        // artwork, so those fields are no longer on the page. See sports.php.
        $blocks['sports'] = '<h3>'
            . iptv_text('sports_title', '') . ' '
            . iptv_text('sports_title_span', '')
            . '</h3>'
            . '<p>' . iptv_text('sports_desc', '') . '</p>'
            . '<img src="" alt="' . esc_attr(iptv_text('sports_image_alt', '')) . '" />'
            . '<p>' . iptv_text('cta_bar_label', '') . '</p>';

        // ── Features ────────────────────────────────────────────────────────
        $features = '';
        for ($i = 1; $i <= 8; $i++) {
            $title = iptv_text('feature_' . $i . '_title', '');
            if ($title === '') {
                continue;
            }
            $features .= '<h3>' . $title . '</h3>'
                . '<p>' . iptv_text('feature_' . $i . '_desc', '') . '</p>';
        }

        $blocks['features'] = '<h2>'
            . iptv_text('features_title', '') . ' '
            . iptv_text('features_title_span', '')
            . '</h2>'
            . '<p>' . iptv_text('features_subtitle', '') . '</p>'
            . $features
            . '<p>' . iptv_text('features_cta_label', '') . ' — '
            . iptv_text('features_cta_note', '') . '</p>';

        // ── Prices ──────────────────────────────────────────────────────────
        $card_features = '';
        for ($i = 1; $i <= 8; $i++) {
            $card_features .= '<li>' . iptv_text('card_feature_' . $i, '') . '</li>';
        }

        $blocks['pricing'] = '<h2>'
            . iptv_text('pricing_title', '') . ' '
            . iptv_text('pricing_title_span', '')
            . '</h2>'
            . '<p>' . iptv_text('pricing_subtitle', '') . '</p>'
            . '<p>' . iptv_text('screens_title', '') . '</p>'
            . '<ul>'
            . '<li>' . iptv_text('month_1_label', '') . '</li>'
            . '<li>' . iptv_text('month_3_label', '') . '</li>'
            . '<li>' . iptv_text('month_6_label', '') . '</li>'
            . '<li>' . iptv_text('month_12_label', '') . '</li>'
            . '</ul>'
            . '<ul>' . $card_features . '</ul>'
            . '<p>' . iptv_text('guarantee_text', '') . '</p>'
            . '<p>'
            . iptv_text('checkout_trust_1', '') . ' · '
            . iptv_text('checkout_trust_2', '') . ' · '
            . iptv_text('checkout_trust_3', '') . ' · '
            . iptv_text('payments_label', '') . '</p>'
            . '<p>' . iptv_text('trial_prompt', '') . ' ' . iptv_text('trial_cta', '') . '</p>';

        // ── Onboarding ──────────────────────────────────────────────────────
        $steps = '';
        for ($i = 1; $i <= 3; $i++) {
            $title = iptv_text('step_' . $i . '_title', '');
            if ($title === '') {
                continue;
            }
            $steps .= '<h3>' . $title . '</h3>'
                . '<p>' . iptv_text('step_' . $i . '_desc', '') . '</p>';
        }

        $blocks['steps'] = '<h2>'
            . iptv_text('steps_title', '') . ' '
            . iptv_text('steps_title_span', '')
            . '</h2>'
            . '<p>' . iptv_text('steps_subtitle', '') . '</p>'
            . $steps
            . '<p>'
            . iptv_text('step_1_visual', '') . ' · '
            . iptv_text('step_2_visual', '') . ' · '
            . iptv_text('step_3_visual', '')
            . '</p>';

        // ── Devices ─────────────────────────────────────────────────────────
        $blocks['unlock'] = '<h2>' . iptv_text('devices_section_title', '') . '</h2>'
            . '<p>' . iptv_text('devices_subtitle', '') . '</p>'
            . '<p>' . iptv_text('device_list', '') . '</p>';

        // ── Reviews ─────────────────────────────────────────────────────────
        $wall = '';
        foreach (iptv_front_reviews() as $review) {
            $wall .= '<p><strong>' . $review['title'] . '</strong> '
                . $review['text'] . ' — ' . $review['author'] . '</p>';
        }

        $blocks['reviews'] = '<h2>' . iptv_text('reviews_title', '') . '</h2>'
            . '<p>' . iptv_text('reviews_subtitle', '') . '</p>'
            . $wall;

        // ── FAQ ─────────────────────────────────────────────────────────────
        $faq = '';
        foreach (iptv_front_faq() as $item) {
            $faq .= '<p><strong>' . $item['q'] . '</strong> ' . $item['a'] . '</p>';
        }

        $blocks['faq'] = '<h2>' . iptv_text('faq_title', '') . '</h2>'
            . '<p>' . iptv_text('faq_subtitle', '') . '</p>'
            . $faq;

        // ── Support ─────────────────────────────────────────────────────────
        $cards = '';
        $rows  = function_exists('get_field')
            ? get_field('contact_cards', get_option('page_on_front'))
            : null;

        foreach ((array) $rows as $row) {
            if (!empty($row['card_label'])) {
                $cards .= '<li>' . $row['card_label'] . ' — '
                    . (isset($row['card_value']) ? $row['card_value'] : '') . '</li>';
            }
        }

        $blocks['contact'] = '<h2>' . iptv_text('contact_title', '') . '</h2>'
            . '<p>' . iptv_text('contact_subtitle', '') . '</p>'
            . ($cards ? '<ul>' . $cards . '</ul>' : '');

        // ── Header and footer ───────────────────────────────────────────────
        // Rendered on this page like any other section, and the only place the
        // page links anywhere. Leaving them out made Rank Math report a page
        // with no internal and no external links, which is simply not true.
        $blocks['nav'] = iptv_front_links_digest();

        return $blocks;
    }
}

if (!function_exists('iptv_front_links_digest')) {
    /**
     * The links the header and footer really render, as anchors.
     *
     * @return string
     */
    function iptv_front_links_digest()
    {
        $links = array(
            array(home_url('/'), iptv_text('nav_link_home', 'Start')),
            array(iptv_page_url('blog'), iptv_text('footer_link_blog', 'Blog')),
            array(iptv_page_url('iptv-guide-setup-apps-devices-tips'), iptv_text('nav_link_guide', 'Anleitung')),
            array(iptv_page_url('m3u-playlist-convert-your-m3u-url'), iptv_text('footer_link_m3u', 'M3U-Konverter')),
            array(iptv_page_url('contact-us'), iptv_text('nav_link_contact', 'Kontakt')),
            array(iptv_page_url('about-us'), iptv_text('footer_link_about', 'Über uns')),
            array(iptv_page_url('privacy-policy'), iptv_text('footer_link_privacy', 'Datenschutz')),
            array(iptv_page_url('terms-of-services'), iptv_text('footer_link_terms', 'AGB')),
            array(iptv_page_url('return-refund-policy'), iptv_text('footer_link_refund', 'Widerruf & Rückerstattung')),
        );

        foreach (array(1, 3, 6, 12) as $months) {
            if (function_exists('iptv_plan_url') && iptv_plan_url($months)) {
                $links[] = array(iptv_plan_url($months), iptv_plan_label($months));
            }
        }

        if (function_exists('iptv_keyword_definitions')) {
            foreach (iptv_keyword_definitions() as $slug => $definition) {
                $links[] = array(iptv_keyword_page_url($slug), $definition['keyword']);
            }
        }

        // The account panel and the checkout, both off-domain and both followed.
        $links[] = array(iptv_text('hero_secondary_cta_url', 'https://panel.nordictv.io/login'), iptv_text('nav_link_account', 'Mein Konto'));
        $links[] = array('https://panel-checkout.com/', iptv_text('checkout_button', 'Jetzt kaufen'));

        $out = '';
        foreach ($links as $link) {
            if (empty($link[0])) {
                continue;
            }
            $out .= sprintf('<a href="%s">%s</a> ', esc_url($link[0]), esc_html($link[1]));
        }

        return '<p>' . $out . '</p>';
    }
}

if (!function_exists('iptv_front_page_digest')) {
    /**
     * The whole front page, in page order.
     *
     * The body band that used to sit after the onboarding panel is no longer
     * rendered — see front-page.php — so it is no longer digested either. A
     * digest that included it would have Rank Math scoring 700 words the
     * visitor never sees, which is the exact failure this file exists to fix.
     *
     * @return string
     */
    function iptv_front_page_digest()
    {
        return implode("\n", iptv_front_copy_blocks());
    }
}

/**
 * Give Rank Math the front page rather than its empty body field.
 *
 * Appended rather than substituted, so body copy an editor does write stays at
 * the start of the content — which is what the "keyword at the beginning of the
 * content" test measures.
 */
add_filter('rank_math/researches/post_content', function ($content, $post = null) {
    $post          = get_post($post);
    $front_page_id = (int) get_option('page_on_front');

    if (!$post || !$front_page_id || (int) $post->ID !== $front_page_id) {
        return $content;
    }

    return $content . "\n" . iptv_front_page_digest();
}, 10, 2);
