<?php
/**
 * Closing CTA
 *
 * Sends the visitor back up to the price grid rather than to a checkout, for
 * the same reason the hero does: the screen count is still theirs to pick.
 *
 * Expects from template-plan.php:
 *   $plan_months (int)  $plan_label (string)  $plan_from (float)
 */

$cta_title = iptv_plan_field('plan_final_title', sprintf(
    /* translators: %s = plan length, e.g. "1 Month" */
    plan_str('Start your %s plan today'),
    $plan_label
));

$cta_text = iptv_plan_field('plan_final_text', $plan_from > 0
    ? sprintf(
        /* translators: %s = formatted price, e.g. "$16.99" */
        plan_str('From %s. Activated in about a minute, watchable on the TV you already own.'),
        iptv_plan_format_price($plan_from)
    )
    : plan_str('Activated in about a minute, watchable on the TV you already own.'));

$cta_button = iptv_plan_field(
    'plan_final_cta_text',
    iptv_plan_field('plan_cta_text', iptv_text('plan_cta_text', plan_str('See prices')))
);
?>
<section class="plan-final">
    <div class="container plan-final-inner">
        <h2 class="plan-final-title"><?php echo esc_html($cta_title); ?></h2>
        <p class="plan-final-text"><?php echo esc_html($cta_text); ?></p>
        <a href="#plan-pricing" class="dv2-btn dv2-btn-white dv2-btn-lg">
            <?php echo esc_html($cta_button); ?>
        </a>
    </div>
</section>
