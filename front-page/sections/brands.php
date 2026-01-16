<!-- Channels & Posters Section -->
<section class="channels-posters">
    <!-- Centered Header -->
    <div class="channels-posters-header">
        <h2><?php echo esc_html(iptv_text('brands_title', 'Stream Your Favorite Channels')); ?></h2>
        <p><?php echo esc_html(iptv_text('brands_subtitle', 'Access premium content from top networks worldwide with crystal-clear quality')); ?>
        </p>
    </div>

    <!-- 3D Poster Carousel -->
    <div class="posters-carousel">
        <div class="swiper swiper-posters">
            <div class="swiper-wrapper">
                <?php
                $sport_images = array(
                    '40-days-IPTV-Service-USA.png',
                    'Canelo-IPTV-subscription.png',
                    'Club-Ibiza-IPTV-United-States-America.png',
                    'Corners-IPTV-USA-Prime.png',
                    'Days-off-IPTV-USA.png',
                    'Dunk-IPTV-subscription-USA.png',
                    'Gracia-unwrapped-IPTV-USA-Premium.png',
                    'Inside-Sailing-IPTV-America.png',
                    'knockout-chaos-IPTV-USA.png',
                    'More-than-machine-IPTV-Provider-USA.png',
                    'no-days-off-IPTV-Service-USA.png',
                    'Riyadh-season-club-IPTV-USA.png',
                    'Tennis-club-IPTV-USA.png'
                );
                foreach ($sport_images as $img):
                    ?>
                    <div class="swiper-slide">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/sport images/<?php echo $img; ?>"
                            alt="Sports Event" loading="lazy">
                    </div>
                <?php endforeach; ?>
            </div>
            <!-- Navigation -->
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
            <!-- Pagination -->
            <div class="swiper-pagination"></div>
        </div>
    </div>

    <!-- Channel Logo Carousel (Auto-scrolling) -->
    <div class="channel-carousel-wrapper">
        <div class="channel-carousel">
            <?php
            $brand_images = array(
                'FOX.png',
                'brand_item05-150x46-1-1.webp',
                'brand_item06-150x46-1.webp',
                'brand_item08-150x46-1-1.webp',
                'brand_item09-150x46-1-1.webp',
                'brand_item10-150x46-1-1.webp',
                'brand_item11-1.webp',
                'brand_item12-1.webp',
                'brand_item13-150x46-1-1.webp',
                'brand_item14-150x46-1-1.webp',
                'brand_item15-150x46-1-1.webp',
                'brand_item16-150x46-1-1.webp',
                'brand_item17-150x46-1-1.webp',
                'brand_item18-150x46-1-1.webp',
                'brand_item21-150x46-1-1.webp',
                'brand_item22-150x46-1-1.webp'
            );
            // Output twice for seamless loop
            for ($i = 0; $i < 2; $i++):
                foreach ($brand_images as $brand):
                    ?>
                    <div class="channel-logo-item">
                        <img src="<?php echo get_template_directory_uri(); ?>/images/brand/<?php echo $brand; ?>" alt="Channel"
                            loading="lazy">
                    </div>
                    <?php
                endforeach;
            endfor;
            ?>
        </div>
    </div>
</section>

<!-- Swiper.js CDN -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        new Swiper('.swiper-posters', {
            effect: 'coverflow',
            grabCursor: true,
            centeredSlides: true,
            slidesPerView: 'auto',
            loop: true,
            autoplay: {
                delay: 3000,
                disableOnInteraction: false,
            },
            coverflowEffect: {
                rotate: 30,
                stretch: 0,
                depth: 100,
                modifier: 1,
                slideShadows: true,
            },
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    });
</script>