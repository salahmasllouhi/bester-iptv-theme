<?php
/**
 * All four plans, side by side
 *
 * Two jobs. It links the four plan pages to each other, which is the whole
 * point of having four pages rather than one; and it shows a visitor on the
 * 1-month page exactly what the longer plans cost per month, which is the only
 * honest way to sell one.
 *
 * Prices are shown for the popular screen count so the column compares like
 * with like. Rows for plans that have no page yet are still shown — they just
 * link to the front-page configurator instead of a dead URL.
 *
 * Expects from template-plan.php:
 *   $plan_months (int)
 */

$compare_title = iptv_plan_field(
    'plan_compare_title',
    plan_str('How the four plans compare')
);

$screens = iptv_plan_popular_screens();

$compare_subtitle = iptv_plan_field('plan_compare_subtitle', sprintf(
    /* translators: %s = screen count label, e.g. "2 Screens" */
    plan_str('Prices shown for %s. Longer plans cost less per month — the service is the same on all of them.'),
    iptv_plan_screens_label($screens)
));

// The cheapest per-month rate on offer, so "best value" is stated rather than
// assumed. Derived, so it stays right if the price ladder ever changes shape.
$best_months = 0;
$best_rate   = null;

foreach (array_keys(iptv_plan_durations()) as $months) {
    $saving = iptv_plan_savings($months, $screens);
    if ($saving['now'] <= 0) {
        continue;
    }
    if ($best_rate === null || $saving['per_month'] < $best_rate) {
        $best_rate   = $saving['per_month'];
        $best_months = $months;
    }
}
?>
<section class="plan-compare dv2-section" id="plan-compare">
    <div class="container">

        <div class="dv2-section-head">
            <h2><?php echo esc_html($compare_title); ?></h2>
            <p><?php echo esc_html($compare_subtitle); ?></p>
        </div>

        <div class="plan-compare-table" role="table"
            aria-label="<?php echo esc_attr($compare_title); ?>">

            <div class="plan-compare-row plan-compare-row--head" role="row">
                <?php // Not iptv_text('duration_title') — that string is the
                      // configurator's question ("How long?"), not a heading. ?>
                <span role="columnheader"><?php echo esc_html(plan_str('Plan')); ?></span>
                <span role="columnheader"><?php echo esc_html(iptv_text('total_label', 'Your total')); ?></span>
                <span role="columnheader"><?php echo esc_html(plan_str('Per month')); ?></span>
                <span role="columnheader"><?php echo esc_html(plan_str('You save')); ?></span>
                <span role="columnheader"><span class="screen-reader-text"><?php echo esc_html(plan_str('Link')); ?></span></span>
            </div>

            <?php foreach (array_keys(iptv_plan_durations()) as $months) :
                $saving = iptv_plan_savings($months, $screens);

                if ($saving['now'] <= 0) {
                    continue;
                }

                $is_current = ($months === $plan_months);
                $is_best    = ($months === $best_months && $months !== 1);
                $url        = $is_current ? '' : iptv_plan_url($months);

                $classes = 'plan-compare-row';
                if ($is_current) {
                    $classes .= ' plan-compare-row--current';
                }
                if ($is_best) {
                    $classes .= ' plan-compare-row--best';
                }
                ?>
                <div class="<?php echo esc_attr($classes); ?>" role="row">

                    <span class="plan-compare-name" role="cell">
                        <?php echo esc_html(iptv_plan_label($months)); ?>
                        <?php if ($is_best) : ?>
                            <span class="plan-compare-flag"><?php echo esc_html(plan_str('Best value')); ?></span>
                        <?php endif; ?>
                    </span>

                    <?php
                    // data-label carries the column heading down to the phone
                    // layout, where the header row is hidden and each cell has
                    // to say what it is. See .plan-compare-row on mobile.
                    ?>
                    <span class="plan-compare-total" role="cell"
                        data-label="<?php echo esc_attr(iptv_text('total_label', 'Your total')); ?>">
                        <?php echo esc_html(iptv_plan_format_price($saving['now'])); ?>
                    </span>

                    <span class="plan-compare-rate" role="cell"
                        data-label="<?php echo esc_attr(plan_str('Per month')); ?>">
                        <?php echo esc_html(iptv_plan_format_price($saving['per_month'])); ?>
                    </span>

                    <span class="plan-compare-save" role="cell"
                        data-label="<?php echo esc_attr(plan_str('You save')); ?>">
                        <?php echo $saving['pct'] > 0
                            ? esc_html(sprintf('%d%%', $saving['pct']))
                            : '<span class="plan-compare-dash">—</span>'; ?>
                    </span>

                    <span class="plan-compare-action" role="cell">
                        <?php if ($is_current) : ?>
                            <span class="plan-compare-here"><?php echo esc_html(plan_str('You are here')); ?></span>
                        <?php elseif ($url) : ?>
                            <a href="<?php echo esc_url($url); ?>">
                                <?php echo esc_html(sprintf(
                                    /* translators: %s = plan length, e.g. "12 Months" */
                                    plan_str('See %s'),
                                    iptv_plan_label($months)
                                )); ?>
                            </a>
                        <?php else : ?>
                            <a href="<?php echo esc_url(home_url('/#pricing')); ?>">
                                <?php echo esc_html(plan_str('See pricing')); ?>
                            </a>
                        <?php endif; ?>
                    </span>

                </div>
            <?php endforeach; ?>

        </div>

    </div>
</section>
