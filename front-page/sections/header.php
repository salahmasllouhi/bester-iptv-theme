<!-- Header Section -->
<?php
// Detect current subsite for default currency/flag
$site_slug = '';

// Method 1: Use WordPress Multisite blog path (most reliable)
if (is_multisite() && function_exists('get_blog_details')) {
    $blog_details = get_blog_details();
    if ($blog_details && !empty($blog_details->path)) {
        $site_slug = trim($blog_details->path, '/');
    }
}

// Method 2: Fallback to REQUEST_URI parsing (only if blog path didn't work)
if (empty($site_slug)) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $path_parts = explode('/', trim($request_uri, '/'));
    $first_segment = isset($path_parts[0]) ? $path_parts[0] : '';
    // Only use if it's actually a language code
    // NOTE: Non-Swedish languages temporarily disabled - see Project_dyali.md
    if (in_array($first_segment, array('sv'))) {
        // LANG-DISABLED: no, dk, fi, is - See Project_dyali.md "Language Reactivation Guide" to revert
        // Original: array('sv', 'no', 'dk', 'fi', 'is')
        $site_slug = $first_segment;
    }
}

// Map subsite to currency
// NOTE: Non-Swedish languages temporarily disabled - see Project_dyali.md
$site_currency_map = array(
    'sv' => array('flag' => '🇸🇪', 'code' => 'SEK', 'symbol' => 'kr'),
    // LANG-DISABLED: no - See Project_dyali.md "Language Reactivation Guide" to revert
    // 'no' => array('flag' => '🇳🇴', 'code' => 'NOK', 'symbol' => 'kr'),
    // LANG-DISABLED: dk - See Project_dyali.md "Language Reactivation Guide" to revert
    // 'dk' => array('flag' => '🇩🇰', 'code' => 'DKK', 'symbol' => 'kr'),
    // LANG-DISABLED: fi - See Project_dyali.md "Language Reactivation Guide" to revert
    // 'fi' => array('flag' => '🇫🇮', 'code' => 'EUR', 'symbol' => '€'),
    // LANG-DISABLED: is - See Project_dyali.md "Language Reactivation Guide" to revert
    // 'is' => array('flag' => '🇮🇸', 'code' => 'ISK', 'symbol' => 'kr'),
);

// Get default based on current subsite (main site = USD)
$default_flag = '🇺🇸';
$default_code = 'USD';
if (isset($site_currency_map[$site_slug])) {
    $default_flag = $site_currency_map[$site_slug]['flag'];
    $default_code = $site_currency_map[$site_slug]['code'];
}
?>
<header class="site-header" id="site-header">
    <div class="container nav-container">
        <a href="<?php echo home_url('/'); ?>" class="logo">
            <img src="<?php echo get_template_directory_uri(); ?>/images/logo/light logo 500_150.png" alt="Nordic IPTV"
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
                <a href="<?php echo home_url('/'); ?>">Home</a>
                <a href="<?php echo home_url('/#features'); ?>">Features</a>
                <a href="<?php echo home_url('/#pricing'); ?>">Pricing</a>
                <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
                <a href="<?php echo home_url('/user-guide/'); ?>">User Guide</a>
                <a href="<?php echo home_url('/#contact'); ?>">Contact</a>
                <a href="https://panel.nordictv.io/login">My Account</a>
            </nav>
        <?php endif; ?>
        <div class="nav-right">
            <div class="country-selector" id="countrySelector">
                <button class="country-btn" onclick="toggleCountryDropdown()">
                    <span class="country-flag" id="selectedFlag"><?php echo $default_flag; ?></span>
                    <span class="country-code" id="selectedCode"><?php echo $default_code; ?></span>
                    <svg width="10" height="10" viewBox="0 0 10 10" fill="currentColor">
                        <path d="M1 3L5 7L9 3" stroke="currentColor" stroke-width="1.5" fill="none" />
                    </svg>
                </button>
                <div class="country-dropdown" id="countryDropdown">
                    <div class="country-option" data-currency="usd" data-symbol="$" data-flag="🇺🇸">
                        <span class="country-flag">🇺🇸</span><span>USD</span>
                    </div>
                    <!-- LANG-DISABLED: eur - See Project_dyali.md "Language Reactivation Guide" to revert
                    <div class="country-option" data-currency="eur" data-symbol="€" data-flag="🇫🇮">
                        <span class="country-flag">🇫🇮</span><span>EUR</span>
                    </div>
                    -->
                    <div class="country-option" data-currency="sek" data-symbol="kr" data-flag="🇸🇪">
                        <span class="country-flag">🇸🇪</span><span>SEK</span>
                    </div>
                    <!-- LANG-DISABLED: nok - See Project_dyali.md "Language Reactivation Guide" to revert
                    <div class="country-option" data-currency="nok" data-symbol="kr" data-flag="🇳🇴">
                        <span class="country-flag">🇳🇴</span><span>NOK</span>
                    </div>
                    -->
                    <!-- LANG-DISABLED: dkk - See Project_dyali.md "Language Reactivation Guide" to revert
                    <div class="country-option" data-currency="dkk" data-symbol="kr" data-flag="🇩🇰">
                        <span class="country-flag">🇩🇰</span><span>DKK</span>
                    </div>
                    -->
                    <!-- LANG-DISABLED: isk - See Project_dyali.md "Language Reactivation Guide" to revert
                    <div class="country-option" data-currency="isk" data-symbol="kr" data-flag="🇮🇸">
                        <span class="country-flag">🇮🇸</span><span>ISK</span>
                    </div>
                    -->
                </div>
            </div>
            <a href="<?php echo home_url('/#pricing'); ?>" class="nav-btn nav-btn-primary">Get Access Now</a>
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
        <a href="<?php echo home_url('/'); ?>">Home</a>
        <a href="<?php echo home_url('/#features'); ?>">Features</a>
        <a href="<?php echo home_url('/#pricing'); ?>">Pricing</a>
        <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
        <a href="<?php echo home_url('/user-guide/'); ?>">User Guide</a>
        <a href="<?php echo home_url('/#contact'); ?>">Contact</a>
        <a href="https://panel.nordictv.io/login">My Account</a>
    <?php endif; ?>

    <!-- Language Selector in Mobile Menu -->
    <div class="mobile-language-selector">
        <span class="mobile-language-label">Region / Currency</span>
        <div class="mobile-language-options">
            <button class="mobile-lang-btn" data-currency="usd" onclick="redirectToRegion('usd')">🇺🇸 USD</button>
            <!-- LANG-DISABLED: eur - See Project_dyali.md "Language Reactivation Guide" to revert
            <button class="mobile-lang-btn" data-currency="eur" onclick="redirectToRegion('eur')">🇫🇮 EUR</button>
            -->
            <button class="mobile-lang-btn" data-currency="sek" onclick="redirectToRegion('sek')">🇸🇪 SEK</button>
            <!-- LANG-DISABLED: nok, dkk, isk - See Project_dyali.md "Language Reactivation Guide" to revert
            <button class="mobile-lang-btn" data-currency="nok" onclick="redirectToRegion('nok')">🇳🇴 NOK</button>
            <button class="mobile-lang-btn" data-currency="dkk" onclick="redirectToRegion('dkk')">🇩🇰 DKK</button>
            <button class="mobile-lang-btn" data-currency="isk" onclick="redirectToRegion('isk')">🇮🇸 ISK</button>
            -->
        </div>
    </div>

    <a href="<?php echo home_url('/#pricing'); ?>" class="nav-btn nav-btn-primary" style="margin-top:1rem;"
        onclick="toggleMobileMenu()">Get Access
        Now</a>
</div>