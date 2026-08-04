<?php
/**
 * Section: Pricing / checkout configurator (Design v2)
 *
 * Structure follows the annotated spec: a single centred panel with a screen
 * picker (radiogroup), a duration picker, the checkout CTA and payment badges.
 * The WooCommerce/currency wiring underneath is unchanged - pricing.js still
 * drives everything off #devices [data-devices] and #durations [data-duration].
 */

// Read the stored price table, never recompute it on a visitor's request. It is
// rebuilt by the weekly rate fetch (inc/currency-rates-api.php) and whenever a
// rate or product price is saved in wp-admin.
$all_prices     = IPTV_Currency_Settings::get_price_table();
$default_device = '1_device';

// Which screen count is pre-selected. Clamped to the 1-4 range we sell.
$default_screens = (int) iptv_config('pricing_default_screens', 1);
if ($default_screens < 1 || $default_screens > 4) {
    $default_screens = 1;
}

// Which duration is pre-selected. The panel expects a duration on load, so the
// default landing URL is ?connections=1&duration=12.
$default_months = (int) iptv_config('pricing_default_months', 12);
if (!in_array($default_months, array(1, 3, 6, 12), true)) {
    $default_months = 12;
}

// Panel checkout endpoints. The panel derives the price from the two params.
$checkout_base = iptv_config('checkout_base_url', 'https://panel.nordictv.io/checkout');
$trial_url     = iptv_config('trial_url', 'https://panel.nordictv.io/checkout/trial');

// The single currency this site prices in. Everything below is the pre-JS
// paint; pricing.js repaints from window.iptvPrices in the same currency.
$iptv_currency = iptv_site_currency();

/**
 * Savings badge percentages are derived from the real prices rather than
 * hard-coded, so the claim stays true if prices change. pricing.js recomputes
 * them whenever the screen count changes.
 */
$base_monthly = isset($all_prices['1_month'][$default_device][$iptv_currency])
    ? (float) $all_prices['1_month'][$default_device][$iptv_currency]
    : 0;

$iptv_savings_pct = function ($duration_key, $months) use ($all_prices, $default_device, $base_monthly, $iptv_currency) {
    if (!$base_monthly || empty($all_prices[$duration_key][$default_device][$iptv_currency])) {
        return 0;
    }
    $per_month = (float) $all_prices[$duration_key][$default_device][$iptv_currency] / $months;
    return max(0, (int) round((1 - ($per_month / $base_monthly)) * 100));
};

$durations = array(
    1  => array('key' => '1_month',   'label' => iptv_text('month_1_label', '1 Month'),   'save' => 0),
    3  => array('key' => '3_months',  'label' => iptv_text('month_3_label', '3 Months'),  'save' => $iptv_savings_pct('3_months', 3)),
    6  => array('key' => '6_months',  'label' => iptv_text('month_6_label', '6 Months'),  'save' => $iptv_savings_pct('6_months', 6)),
    12 => array('key' => '12_months', 'label' => iptv_text('month_12_label', '12 Months'), 'save' => $iptv_savings_pct('12_months', 12)),
);

// Screen count that carries the "POPULAR" flag in the sticky picker.
$popular_screens = (int) iptv_config('pricing_popular_screens', 2);

// Which plan card is highlighted. Defaults to the pre-selected duration, so the
// flagged card and the landing default are the same plan rather than two
// different recommendations on one screen.
$popular_months = (int) iptv_config('pricing_popular_months', $default_months);
if (!in_array($popular_months, array(1, 3, 6, 12), true)) {
    $popular_months = $default_months;
}

// Variation ID map for checkout URLs. WooCommerce may be inactive (e.g. fresh
// install): degrade to an empty map.
$variation_map = array();
$duration_skus = array(1 => '1_month', 3 => '3_months', 6 => '6_months', 12 => '12_months');

if (function_exists('wc_get_product_id_by_sku') && function_exists('wc_get_product')) {
    foreach ($duration_skus as $months => $sku) {
        $product_id = wc_get_product_id_by_sku($sku);
        if (!$product_id) {
            continue;
        }
        $product = wc_get_product($product_id);
        if (!$product || !$product->is_type('variable')) {
            continue;
        }
        foreach ($product->get_children() as $child_id) {
            $variation = wc_get_product($child_id);
            if (!$variation) {
                continue;
            }
            $attributes = $variation->get_attributes();
            // Try global attribute (pa_devices) first, then local (devices)
            $device_attr = isset($attributes['pa_devices']) ? $attributes['pa_devices'] : '';
            if (!$device_attr) {
                $device_attr = isset($attributes['devices']) ? $attributes['devices'] : '';
            }
            if ($device_attr) {
                $variation_map[$device_attr . '-' . $months] = $child_id; // e.g. "1-12", "2-3"
            }
        }
    }
}

