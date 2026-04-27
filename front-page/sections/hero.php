<!-- Hero Section (Strearena Style) -->
<div class="bg-aurora"></div>

<section class="hero">
    <div class="hero-content">
        <!-- Badges Container -->
        <div class="hero-badges-container">
            <!-- Rating Badge -->
            <div class="hero-rating">
                <span class="stars">★★★★★</span>
                <span
                    class="rating-text"><?php echo esc_html(iptv_text('hero_badge', '4.8/5 from 53,000+ customers')); ?></span>
            </div>

            <!-- Savings Badge -->
            <div class="hero-savings">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="12" cy="12" r="10" />
                    <path d="M12 6v6l4 2" />
                </svg>
                <?php echo esc_html(iptv_text('hero_savings_badge', 'Save Over $1,500 Annually!')); ?>
            </div>
        </div>

        <h1>
            <?php echo iptv_text('hero_title', 'Nordic IPTV.'); ?><br>
            <span class="gradient-text"><?php echo iptv_text('hero_title_span', 'Premium Streaming Experience.'); ?></span><br>
            <?php echo iptv_text('hero_title_3', 'Any Device, No Limits'); ?>
        </h1>

        <p class="hero-subtitle">
            <?php echo wp_kses_post(iptv_text('hero_subtitle', 'Stop paying for <strong>6+ streaming services</strong>. Get every sport, every show, every PPV event in one place. <strong>No blackouts. No restrictions.</strong>')); ?>
        </p>

        <div class="hero-features">
            <div class="hero-feature">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                35,000+ Channels
            </div>
            <div class="hero-feature">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                PPV $0 Extra
            </div>
            <div class="hero-feature">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                4K Ultra HD
            </div>
        </div>

        <div class="hero-actions">
            <a href="#pricing" class="btn btn-primary">
                <?php echo esc_html(iptv_text('hero_cta', 'Start Streaming Now')); ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>
        </div>
    </div>

    <!-- Stats Cards - 3 Stacked -->
    <div class="hero-stats">
        <div class="stats-stack">
            <!-- Card 1: Live Now (Animated) -->
            <div class="stat-card stat-live">
                <div class="stat-header">
                    <span class="live-dot"></span>
                    <span class="stat-label">LIVE NOW</span>
                </div>
                <div class="stat-value" id="liveCounter"><?php echo esc_html(iptv_text('hero_stat_1_val', '42,537')); ?>
                </div>
                <div class="stat-desc">
                    <?php echo esc_html(iptv_text('hero_stat_1_desc', 'viewers streaming right now')); ?></div>
            </div>

            <!-- Card 2: PPV Events -->
            <div class="stat-card stat-ppv">
                <div class="stat-value price"><?php echo esc_html(iptv_text('hero_stat_2_val', '$0')); ?></div>
                <div class="stat-label-large"><?php echo esc_html(iptv_text('hero_stat_2_label', 'PPV Events')); ?>
                </div>
                <div class="stat-desc">
                    <?php echo esc_html(iptv_text('hero_stat_2_desc', 'UFC, Boxing - All Included')); ?></div>
            </div>

            <!-- Card 3: Live Channels -->
            <div class="stat-card stat-channels">
                <div class="stat-value channels"><?php echo esc_html(iptv_text('hero_stat_3_val', '35,000+')); ?></div>
                <div class="stat-label-large"><?php echo esc_html(iptv_text('hero_stat_3_label', 'Live Channels')); ?>
                </div>
                <div class="stat-desc"><?php echo esc_html(iptv_text('hero_stat_3_desc', 'From 198 countries')); ?>
                </div>
            </div>
        </div>
    </div>
</section>