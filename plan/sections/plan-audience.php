<?php
/**
 * Who this plan is for
 *
 * The only section that genuinely differs between the four pages, and the
 * reason they exist as separate pages at all: a 1-month page argues "try it
 * without committing", a 12-month page argues "you already know you want it".
 * Defaults are written per length; ACF overrides them per page and language.
 *
 * Expects from template-plan.php:
 *   $plan_months (int)  $plan_label (string)
 */

$audience_title = iptv_plan_field('plan_audience_title', sprintf(
    /* translators: %s = plan length, e.g. "1 Month" */
    __('Who the %s plan suits', 'my-iptv'),
    $plan_label
));

$points = array();
$rows   = iptv_plan_field('plan_audience_points', array());

if (is_array($rows)) {
    foreach ($rows as $row) {
        if (!empty($row['title'])) {
            $points[] = array(
                'title' => $row['title'],
                'text'  => isset($row['text']) ? $row['text'] : '',
            );
        }
    }
}

if (empty($points)) {
    $by_length = array(
        1 => array(
            array(
                'title' => __('You want to try it properly', 'my-iptv'),
                'text'  => __('A full month of the complete service — every channel, every film, every match. Long enough to judge it on your own TV, your own connection and your own evenings.', 'my-iptv'),
            ),
            array(
                'title' => __('You are not ready to commit', 'my-iptv'),
                'text'  => __('Nothing renews on its own and there is no contract to leave. When the month ends, it ends — you decide whether there is a next one.', 'my-iptv'),
            ),
            array(
                'title' => __('You only need it for a while', 'my-iptv'),
                'text'  => __('A season, a tournament, a long winter, a rented flat. Take the month you need and stop.', 'my-iptv'),
            ),
        ),
        3 => array(
            array(
                'title' => __('You have already made up your mind', 'my-iptv'),
                'text'  => __('You have tried IPTV before and you know what you want. Three months costs noticeably less per month than paying monthly.', 'my-iptv'),
            ),
            array(
                'title' => __('You are covering a season', 'my-iptv'),
                'text'  => __('One league, one winter, one stretch of long evenings — a quarter is usually the shape of it.', 'my-iptv'),
            ),
            array(
                'title' => __('You want less admin', 'my-iptv'),
                'text'  => __('One payment instead of three, and no renewal to remember in between.', 'my-iptv'),
            ),
        ),
        6 => array(
            array(
                'title' => __('You watch all year', 'my-iptv'),
                'text'  => __('Half a year of everything, at a rate that makes monthly billing look expensive.', 'my-iptv'),
            ),
            array(
                'title' => __('You want the saving without the full year', 'my-iptv'),
                'text'  => __('Most of the discount of the annual plan, at half the amount up front.', 'my-iptv'),
            ),
            array(
                'title' => __('You are done comparing', 'my-iptv'),
                'text'  => __('Set it up once, forget the billing, and go back to watching television.', 'my-iptv'),
            ),
        ),
        12 => array(
            array(
                'title' => __('You want the lowest price there is', 'my-iptv'),
                'text'  => __('The annual plan is the cheapest month of television we sell. Nothing else comes close per month.', 'my-iptv'),
            ),
            array(
                'title' => __('This is your main television', 'my-iptv'),
                'text'  => __('If the household watches most nights, a year is the plan that matches how you actually use it.', 'my-iptv'),
            ),
            array(
                'title' => __('You want to pay once and forget it', 'my-iptv'),
                'text'  => __('One payment, twelve months, no renewal notice and no auto-charge at the end of it.', 'my-iptv'),
            ),
        ),
    );

    $points = isset($by_length[$plan_months]) ? $by_length[$plan_months] : $by_length[1];
}
?>
<section class="plan-audience dv2-section">
    <div class="container">

        <div class="dv2-section-head">
            <h2><?php echo esc_html($audience_title); ?></h2>
        </div>

        <div class="plan-audience-grid">
            <?php foreach ($points as $point) : ?>
                <div class="plan-audience-card">
                    <h3><?php echo esc_html($point['title']); ?></h3>
                    <?php if (!empty($point['text'])) : ?>
                        <p><?php echo esc_html($point['text']); ?></p>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
