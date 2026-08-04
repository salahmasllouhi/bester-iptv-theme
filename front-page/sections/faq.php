<?php
/**
 * Section: FAQ (Design v2)
 *
 * Single-column accordion. The questions come from iptv_front_faq(): the
 * keyword page's own list when one is being rendered, the front page's ACF
 * repeater otherwise, and the defaults in inc/front-page-strings.php when
 * neither is filled in. That lookup is shared with the Rank Math digest, so the
 * analysis always sees the questions the page really shows.
 */
$faq_title    = iptv_text('faq_title', 'Frequently asked questions');
$faq_subtitle = iptv_text('faq_subtitle', 'Got questions? We have answers.');

$faq_items = iptv_front_faq();
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
