<?php
/**
 * Plan FAQ
 *
 * Same accordion markup and behaviour as the front page, so it inherits the
 * .dv2-faq-* styling. Questions come from iptv_plan_faq_items(), which the
 * schema section reads too — one source, so the accordion and the rich result
 * can never list different questions.
 *
 * Expects from template-plan.php:
 *   $plan_months (int)  $plan_faq_items (array)
 */

$faq_title = iptv_plan_field(
    'plan_faq_title',
    iptv_text('faq_title', 'Frequently asked questions')
);

if (empty($plan_faq_items)) {
    return;
}
?>
<section class="plan-faq dv2-section" id="faq">
    <div class="faq-container">

        <div class="dv2-section-head">
            <h2><?php echo esc_html($faq_title); ?></h2>
        </div>

        <div class="dv2-faq-list">
            <?php foreach ($plan_faq_items as $item) : ?>
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
    // One panel open at a time, matching the front page.
    document.querySelectorAll('.plan-faq .dv2-faq-q').forEach(function (button) {
        button.addEventListener('click', function () {
            var item = button.parentElement;
            var willOpen = !item.classList.contains('open');

            document.querySelectorAll('.plan-faq .dv2-faq-item.open').forEach(function (openItem) {
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
