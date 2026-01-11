<!-- Hero Section -->
<section class="hero">
    <div class="poster-perspective">
        <div class="poster-grid-wrapper">
            <div class="poster-grid">
                <?php
                $movie_images = array(
                    'efc10d_58769540c3af46e4b1b8373d25053dce_mv2.webp',
                    'efc10d_82f6ea18c2494be98c46c59b9e55cf71_mv2.webp',
                    'efc10d_c4763bc9523c4fc89ba4e9f22e5ce52d_mv2.webp',
                    'efc10d_cf5918f2dcf64cf39fcefe135495cc43_mv2.webp',
                    'efc10d_d0631c9b9c3f4be9bd6caef12c4d0694_mv2.webp',
                    'efc10d_e83995fe1b33435a982de93f05c82c29_mv2.webp'
                );
                $image_count = count($movie_images);
                for ($i = 0; $i < 80; $i++):
                    $img = $movie_images[$i % $image_count];
                    ?>
                    <div class="poster-item">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/movies/<?php echo $img; ?>"
                            alt="Movie Poster" loading="lazy">
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

    <div class="hero-overlay"></div>
    <div class="hero-vignette"></div>

    <div class="hero-content">
        <div class="hero-badge"><?php echo esc_html(iptv_text('hero_badge', '✓ Unlimited Entertainment')); ?></div>
        <h1><?php echo esc_html(iptv_text('hero_title', 'Nordic IPTV')); ?>
            <br><span><?php echo esc_html(iptv_text('hero_title_span', 'A Premium Streaming Experience, Seamlessly Delivered')); ?></span>
        </h1>
        <p class="hero-subtitle">
            <strong><?php echo esc_html(iptv_text('hero_tagline', 'Others complicate streaming. We simplify it.')); ?></strong><br>
            <?php echo esc_html(iptv_text('hero_subtitle', 'Nordic IPTV with 35,000+ channels, 150,000+ movies, and zero compromises. No contracts. No hidden fees. Just seamless streaming on every device.')); ?>
        </p>
        <a href="#pricing" class="hero-btn"><?php echo esc_html(iptv_text('hero_cta', 'Get Access Now')); ?></a>
        <p class="hero-price"><?php echo esc_html(iptv_text('hero_price', 'Plans start from $5,83/month.')); ?></p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-num">35K+</div>
                <div class="hero-stat-label"><?php echo esc_html(iptv_text('hero_stat_1', 'Live Channels')); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">150K+</div>
                <div class="hero-stat-label"><?php echo esc_html(iptv_text('hero_stat_2', 'Movies & Series')); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">4K</div>
                <div class="hero-stat-label"><?php echo esc_html(iptv_text('hero_stat_3', 'Ultra HD')); ?></div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-num">24/7</div>
                <div class="hero-stat-label"><?php echo esc_html(iptv_text('hero_stat_4', 'Support')); ?></div>
            </div>
        </div>
    </div>
</section>