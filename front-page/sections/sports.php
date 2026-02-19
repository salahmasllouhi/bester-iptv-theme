<section class="sports">
    <div class="sports-container">
        <div class="section-header">
            <div class="section-tag"><?php echo esc_html(iptv_text('sports_tag', 'Sports')); ?></div>
            <h2 class="section-title"><?php echo iptv_text('sports_title', 'Never Miss a'); ?> <span
                    class="gradient-text"><?php echo iptv_text('sports_title_span', 'Game'); ?></span></h2>
            <p class="section-subtitle">
                <?php echo esc_html(iptv_text('sports_desc', 'All your favorite leagues and tournaments, live and on-demand.')); ?>
            </p>
        </div>
        <div class="sports-grid">
            <!-- Sport 1: Soccer -->
            <div class="sport-card animate-on-scroll">
                <div class="sport-icon">⚽</div>
                <div class="sport-name"><?php echo esc_html(iptv_text('sport_1_name', 'Soccer')); ?></div>
                <div class="sport-leagues">
                    <?php echo esc_html(iptv_text('sport_1_subtitle', 'Premier League, Champions League, World Cup')); ?>
                </div>
                <div class="sport-live"><?php echo esc_html(iptv_text('sport_live_text', 'LIVE NOW')); ?></div>
            </div>

            <!-- Sport 2: NBA -->
            <div class="sport-card animate-on-scroll">
                <div class="sport-icon">🏀</div>
                <div class="sport-name"><?php echo esc_html(iptv_text('sport_2_name', 'NBA')); ?></div>
                <div class="sport-leagues">
                    <?php echo esc_html(iptv_text('sport_2_subtitle', 'Regular Season, Playoffs & Finals')); ?></div>
                <div class="sport-live"><?php echo esc_html(iptv_text('sport_live_text', 'LIVE NOW')); ?></div>
            </div>

            <!-- Sport 3: MLB -->
            <div class="sport-card animate-on-scroll">
                <div class="sport-icon">⚾</div>
                <div class="sport-name"><?php echo esc_html(iptv_text('sport_3_name', 'MLB')); ?></div>
                <div class="sport-leagues">
                    <?php echo esc_html(iptv_text('sport_3_subtitle', 'Full Season, World Series')); ?></div>
                <div class="sport-live"><?php echo esc_html(iptv_text('sport_live_text', 'LIVE NOW')); ?></div>
            </div>

            <!-- Sport 4: NFL -->
            <div class="sport-card animate-on-scroll">
                <div class="sport-icon">🏈</div>
                <div class="sport-name"><?php echo esc_html(iptv_text('sport_4_name', 'NFL')); ?></div>
                <div class="sport-leagues">
                    <?php echo esc_html(iptv_text('sport_4_subtitle', 'Regular Season, Super Bowl')); ?></div>
                <div class="sport-live"><?php echo esc_html(iptv_text('sport_live_text', 'LIVE NOW')); ?></div>
            </div>

            <!-- Sport 5: F1 -->
            <div class="sport-card animate-on-scroll">
                <div class="sport-icon">🏎️</div>
                <div class="sport-name"><?php echo esc_html(iptv_text('sport_5_name', 'F1')); ?></div>
                <div class="sport-leagues">
                    <?php echo esc_html(iptv_text('sport_5_subtitle', 'All Grand Prix Races')); ?></div>
                <div class="sport-live"><?php echo esc_html(iptv_text('sport_live_text', 'LIVE NOW')); ?></div>
            </div>

            <!-- Sport 6: UFC/Boxing -->
            <div class="sport-card animate-on-scroll">
                <div class="sport-icon">🥊</div>
                <div class="sport-name"><?php echo esc_html(iptv_text('sport_6_name', 'UFC/Boxing')); ?></div>
                <div class="sport-leagues"><?php echo esc_html(iptv_text('sport_6_subtitle', 'All PPV Events Free')); ?>
                </div>
                <div class="sport-live"><?php echo esc_html(iptv_text('sport_live_text', 'LIVE NOW')); ?></div>
            </div>
        </div>
    </div>
</section>