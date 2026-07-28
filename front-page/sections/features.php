<?php
/**
 * Section: Features band (Design v2)
 * Eight compact capability chips.
 */

$s = 'width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="#7c3aed" stroke-width="1.6"';

$feature_chips = [
    1 => [
        'default' => '35,000+ Live Channels',
        'icon'    => '<svg ' . $s . '><rect x="2" y="4" width="20" height="13" rx="2"></rect><path d="M8 21h8"></path></svg>',
    ],
    2 => [
        'default' => '150,000+ Movies & Series',
        'icon'    => '<svg ' . $s . '><rect x="3" y="5" width="18" height="14" rx="2"></rect><path d="M3 9h18M8 5v14M16 5v14" stroke-width="1.3"></path></svg>',
    ],
    3 => [
        'default' => '4K & 8K Ultra HD',
        'icon'    => '<span class="dv2-chip-badge">4K</span>',
    ],
    4 => [
        'default' => 'Anti-Freeze Technology',
        'icon'    => '<svg width="26" height="26" viewBox="0 0 24 24" fill="#7c3aed"><path d="M13 2 4 14h6l-1 8 9-12h-6z"></path></svg>',
    ],
    5 => [
        'default' => 'Multi-Device Support',
        'icon'    => '<svg ' . $s . '><rect x="2" y="5" width="14" height="10" rx="1.5"></rect><rect x="17" y="8" width="5" height="11" rx="1.5"></rect></svg>',
    ],
    6 => [
        'default' => 'EPG & Daily Updates',
        'icon'    => '<svg ' . $s . '><path d="M3 12a9 9 0 0 1 15-6.7L21 8"></path><path d="M21 3v5h-5"></path><path d="M21 12a9 9 0 0 1-15 6.7L3 16"></path><path d="M3 21v-5h5"></path></svg>',
    ],
    7 => [
        'default' => '198 Countries Covered',
        'icon'    => '<svg ' . $s . '><circle cx="12" cy="12" r="9"></circle><path d="M12 3a14 14 0 0 0 0 18M12 3a14 14 0 0 1 0 18M3 12h18"></path></svg>',
    ],
    8 => [
        'default' => '24/7 Live Support',
        'icon'    => '<svg ' . $s . '><circle cx="9" cy="8" r="3.2"></circle><path d="M2.5 20a6.5 6.5 0 0 1 13 0"></path><path d="M17 6.5a3 3 0 0 1 0 5.5M18 20a6 6 0 0 0-3-5"></path></svg>',
    ],
];

$allowed_svg = [
    'svg'    => ['width' => true, 'height' => true, 'viewbox' => true, 'fill' => true, 'stroke' => true, 'stroke-width' => true],
    'rect'   => ['x' => true, 'y' => true, 'width' => true, 'height' => true, 'rx' => true],
    'circle' => ['cx' => true, 'cy' => true, 'r' => true],
    'path'   => ['d' => true, 'stroke-width' => true],
    'span'   => ['class' => true],
];
?>
<section class="features dv2-section" id="features">
    <div class="features-inner">
        <div class="dv2-section-head">
            <h2><?php echo esc_html(iptv_text('features_title', 'Built for global viewers')); ?></h2>
            <p>
                <?php echo esc_html(iptv_text('features_subtitle', 'From Nordic public TV to Premier League, Bollywood to Hollywood — all sports, all genres, all countries in one affordable package')); ?>
            </p>
        </div>

        <div class="dv2-feature-grid">
            <?php foreach ($feature_chips as $n => $chip) : ?>
                <div class="dv2-feature-chip">
                    <?php echo wp_kses($chip['icon'], $allowed_svg); ?>
                    <span class="dv2-feature-chip-label">
                        <?php echo esc_html(iptv_text("feature_{$n}_title", $chip['default'])); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
