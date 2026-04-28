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

        <div class="comp-table-wrap">
            <table class="comp-table">
                <colgroup>
                    <col style="width:38%">
                    <col style="width:31%">
                    <col style="width:31%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="comp-th comp-th-feature">
                            <?php echo esc_html(iptv_text('comp_th_feature', 'Feature')); ?>
                        </th>
                        <th class="comp-th comp-th-cable">
                            <span class="comp-dot comp-dot-red"></span>
                            <?php echo esc_html(iptv_text('comp_col_1', 'Traditional Cable')); ?>
                        </th>
                        <th class="comp-th comp-th-nordic">
                            <span class="comp-dot comp-dot-teal"></span>
                            <?php echo esc_html(iptv_text('comp_col_2', 'Nordic IPTV')); ?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Row 1 -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_1_label', 'Netflix')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-negative">
                                <?php echo esc_html(iptv_text('comp_row_1_val_1', '$17.99/mo')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-included">
                                <?php echo esc_html(iptv_text('comp_row_1_val_2', 'Included')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Row 2 -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_2_label', 'Disney+')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-negative">
                                <?php echo esc_html(iptv_text('comp_row_2_val_1', '$12.99/mo')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-included">
                                <?php echo esc_html(iptv_text('comp_row_2_val_2', 'Included')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Row 3 -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_3_label', 'HBO Max')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-negative">
                                <?php echo esc_html(iptv_text('comp_row_3_val_1', '$14.99/mo')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-included">
                                <?php echo esc_html(iptv_text('comp_row_3_val_2', 'Included')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Row 4 -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_4_label', 'Sports Package')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-negative">
                                <?php echo esc_html(iptv_text('comp_row_4_val_1', '$35.00/mo')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-included">
                                <?php echo esc_html(iptv_text('comp_row_4_val_2', 'Included')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Row 5 — PPV -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_5_label', 'PPV Events (yearly)')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-negative comp-val-ppv">
                                <?php echo esc_html(iptv_text('comp_row_5_val_1', '$700+/yr')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-ppv-free">
                                <?php echo esc_html(iptv_text('comp_row_8_val_2', '$0 Extra')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Row 6 — Channels -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_9_label', 'Channels')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-neutral">
                                <?php echo esc_html(iptv_text('comp_row_9_val_1', 'Limited')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-included">
                                <?php echo esc_html(iptv_text('comp_row_9_val_2', '20,000+')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Row 7 — Quality -->
                    <tr class="comp-row">
                        <td class="comp-td comp-td-feature">
                            <?php echo esc_html(iptv_text('comp_row_10_label', 'Quality')); ?>
                        </td>
                        <td class="comp-td comp-td-cable">
                            <span class="comp-val-neutral">
                                <?php echo esc_html(iptv_text('comp_row_10_val_1', 'HD only')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic">
                            <span class="comp-val-included">
                                <?php echo esc_html(iptv_text('comp_row_10_val_2', '4K Included')); ?>
                            </span>
                        </td>
                    </tr>
                    <!-- Annual Cost row -->
                    <tr class="comp-row comp-row-total">
                        <td class="comp-td comp-td-feature comp-td-total-label">
                            <?php echo esc_html(iptv_text('comp_total_label', 'Annual Cost')); ?>
                        </td>
                        <td class="comp-td comp-td-cable comp-td-total">
                            <span class="comp-total-val comp-total-cable">
                                <?php echo esc_html(iptv_text('comp_total_val_1', '$1,200+')); ?>
                            </span>
                        </td>
                        <td class="comp-td comp-td-nordic comp-td-total">
                            <span class="comp-total-val comp-total-nordic">
                                <?php echo esc_html(iptv_text('comp_price', '$69.99')); ?>
                            </span>
                            <span class="comp-total-sub">
                                <?php echo esc_html(iptv_text('comp_price_sub', '~$5.83/month')); ?>
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="savings-banner">
            <div class="savings-label">
                <?php echo esc_html(iptv_text('comp_savings_label', 'Your Annual Savings')); ?>
            </div>
            <div class="savings-value">
                <?php echo esc_html(iptv_text('comp_savings_val', '$1,100+')); ?>
            </div>
        </div>
    </div>
</section>
