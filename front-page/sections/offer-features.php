<?php
/**
 * Offer Features Section — "What You Get"
 * 3 cards per row desktop, 2 per row mobile, text centred
 *
 * Variables expected from template-offer-landing.php:
 *   $offer_features_rows  (array)
 */

$default_features = [
    ['icon' => '📺', 'text' => iptv_text('offer_feat_1', '40,000+ live channels')],
    ['icon' => '🎬', 'text' => iptv_text('offer_feat_2', '200,000+ movies & series')],
    ['icon' => '🔷', 'text' => iptv_text('offer_feat_3', '4K & 8K Ultra HD quality')],
    ['icon' => '💬', 'text' => iptv_text('offer_feat_4', 'Multi-language subtitles')],
    ['icon' => '🔄', 'text' => iptv_text('offer_feat_5', 'Daily content updates')],
    ['icon' => '📱', 'text' => iptv_text('offer_feat_6', 'Any device — TV, phone, tablet')],
];

$features = [];
if (!empty($offer_features_rows)) {
    foreach ($offer_features_rows as $row) {
        $features[] = [
            'icon' => $row['feature_icon'] ?? '✓',
            'text' => $row['feature_text'] ?? '',
        ];
    }
} else {
    $features = $default_features;
}
?>

<section class="offer-features" id="offer-features">
    <div class="offer-section-inner">
        <div class="offer-section-header">
            <div class="offer-section-tag"><?php echo esc_html(iptv_text('offer_features_tag', 'What You Get')); ?>
            </div>
            <h2 class="offer-section-title">
                <?php echo esc_html(iptv_text('offer_features_title', 'Everything included in your plan')); ?>
            </h2>
        </div>

        <div class="offer-features__grid">
            <?php foreach ($features as $feat): ?>
                <?php if (empty($feat['text']))
                    continue; ?>
                <div class="offer-features__item">
                    <span class="offer-features__icon" aria-hidden="true">
                        <?php echo esc_html($feat['icon'] ?: '✓'); ?>
                    </span>
                    <span class="offer-features__text">
                        <?php echo esc_html($feat['text']); ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
    .offer-features {
        background: var(--bg-page);
        padding: 5rem 1.5rem;
    }

    /* 3 columns on desktop */
    .offer-features__grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 1.25rem;
        margin-top: 2.5rem;
    }

    .offer-features__item {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 0.75rem;
        background: var(--bg-card);
        border: 1px solid var(--border-subtle);
        border-radius: var(--radius-md);
        padding: 1.5rem 1.25rem;
        box-shadow: var(--shadow-sm);
        transition: box-shadow 0.2s ease, transform 0.2s ease;
    }

    .offer-features__item:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-3px);
    }

    .offer-features__icon {
        font-size: 2rem;
        line-height: 1;
    }

    .offer-features__text {
        font-size: 0.975rem;
        font-weight: 600;
        color: var(--text-primary);
        line-height: 1.4;
    }

    /* 2 columns on mobile */
    @media (max-width: 768px) {
        .offer-features__grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }
    }

    @media (max-width: 480px) {
        .offer-features {
            padding: 4rem 1rem;
        }

        .offer-features__item {
            padding: 1.25rem 1rem;
        }
    }
</style>