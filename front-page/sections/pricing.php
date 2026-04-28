<!-- Pricing Section -->
<script>
    // Main site URL for cross-site cart (used by pricing.js)
    window.iptvMainSiteUrl = '<?php echo esc_js(defined("IPTV_MAIN_SITE_URL") ? IPTV_MAIN_SITE_URL : network_site_url("/")); ?>';
</script>
<section id="pricing" class="pricing">
    <div class="container">
        <div style="text-align:center;margin-bottom:1rem;">
            <span class="section-tag">
                <?php echo esc_html(iptv_text('pricing_badge', 'Stream Smarter, Pay Less – Start Today!')); ?>
            </span>
        </div>
        <div class="pricing-header">
            <h2 class="mobile-split-title"><?php echo esc_html(iptv_text('pricing_title', 'Unlimited Streaming')); ?>
                <span class="gradient-text"><?php echo esc_html(iptv_text('pricing_title_span', 'at a fair price')); ?></span>
            </h2>
            <p style="color:var(--text-secondary);margin-top:0.5rem;">
                <?php echo esc_html(iptv_text('pricing_subtitle', '35,000+ live channels and 150,000+ movies & series in 4K.')); ?>
            </p>
        </div>

        <style>
            @media (max-width: 768px) {
                .mobile-split-title span {
                    display: block;
                    margin-top: 0.2rem;
                }

                /* Mobile Steps Simplification */
                .steps-container {
                    gap: 0.5rem !important;
                }

                .step-label {
                    display: none;
                    /* Hide labels on mobile to reduce crowding */
                }

                .step-separator {
                    width: 1rem !important;
                }
            }
        </style>

        <!-- Step Indicators -->
        <div class="steps-container"
            style="display:flex;align-items:center;justify-content:center;gap:1rem;margin-bottom:2rem;flex-wrap:wrap;">
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <span
                    style="width:1.75rem;height:1.75rem;background:var(--color-teal);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;">1</span>
                <span class="step-label"
                    style="font-weight:600;font-size:0.875rem;"><?php echo esc_html(iptv_text('step_1_label', 'Select Devices')); ?></span>
            </div>
            <div class="step-separator" style="width:3rem;height:2px;background:var(--border);"></div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <span
                    style="width:1.75rem;height:1.75rem;background:var(--border);color:var(--text-muted);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;"
                    id="step2">2</span>
                <span class="step-label" style="font-weight:600;font-size:0.875rem;color:var(--text-muted);"
                    id="step2-label"><?php echo esc_html(iptv_text('step_2_label', 'Choose Plan')); ?></span>
            </div>
            <div class="step-separator" style="width:3rem;height:2px;background:var(--border);"></div>
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <span
                    style="width:1.75rem;height:1.75rem;background:var(--border);color:var(--text-muted);border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.75rem;"
                    id="step3">3</span>
                <span class="step-label" style="font-weight:600;font-size:0.875rem;color:var(--text-muted);"
                    id="step3-label"><?php echo esc_html(iptv_text('step_3_label', 'Complete Order')); ?></span>
            </div>
        </div>

        <div class="configurator">
            <!-- Device Selection -->
            <div class="config-section"
                style="background:var(--bg);border-radius:0.75rem;padding:1.25rem;margin-bottom:1.25rem;">
                <div class="config-title" style="margin-bottom:1rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="width:1.25rem;height:1.25rem;color:var(--color-teal);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <?php echo esc_html(iptv_text('devices_title', 'How many devices will you use?')); ?>
                </div>
                <div class="card-grid" id="devices">
                    <?php
                    $dev_sing = iptv_text('device_singular', 'Device');
                    $dev_plur = iptv_text('device_plural', 'Devices');
                    for ($i = 1; $i <= 4; $i++):
                        ?>
                        <div class="select-card" data-devices="<?php echo $i; ?>">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                style="width:1.5rem;height:1.5rem;margin-bottom:0.5rem;color:var(--text-muted);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            <div class="num"><?php echo $i; ?>
                                <?php echo $i > 1 ? esc_html($dev_plur) : esc_html($dev_sing); ?>
                            </div>
                        </div>
                    <?php endfor; ?>
                </div>
            </div>

            <!-- Duration Selection -->
            <div class="config-section"
                style="background:var(--bg);border-radius:0.75rem;padding:1.25rem;margin-bottom:1.25rem;">
                <div class="config-title" style="margin-bottom:1rem;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        style="width:1.25rem;height:1.25rem;color:var(--color-teal);">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <?php echo esc_html(iptv_text('duration_title', 'Select your plan duration')); ?>
                </div>
                <div class="card-grid" id="durations">
                    <?php
                    $all_prices = IPTV_Currency_Settings::calculate_all_prices();
                    $default_device = '1_device';
                    $save_more_text = iptv_text('save_more', 'Save more');
                    $per_month_text = iptv_text('per_month', 'per month');
                    $best_deal_text = iptv_text('best_deal', 'Best deal!');
                    ?>
                    <script>window.iptvPrices = <?php echo json_encode($all_prices); ?>;</script>
                    <?php
                    // Generate variation ID map for checkout URLs
                    $variation_map = array();
                    $duration_skus = array(
                        1 => '1_month',
                        3 => '3_months',
                        6 => '6_months',
                        12 => '12_months'
                    );

                    foreach ($duration_skus as $months => $sku) {
                        $product_id = wc_get_product_id_by_sku($sku);
                        if ($product_id) {
                            $product = wc_get_product($product_id);
                            if ($product && $product->is_type('variable')) {
                                $children = $product->get_children();
                                foreach ($children as $child_id) {
                                    $variation = wc_get_product($child_id);
                                    if ($variation) {
                                        $attributes = $variation->get_attributes();
                                        // Try global attribute (pa_devices) first, then local (devices)
                                        $device_attr = isset($attributes['pa_devices']) ? $attributes['pa_devices'] : '';
                                        if (!$device_attr) {
                                            $device_attr = isset($attributes['devices']) ? $attributes['devices'] : '';
                                        }
                                        if ($device_attr) {
                                            $key = $device_attr . '-' . $months; // e.g., "1-12", "2-3"
                                            $variation_map[$key] = $child_id;
                                        }
                                    }
                                }
                            }
                        }
                    }
                    ?>
                    <script>window.iptvVariationIds = <?php echo json_encode($variation_map); ?>;</script>


                    <div class="select-card duration-card" data-duration="1" data-months="1">
                        <div class="duration-header"><?php echo esc_html(iptv_text('month_1_label', '1 Month')); ?>
                        </div>
                        <div class="duration-price price-display" id="price-1mo">$
                            <?php echo esc_html($all_prices['1_month'][$default_device]['usd']); ?>
                        </div>
                        <div class="duration-per" id="per-1mo"><?php echo esc_html($per_month_text); ?></div>
                    </div>
                    <div class="select-card duration-card" data-duration="3" data-months="3">
                        <span
                            class="badge badge-green"><?php echo esc_html(iptv_text('save_40_text', 'Save 40%')); ?></span>
                        <div class="duration-header"><?php echo esc_html(iptv_text('month_3_label', '3 Months')); ?>
                        </div>
                        <div class="duration-price price-display" id="price-3mo">$
                            <?php echo esc_html($all_prices['3_months'][$default_device]['usd']); ?>
                        </div>
                        <div class="duration-per" id="per-3mo">
                            ~$<?php echo round($all_prices['3_months'][$default_device]['usd'] / 3, 2); ?>/mo</div>
                        <div class="duration-savings"><?php echo esc_html($save_more_text); ?></div>
                    </div>
                    <div class="select-card duration-card" data-duration="6" data-months="6">
                        <span
                            class="badge badge-green"><?php echo esc_html(iptv_text('save_58_text', 'Save 58%')); ?></span>
                        <div class="duration-header"><?php echo esc_html(iptv_text('month_6_label', '6 Months')); ?>
                        </div>
                        <div class="duration-price price-display" id="price-6mo">$
                            <?php echo esc_html($all_prices['6_months'][$default_device]['usd']); ?>
                        </div>
                        <div class="duration-per" id="per-6mo">
                            ~$<?php echo round($all_prices['6_months'][$default_device]['usd'] / 6, 2); ?>/mo</div>
                        <div class="duration-savings"><?php echo esc_html($save_more_text); ?></div>
                    </div>
                    <div class="select-card duration-card" data-duration="12" data-months="12">
                        <span
                            class="badge badge-orange"><?php echo esc_html(iptv_text('best_value_text', 'Best Value')); ?></span>
                        <div class="duration-header"><?php echo esc_html(iptv_text('month_12_label', '12 Months')); ?>
                        </div>
                        <div class="duration-price price-display" id="price-12mo">$
                            <?php echo esc_html($all_prices['12_months'][$default_device]['usd']); ?>
                        </div>
                        <div class="duration-per" id="per-12mo">
                            ~$<?php echo round($all_prices['12_months'][$default_device]['usd'] / 12, 2); ?>/mo</div>
                        <div class="duration-savings"><?php echo esc_html($best_deal_text); ?></div>
                    </div>
                </div>
            </div>

            <!-- CTA Button -->
            <a href="#" id="checkout-btn" class="cta-btn" style="margin-bottom:1rem;">
                <span
                    id="button-text"><?php echo esc_html(iptv_text('checkout_button', 'Complete Your Order')); ?></span>
                <span style="margin-left:0.5rem;">→</span>
            </a>

            <p style="text-align:center;color:var(--text-muted);font-size:0.875rem;margin-bottom:1.5rem;">
                <?php echo esc_html(iptv_text('guarantee_text', '14-day money-back guarantee. No questions asked.')); ?>
            </p>

            <!-- Trust Badges -->
            <div class="trust-badges">
                <div>
                    <div style="width:2.5rem;height:2.5rem;background:rgba(0,212,170,0.1);border-radius:0.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg fill="none" stroke="var(--color-teal)" viewBox="0 0 24 24" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:0.875rem;">
                            <?php echo esc_html(iptv_text('trust_1_title', 'Transparent pricing')); ?>
                        </div>
                        <div style="color:var(--text-muted);font-size:0.75rem;">
                            <?php echo esc_html(iptv_text('trust_1_desc', 'No contracts. Cancel anytime.')); ?>
                        </div>
                    </div>
                </div>
                <div>
                    <div style="width:2.5rem;height:2.5rem;background:rgba(0,212,170,0.1);border-radius:0.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg fill="none" stroke="var(--color-teal)" viewBox="0 0 24 24" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:0.875rem;">
                            <?php echo esc_html(iptv_text('trust_2_title', 'Instant activation')); ?>
                        </div>
                        <div style="color:var(--text-muted);font-size:0.75rem;">
                            <?php echo esc_html(iptv_text('trust_2_desc', 'Start watching in minutes.')); ?>
                        </div>
                    </div>
                </div>
                <div>
                    <div style="width:2.5rem;height:2.5rem;background:rgba(0,212,170,0.1);border-radius:0.5rem;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg fill="none" stroke="var(--color-teal)" viewBox="0 0 24 24" style="width:1.25rem;height:1.25rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                        </svg>
                    </div>
                    <div>
                        <div style="font-weight:600;font-size:0.875rem;">
                            <?php echo esc_html(iptv_text('trust_3_title', 'Risk-free')); ?>
                        </div>
                        <div style="color:var(--text-muted);font-size:0.75rem;">
                            <?php echo esc_html(iptv_text('trust_3_desc', '14-day money-back guarantee.')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>