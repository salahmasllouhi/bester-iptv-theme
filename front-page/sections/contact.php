<?php
// Get Content Settings
$content = get_option('iptv_content', []);
$title = $content['contact_title'] ?? 'Need Help?';
$subtitle = $content['contact_subtitle'] ?? 'Our support team is here for you 24/7';

$email_label = $content['contact_email'] ?? 'Email Support';
$email_text = $content['contact_email_text'] ?? 'support@nordictv.com';
$email_link = $content['contact_email_link'] ?? 'mailto:support@nordictv.com';

$whatsapp_label = $content['contact_whatsapp'] ?? 'WhatsApp Support';
$whatsapp_text = $content['contact_whatsapp_text'] ?? '+1 234 567 890';
$whatsapp_link = $content['contact_whatsapp_link'] ?? 'https://wa.me/1234567890';
?>

<!-- Contact Section -->
<section id="contact" class="contact">
    <div class="container">

        <!-- Header -->
        <div class="contact-header">
            <h2>
                <?php echo esc_html($title); ?>
            </h2>
            <p>
                <?php echo esc_html($subtitle); ?>
            </p>
        </div>

        <!-- Contact Grid (2 Columns) -->
        <div class="contact-grid">

            <!-- Email Card -->
            <a href="<?php echo esc_url($email_link); ?>" class="contact-card">
                <div class="contact-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                </div>
                <h3>
                    <?php echo esc_html($email_label); ?>
                </h3>
                <p>
                    <?php echo esc_html($email_text); ?>
                </p>
            </a>

            <!-- WhatsApp Card -->
            <a href="<?php echo esc_url($whatsapp_link); ?>" class="contact-card" target="_blank"
                rel="noopener noreferrer">
                <div class="contact-icon">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                </div>
                <h3>
                    <?php echo esc_html($whatsapp_label); ?>
                </h3>
                <p>
                    <?php echo esc_html($whatsapp_text); ?>
                </p>
            </a>

        </div>
    </div>
</section>