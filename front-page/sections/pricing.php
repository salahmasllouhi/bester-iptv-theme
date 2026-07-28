<?php
/**
 * Section: Pricing / checkout configurator (Design v2)
 *
 * Structure follows the annotated spec: a single centred panel with a screen
 * picker (radiogroup), a duration picker, the checkout CTA and payment badges.
 * The WooCommerce/currency wiring underneath is unchanged - pricing.js still
 * drives everything off #devices [data-devices] and #durations [data-duration].
 */

$all_prices     = IPTV_Currency_Settings::calculate_all_prices();
$default_device = '1_device';

// Which screen count is pre-selected. Clamped to the 1-4 range we sell.
$default_screens = (int) iptv_text('pricing_default_screens', '1');
if ($default_screens < 1 || $default_screens > 4) {
    $default_screens = 1;
}

/**
 * Savings badge percentages are derived from the real prices rather than
 * hard-coded, so the claim stays true if prices change. pricing.js recomputes
 * them whenever the screen count changes.
 */
$base_monthly = isset($all_prices['1_month'][$default_device]['usd'])
    ? (float) $all_prices['1_month'][$default_device]['usd']
    : 0;

$iptv_savings_pct = function ($duration_key, $months) use ($all_prices, $default_device, $base_monthly) {
    if (!$base_monthly || empty($all_prices[$duration_key][$default_device]['usd'])) {
        return 0;
    }
    $per_month = (float) $all_prices[$duration_key][$default_device]['usd'] / $months;
    return max(0, (int) round((1 - ($per_month / $base_monthly)) * 100));
};

$durations = array(
    1  => array('key' => '1_month',   'label' => iptv_text('month_1_label', '1 Month'),   'save' => 0),
    3  => array('key' => '3_months',  'label' => iptv_text('month_3_label', '3 Months'),  'save' => $iptv_savings_pct('3_months', 3)),
    6  => array('key' => '6_months',  'label' => iptv_text('month_6_label', '6 Months'),  'save' => $iptv_savings_pct('6_months', 6)),
    12 => array('key' => '12_months', 'label' => iptv_text('month_12_label', '12 Months'), 'save' => $iptv_savings_pct('12_months', 12)),
);

// Screen count that carries the "POPULAR" flag.
$popular_screens = (int) iptv_text('pricing_popular_screens', '2');

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
    window.iptvVariationIds = <?php echo json_encode($variation_map); ?>;
    window.iptvDefaultScreens = <?php echo (int) $default_screens; ?>;
</script>

<section id="pricing" class="pricing dv2-section">
    <div class="container">
        <div class="dv2-section-head pricing-header">
            <h2><?php echo esc_html(iptv_text('pricing_title', 'Choose your plan that fits you')); ?></h2>
            <p>
                <?php echo esc_html(iptv_text('pricing_subtitle', 'Unlock unbeatable value and embrace remarkable savings with the best-priced IPTV subscription available today! Stream smart and watch your savings soar!')); ?>
            </p>
        </div>

        <div class="dv2-pricing-perks">
            <span><?php echo esc_html(iptv_text('pricing_perk_1', '35,000+ live channels')); ?></span>
            <span><?php echo esc_html(iptv_text('pricing_perk_2', 'All sports & PPV included')); ?></span>
            <span><?php echo esc_html(iptv_text('pricing_perk_3', 'Anti-freeze, stable servers')); ?></span>
        </div>

        <div class="configurator">
            <!-- Step 1: screens -->
            <div class="config-section">
                <div class="config-step">
                    <span class="config-step-num">1</span>
                    <span class="config-title"><?php echo esc_html(iptv_text('screens_title', 'How many screens?')); ?></span>
                </div>
                <div class="dv2-screen-row" id="devices" role="radiogroup"
                    aria-label="<?php echo esc_attr(iptv_text('screens_title', 'How many screens?')); ?>">
                    <?php for ($i = 1; $i <= 4; $i++) :
                        $is_default = ($i === $default_screens);
                        $label = $i . ' ' . ($i > 1 ? $screen_plural : $screen_singular);
                        ?>
                        <button type="button"
                            class="select-card dv2-screen-pill<?php echo $is_default ? ' active' : ''; ?>"
                            data-devices="<?php echo $i; ?>"
                            role="radio"
                            aria-checked="<?php echo $is_default ? 'true' : 'false'; ?>"
                            aria-pressed="<?php echo $is_default ? 'true' : 'false'; ?>"
                            tabindex="<?php echo $is_default ? '0' : '-1'; ?>">
                            <span class="dv2-screen-label"><?php echo esc_html($label); ?></span>
                            <?php if ($i === $popular_screens) : ?>
                                <span class="dv2-pill-badge"><?php echo esc_html(iptv_text('popular_badge', 'POPULAR')); ?></span>
                            <?php endif; ?>
                        </button>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Step 2: duration -->
            <div class="config-section">
                <div class="config-step">
                    <span class="config-step-num">2</span>
                    <span class="config-title"><?php echo esc_html(iptv_text('duration_title', 'How long?')); ?></span>
                </div>
                <div class="dv2-duration-row" id="durations" role="radiogroup"
                    aria-label="<?php echo esc_attr(iptv_text('duration_title', 'How long?')); ?>">
                    <?php foreach ($durations as $months => $d) :
                        $price   = isset($all_prices[$d['key']][$default_device]['usd']) ? $all_prices[$d['key']][$default_device]['usd'] : 0;
                        $suffix  = $months === 1 ? 'mo' : 'mo';
                        $slug    = $months . $suffix; // price-1mo, price-3mo, ...
                        ?>
                        <button type="button"
                            class="select-card duration-card dv2-duration-card"
                            data-duration="<?php echo $months; ?>" data-months="<?php echo $months; ?>"
                            role="radio" aria-checked="false" tabindex="-1">
                            <span class="badge badge-green<?php echo $d['save'] ? '' : ' is-hidden'; ?>"
                                id="save-<?php echo esc_attr($slug); ?>">
                                <?php echo esc_html(sprintf(iptv_text('save_percent_format', 'Save %d%%'), $d['save'])); ?>
                            </span>
                            <span class="duration-header"><?php echo esc_html($d['label']); ?></span>
                            <span class="duration-price price-display" id="price-<?php echo esc_attr($slug); ?>">
                                $<?php echo esc_html($price); ?>
                            </span>
                            <span class="duration-per" id="per-<?php echo esc_attr($slug); ?>">
                                <?php echo $months === 1
                                    ? esc_html(iptv_text('per_month', 'per month'))
                                    : '~$' . esc_html(round($price / $months, 2)) . '/mo'; ?>
                            </span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Checkout -->
            <a href="#" id="checkout-btn" class="cta-btn">
                <span id="button-text"><?php echo esc_html(iptv_text('checkout_button', 'Complete Your Order')); ?></span>
                <span aria-hidden="true" style="margin-left:0.5rem;">→</span>
            </a>

            <p class="dv2-guarantee">
                <?php echo esc_html(iptv_text('guarantee_text', '14-day money-back guarantee. No questions asked.')); ?>
            </p>

            <!-- Payment methods -->
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
    </div>
</section>
