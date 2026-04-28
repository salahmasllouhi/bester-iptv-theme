<?php
/**
 * Section: Content Showcase
 * Description: Infinite marquee of content categories/posters
 */
?>
<section class="content-showcase">
    <div class="showcase-container">
        <!-- Text Column -->
        <div class="showcase-content">
            <div class="section-tag"><?php echo esc_html(iptv_text('showcase_tag', 'Unlimited Entertainment')); ?></div>
            <h2 class="section-title"><?php echo iptv_text('showcase_title', 'What\'s on'); ?> <span
                    class="gradient-text"><?php echo iptv_text('showcase_title_span', 'Nordic TV?'); ?></span></h2>
            <p class="section-subtitle" style="margin-left: 0; max-width: 100%;">
                <?php echo esc_html(iptv_text('showcase_subtitle', 'From the latest blockbusters to timeless classics, we have it all. Enjoy seamless streaming of your favorite content.')); ?>
            </p>

            <ul class="showcase-features">
                <li>
                    <span class="check-icon">✓</span>
                    <span><?php echo wp_kses_post(iptv_text('showcase_f1', '150,000+ Movies & Series')); ?></span>
                </li>
                <li>
                    <span class="check-icon">✓</span>
                    <span><?php echo wp_kses_post(iptv_text('showcase_f2', '4K & 8K Ultra HD Quality')); ?></span>
                </li>
                <li>
                    <span class="check-icon">✓</span>
                    <span><?php echo wp_kses_post(iptv_text('showcase_f3', 'Multi-Language Subtitles')); ?></span>
                </li>
                <li>
                    <span class="check-icon">✓</span>
                    <span><?php echo wp_kses_post(iptv_text('showcase_f4', 'Daily Updates')); ?></span>
                </li>
            </ul>

            <a href="#pricing" class="btn btn-primary" style="margin-top: 2rem;">
                <?php echo esc_html(iptv_text('showcase_cta', 'Get Access Now')); ?>
            </a>
        </div>

        <!-- Image Column -->
        <div class="showcase-image">
            <img src="https://nordictv.io/wp-content/uploads/2026/01/content.png" alt="Nordic TV Content Preview"
                loading="lazy">
        </div>
    </div>
</section>

<style>
    /* Content Showcase Styles - Split Layout */
    .content-showcase {
        padding: var(--space-lg) 0;
        background: transparent;
        overflow: visible;
    }

    .showcase-container {
        max-width: 1400px;
        margin: 0 auto;
        background: rgba(240, 244, 250, 1);
        border-radius: var(--radius-xl);
        padding: calc(var(--space-2xl) * 0.7) var(--space-xl);
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-2xl);
        align-items: center;
        overflow: hidden;
    }

    @media (max-width: 1440px) {
        .showcase-container {
            margin: 0 var(--space-md);
        }
    }

    @media (max-width: 768px) {
        .showcase-container {
            margin: 0 var(--space-sm);
            border-radius: var(--radius-lg);
            padding: calc(var(--space-xl) * 0.7) var(--space-md);
        }
    }

    /* Match hero gradient and font */
    .content-showcase .section-title {
        font-family: 'DM Sans', sans-serif;
        font-weight: 800;
        letter-spacing: -0.04em;
        color: var(--text-primary);
    }

    .content-showcase .section-title .gradient-text {
        background: linear-gradient(to right, var(--color-teal), #2A81C2, var(--color-indigo-hover));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .content-showcase .section-subtitle {
        font-family: 'DM Sans', sans-serif;
        font-size: 1.05rem;
        color: var(--text-secondary);
        line-height: 1.7;
    }

    /* Text Side */
    .showcase-content {
        padding-right: var(--space-md);
    }

    .showcase-features {
        list-style: none;
        padding: 0;
        margin: 2rem 0;
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1.5rem;
    }

    .showcase-features li {
        display: flex;
        align-items: center;
        gap: 12px;
        font-size: 1.05rem;
        color: var(--text-secondary);
    }

    .check-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 24px;
        height: 24px;
        background: rgba(0, 212, 170, 0.15);
        color: var(--color-teal);
        border-radius: 50%;
        font-weight: 800;
        font-size: 0.8rem;
        flex-shrink: 0;
    }

    /* Image Side */
    .showcase-image {
        position: relative;
        text-align: center;
        /* Center the image if needed */
    }

    /* Removed .image-wrapper completely */

    .showcase-image img {
        width: 100%;
        height: auto;
        display: block;
        /* mix-blend-mode removed - use natural transparency */
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .showcase-container {
            grid-template-columns: 1fr;
            gap: var(--space-xl);
            text-align: center;
        }

        .showcase-content {
            padding-right: 0;
            order: 1;
            /* Text first */
        }

        .section-subtitle {
            margin: 0 auto !important;
        }

        .showcase-features {
            max-width: 500px;
            margin: 2rem auto;
            text-align: left;
        }

        .showcase-image {
            order: 2;
            padding: 0 var(--space-md);
        }

        .image-wrapper {
            transform: none;
            /* Disable 3D on mobile for simplicity */
        }

        .image-wrapper:hover {
            transform: none;
        }
    }
</style>