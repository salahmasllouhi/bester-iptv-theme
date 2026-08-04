<!-- Header Section -->
<?php
// The switcher is a currency switcher — the site is English-only, so it no
// longer navigates anywhere. What it shows on first paint is USD; currency.js
// repaints it from the visitor's stored choice on DOMContentLoaded.
$default_flag = '🇺🇸';
$default_name = 'USD';

$nav_home = trailingslashit(home_url('/'));

// The guide's real slug. home_url('/user-guide/') was a 404.
$nav_guide = function_exists('iptv_page_url')
    ? iptv_page_url('iptv-guide-setup-apps-devices-tips', $nav_home)
    : $nav_home;
?>
<header class="site-header" id="site-header">
    <div class="container nav-container">
        <a href="<?php echo esc_url($nav_home); ?>" class="logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo/dark logo 500_150.png" alt="Nordic IPTV"
                class="logo-img">
        </a>
        <?php if (has_nav_menu('primary')): ?>
            <?php wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_class' => 'nav-links',
                'menu_class' => '',
                'fallback_cb' => false,
            )); ?>
        <?php else: ?>
            <nav class="nav-links">
                <a href="<?php echo esc_url($nav_home); ?>"><?php echo esc_html(iptv_text('nav_link_home', 'Home')); ?></a>
                <a href="<?php echo esc_url($nav_home . '#features'); ?>"><?php echo esc_html(iptv_text('nav_link_features', 'Features')); ?></a>
                <a href="<?php echo esc_url($nav_home . '#pricing'); ?>"><?php echo esc_html(iptv_text('nav_link_pricing', 'Pricing')); ?></a>
                <!-- Blog lives in the footer only. -->
                <a href="<?php echo esc_url($nav_guide); ?>"><?php echo esc_html(iptv_text('nav_link_guide', 'User Guide')); ?></a>
                <a href="<?php echo esc_url($nav_home . '#contact'); ?>"><?php echo esc_html(iptv_text('nav_link_contact', 'Contact')); ?></a>
                <a href="https://panel.nordictv.io/login"><?php echo esc_html(iptv_text('nav_link_account', 'My Account')); ?></a>
            </nav>
        <?php endif; ?>
        <div class="nav-right">
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
            <a href="<?php echo esc_url($nav_home . '#pricing'); ?>" class="nav-btn nav-btn-primary"><?php echo esc_html(iptv_text('nav_cta_label', 'Get Access Now')); ?></a>
        </div>
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
    <button class="mobile-menu-close" onclick="toggleMobileMenu()">&times;</button>

    <?php if (has_nav_menu('primary')): ?>
        <?php wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => '',
            'fallback_cb' => false,
        )); ?>
    <?php else: ?>
        <a href="<?php echo esc_url($nav_home); ?>"><?php echo esc_html(iptv_text('nav_link_home', 'Home')); ?></a>
        <a href="<?php echo esc_url($nav_home . '#features'); ?>"><?php echo esc_html(iptv_text('nav_link_features', 'Features')); ?></a>
        <a href="<?php echo esc_url($nav_home . '#pricing'); ?>"><?php echo esc_html(iptv_text('nav_link_pricing', 'Pricing')); ?></a>
        <!-- Blog lives in the footer only. -->
        <a href="<?php echo esc_url($nav_guide); ?>"><?php echo esc_html(iptv_text('nav_link_guide', 'User Guide')); ?></a>
        <a href="<?php echo esc_url($nav_home . '#contact'); ?>"><?php echo esc_html(iptv_text('nav_link_contact', 'Contact')); ?></a>
        <a href="https://panel.nordictv.io/login"><?php echo esc_html(iptv_text('nav_link_account', 'My Account')); ?></a>
    <?php endif; ?>

    <!-- Language Selector in Mobile Menu -->
    <div class="mobile-language-selector">
        <span class="mobile-language-label"><?php echo esc_html(iptv_text('nav_region_label', 'Currency')); ?></span>
        <div class="mobile-language-options">
            <button class="mobile-lang-btn" data-currency="usd" onclick="setCurrency('usd')">🇺🇸 USD</button>
            <button class="mobile-lang-btn" data-currency="sek" onclick="setCurrency('sek')">🇸🇪 SEK</button>
            <button class="mobile-lang-btn" data-currency="nok" onclick="setCurrency('nok')">🇳🇴 NOK</button>
            <button class="mobile-lang-btn" data-currency="dkk" onclick="setCurrency('dkk')">🇩🇰 DKK</button>
            <button class="mobile-lang-btn" data-currency="eur" onclick="setCurrency('eur')">🇫🇮 EUR</button>
            <button class="mobile-lang-btn" data-currency="isk" onclick="setCurrency('isk')">🇮🇸 ISK</button>
        </div>
    </div>

    <a href="<?php echo esc_url($nav_home . '#pricing'); ?>" class="nav-btn nav-btn-primary" style="margin-top:1rem;"
        onclick="toggleMobileMenu()"><?php echo esc_html(iptv_text('nav_cta_label', 'Get Access Now')); ?></a>
</div>