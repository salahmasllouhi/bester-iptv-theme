<!-- Sports Section -->
<section class="ultimate-experience">
    <div class="container">
        <div class="sports-grid" id="sportsCarousel">
            <?php
            // Get content for sports (helper function assumption or inline logic)
            // We need to re-fetch/utilize the global $content or fetch it here
            $all_content = get_option('iptv_content', array());
            $lang = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : 'en';
            $content = isset($all_content[$lang]) ? $all_content[$lang] : (isset($all_content['en']) ? $all_content['en'] : array());

            // Helper to get text
            if (!function_exists('iptv_sport_text')) {
                function iptv_sport_text($key, $default, $content)
                {
                    return isset($content[$key]) && !empty($content[$key]) ? $content[$key] : $default;
                }
            }

            // Construct Sports Array dynamically
            $sports = array();
            for ($i = 1; $i <= 8; $i++) {
                $sports[] = array(
                    'name' => iptv_sport_text("sport_{$i}_name", "Sport $i", $content),
                    'subtitle' => iptv_sport_text("sport_{$i}_subtitle", "Subtitle $i", $content),
                    'features' => array(
                        iptv_sport_text("sport_{$i}_feat_1", "Feature 1", $content),
                        iptv_sport_text("sport_{$i}_feat_2", "Feature 2", $content),
                        iptv_sport_text("sport_{$i}_feat_3", "Feature 3", $content),
                    )
                );
            }

            $live_text = iptv_sport_text('sport_live_text', 'LIVE NOW', $content);

            foreach ($sports as $sport):
                ?>
                <div class="sport-card">
                    <div class="sport-header">
                        <div class="sport-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="sport-name">
                            <h3>
                                <?php echo esc_html($sport['name']); ?>
                            </h3>
                            <span>
                                <?php echo esc_html($sport['subtitle']); ?>
                            </span>
                        </div>
                    </div>
                    <ul class="sport-features">
                        <?php foreach ($sport['features'] as $feature): ?>
                            <li>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <?php echo esc_html($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="live-badge">
                        <div class="live-dot"></div><?php echo esc_html($live_text); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>