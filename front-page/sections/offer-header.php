<?php
/**
 * Offer Landing Page Header
 * Same design as site-header but stripped to: Logo | Currency selector | CTA button
 * Nav links removed — keeps focus on one action.
 *
 * Variables expected from template-offer-landing.php:
 *   $offer_cta_text      (string)
 *   $offer_checkout_url  (string)
 */

// First paint of the currency selector. currency.js repaints it from the
// visitor's stored choice on DOMContentLoaded — see header.php.
$default_flag = '🇺🇸';
$default_name = 'USD';
?>

<header class="site-header offer-header" id="site-header">
    <div class="container nav-container">

        <!-- Logo -->
        <a href="<?php echo home_url('/'); ?>" class="logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo/light logo 500_150.png" alt="Nordic IPTV"
                class="logo-img">
        </a>

        <!-- Right side: currency + CTA -->
        <div class="nav-right">
            <!-- Language selector (same markup as header.php so currency.js works) -->
            <div class="country-selector" id="countrySelector">
                <button class="country-btn" onclick="toggleCountryDropdown()">
                    <span class="country-flag" id="selectedFlag"><?php echo $default_flag; ?></span>
                    <span class="country-code" id="selectedCode"><?php echo $default_name; ?></span>
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor">
                        <path d="M1 3L5 7L9 3" stroke="currentColor" stroke-width="1.5" fill="none" />
                    </svg>
                </button>
                <div class="country-dropdown" id="countryDropdown">
                    <div class="country-option" data-currency="usd" data-symbol="$" data-flag="🇺🇸">
                        <span class="country-flag">🇺🇸</span><span>USD</span>
                    </div>
                    <div class="country-option" data-currency="sek" data-symbol="kr" data-flag="🇸🇪">
                        <span class="country-flag">🇸🇪</span><span>SEK</span>
                    </div>
                    <div class="country-option" data-currency="nok" data-symbol="kr" data-flag="🇳🇴">
                        <span class="country-flag">🇳🇴</span><span>NOK</span>
                    </div>
                    <div class="country-option" data-currency="dkk" data-symbol="kr" data-flag="🇩🇰">
                        <span class="country-flag">🇩🇰</span><span>DKK</span>
                    </div>
                    <div class="country-option" data-currency="eur" data-symbol="€" data-flag="🇫🇮">
                        <span class="country-flag">🇫🇮</span><span>EUR</span>
                    </div>
                    <div class="country-option" data-currency="isk" data-symbol="kr" data-flag="🇮🇸">
                        <span class="country-flag">🇮🇸</span><span>ISK</span>
                    </div>
                </div>
            </div>

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

<!-- Minimal mobile menu: only currency + CTA -->
<div class="mobile-menu" id="offer-mobile-menu">
    <button class="mobile-menu-close" onclick="toggleOfferMobileMenu()">&times;</button>
    <div class="mobile-language-selector">
        <span class="mobile-language-label">Currency</span>
        <div class="mobile-language-options">
            <button class="mobile-lang-btn" data-currency="usd" onclick="setCurrency('usd')">🇺🇸 USD</button>
            <button class="mobile-lang-btn" data-currency="sek" onclick="setCurrency('sek')">🇸🇪 SEK</button>
            <button class="mobile-lang-btn" data-currency="nok" onclick="setCurrency('nok')">🇳🇴 NOK</button>
            <button class="mobile-lang-btn" data-currency="dkk" onclick="setCurrency('dkk')">🇩🇰 DKK</button>
            <button class="mobile-lang-btn" data-currency="eur" onclick="setCurrency('eur')">🇫🇮 EUR</button>
            <button class="mobile-lang-btn" data-currency="isk" onclick="setCurrency('isk')">🇮🇸 ISK</button>
        </div>
    </div>
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