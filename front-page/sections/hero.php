<!-- Hero Section -->
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
            <?php echo iptv_text('hero_title', 'Nordic IPTV'); ?><br>
            <span
                class="gradient-text"><?php echo iptv_text('hero_title_span', 'Premium Streaming Experience,'); ?></span><br>
            <?php echo iptv_text('hero_title_3', 'Seamlessly Delivered'); ?>
            <?php $hero_title_4 = iptv_text('hero_title_4', ''); if ($hero_title_4) : ?><br><?php echo $hero_title_4; ?><?php endif; ?>
        </h1>

        <p class="hero-subtitle">
            <?php echo wp_kses_post(iptv_text('hero_subtitle', 'Stop paying for <strong>6+ streaming services</strong>. Get every sport, every show, every PPV event in one place. <strong>No blackouts. No restrictions.</strong>')); ?>
        </p>

        <!-- CTA with glow container -->
        <?php
        $front_page_id = get_option('page_on_front');
        $primary_label   = get_field('hero_primary_cta_label', $front_page_id) ?: 'Get Started';
        $primary_url     = get_field('hero_primary_cta_url', $front_page_id) ?: '#plans';
        $secondary_label = get_field('hero_secondary_cta_label', $front_page_id) ?: 'My Dashboard';
        $secondary_url   = get_field('hero_secondary_cta_url', $front_page_id) ?: 'https://panel.nordictv.io/login';
        ?>
        <div class="hero-cta-wrap" style="gap:1rem;flex-wrap:wrap;">
            <a href="<?php echo esc_url($primary_url); ?>" class="btn btn-primary">
                <?php echo esc_html($primary_label); ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>
            <a href="<?php echo esc_url($secondary_url); ?>" class="btn btn-secondary">
                <?php echo esc_html($secondary_label); ?>
            </a>
        </div>

        <!-- Stats Row -->
        <div class="hero-stats">
            <!-- Card 1: Live Now (Animated) -->
            <div class="stat-card stat-live">
                <div class="stat-value" id="liveCounter"><?php echo esc_html(iptv_text('hero_stat_1_val', '42,537')); ?>
                </div>
                <div class="stat-header">
                    <span class="live-dot"></span>
                    <span class="stat-label">LIVE NOW</span>
                </div>
                <div class="stat-desc"><?php echo esc_html(iptv_text('hero_stat_1_desc', 'active viewers')); ?></div>
            </div>

            <!-- Card 2: PPV Events -->
            <div class="stat-card stat-ppv">
                <div class="stat-value price"><?php echo esc_html(iptv_text('hero_stat_2_val', '$0')); ?></div>
                <div class="stat-label-large"><?php echo esc_html(iptv_text('hero_stat_2_label', 'PPV Events')); ?>
                </div>
                <div class="stat-desc">
                    <?php echo esc_html(iptv_text('hero_stat_2_desc', 'UFC, Boxing - All Included')); ?>
                </div>
            </div>

            <!-- Card 3: Live Channels -->
            <div class="stat-card stat-channels">
                <div class="stat-value channels"><?php echo esc_html(iptv_text('hero_stat_3_val', '35,000+')); ?></div>
                <div class="stat-label-large"><?php echo esc_html(iptv_text('hero_stat_3_label', 'Live Channels')); ?></div>
                <div class="stat-desc"><?php echo esc_html(iptv_text('hero_stat_3_desc', 'From 198 countries')); ?></div>
            </div>

            <!-- Card 4: Movies & Series -->
            <div class="stat-card stat-movies">
                <div class="stat-value movies"><?php echo esc_html(iptv_text('hero_stat_4_val', '150,000+')); ?></div>
                <div class="stat-label-large"><?php echo esc_html(iptv_text('hero_stat_4_label', 'Movies & Series')); ?></div>
                <div class="stat-desc"><?php echo esc_html(iptv_text('hero_stat_4_desc', 'All genres included')); ?></div>
            </div>
        </div>
    </div>
</section>