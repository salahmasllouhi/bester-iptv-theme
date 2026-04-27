<?php
/**
 * Offer Pricing Section
 *
 * Shows:
 *   - Price you pay (paid months, from WooCommerce product)
 *   - Strikethrough value (what total months would cost at 1-month rate)
 *   - "You save X" badge
 *   - Single CTA button
 *
 * Variables expected from template-offer-landing.php:
 *   $offer_paid_months    (int)
 *   $offer_free_months    (int)
 *   $offer_highlight_text (string) – savings badge text
 *   $offer_cta_text       (string)
 *   $offer_checkout_url   (string)
 *   $offer_product_id     (int|null)
 *   $offer_all_prices     (array)   – from IPTV_Currency_Settings
 *   $offer_currency       (string)
 */

$paid = $offer_paid_months ?? 12;
$free = $offer_free_months ?? 3;
$total = $paid + $free;

// Try to get WooCommerce product price for the paid months plan
// We look for a product whose SKU matches "{$paid}_months" (e.g. "12_months")
$actual_price_raw = null;
$regular_price_raw = null;

if (!empty($offer_product_id) && class_exists('WooCommerce')) {
    $product = wc_get_product($offer_product_id);
    if ($product) {
        if ($product->is_type('variable')) {
            // Use the lowest variation price as the display price
            $actual_price_raw = (float) $product->get_variation_sale_price('min');
            if (!$actual_price_raw) {
                $actual_price_raw = (float) $product->get_variation_regular_price('min');
            }
        } else {
            $actual_price_raw = (float) $product->get_sale_price() ?: (float) $product->get_regular_price();
        }
    }
}

// Derive a "full value" (strikethrough) using iptvPrices if available: 1-month rate × total months
// This will be recalculated live in JS for currency switching; PHP provides an SSR fallback.
$one_month_usd = 16.99; // fallback default (1 device, 1 month)
if (!empty($offer_all_prices['1_month']['1_device']['usd'])) {
    $one_month_usd = (float) $offer_all_prices['1_month']['1_device']['usd'];
}
$full_value_usd = $one_month_usd * $total;
?>

<section class="offer-pricing" id="pricing">
    <div class="offer-section-inner offer-pricing__inner">

        <div class="offer-section-header">
            <div class="offer-section-tag"><?php echo esc_html(iptv_text('offer_pricing_tag', 'Pricing')); ?></div>
            <h2 class="offer-section-title">
                <?php printf(
                    /* translators: %1$d = paid months, %2$d = total months */
                    esc_html(iptv_text('offer_pricing_title', 'Pay for %1$d months, get %2$d months')),
                    (int) $paid,
                    (int) $total
                ); ?>
            </h2>
        </div>

        <div class="offer-pricing__card">
            <!-- Savings Badge -->
            <div class="offer-pricing__badge">
                <?php echo esc_html($offer_highlight_text); ?>
            </div>

            <!-- Price Display -->
            <div class="offer-pricing__prices">
                <div class="offer-pricing__actual">
                    <?php if ($actual_price_raw): ?>
                        <span id="offer-price-actual" class="offer-pricing__amount"><?php
                        // Format using WooCommerce's currency (SSR only; JS overrides on currency switch)
                        echo wp_kses_post(wc_price($actual_price_raw));
                        ?></span>
                    <?php else: ?>
                        <span id="offer-price-actual" class="offer-pricing__amount offer-pricing__amount--js">
                            <!-- JS will populate this -->
                        </span>
                    <?php endif; ?>
                    <span class="offer-pricing__duration">
                        <?php printf(
                            /* translators: %d = paid months */
                            esc_html(iptv_text('offer_pricing_for', 'for %d months')),
                            (int) $paid
                        ); ?>
                    </span>
                </div>

                <div class="offer-pricing__full-value">
                    <span class="offer-pricing__full-label">
                        <?php echo esc_html(iptv_text('offer_pricing_value_label', 'Regular value')); ?>
                    </span>
                    <span id="offer-price-full" class="offer-pricing__strikethrough">
                        <?php printf('$%.2f', $full_value_usd); ?>
                    </span>
                </div>
            </div>

            <!-- CTA -->
            <a href="<?php echo esc_url($offer_checkout_url); ?>"
                class="btn btn-primary offer-cta-btn offer-pricing__cta">
                <?php echo esc_html($offer_cta_text); ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>

            <p class="offer-pricing__guarantee">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <?php echo esc_html(iptv_text('offer_pricing_guarantee', 'Secure payment · Instant delivery · No contract')); ?>
            </p>
        </div>

    </div>
</section>

<style>
    .offer-pricing {
        background: var(--bg-page);
        padding: 5rem 1.5rem;
    }

    .offer-pricing__inner {
        max-width: 560px;
    }

    .offer-pricing__card {
        background: var(--bg-card);
        border: 1px solid var(--border-medium);
        border-radius: var(--radius-xl);
        padding: 2.5rem 2rem;
        box-shadow: var(--shadow-lg);
        text-align: center;
        margin-top: 2.5rem;
        position: relative;
        overflow: hidden;
    }

    .offer-pricing__card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--color-teal), var(--color-indigo-hover));
    }

    .offer-pricing__badge {
        display: inline-block;
        background: linear-gradient(135deg, var(--color-gold), #FF8F3F);
        color: #fff;
        font-size: 0.875rem;
        font-weight: 700;
        padding: 0.45rem 1.25rem;
        border-radius: var(--radius-full);
        margin-bottom: 1.75rem;
        letter-spacing: 0.02em;
        box-shadow: 0 4px 12px rgba(255, 184, 48, 0.35);
    }

    .offer-pricing__prices {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 2rem;
    }

    .offer-pricing__actual {
        display: flex;
        align-items: baseline;
        gap: 0.75rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    .offer-pricing__amount {
        font-size: 3rem;
        font-weight: 800;
        color: var(--text-primary);
        line-height: 1;
    }

    .offer-pricing__amount--js {
        min-height: 3rem;
        /* prevent layout shift before JS runs */
    }

    /* Override WooCommerce price styling inside our card */
    .offer-pricing__amount .woocommerce-Price-amount {
        font-size: inherit;
        font-weight: inherit;
        color: inherit;
    }

    .offer-pricing__duration {
        font-size: 1rem;
        color: var(--text-secondary);
        font-weight: 500;
    }

    .offer-pricing__full-value {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.9rem;
        color: var(--text-muted);
    }

    .offer-pricing__full-label {
        font-weight: 500;
    }

    .offer-pricing__strikethrough {
        text-decoration: line-through;
        color: var(--color-coral);
        font-weight: 600;
    }

    .offer-pricing__cta {
        width: 100%;
        justify-content: center;
        font-size: 1.05rem;
        padding: 1rem 2rem;
        box-shadow: var(--shadow-glow);
    }

    .offer-pricing__guarantee {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.4rem;
        margin: 1rem 0 0;
        font-size: 0.8rem;
        color: var(--text-muted);
    }

    .offer-pricing__guarantee svg {
        flex-shrink: 0;
        color: var(--color-teal);
    }

    @media (max-width: 480px) {
        .offer-pricing {
            padding: 4rem 1rem;
        }

        .offer-pricing__card {
            padding: 2rem 1.25rem;
        }

        .offer-pricing__amount {
            font-size: 2.25rem;
        }
    }
</style>