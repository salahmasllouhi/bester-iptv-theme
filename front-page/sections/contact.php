<?php
/**
 * Section: Support (Design v2)
 * Three contact cards. Values still come from the content settings option.
 */
$title    = iptv_text('contact_title', 'We\'re here to help');
$subtitle = iptv_text('contact_subtitle', 'Reach out anytime via email, WhatsApp, or Telegram. Our support team typically responds within minutes.');

// Cards come from the `contact_cards` repeater on the front page so they are
// translated per language. An empty repeater falls back to the defaults below.
$cards = [];
$card_rows = function_exists('get_field') ? get_field('contact_cards', get_option('page_on_front')) : null;

if (is_array($card_rows)) {
    foreach ($card_rows as $row) {
        if (empty($row['card_label'])) {
            continue;
        }
        $cards[] = [
            'label' => $row['card_label'],
            'value' => $row['card_value'] ?? '',
            'link'  => $row['card_link'] ?? '',
            'blank' => !empty($row['card_blank']),
        ];
    }
}

if (empty($cards)) {
    $cards = [
        [
            'label' => 'Email Support',
            'value' => 'support@nordictv.io',
            'link'  => 'mailto:support@nordictv.io',
            'blank' => false,
        ],
        [
            'label' => 'WhatsApp',
            'value' => 'Chat with us live',
            'link'  => 'https://wa.me/33745476690',
            'blank' => true,
        ],
        [
            'label' => 'Telegram',
            'value' => '@NordicTV',
            'link'  => 'https://t.me/NordicTV',
            'blank' => true,
        ],
    ];
}
?>
<section id="contact" class="contact dv2-section">
    <div class="container">
        <div class="dv2-section-head">
            <h2><?php echo esc_html($title); ?></h2>
            <p style="max-width:620px;"><?php echo esc_html($subtitle); ?></p>
        </div>

        <div class="dv2-support-grid">
            <?php foreach ($cards as $card) : ?>
                <a href="<?php echo esc_url($card['link']); ?>" class="dv2-support-card"
                    <?php echo $card['blank'] ? ' target="_blank" rel="noopener noreferrer"' : ''; ?>>
                    <h3><?php echo esc_html($card['label']); ?></h3>
                    <p><?php echo esc_html($card['value']); ?></p>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</section>
