<div class="logos-bar">
    <div class="logos-container">
        <div class="logos-label"><?php echo esc_html(iptv_text('logos_label', 'Access Premium Content From')); ?></div>

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
</div>

<style>
    /* Inline critical styles for logo sizing */
    .logos-bar {
        background: var(--primary-deep);
        padding: 2rem 0;
        margin-top: -2px;
        overflow: hidden;
        /* Hide overflow for marquee */
    }

    .logos-label {
        color: rgba(255, 255, 255, 0.6);
        text-align: center;
        margin-bottom: 1.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }

    .logos-marquee {
        width: 100%;
        overflow: hidden;
        position: relative;
    }

    .logos-track {
        display: flex;
        align-items: center;
        gap: 3rem;
        width: max-content;
    }

    /* Desktop: Center grid (disable animation essentially, or center it) */
    @media (min-width: 769px) {
        .logos-marquee {
            display: flex;
            justify-content: center;
        }

        .logos-track {
            justify-content: center;
            flex-wrap: wrap;
            /* Stack on desktop if needed, or single row */
            width: auto;
            animation: none;
        }

        /* Hide the second set of logos on desktop to avoid duplicates */
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

        /* Ensure all logos are visible for scrolling */
        .logo-item:nth-child(n+9) {
            display: block;
        }
    }

    @keyframes scrollLogos {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .logo-item img {
        height: 28px;
        width: auto;
        opacity: 0.8;
        transition: all 0.3s ease;
        filter: brightness(0) invert(1);
    }

    .logo-item img:hover {
        opacity: 1;
        transform: scale(1.05);
        filter: none;
    }
</style>