<?php
/**
 * Section: Sports (Design v2)
 * Split panel with a six-tile mosaic.
 */

$sports_cta_field  = function_exists('get_field') ? get_field('sports_cta', get_option('page_on_front')) : null;
$sports_cta_url    = (!empty($sports_cta_field['url'])) ? $sports_cta_field['url'] : '#pricing';
$sports_cta_label  = (!empty($sports_cta_field['title'])) ? $sports_cta_field['title'] : iptv_text('sports_cta', 'Watch live sport now');
$sports_cta_target = (!empty($sports_cta_field['target'])) ? ' target="' . esc_attr($sports_cta_field['target']) . '"' : '';

$img_base = get_template_directory_uri() . '/images/sport%20images/';

// Mosaic: three photo tiles interleaved with three solid league tiles.
$mosaic = [
    ['type' => 'photo', 'src' => $img_base . 'Corners-IPTV-USA-Prime.png',            'key' => 'sport_1_name', 'default' => 'Football'],
    ['type' => 'photo', 'src' => $img_base . 'More-than-machine-IPTV-Provider-USA.png', 'key' => 'sport_5_name', 'default' => 'Formula 1'],
    ['type' => 'solid', 'key' => 'sport_4_name', 'default' => 'NFL'],
    ['type' => 'solid', 'key' => 'sport_2_name', 'default' => 'NBA'],
    ['type' => 'photo', 'src' => $img_base . 'Canelo-IPTV-subscription.png',          'key' => 'sport_6_name', 'default' => 'Boxing'],
    ['type' => 'solid', 'key' => 'sport_3_name', 'default' => 'NHL'],
];
?>
<section class="dv2-split">
    <div class="dv2-split-copy">
        <h3 class="dv2-split-title">
            <?php echo esc_html(iptv_text('sports_title', 'Every sport.')); ?>
            <em><?php echo esc_html(iptv_text('sports_title_span', 'Every match.')); ?></em>
        </h3>
        <p>
            <?php echo esc_html(iptv_text('sports_desc', 'Never miss a game again. Every major league, every tournament, every PPV event — NFL, NBA, Formula 1, football, boxing and more, in HD and 4K.')); ?>
        </p>
        <a href="<?php echo esc_url($sports_cta_url); ?>" class="dv2-btn dv2-btn-primary"<?php echo $sports_cta_target; ?>>
            <?php echo esc_html($sports_cta_label); ?>
            <span class="dv2-btn-arrow" aria-hidden="true">→</span>
        </a>
    </div>

    <div class="dv2-sport-mosaic">
        <?php foreach ($mosaic as $tile) :
            $label = iptv_text($tile['key'], $tile['default']);
            ?>
            <?php if ($tile['type'] === 'photo') : ?>
                <div class="dv2-sport-tile dv2-sport-tile--photo">
                    <img src="<?php echo esc_url($tile['src']); ?>" alt="<?php echo esc_attr($label); ?>" loading="lazy">
                </div>
            <?php else : ?>
                <div class="dv2-sport-tile dv2-sport-tile--solid"><?php echo esc_html($label); ?></div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</section>
