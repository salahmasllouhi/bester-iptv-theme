<div class="logos-bar">
    <!-- Marquee Wrapper -->
    <div class="logos-marquee">
        <div class="logos-track">
            <?php
            // List of logo files
            $logos = [
                'FOX.png',
                'brand_item05-150x46-1-1.webp',
                'brand_item06-150x46-1.webp',
                'brand_item08-150x46-1-1.webp',
                'brand_item09-150x46-1-1.webp',
                'brand_item11-1.webp',
                'brand_item12-1.webp',
                'brand_item21-150x46-1-1.webp'
            ];

            // Output twice for seamless infinite scroll
            for ($i = 0; $i < 2; $i++) {
                foreach ($logos as $logo) {
                    $url = get_template_directory_uri() . '/images/brand/' . $logo;
                    echo '<div class="logo-item"><img src="' . esc_url($url) . '" alt="Brand Logo" loading="lazy"></div>';
                }
            }
            ?>
        </div>
    </div>
</div>

<style>
    .logos-bar {
        padding: var(--space-lg) 0;
        overflow: hidden;
        width: 100%;
    }

    .logos-marquee {
        width: 100%;
        overflow: hidden;
        position: relative;
        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }

    .logos-track {
        display: flex;
        align-items: center;
        gap: 3rem;
        width: max-content;
    }

    /* Desktop: Center, no animation */
    @media (min-width: 769px) {
        .logos-marquee {
            display: flex;
            justify-content: center;
            mask-image: none;
            -webkit-mask-image: none;
        }

        .logos-track {
            justify-content: center;
            flex-wrap: wrap;
            width: auto;
            animation: none;
        }

        .logo-item:nth-child(n+9) {
            display: none;
        }
    }

    /* Mobile: Infinite Scroll */
    @media (max-width: 768px) {
        .logos-track {
            animation: scrollLogos 20s linear infinite;
            flex-wrap: nowrap;
        }

        .logo-item:nth-child(n+9) {
            display: block;
        }
    }

    @keyframes scrollLogos {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    .logo-item img {
        height: 28px;
        width: auto;
        opacity: 0.6;
        transition: all 0.3s ease;
        filter: grayscale(100%);
    }

    .logo-item img:hover {
        opacity: 1;
        transform: scale(1.05);
        filter: none;
    }
</style>