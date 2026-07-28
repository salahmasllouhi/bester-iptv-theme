<?php
/**
 * Section: Content showcase (Design v2)
 * Two split panels — live channels and the VOD library.
 */

$showcase_cta_field  = function_exists('get_field') ? get_field('showcase_cta', get_option('page_on_front')) : null;
$showcase_cta_url    = (!empty($showcase_cta_field['url'])) ? $showcase_cta_field['url'] : '#pricing';
$showcase_cta_label  = (!empty($showcase_cta_field['title'])) ? $showcase_cta_field['title'] : iptv_text('showcase_cta', 'Explore the full channel lineup');
$showcase_cta_target = (!empty($showcase_cta_field['target'])) ? ' target="' . esc_attr($showcase_cta_field['target']) . '"' : '';

// Sample channel rows. Row 2 is the highlighted one, as in the design.
$channel_rows = [
    1 => ['132', 'Sport News HD'],
    2 => ['133', 'Premier League 4K'],
    3 => ['134', 'Nordic Public TV'],
    4 => ['135', 'NBA League Pass'],
    5 => ['136', 'Formula 1 HD'],
    6 => ['137', 'Documentary 8K'],
];
$highlight_row = 2;
?>

<!-- Explore channels -->
<section class="dv2-split dv2-split--first">
    <div class="dv2-split-copy">
        <h3 class="dv2-split-title dv2-split-title--lg">
            <?php echo esc_html(iptv_text('showcase_title', 'Explore')); ?>
            <em><?php echo esc_html(iptv_text('showcase_title_span', '35,000+')); ?></em>
            <?php echo esc_html(iptv_text('showcase_title_3', 'live TV channels')); ?>
        </h3>
        <p>
            <?php echo esc_html(iptv_text('showcase_subtitle', 'From local Nordic news to global sports, entertainment, kids, and international channels — 198 countries covered.')); ?>
        </p>
        <a href="<?php echo esc_url($showcase_cta_url); ?>" class="dv2-btn dv2-btn-ink"<?php echo $showcase_cta_target; ?>>
            <?php echo esc_html($showcase_cta_label); ?>
            <span class="dv2-btn-arrow" aria-hidden="true">→</span>
        </a>
    </div>

    <div class="dv2-split-aside">
        <div class="dv2-channel-list">
            <?php foreach ($channel_rows as $n => $row) : ?>
                <div class="dv2-channel-row<?php echo $n === $highlight_row ? ' dv2-channel-row--active' : ''; ?>">
                    <span class="dv2-channel-num"><?php echo esc_html(iptv_text("showcase_channel_{$n}_num", $row[0])); ?></span>
                    <span class="dv2-channel-name"><?php echo esc_html(iptv_text("showcase_channel_{$n}_name", $row[1])); ?></span>
                </div>
            <?php endforeach; ?>
            <div class="dv2-channel-more">
                <?php echo esc_html(iptv_text('showcase_channel_more', '+35,000 live TV channels from 198 countries')); ?>
            </div>
        </div>
    </div>
</section>

<!-- Movies & series -->
<?php
$vod_cta_field  = function_exists('get_field') ? get_field('vod_cta', get_option('page_on_front')) : null;
$vod_cta_url    = (!empty($vod_cta_field['url'])) ? $vod_cta_field['url'] : '#pricing';
$vod_cta_label  = (!empty($vod_cta_field['title'])) ? $vod_cta_field['title'] : iptv_text('vod_cta', 'Start watching today');
$vod_cta_target = (!empty($vod_cta_field['target'])) ? ' target="' . esc_attr($vod_cta_field['target']) . '"' : '';
?>
<section class="dv2-split">
    <div class="dv2-split-copy">
        <h3 class="dv2-split-title">
            <?php echo esc_html(iptv_text('vod_title', 'Indulge in')); ?>
            <em><?php echo esc_html(iptv_text('vod_title_span', '150,000+')); ?></em>
            <?php echo esc_html(iptv_text('vod_title_3', 'movies and series')); ?>
        </h3>
        <p>
            <?php echo esc_html(iptv_text('vod_subtitle', 'All genres and languages, on demand whenever it suits you. Full electronic program guide with daily content updates and multi-language subtitles.')); ?>
        </p>
        <a href="<?php echo esc_url($vod_cta_url); ?>" class="dv2-btn dv2-btn-primary"<?php echo $vod_cta_target; ?>>
            <?php echo esc_html($vod_cta_label); ?>
            <span class="dv2-btn-arrow" aria-hidden="true">→</span>
        </a>
    </div>

    <div class="dv2-split-aside">
        <div class="dv2-vod-search">
            <span><?php echo esc_html(iptv_text('vod_search_placeholder', 'Search 150,000+ titles')); ?></span>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#8a80a3" stroke-width="1.8" aria-hidden="true">
                <circle cx="11" cy="11" r="7"></circle>
                <path d="M21 21l-4-4"></path>
            </svg>
        </div>
        <div class="dv2-vod-row">
            <span class="dv2-vod-poster" aria-hidden="true"></span>
            <span><?php echo esc_html(iptv_text('vod_row_1', 'New release — 4K')); ?></span>
        </div>
        <div class="dv2-vod-row">
            <span class="dv2-vod-poster" aria-hidden="true"></span>
            <span><?php echo esc_html(iptv_text('vod_row_2', 'Hit series — full season')); ?></span>
        </div>
        <div class="dv2-vod-note">
            <span class="dv2-vod-note-mark" aria-hidden="true">+</span>
            <?php echo esc_html(iptv_text('vod_note', 'Updated daily, all genres & languages')); ?>
        </div>
    </div>
</section>
