<?php
/**
 * Section: Reviews (Design v2)
 * Score card plus a grid of customer reviews.
 */

$title    = iptv_text('reviews_title', 'What our customers actually say');
$subtitle = iptv_text('reviews_subtitle', 'Join thousands of cord-cutters across Scandinavia who\'ve switched to IPTV Anbieter.');

// Reviews come from the `reviews_list` repeater on the front page, falling back
// to the defaults in inc/front-page-strings.php. Resolved through
// iptv_front_reviews() so the Rank Math digest reads the same wall the visitor
// does. Title and date are optional; a row without text and author is skipped.
$reviews = iptv_front_reviews();

/**
 * Renders one review card.
 */
$render_review = function ($review) {
    ?>
    <div class="dv2-review-card">
        <div class="dv2-review-top">
            <span class="dv2-review-stars" aria-hidden="true">★★★★★</span>
            <?php if (!empty($review['when'])) : ?>
                <span class="dv2-review-when"><?php echo esc_html($review['when']); ?></span>
            <?php endif; ?>
        </div>
        <?php if (!empty($review['title'])) : ?>
            <div class="dv2-review-title"><?php echo esc_html($review['title']); ?></div>
        <?php endif; ?>
        <p class="dv2-review-body"><?php echo esc_html($review['text']); ?></p>
        <div class="dv2-review-name"><?php echo esc_html($review['author']); ?></div>
    </div>
    <?php
};

// Summary panel above the rail. The count is the number of reviews actually
// rendered, so it cannot drift from what is on the page. Every score here is a
// configured claim rather than a computed one — the review cards carry no
// per-review rating, and none at all per category, so there is nothing to
// average. They are editable via the keys below.
$review_count = count($reviews);
$review_score = iptv_text('reviews_score', '4,8');
$review_max   = iptv_text('reviews_score_max', '5');

// Category breakdown. Named for what an IPTV buyer actually weighs up rather
// than the generic labels of a software review.
$review_breakdown = array(
    1 => array(
        'label' => iptv_text('reviews_cat_1_label', 'Sender & Mediathek'),
        'score' => iptv_text('reviews_cat_1_score', '4,9'),
    ),
    2 => array(
        'label' => iptv_text('reviews_cat_2_label', 'Stabilität'),
        'score' => iptv_text('reviews_cat_2_score', '4,7'),
    ),
    3 => array(
        'label' => iptv_text('reviews_cat_3_label', 'Geräte-Unterstützung'),
        'score' => iptv_text('reviews_cat_3_score', '4,9'),
    ),
    4 => array(
        'label' => iptv_text('reviews_cat_4_label', 'Preis-Leistung'),
        'score' => iptv_text('reviews_cat_4_score', '4,8'),
    ),
);

// Bar length comes from the score, so a rewritten score cannot leave a bar
// telling a different story. German copy writes 4,9 — normalise before the cast.
$review_bar_pct = function ($score) use ($review_max) {
    $value = (float) str_replace(',', '.', (string) $score);
    $max   = (float) str_replace(',', '.', (string) $review_max);
    if ($max <= 0) {
        return 0;
    }
    return max(0, min(100, round(($value / $max) * 100, 1)));
};
?>
<section class="reviews dv2-section">
    <div class="container">
        <div class="dv2-section-head">
            <h2><?php echo esc_html($title); ?></h2>
            <p><?php echo esc_html($subtitle); ?></p>
        </div>

        <div class="dv2-review-summary">
            <div class="dv2-review-summary-score">
                <span class="dv2-review-summary-num"><?php echo esc_html($review_score); ?></span>
                <div class="dv2-review-summary-meta">
                    <span class="dv2-review-summary-stars" aria-hidden="true">★★★★★</span>
                    <span class="dv2-review-summary-label">
                        <?php echo esc_html(iptv_text('reviews_score_label', 'Unsere Bewertung')); ?>
                    </span>
                    <span class="dv2-review-summary-count">
                        <?php echo esc_html(sprintf(
                            iptv_text('reviews_count_format', 'Basierend auf %d Bewertungen'),
                            $review_count
                        )); ?>
                    </span>
                </div>
            </div>

            <ul class="dv2-review-bars">
                <?php foreach ($review_breakdown as $cat) : ?>
                    <li class="dv2-review-bar-row">
                        <div class="dv2-review-bar-head">
                            <span class="dv2-review-bar-label"><?php echo esc_html($cat['label']); ?></span>
                            <strong class="dv2-review-bar-score"><?php echo esc_html($cat['score']); ?></strong>
                        </div>
                        <?php // aria-hidden: the score is already read out above as text,
                              // so the bar is decoration rather than a second announcement. ?>
                        <div class="dv2-review-bar" aria-hidden="true">
                            <span style="width: <?php echo esc_attr($review_bar_pct($cat['score'])); ?>%"></span>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <?php // One row, scrolled by the arrows. It used to be two rows animating
              // in opposite directions on a loop, which meant the text a visitor
              // was reading slid out from under them and could not be brought
              // back. Scroll-snap keeps a card aligned after each press, and the
              // rail is still swipeable and keyboard-scrollable on its own. ?>
        <div class="dv2-review-rail-wrap">
            <button type="button" class="dv2-review-arrow dv2-review-arrow--prev"
                aria-label="<?php echo esc_attr(iptv_text('reviews_prev', 'Vorherige Bewertungen')); ?>">‹</button>

            <div class="dv2-review-rail" id="review-rail" tabindex="0">
                <?php foreach ($reviews as $review) {
                    $render_review($review);
                } ?>
            </div>

            <button type="button" class="dv2-review-arrow dv2-review-arrow--next"
                aria-label="<?php echo esc_attr(iptv_text('reviews_next', 'Weitere Bewertungen')); ?>">›</button>
        </div>
    </div>
</section>
