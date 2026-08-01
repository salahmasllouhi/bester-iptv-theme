<?php
/**
 * Plan hero
 *
 * Headline, the "from" price and three trust lines. The CTA points at the price
 * grid rather than straight at checkout: the price depends on how many screens
 * the visitor wants, and sending them to a checkout for a screen count they
 * never chose is how you get refunds.
 *
 * Expects from template-plan.php:
 *   $plan_months (int)  $plan_label (string)  $plan_from (float)
 */

$hero_eyebrow  = iptv_plan_field('plan_eyebrow', iptv_text('plan_eyebrow', 'IPTV Subscription'));
$hero_headline = iptv_plan_field('plan_headline', sprintf(
    /* translators: %s = plan length, e.g. "1 Month" */
    __('%s IPTV Subscription', 'my-iptv'),
    $plan_label
));
$hero_subline = iptv_plan_field('plan_subline', $plan_months === 1
    ? __('The whole service, one month at a time. No contract, no auto-renew — stop whenever you like.', 'my-iptv')
    : sprintf(
        /* translators: %s = plan length, e.g. "6 Months" */
        __('The whole service for %s. One payment, no contract, no auto-renew.', 'my-iptv'),
        $plan_label
    ));

// Three short reassurances. ACF repeater when filled, otherwise these.
$hero_points = array();
$rows        = iptv_plan_field('plan_hero_points', array());

if (is_array($rows)) {
    foreach ($rows as $row) {
        if (!empty($row['text'])) {
            $hero_points[] = $row['text'];
        }
    }
}

if (empty($hero_points)) {
    $hero_points = array(
        __('Watching in 60 seconds', 'my-iptv'),
        __('No contract, no auto-renew', 'my-iptv'),
        __('24/7 support', 'my-iptv'),
    );
}

$hero_cta   = iptv_plan_field('plan_cta_text', iptv_text('plan_cta_text', 'See prices'));
$trial_url  = iptv_config('trial_url', 'https://panel.nordictv.io/checkout/trial');
$trial_text = iptv_text('trial_cta', 'Start a 24-hour trial — no card');
?>
<section class="plan-hero">
    <div class="container plan-hero-inner">

        <p class="plan-hero-eyebrow"><?php echo esc_html($hero_eyebrow); ?></p>

        <h1 class="plan-hero-title"><?php echo esc_html($hero_headline); ?></h1>

        <p class="plan-hero-sub"><?php echo esc_html($hero_subline); ?></p>

        <?php if ($plan_from > 0) : ?>
            <p class="plan-hero-price">
                <span class="plan-hero-price-label"><?php echo esc_html(iptv_text('plan_from_label', 'From')); ?></span>
                <span class="plan-hero-price-value"><?php echo esc_html(iptv_plan_format_price($plan_from)); ?></span>
                <span class="plan-hero-price-per">
                    <?php echo esc_html($plan_months === 1
                        ? iptv_text('per_month', 'per month')
                        : sprintf(
                            /* translators: %s = plan length, e.g. "6 Months" */
                            __('for %s', 'my-iptv'),
                            $plan_label
                        )); ?>
                </span>
            </p>
        <?php endif; ?>

        <div class="plan-hero-actions">
            <a href="#plan-pricing" class="dv2-btn dv2-btn-primary dv2-btn-lg">
                <?php echo esc_html($hero_cta); ?>
            </a>
            <?php // Same white-button treatment as the front-page hero's second CTA. ?>
            <a href="<?php echo esc_url($trial_url); ?>" class="dv2-btn dv2-hero-link">
                <?php echo esc_html($trial_text); ?>
            </a>
        </div>

        <ul class="plan-hero-points">
            <?php foreach ($hero_points as $point) : ?>
                <li><?php echo esc_html($point); ?></li>
            <?php endforeach; ?>
        </ul>

    </div>
</section>
