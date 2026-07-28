<?php
/**
 * Section: Reviews (Design v2)
 * Score card plus a grid of customer reviews.
 */

$title    = iptv_text('reviews_title', 'What our customers actually say');
$subtitle = iptv_text('reviews_subtitle', 'Join thousands of cord-cutters across Scandinavia who\'ve switched to NordicTV.');

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
        ['title' => 'Zero downtime, ever', 'when' => 'Jan 2025', 'author' => 'Jonas H. · Gothenburg, SE', 'text' => 'Been with NordicTV for over a year now. Zero downtime, constant channel list updates. This is how streaming should be done.'],
        ['title' => 'Great value for the price', 'when' => '3 days ago', 'author' => 'Sofia N. · Bergen, NO', 'text' => 'Great value for the price. Support answered my questions within minutes on WhatsApp — no waiting around.'],
        ['title' => 'Replaced four subscriptions', 'when' => '1 week ago', 'author' => 'Henrik D. · Malmö, SE', 'text' => 'I cancelled cable and three streaming apps. One bill, more content, and 4K on everything. Should have done it years ago.'],
    ];
}

/**
 * The reviews run as two infinite marquee rows. Each row needs enough cards to
 * overflow a wide viewport before it can loop seamlessly, so the row's slice is
 * repeated until it holds at least $row_min cards. The track then prints that
 * set twice and the keyframes translate it by exactly -50%, which lands the
 * clone where the original started.
 */
// 8 cards is ~3000px of track, so even an ultrawide viewport never sees the
// end of a set before the clone has taken over.
$row_min = 8;
$rows    = [];

if (!empty($reviews)) {
    $half = (int) ceil(count($reviews) / 2);
    $rows = [array_slice($reviews, 0, $half), array_slice($reviews, $half)];

    foreach ($rows as $i => $row) {
        // A single review leaves the second slice empty; fall back to the full
        // set so both rows still have something to scroll.
        if (empty($row)) {
            $row = $reviews;
            $rows[$i] = $reviews;
        }
        while (count($rows[$i]) < $row_min) {
            $rows[$i] = array_merge($rows[$i], $row);
        }
    }
}

/**
 * Renders one review card. $clone marks the duplicated half of a track, which
 * is hidden from assistive tech so each review is only announced once.
 */
$render_review = function ($review, $clone = false) {
    ?>
    <div class="dv2-review-card"<?php echo $clone ? ' aria-hidden="true"' : ''; ?>>
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
?>
<section class="reviews dv2-section">
    <div class="container">
        <div class="dv2-section-head">
            <h2><?php echo esc_html($title); ?></h2>
            <p><?php echo esc_html($subtitle); ?></p>
        </div>

    </div>

    <div class="dv2-review-marquee">
        <?php foreach ($rows as $i => $row) : ?>
            <div class="dv2-review-row dv2-review-row--<?php echo $i === 0 ? 'rtl' : 'ltr'; ?>">
                <div class="dv2-review-track">
                    <?php
                    foreach ($row as $review) {
                        $render_review($review, false);
                    }
                    // Second pass is the seam that makes the loop look endless.
                    foreach ($row as $review) {
                        $render_review($review, true);
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
