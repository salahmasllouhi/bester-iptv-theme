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
    plan_str('Who the %s plan suits'),
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
    // Defaults live in plan/inc/plan-strings.php, which is also what registers
    // them with Polylang — one array, so copy edited there cannot drift out of
    // registration and silently stop translating.
    $by_length = iptv_plan_audience_defaults();
    $cards     = isset($by_length[$plan_months]) ? $by_length[$plan_months] : $by_length[1];

    foreach ($cards as $card) {
        $points[] = array(
            'title' => plan_str($card['title']),
            'text'  => plan_str($card['text']),
        );
    }
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
