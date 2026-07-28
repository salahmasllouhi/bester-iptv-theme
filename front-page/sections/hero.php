<?php
/**
 * Section: Hero (Design v2 — NordicTV Light Purple)
 */

// Hero image: ACF field on the front page, else the shared content render.
$hero_image_url = '';
if (function_exists('get_field')) {
    $hero_field = get_field('hero_image', get_option('page_on_front'));
    if (is_array($hero_field) && !empty($hero_field['url'])) {
        $hero_image_url = $hero_field['url'];
    } elseif (is_string($hero_field) && $hero_field) {
        $hero_image_url = $hero_field;
    }
}
if (!$hero_image_url) {
    $hero_image_url = 'https://nordictv.io/wp-content/uploads/2026/01/content.png';
}

$primary_label   = iptv_text('hero_primary_cta_label', 'Get Access Now');
$primary_url     = iptv_text('hero_primary_cta_url', '#pricing');
$secondary_label = iptv_text('hero_secondary_cta_label', 'Pricing & Plans ↓');
$secondary_url   = iptv_text('hero_secondary_cta_url', '#pricing');
?>
<div class="dv2-grid-wash" aria-hidden="true"></div>

<section class="dv2-hero container">
    <div class="dv2-hero-copy">
        <div class="dv2-trustpilot">
            <span class="dv2-trustpilot-name">★ Trustpilot</span>
            <span><?php echo esc_html(iptv_text('hero_trust_label', 'Excellent')); ?></span>
            <span class="dv2-trustpilot-stars" aria-hidden="true">
                <span>★</span><span>★</span><span>★</span><span>★</span><span>★</span>
            </span>
            <span>
                <strong><?php echo esc_html(iptv_text('hero_trust_score', '4.9')); ?></strong>
                <?php echo esc_html(iptv_text('hero_trust_suffix', 'out of 5')); ?>
            </span>
        </div>

        <h1 class="dv2-hero-title">
            <?php echo esc_html(iptv_text('hero_title', 'Watch Everything.')); ?>
            <?php echo esc_html(iptv_text('hero_title_span', 'Pay Almost Nothing.')); ?>
        </h1>

        <p class="dv2-hero-sub">
            <?php echo wp_kses_post(iptv_text('hero_subtitle', 'As a premier IPTV service provider, NordicTV offers 35,000+ channels, 150,000+ VODs, and all sports in 4K/8K. Stream premium content on any device today.')); ?>
        </p>

        <div class="dv2-live-pill">
            <span class="dv2-live-dot" aria-hidden="true"></span>
            <strong id="liveCounter"><?php echo esc_html(iptv_text('hero_stat_1_val', '12,849')); ?></strong>
            <?php echo esc_html(iptv_text('hero_stat_1_desc', 'users watching now')); ?>
        </div>

        <div class="dv2-hero-actions">
            <a href="<?php echo esc_url($primary_url); ?>" class="dv2-btn dv2-btn-green">
                ▶ <?php echo esc_html($primary_label); ?>
            </a>
            <a href="<?php echo esc_url($secondary_url); ?>" class="dv2-hero-link">
                <?php echo esc_html($secondary_label); ?>
            </a>
        </div>
    </div>

    <div class="dv2-hero-media">
        <div class="dv2-hero-glow" aria-hidden="true"></div>
        <img src="<?php echo esc_url($hero_image_url); ?>"
             alt="<?php echo esc_attr(iptv_text('hero_image_alt', 'NordicTV live sports and entertainment')); ?>"
             fetchpriority="high">
    </div>
</section>
