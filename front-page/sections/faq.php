<?php
/**
 * Section: FAQ (Design v2)
 * Single-column accordion; content still comes from the ACF faq_list repeater.
 */
$faq_title    = iptv_text('faq_title', 'Frequently asked questions');
$faq_subtitle = iptv_text('faq_subtitle', 'Got questions? We have answers.');

$faq_items = [];
if (function_exists('get_field')) {
    $front_page_id = get_option('page_on_front');
    $acf_items     = $front_page_id ? get_field('faq_list', $front_page_id) : get_field('faq_list');
    if (!empty($acf_items) && is_array($acf_items)) {
        foreach ($acf_items as $row) {
            if (!empty($row['question'])) {
                $faq_items[] = ['q' => $row['question'], 'a' => isset($row['answer']) ? $row['answer'] : ''];
            }
        }
    }
}

if (empty($faq_items)) {
    $faq_items = [
        ['q' => 'Was ist IPTV?',                              'a' => 'IPTV liefert TV-Sender und Inhalte auf Abruf über das Internet statt über Satellit oder Kabel. Du verbindest eine passende App mit deinen Zugangsdaten und kannst sofort loslegen.'],
        ['q' => 'Welche Geräte werden unterstützt?',          'a' => 'Smart TVs, Android TV, Apple TV, Fire Stick, iOS, Android, Windows, Mac, Set-Top-Boxen, Chromecast, Roku und Kodi — du brauchst keine neue Hardware.'],
        ['q' => 'Wie schnell muss meine Internetverbindung sein?', 'a' => 'Wir empfehlen mindestens 15–25 Mbit/s für flüssiges HD- und 4K-Streaming. Bei langsameren Verbindungen passt sich die Bitrate automatisch an.'],
        ['q' => 'Kann ich jederzeit kündigen?',               'a' => 'Ja. Es gibt keine Verträge und keine automatische Verlängerung — du kündigst, wann du möchtest, ohne Gebühren.'],
        ['q' => 'Wie viele Geräte kann ich gleichzeitig nutzen?', 'a' => 'Je nach Paket können 1 bis 4 Geräte gleichzeitig streamen.'],
        ['q' => 'Gibt es einen kostenlosen Test?',            'a' => 'Ja, es gibt einen 24-Stunden-Test, mit dem du den Dienst unverbindlich ausprobieren kannst. Auf jedes bezahlte Paket gibt es zusätzlich 30 Tage Geld-zurück-Garantie.'],
        ['q' => 'Welche Zahlungsmethoden werden akzeptiert?', 'a' => 'Visa, Mastercard, PayPal und Bitcoin — alles über eine sichere SSL-Kasse.'],
        ['q' => 'Welche Länder und Sprachen sind verfügbar?', 'a' => 'Die Inhalte umfassen 198 Länder, mit Untertiteln in vielen Sprachen und vollständiger Programmzeitschrift.'],
        ['q' => 'Wie erreiche ich den Support?',              'a' => 'Unser Support ist rund um die Uhr für dich da: <a href="mailto:support@bester-iptv-anbieter.com">support@bester-iptv-anbieter.com</a>.'],
    ];
}
?>
<section class="faq dv2-section" id="faq">
    <div class="faq-container">
        <div class="dv2-section-head">
            <h2><?php echo esc_html($faq_title); ?></h2>
            <p><?php echo esc_html($faq_subtitle); ?></p>
        </div>

        <div class="dv2-faq-list">
            <?php foreach ($faq_items as $item) :
                if (empty($item['q'])) {
                    continue;
                }
                ?>
                <div class="dv2-faq-item">
                    <button class="dv2-faq-q" type="button" aria-expanded="false">
                        <span><?php echo esc_html($item['q']); ?></span>
                        <span class="dv2-faq-icon" aria-hidden="true">›</span>
                    </button>
                    <div class="dv2-faq-a">
                        <div class="dv2-faq-a-inner"><?php echo wp_kses_post($item['a']); ?></div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<script>
    // One panel open at a time, matching the mockup's behaviour.
    document.querySelectorAll('.dv2-faq-q').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.parentElement;
            var willOpen = !item.classList.contains('open');

            document.querySelectorAll('.dv2-faq-item.open').forEach(function (openItem) {
                openItem.classList.remove('open');
                var q = openItem.querySelector('.dv2-faq-q');
                if (q) q.setAttribute('aria-expanded', 'false');
            });

            if (willOpen) {
                item.classList.add('open');
                button.setAttribute('aria-expanded', 'true');
            }
        });
    });
</script>
