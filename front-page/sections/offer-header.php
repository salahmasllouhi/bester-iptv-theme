<?php
/**
 * Offer Landing Page Header
 * Same design as site-header but stripped to: Logo | CTA button
 * Nav links removed — keeps focus on one action.
 *
 * Variables expected from template-offer-landing.php:
 *   $offer_cta_text      (string)
 *   $offer_checkout_url  (string)
 */
?>

<header class="site-header offer-header" id="site-header">
    <div class="container nav-container">

        <!-- Logo -->
        <?php iptv_brand_logo(); ?>

        <!-- Right side: CTA -->
        <div class="nav-right">
            <!-- CTA — same pulse style as page buttons -->
            <a href="<?php echo esc_url($offer_checkout_url); ?>" class="nav-btn offer-cta-btn offer-header__cta">
                <?php echo esc_html($offer_cta_text); ?>
            </a>
        </div>

        <!-- Mobile toggle -->
        <button class="mobile-menu-toggle" onclick="toggleOfferMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<!-- Minimal mobile menu: only the CTA -->
<div class="mobile-menu" id="offer-mobile-menu">
    <button class="mobile-menu-close" onclick="toggleOfferMobileMenu()">&times;</button>
    <a href="<?php echo esc_url($offer_checkout_url); ?>" class="nav-btn offer-cta-btn" style="margin-top:1rem;"
        onclick="toggleOfferMobileMenu()">
        <?php echo esc_html($offer_cta_text); ?>
    </a>
</div>

<style>
    /* Strip nav links, keep everything else identical to site-header */
    .offer-header .nav-links {
        display: none !important;
    }

    /* CTA in header — smaller than page CTAs, no full-width pulse override */
    .offer-header__cta {
        font-size: 0.875rem !important;
        padding: 10px 22px !important;
        width: auto !important;
        /* cancel the mobile full-width rule */
        animation: offerCtaPulse 2.5s ease-in-out infinite;
    }
</style>

<script>
    function toggleOfferMobileMenu() {
        var menu = document.getElementById('offer-mobile-menu');
        if (menu) menu.classList.toggle('open');
    }
</script>