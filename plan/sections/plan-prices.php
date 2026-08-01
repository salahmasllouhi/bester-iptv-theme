<?php
/**
 * Plan price grid
 *
 * Four cards, one per screen count, each a direct link to the panel checkout.
 * The length is fixed by the page, so the only choice left is how many screens
 * — which is why every price is on screen at once instead of behind a picker.
 *
 * Every number is printed server-side in the request's currency. Currency and
 * language are the same choice on this site (switching either navigates), so
 * there is nothing for JS to repaint and a crawler sees the real prices.
 *
 * Expects from template-plan.php:
 *   $plan_months (int)  $plan_label (string)
 */

$popular = iptv_plan_popular_screens();

$prices_title = iptv_plan_field('plan_pricing_title', sprintf(
    /* translators: %s = plan length, e.g. "1 Month" */
    plan_str('%s — choose your screens'),
    $plan_label
));
$prices_subtitle = iptv_plan_field(
    'plan_pricing_subtitle',
    plan_str('One screen streams on one device at a time. Everything else is identical on every plan.')
);
$buy_text = iptv_plan_field('plan_buy_text', iptv_text('checkout_button', 'Start watching'));

// Does anything on this page have a saving to show? A one-month plan is the
// monthly rate, so it is discounted against itself and never does. Deciding
// once, for the whole grid, keeps the four cards aligned without leaving a
// reserved-but-empty strip on every card of the 1-month page.
$grid_has_savings = false;

if ($plan_months > 1) {
    for ($i = 1; $i <= 4; $i++) {
        if (iptv_plan_savings($plan_months, $i)['off'] > 0.005) {
            $grid_has_savings = true;
            break;
        }
    }
}
?>
<section class="plan-pricing dv2-section" id="plan-pricing">
    <div class="container">

        <div class="dv2-section-head">
            <h2><?php echo esc_html($prices_title); ?></h2>
            <p><?php echo esc_html($prices_subtitle); ?></p>
        </div>

        <div class="plan-price-grid">
            <?php for ($screens = 1; $screens <= 4; $screens++) :
                $saving     = iptv_plan_savings($plan_months, $screens);
                $is_popular = ($screens === $popular);

                // Shown only where the saving is real. On a page where some
                // card has one, the others still reserve the space so all four
                // buttons stay on the same line.
                $show_saving = ($grid_has_savings && $saving['off'] > 0.005);

                if ($saving['now'] <= 0) {
                    continue;
                }
                ?>
                <div class="plan-price-card<?php echo $is_popular ? ' plan-price-card--popular' : ''; ?>">

                    <?php if ($is_popular) : ?>
                        <span class="plan-price-flag"><?php echo esc_html(iptv_text('popular_badge', 'POPULAR')); ?></span>
                    <?php endif; ?>

                    <h3 class="plan-price-screens"><?php echo esc_html(iptv_plan_screens_label($screens)); ?></h3>

                    <p class="plan-price-note">
                        <?php
                        // Two registered strings rather than _n(): Polylang's
                        // string translation has no plural forms, and the
                        // singular here is only ever "one", so a count-based
                        // rule buys nothing.
                        echo esc_html($screens === 1
                            ? plan_str('One device watching at a time')
                            : sprintf(
                                /* translators: %d = number of screens */
                                plan_str('%d devices watching at the same time'),
                                $screens
                            ));
                        ?>
                    </p>

                    <p class="plan-price-amount">
                        <?php echo esc_html(iptv_plan_format_price($saving['now'])); ?>
                    </p>

                    <p class="plan-price-per">
                        <?php
                        if ($plan_months === 1) {
                            echo esc_html(iptv_text('per_month', 'per month'));
                        } else {
                            printf(
                                /* translators: %s = formatted price, e.g. "$6.83" */
                                esc_html(plan_str('%s / month')),
                                esc_html(iptv_plan_format_price($saving['per_month']))
                            );
                        }
                        ?>
                    </p>

                    <?php if ($show_saving) : ?>
                        <p class="plan-price-saving">
                            <span class="plan-price-was"><?php echo esc_html(iptv_plan_format_price($saving['was'])); ?></span>
                            <span class="plan-price-off">
                                <?php echo esc_html(sprintf(
                                    /* translators: %d = percentage saved */
                                    plan_str('Save %d%%'),
                                    $saving['pct']
                                )); ?>
                            </span>
                        </p>
                    <?php elseif ($grid_has_savings) : ?>
                        <p class="plan-price-saving" aria-hidden="true"></p>
                    <?php endif; ?>

                    <a href="<?php echo esc_url(iptv_plan_checkout_url($screens, $plan_months)); ?>"
                        class="dv2-btn dv2-btn-primary plan-price-btn">
                        <?php echo esc_html($buy_text); ?>
                    </a>

                </div>
            <?php endfor; ?>
        </div>

        <ul class="dv2-checkout-trust plan-trust">
            <li><?php echo esc_html(iptv_text('checkout_trust_1', '14-day money-back')); ?></li>
            <li><?php echo esc_html(iptv_text('checkout_trust_2', 'Instant activation')); ?></li>
            <li><?php echo esc_html(iptv_text('checkout_trust_3', 'No auto-renew')); ?></li>
        </ul>

        <p class="dv2-guarantee">
            <?php echo esc_html(iptv_text('guarantee_text', 'Watching in 60 seconds · Secure checkout · Pay once, no auto-renew')); ?>
        </p>

        <div class="dv2-payments">
            <span class="dv2-payments-label">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    aria-hidden="true">
                    <rect x="4" y="11" width="16" height="10" rx="2"></rect>
                    <path d="M8 11V7a4 4 0 0 1 8 0v4"></path>
                </svg>
                <?php echo esc_html(iptv_text('payments_label', 'Secure checkout')); ?>
            </span>
            <ul class="dv2-payment-badges">
                <li class="dv2-payment-badge dv2-payment-badge--visa">VISA</li>
                <li class="dv2-payment-badge dv2-payment-badge--mc" aria-label="Mastercard">
                    <span class="dv2-mc-mark" aria-hidden="true"><i></i><i></i></span>
                    <span>Mastercard</span>
                </li>
                <li class="dv2-payment-badge dv2-payment-badge--amex">AMEX</li>
                <li class="dv2-payment-badge dv2-payment-badge--paypal">PayPal</li>
                <li class="dv2-payment-badge dv2-payment-badge--btc">₿ Bitcoin</li>
            </ul>
        </div>

    </div>
</section>
