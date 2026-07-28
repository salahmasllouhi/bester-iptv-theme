<?php
/**
 * Section: Full-width savings CTA bar (Design v2)
 */
$bar_field  = function_exists('get_field') ? get_field('cta_bar', get_option('page_on_front')) : null;
$bar_url    = (!empty($bar_field['url'])) ? $bar_field['url'] : '#pricing';
$bar_label  = (!empty($bar_field['title'])) ? $bar_field['title'] : iptv_text('cta_bar_label', 'SAVE 90% VS TRADITIONAL SERVICES');
$bar_target = (!empty($bar_field['target'])) ? ' target="' . esc_attr($bar_field['target']) . '"' : '';
?>
<div class="container">
    <a href="<?php echo esc_url($bar_url); ?>" class="dv2-cta-bar"<?php echo $bar_target; ?>>
        <?php echo esc_html($bar_label); ?>
        <span class="dv2-cta-bar-arrow" aria-hidden="true">→</span>
    </a>
</div>
