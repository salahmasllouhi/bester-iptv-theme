<section class="comparison">
    <div class="comparison-inner">
        <div class="section-header">
            <div class="section-tag">
                <?php echo esc_html(iptv_text('comp_badge', 'Save Money')); ?>
            </div>
            <h2 class="section-title">
                <?php echo iptv_text('comp_title_main', 'Stop The'); ?> <span class="gradient-text">
                    <?php echo iptv_text('comp_title_sub', 'Subscription Trap'); ?>
                </span>
            </h2>
            <p class="section-subtitle">
                <?php echo esc_html(iptv_text('comp_desc', 'See how much you\'re really paying vs Nordic IPTV.')); ?>
            </p>
        </div>

        <div class="comp-cards-wrap">

            <!-- Feature rows -->
            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_1_label', 'Netflix')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_1_val_1', '$17.99/mo')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_1_val_2', 'Included')); ?></div>
            </div>

            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_2_label', 'Disney+')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_2_val_1', '$12.99/mo')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_2_val_2', 'Included')); ?></div>
            </div>

            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_3_label', 'HBO Max')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_3_val_1', '$14.99/mo')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_3_val_2', 'Included')); ?></div>
            </div>

            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_4_label', 'Sports Package')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_4_val_1', '$35.00/mo')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_4_val_2', 'Included')); ?></div>
            </div>

            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_5_label', 'PPV Events (yearly)')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_5_val_1', '$700+/yr')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_8_val_2', '$0 Extra')); ?></div>
            </div>

            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_9_label', '20,000+ Channels')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_9_val_1', 'Extra Fees')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_9_val_2', 'Included')); ?></div>
            </div>

            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text('comp_row_10_label', '4K Quality')); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text('comp_row_10_val_1', 'Extra Fees')); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text('comp_row_10_val_2', 'Included')); ?></div>
            </div>

            <!-- Annual Cost card -->
            <div class="annual-card">
                <div class="ac-left">
                    <div class="ac-label"><?php echo esc_html(iptv_text('comp_total_label', 'Annual Cost')); ?></div>
                    <div class="ac-cable"><?php echo esc_html(iptv_text('comp_total_val_1', '$1,200+')); ?> cable</div>
                </div>
                <div class="ac-right">
                    <div class="ac-price"><?php echo esc_html(iptv_text('comp_price', '$69.99')); ?></div>
                    <div class="ac-sub"><?php echo esc_html(iptv_text('comp_price_sub', '~$5.83/month')); ?></div>
                </div>
            </div>

            <!-- Savings bar -->
            <div class="savings-bar">
                <div class="sav-label"><?php echo esc_html(iptv_text('comp_savings_label', 'Your Annual Savings')); ?></div>
                <div class="sav-val"><?php echo esc_html(iptv_text('comp_savings_val', '$1,100+')); ?></div>
            </div>

        </div>

        <div style="text-align:center;margin-top:var(--space-xl);">
            <a href="#pricing" class="btn btn-primary">
                <?php echo esc_html(iptv_text('comp_cta', 'Start Saving Today')); ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>
        </div>
    </div>
</section>
