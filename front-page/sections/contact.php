<?php
/**
 * Section: Support (Design v2)
 * Three contact cards. Values still come from the content settings option.
 */
$content  = get_option('iptv_content', []);
$title    = $content['contact_title']    ?? 'We\'re here to help';
$subtitle = $content['contact_subtitle'] ?? 'Reach out anytime via email, WhatsApp, or Telegram. Our support team typically responds within minutes.';

$cards = [
    [
        'label' => $content['contact_email'] ?? 'Email Support',
        'value' => $content['contact_email_text'] ?? 'support@nordictv.io',
        'link'  => $content['contact_email_link'] ?? 'mailto:support@nordictv.io',
        'blank' => false,
    ],
    [
        'label' => $content['contact_whatsapp'] ?? 'WhatsApp',
        'value' => $content['contact_whatsapp_text'] ?? 'Chat with us live',
        'link'  => $content['contact_whatsapp_link'] ?? 'https://wa.me/1234567890',
        'blank' => true,
    ],
    [
        'label' => $content['contact_telegram'] ?? 'Telegram',
        'value' => $content['contact_telegram_text'] ?? '@NordicTV',
        'link'  => $content['contact_telegram_link'] ?? 'https://t.me/NordicTV',
        'blank' => true,
    ],
];
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
