<?php
/**
 * Section: Reviews (Design v2)
 * Score card plus a grid of customer reviews.
 */

$content  = get_option('iptv_content', []);
$title    = $content['reviews_title']    ?? 'What our customers actually say';
$subtitle = $content['reviews_subtitle'] ?? 'Join thousands of cord-cutters across Scandinavia who\'ve switched to NordicTV.';

// Reviews come from the content settings screen; title and date are optional.
$reviews = [];
for ($i = 1; $i <= 7; $i++) {
    $text = $content["review_{$i}_text"] ?? '';
    $author = $content["review_{$i}_author"] ?? '';

    if ($text && $author) {
        $reviews[] = [
            'text'   => $text,
            'author' => $author,
            'title'  => $content["review_{$i}_title"] ?? '',
            'when'   => $content["review_{$i}_when"] ?? '',
        ];
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

$score_rows = [
    1 => 'Streaming Quality',
    2 => 'Sports Coverage',
    3 => 'Support',
];
?>
<section class="reviews dv2-section">
    <div class="container">
        <div class="dv2-section-head">
            <h2><?php echo esc_html($title); ?></h2>
            <p><?php echo esc_html($subtitle); ?></p>
        </div>

        <div class="dv2-reviews-grid">
            <div class="dv2-score-card">
                <div class="dv2-score-value">
                    <?php echo esc_html(iptv_text('reviews_score', '4.8')); ?>
                    <small><?php echo esc_html(iptv_text('reviews_score_basis', 'Based on 2,500+ Reviews')); ?></small>
                </div>
                <div class="dv2-score-brand">★ Trustpilot &nbsp; ★★★★★</div>
                <?php foreach ($score_rows as $n => $label) : ?>
                    <div class="dv2-score-row">
                        <?php echo esc_html(iptv_text("reviews_score_row_{$n}", $label)); ?>
                        <span aria-hidden="true">★★★★★</span>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php foreach ($reviews as $review) : ?>
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
            <?php endforeach; ?>
        </div>
    </div>
</section>
