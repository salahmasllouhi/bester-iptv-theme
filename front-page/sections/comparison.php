<?php
$front_page_id = get_option('page_on_front');

$col1_rows  = $front_page_id ? get_field('comp_col_1_rows', $front_page_id) : get_field('comp_col_1_rows');
$col2_rows  = $front_page_id ? get_field('comp_col_2_rows', $front_page_id) : get_field('comp_col_2_rows');

// Merge col1 (left/without) and col2 (right/with) rows by index
$rows = [];
$count = max(count((array) $col1_rows), count((array) $col2_rows));
for ($i = 0; $i < $count; $i++) {
    $rows[] = [
        'label'  => isset($col1_rows[$i]['label']) ? $col1_rows[$i]['label'] : '',
        'val_1'  => isset($col1_rows[$i]['value']) ? $col1_rows[$i]['value'] : '',
        'val_2'  => isset($col2_rows[$i]['value']) ? $col2_rows[$i]['value'] : '',
    ];
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

            <?php foreach ($rows as $row) : ?>
            <div class="feat-card">
                <div class="fc-left">
                    <div class="fc-name"><?php echo esc_html($row['label']); ?></div>
                    <div class="fc-cable"><?php echo esc_html($row['val_1']); ?></div>
                </div>
                <div class="fc-right"><?php echo esc_html($row['val_2']); ?></div>
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
