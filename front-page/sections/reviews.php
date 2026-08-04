<?php
/**
 * Section: Reviews (Design v2)
 * Score card plus a grid of customer reviews.
 */

$title    = iptv_text('reviews_title', 'What our customers actually say');
$subtitle = iptv_text('reviews_subtitle', 'Join thousands of cord-cutters across Scandinavia who\'ve switched to IPTV Anbieter.');

// Reviews come from the `reviews_list` repeater on the front page, so they are
// translated per language alongside the rest of the page copy. Title and date are
// optional; a row without text and author is skipped.
$reviews = [];
$review_rows = function_exists('get_field') ? get_field('reviews_list', get_option('page_on_front')) : null;

if (is_array($review_rows)) {
    foreach ($review_rows as $row) {
        $text   = $row['review_text']   ?? '';
        $author = $row['review_author'] ?? '';

        if ($text && $author) {
            $reviews[] = [
                'text'   => $text,
                'author' => $author,
                'title'  => $row['review_title'] ?? '',
                'when'   => $row['review_when'] ?? '',
            ];
        }
    }
}

if (empty($reviews)) {
    $reviews = [
        ['title' => 'Crystal clear on every device', 'when' => 'Dec 2024', 'author' => 'Marcus L. · Stockholm, SE', 'text' => 'Crystal clear picture on all my devices. No buffering, no freezing — just pure streaming. Switched from cable 6 months ago and never looked back.'],
        ['title' => 'The sports coverage is insane', 'when' => 'Jan 2025', 'author' => 'Anna K. · Oslo, NO', 'text' => 'Every Premier League game, Champions League, NBA — all in HD. Setup took 5 minutes. Incredible service.'],
        ['title' => 'The quality blew me away', 'when' => 'Nov 2024', 'author' => 'Thomas B. · Copenhagen, DK', 'text' => 'I was skeptical at first but the quality blew me away. 40,000+ channels and they all work perfectly. Customer support replied within the hour.'],
        ['title' => 'Works on everything at once', 'when' => 'Feb 2025', 'author' => 'Erika V. · Helsinki, FI', 'text' => 'Finally a service that actually works on my Fire Stick AND smart TV at the same time. The 4-device plan is worth every penny.'],
        ['title' => 'Zero downtime, ever', 'when' => 'Jan 2025', 'author' => 'Jonas H. · Gothenburg, SE', 'text' => 'Been with IPTV Anbieter for over a year now. Zero downtime, constant channel list updates. This is how streaming should be done.'],
        ['title' => 'Great value for the price', 'when' => '3 days ago', 'author' => 'Sofia N. · Bergen, NO', 'text' => 'Great value for the price. Support answered my questions within minutes on WhatsApp — no waiting around.'],
        ['title' => 'Replaced four subscriptions', 'when' => '1 week ago', 'author' => 'Henrik D. · Malmö, SE', 'text' => 'I cancelled cable and three streaming apps. One bill, more content, and 4K on everything. Should have done it years ago.'],
    ];
}

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

// Summary strip above the rail. The count is the number of reviews actually
// rendered, so it cannot drift from what is on the page; the score is a
// configured claim rather than a computed one, because these cards carry no
// per-review rating to average.
$review_count = count($reviews);
$review_score = iptv_text('reviews_score', '4,8');
$review_max   = iptv_text('reviews_score_max', '5');
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
                <span class="dv2-review-summary-max">/ <?php echo esc_html($review_max); ?></span>
            </div>
            <div class="dv2-review-summary-meta">
                <span class="dv2-review-stars" aria-hidden="true">★★★★★</span>
                <span class="dv2-review-summary-count">
                    <?php echo esc_html(sprintf(
                        iptv_text('reviews_count_format', 'Basierend auf %d Bewertungen'),
                        $review_count
                    )); ?>
                </span>
            </div>
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
