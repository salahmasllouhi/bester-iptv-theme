<?php
// DEBUG - remove after confirming ACF saves correctly
if (current_user_can('manage_options')) {
    $fp = get_option('page_on_front');
    $v = $fp ? get_field('comp_row_1_label', $fp) : get_field('comp_row_1_label');
    echo '<!-- ACF comp_row_1_label = ' . esc_html(var_export($v, true)) . ' -->';
}
?>
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

            <?php
            $comp_rows = [
                ['label' => 'Netflix',             'val_1' => '$17.99/mo',   'val_2' => 'Included'],
                ['label' => 'Disney+',             'val_1' => '$12.99/mo',   'val_2' => 'Included'],
                ['label' => 'HBO Max',             'val_1' => '$14.99/mo',   'val_2' => 'Included'],
                ['label' => 'Sports Package',      'val_1' => '$35.00/mo',   'val_2' => 'Included'],
                ['label' => 'PPV Events (yearly)', 'val_1' => '$700+',       'val_2' => '$0 Extra'],
                ['label' => '20,000+ Channels',    'val_1' => 'Extra Fees',  'val_2' => 'Included'],
                ['label' => '4K Quality',          'val_1' => 'Extra Fees',  'val_2' => 'Included'],
            ];
            foreach ($comp_rows as $i => $defaults) :
                $n = $i + 1;
            ?>
            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html(iptv_text("comp_row_{$n}_label", $defaults['label'])); ?></div>
                    <div class="fc-cable"><?php echo esc_html(iptv_text("comp_row_{$n}_val_1", $defaults['val_1'])); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html(iptv_text("comp_row_{$n}_val_2", $defaults['val_2'])); ?></div>
            </div>
            <?php endforeach; ?>

            <!-- Annual Cost card -->
            <div class="annual-card">
                <div class="ac-left">
                    <div class="ac-label"><?php echo esc_html(iptv_text('comp_total_label', 'Annual Cost')); ?></div>
                    <div class="ac-cable"><?php echo esc_html(iptv_text('comp_c1_total_val', '$1,200+')); ?> cable</div>
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