$screen_singular = iptv_text('screen_singular', 'Screen');
$screen_plural   = iptv_text('screen_plural', 'Screens');
?>
<script>
    // Main site URL for cross-site cart (used by pricing.js)
    window.iptvMainSiteUrl = '<?php echo esc_js(defined("IPTV_MAIN_SITE_URL") ? IPTV_MAIN_SITE_URL : network_site_url("/")); ?>';
    window.iptvPrices = <?php echo json_encode($all_prices); ?>;
    // PHP owns the site currency — see iptv_site_currency(). The JS reads it
    // rather than carrying a second default that could drift.
    window.SITE_CURRENCY = '<?php echo esc_js($iptv_currency); ?>';
    window.iptvVariationIds = <?php echo json_encode($variation_map); ?>;
    window.iptvDefaultScreens = <?php echo (int) $default_screens; ?>;
    window.iptvDefaultMonths = <?php echo (int) $default_months; ?>;
    window.iptvCheckoutBase = '<?php echo esc_js($checkout_base); ?>';
</script>

<section id="pricing" class="pricing dv2-section">
    <div class="container">
        <div class="dv2-section-head pricing-header">
            <h2><?php echo esc_html(iptv_text('pricing_title', 'Choose your plan that fits you')); ?></h2>
            <p>
                <?php echo esc_html(iptv_text('pricing_subtitle', 'Unlock unbeatable value and embrace remarkable savings with the best-priced IPTV subscription available today! Stream smart and watch your savings soar!')); ?>
            </p>
        </div>

        <div class="configurator">
            <?php
            // Days the discount is held for a returning visitor. pricing.js
            // stores the deadline locally so the timer does not reset on
            // every page view.
            $offer_days = max(1, (int) iptv_config('offer_lock_days', 5));

            // Shared feature list. Every plan includes the same service — only
            // the length and the screen count differ — so one list is printed
            // into each card rather than inventing per-tier differences.
            //
            // This absorbed the separate "Every plan is fully loaded" panel that
            // used to sit below the configurator: the same claims, stated once
            // inside the card the visitor is actually reading. The plan pages
            // keep their own copy of that panel (plan/sections/plan-includes.php).
            $card_features = array(
                1 => iptv_text('card_feature_1', '40.000+ Live-Sender'),
                2 => iptv_text('card_feature_2', '200.000+ Filme & Serien'),
                3 => iptv_text('card_feature_3', '4K, Ultra HD & HD'),
                4 => iptv_text('card_feature_4', 'Bundesliga, Champions League & alle Sportarten'),
                5 => iptv_text('card_feature_5', 'Alle PPV-Events inklusive'),
                6 => iptv_text('card_feature_6', 'Programmzeitschrift (EPG)'),
                7 => iptv_text('card_feature_7', 'Stabile Server & Anti-Buffer™'),
                8 => iptv_text('card_feature_8', 'Sofort aktiviert, Support 24/7'),
            );
            ?>

            <!-- Screen picker. Sticks to the top of the viewport while the cards
                 scroll past: on a phone the cards are one per row, so without
                 this the visitor scrolls the picker off screen and can no longer
                 see — or change — how many screens the prices refer to. -->
            <div class="dv2-screen-sticky" id="screen-sticky">
                <div class="dv2-screen-sticky-inner">
                    <span class="dv2-screen-sticky-label">
                        <?php echo esc_html(iptv_text('screens_title', 'Wie viele Bildschirme?')); ?>
                    </span>
                    <div class="dv2-screen-row" id="devices" role="radiogroup"
                        aria-label="<?php echo esc_attr(iptv_text('screens_title', 'Wie viele Bildschirme?')); ?>">
                        <?php for ($i = 1; $i <= 4; $i++) :
                            $is_default = ($i === $default_screens);
                            // Number and word are separate elements so the word can be
                            // dropped on a narrow phone and all four pills still fit on
                            // one line — see .dv2-screen-word in pricing.css.
                            $word = ($i > 1 ? $screen_plural : $screen_singular);
                            ?>
                            <button type="button"
                                class="select-card dv2-screen-pill<?php echo $is_default ? ' active' : ''; ?>"
                                data-devices="<?php echo $i; ?>"
                                role="radio"
                                aria-checked="<?php echo $is_default ? 'true' : 'false'; ?>"
                                aria-pressed="<?php echo $is_default ? 'true' : 'false'; ?>"
                                tabindex="<?php echo $is_default ? '0' : '-1'; ?>">
                                <span class="dv2-screen-label">
                                    <span class="dv2-screen-num"><?php echo (int) $i; ?></span>
                                    <span class="dv2-screen-word"><?php echo esc_html($word); ?></span>
                                </span>
                                <?php if ($i === $popular_screens) : ?>
                                    <span class="dv2-pill-badge"><?php echo esc_html(iptv_text('popular_badge', 'BELIEBT')); ?></span>
                                <?php endif; ?>
                            </button>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>

            <!-- One card per length. Prices, per-month lines, savings badges and
                 the four checkout links are all rewritten by pricing.js whenever
                 the screen count above changes. -->
            <div class="dv2-plan-grid" id="durations">
                <?php foreach ($durations as $months => $d) :
                    $price = isset($all_prices[$d['key']][$default_device][$iptv_currency])
                        ? (float) $all_prices[$d['key']][$default_device][$iptv_currency]
                        : 0;
                    $slug       = $months . 'mo';
                    $is_popular = ($months === $popular_months);
                    ?>
                    <article class="dv2-plan-card<?php echo $is_popular ? ' is-popular' : ''; ?>"
                        data-duration="<?php echo $months; ?>" data-months="<?php echo $months; ?>">

                        <header class="dv2-plan-head">
                            <h3 class="dv2-plan-name"><?php echo esc_html($d['label']); ?></h3>
                            <?php if ($is_popular) : ?>
                                <span class="dv2-plan-flag"><?php echo esc_html(iptv_text('best_value_text', 'Bestes Angebot')); ?></span>
                            <?php endif; ?>
                        </header>

                        <div class="dv2-plan-price">
                            <span class="dv2-plan-amount price-display" id="price-<?php echo esc_attr($slug); ?>">
                                <?php echo esc_html(iptv_price($price)); ?>
                            </span>
                            <?php // The 1-month price is already a monthly price, so "pro Monat"
                                  // under it said the same thing twice. Only the multi-month cards
                                  // carry the per-month equivalent. ?>
                            <?php if ($months > 1) : ?>
                                <span class="dv2-plan-per" id="per-<?php echo esc_attr($slug); ?>">
                                    <?php echo '~' . esc_html(iptv_price($price / $months)) . '/'
                                        . esc_html(iptv_text('per_month_short', 'Mon.')); ?>
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php // Percentage saved against paying month by month at the 1-month
                              // rate, recomputed by pricing.js for the chosen screen count. ?>
                        <span class="badge badge-green dv2-plan-save<?php echo $d['save'] ? '' : ' is-hidden'; ?>"
                            id="save-<?php echo esc_attr($slug); ?>">
                            <?php echo esc_html(sprintf(iptv_text('save_percent_format', '%d %% sparen'), $d['save'])); ?>
                        </span>

                        <ul class="dv2-plan-features">
                            <?php foreach ($card_features as $feature) : ?>
                                <li><?php echo esc_html($feature); ?></li>
                            <?php endforeach; ?>
                        </ul>

                        <a class="dv2-plan-cta" id="cta-<?php echo esc_attr($slug); ?>"
                            href="<?php echo esc_url($checkout_base . '?connections=' . $default_screens . '&duration=' . $months); ?>">
                            <?php echo esc_html(iptv_text('checkout_button', 'Jetzt kaufen')); ?>
                        </a>

                        <?php // Payment marks per card rather than once per section: they are
                              // reassurance at the moment of clicking, not a section footnote. ?>
                        <ul class="dv2-plan-payments" aria-label="<?php echo esc_attr(iptv_text('payments_label', 'Sichere Bezahlung')); ?>">
                            <li class="dv2-payment-badge dv2-payment-badge--visa">VISA</li>
                            <li class="dv2-payment-badge dv2-payment-badge--mc" aria-label="Mastercard">
                                <span class="dv2-mc-mark" aria-hidden="true"><i></i><i></i></span>
                            </li>
                            <li class="dv2-payment-badge dv2-payment-badge--amex">AMEX</li>
                            <li class="dv2-payment-badge dv2-payment-badge--paypal">PayPal</li>
                        </ul>
                    </article>
                <?php endforeach; ?>
            </div>

            <?php // The scarcity line moved up into the sticky picker. ?>

            <ul class="dv2-checkout-trust">
                <li><?php echo esc_html(iptv_text('checkout_trust_1', '14-day money-back')); ?></li>
                <li><?php echo esc_html(iptv_text('checkout_trust_2', 'Instant activation')); ?></li>
                <li><?php echo esc_html(iptv_text('checkout_trust_3', 'No auto-renew')); ?></li>
            </ul>

            <p class="dv2-guarantee">
                <?php echo esc_html(iptv_text('guarantee_text', 'Watching in 60 seconds · Secure checkout · Pay once, no auto-renew')); ?>
            </p>

            <?php // The payment marks moved into each card, next to its CTA. ?>

            <?php // The "Every plan is fully loaded" panel used to sit here. Its
                  // claims now live in each card's feature list — see
                  // $card_features above — so the visitor reads them inside the
                  // thing they are about to buy rather than in a footnote below
                  // four cards. The plan pages still render their own copy from
                  // plan/sections/plan-includes.php, which keeps the same
                  // plan_includes_* keys. ?>

            <!-- Trial -->
            <div class="dv2-trial">
                <span class="dv2-trial-copy"><?php echo esc_html(iptv_text('trial_prompt', 'Not ready to buy?')); ?></span>
                <a href="<?php echo esc_url($trial_url); ?>" class="dv2-btn dv2-trial-btn">
                    <?php echo esc_html(iptv_text('trial_cta', 'Start a 24-hour trial — no card')); ?>
                </a>
            </div>
        </div>
    </div>
</section>
