<?php
// Get Content Settings
$content = get_option('iptv_content', []);
$title = $content['reviews_title'] ?? 'Rated 4.9/5 on Trustpilot';
$subtitle = $content['reviews_subtitle'] ?? 'Based on 2,500+ reviews';
$cta_text = $content['reviews_cta_text'] ?? 'Get Started Now';
$cta_link = $content['reviews_cta_link'] ?? '#pricing';

// Reviews Data
$reviews = [];
for ($i = 1; $i <= 6; $i++) {
    $text = $content["review_{$i}_text"] ?? '';
    $author = $content["review_{$i}_author"] ?? '';

    if ($text && $author) {
        $reviews[] = [
            'text' => $text,
            'author' => $author
        ];
    }
}

// Fallback if no reviews set (shouldn't happen with defaults, but good for safety)
if (empty($reviews)) {
    $reviews = [
        ['text' => "Best IPTV service I've ever used. Crystal clear picture.", 'author' => 'John D.'],
        ['text' => "Amazing content library and great customer support.", 'author' => 'Sarah M.'],
        ['text' => "Finally cut the cord! Saving so much money.", 'author' => 'Mike R.'],
    ];
}
?>

<!-- Reviews Section -->
<section class="reviews">
    <div class="container">

        <!-- Header -->
        <div class="reviews-header">
            <div class="section-tag" style="margin-bottom:1rem;"><?php echo esc_html(iptv_text('reviews_tag', 'Customer Reviews')); ?></div>
            <h2>
                <?php echo esc_html($title); ?>
            </h2>
            <div class="rating">
                <span class="stars">★★★★★</span>
                <span>
                    <?php echo esc_html($subtitle); ?>
                </span>
            </div>
        </div>

        <!-- Carousel Wrapper -->
        <div class="reviews-carousel-wrapper">
            <!-- Fade Overlays -->
            <div class="fade-overlay fade-left"></div>
            <div class="fade-overlay fade-right"></div>

            <!-- Marquee Track -->
            <div class="reviews-track">
                <!-- Original Set -->
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card">
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">"
                            <?php echo esc_html($review['text']); ?>"
                        </p>
                        <div class="review-author">
                            <?php echo esc_html($review['author']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Duplicate Set (For smooth infinite scroll) -->
                <?php foreach ($reviews as $review): ?>
                    <div class="review-card" aria-hidden="true">
                        <div class="review-stars">★★★★★</div>
                        <p class="review-text">"
                            <?php echo esc_html($review['text']); ?>"
                        </p>
                        <div class="review-author">
                            <?php echo esc_html($review['author']); ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- CTA Button -->
        <div class="reviews-cta">
            <a href="<?php echo esc_url($cta_link); ?>" class="button button-primary button-large">
                <?php echo esc_html($cta_text); ?>
            </a>
        </div>

    </div>
</section>