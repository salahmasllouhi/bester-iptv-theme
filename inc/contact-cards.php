<?php
/**
 * Contact cards
 *
 * The support cards on the front page — email, WhatsApp, Telegram — extracted so
 * the Contact page can show the same thing instead of a form.
 *
 * The cards come from the `contact_cards` ACF repeater on the front page, so one
 * shortcode renders the same cards on the Contact page. Pasting the markup into
 * the page content would have meant two copies to keep in step.
 *
 * Usage in a page: [nordictv_contact]
 *   heading="1"  also render the section's <h2> (the page template already
 *                prints the page title, so this is off by default)
 *   intro="0"    drop the intro paragraph
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_contact_cards')) {
    /**
     * The support cards, translated for the current language.
     *
     * @return array<int,array{label:string,value:string,link:string,blank:bool}>
     */
    function iptv_contact_cards()
    {
        $cards = array();

        $rows = function_exists('get_field')
            ? get_field('contact_cards', get_option('page_on_front'))
            : null;

        if (is_array($rows)) {
            foreach ($rows as $row) {
                if (empty($row['card_label'])) {
                    continue;
                }
                $cards[] = array(
                    'label' => $row['card_label'],
                    'value' => isset($row['card_value']) ? $row['card_value'] : '',
                    'link'  => isset($row['card_link']) ? $row['card_link'] : '',
                    'blank' => !empty($row['card_blank']),
                );
            }
        }

        if (empty($cards)) {
            $cards = array(
                array(
                    'label' => iptv_text('contact_card_email_label', 'Email Support'),
                    'value' => iptv_text('contact_card_email_value', 'support@nordictv.io'),
                    'link'  => 'mailto:support@nordictv.io',
                    'blank' => false,
                ),
                array(
                    'label' => iptv_text('contact_card_whatsapp_label', 'WhatsApp'),
                    'value' => iptv_text('contact_card_whatsapp_value', 'Chat with us live'),
                    'link'  => 'https://wa.me/33745476690',
                    'blank' => true,
                ),
                array(
                    'label' => iptv_text('contact_card_telegram_label', 'Telegram'),
                    'value' => iptv_text('contact_card_telegram_value', '@IPTV Anbieter'),
                    'link'  => 'https://t.me/IPTV Anbieter',
                    'blank' => true,
                ),
            );
        }

        return $cards;
    }
}

if (!function_exists('iptv_contact_cards_grid')) {
    /**
     * The cards themselves, without any section chrome.
     *
     * @return string
     */
    function iptv_contact_cards_grid()
    {
        $cards = iptv_contact_cards();
        if (empty($cards)) {
            return '';
        }

        $out = '<div class="dv2-support-grid">';

        foreach ($cards as $card) {
            $target = $card['blank'] ? ' target="_blank" rel="noopener noreferrer"' : '';

            // Channel is read off the link rather than the label, so a renamed
            // or translated label cannot silently change the styling.
            $link    = (string) $card['link'];
            $channel = 'generic';
            if (strpos($link, 'wa.me') !== false || strpos($link, 'whatsapp') !== false) {
                $channel = 'whatsapp';
            } elseif (strpos($link, 'mailto:') === 0) {
                $channel = 'email';
            } elseif (strpos($link, 't.me') !== false || strpos($link, 'telegram') !== false) {
                $channel = 'telegram';
            }

            // WhatsApp shows the number itself: "chat with us" asks for trust,
            // a number you can see and dial gives a reason for it. Taken from
            // the wa.me link so there is one source for it.
            $detail = '';
            if ($channel === 'whatsapp' && preg_match('#wa\.me/(\+?\d+)#', $link, $m)) {
                $digits = ltrim($m[1], '+');
                // +33 7 45 47 66 90 — grouped so it reads as a phone number.
                $pretty = '+' . $digits;
                if (strlen($digits) > 4) {
                    $pretty = '+' . substr($digits, 0, 2) . ' ' . trim(chunk_split(substr($digits, 2), 2, ' '));
                }
                $detail = '<span class="dv2-support-number">' . esc_html($pretty) . '</span>';
            }

            $cta_label = $card['label'];
            if ($channel === 'whatsapp') {
                $cta_label = iptv_text('contact_cta_whatsapp', 'Nachricht senden');
            } elseif ($channel === 'email') {
                $cta_label = iptv_text('contact_cta_email', 'E-Mail schreiben');
            } elseif ($channel === 'telegram') {
                $cta_label = iptv_text('contact_cta_telegram', 'Telegram öffnen');
            }

            $out .= sprintf(
                '<a href="%s" class="dv2-support-card dv2-support-card--%s"%s>'
                    . '<h3>%s</h3><p>%s</p>%s'
                    . '<span class="dv2-support-cta">%s</span>'
                    . '</a>',
                esc_url($link),
                esc_attr($channel),
                $target,
                esc_html($card['label']),
                esc_html($card['value']),
                $detail,
                esc_html($cta_label)
            );
        }

        return $out . '</div>';
    }
}

/**
 * [nordictv_contact] — the front page's support cards, for use on the Contact
 * page in place of a contact form.
 */
add_shortcode('nordictv_contact', function ($atts) {
    $atts = shortcode_atts(array(
        'heading' => '0',
        'intro'   => '1',
    ), $atts, 'nordictv_contact');

    $out = '<div class="dv2-contact-page">';

    if ($atts['heading'] !== '0') {
        $out .= '<h2>' . esc_html(iptv_text('contact_title', 'We\'re here to help')) . '</h2>';
    }

    if ($atts['intro'] !== '0') {
        $out .= '<p class="dv2-contact-page-intro">' . esc_html(iptv_text(
            'contact_subtitle',
            'Reach out anytime via email, WhatsApp, or Telegram. Our support team typically responds within minutes.'
        )) . '</p>';
    }

    $out .= iptv_contact_cards_grid();

    return $out . '</div>';
});
