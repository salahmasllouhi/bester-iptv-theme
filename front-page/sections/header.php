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
    if (in_array($first_segment, array('se', 'no', 'dk', 'fi', 'is'))) {
        $site_slug = $first_segment;
    }
}

// Map subsite to currency
$site_currency_map = array(
    'se' => array('flag' => '🇸🇪', 'code' => 'SEK', 'symbol' => 'kr'),
    'no' => array('flag' => '🇳🇴', 'code' => 'NOK', 'symbol' => 'kr'),
    'dk' => array('flag' => '🇩🇰', 'code' => 'DKK', 'symbol' => 'kr'),
    'fi' => array('flag' => '🇫🇮', 'code' => 'EUR', 'symbol' => '€'),
    'is' => array('flag' => '🇮🇸', 'code' => 'ISK', 'symbol' => 'kr'),
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
        <nav class="nav-links">
            <a href="<?php echo home_url('/'); ?>">Home</a>
            <a href="#features">Features</a>
            <a href="#pricing">Pricing</a>
            <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
            <a href="<?php echo home_url('/user-guide/'); ?>">User Guide</a>
            <a href="#contact">Contact</a>
        </nav>
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
                    <div class="country-option" data-currency="eur" data-symbol="€" data-flag="🇫🇮">
                        <span class="country-flag">🇫🇮</span><span>EUR</span>
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
                    <div class="country-option" data-currency="isk" data-symbol="kr" data-flag="🇮🇸">
                        <span class="country-flag">🇮🇸</span><span>ISK</span>
                    </div>
                </div>
            </div>
            <a href="#pricing" class="nav-btn nav-btn-primary">Get Access Now</a>
        </div>
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
    <button class="mobile-menu-close" onclick="toggleMobileMenu()">&times;</button>
    <a href="<?php echo home_url('/'); ?>" onclick="toggleMobileMenu()">Home</a>
    <a href="#features" onclick="toggleMobileMenu()">Features</a>
    <a href="#pricing" onclick="toggleMobileMenu()">Pricing</a>
    <a href="<?php echo home_url('/blog/'); ?>" onclick="toggleMobileMenu()">Blog</a>
    <a href="<?php echo home_url('/user-guide/'); ?>" onclick="toggleMobileMenu()">User Guide</a>
    <a href="#contact" onclick="toggleMobileMenu()">Contact</a>

    <!-- Language Selector in Mobile Menu -->
    <div class="mobile-language-selector">
        <span class="mobile-language-label">Region / Currency</span>
        <div class="mobile-language-options">
            <button class="mobile-lang-btn" data-currency="usd" onclick="redirectToRegion('usd')">🇺🇸 USD</button>
            <button class="mobile-lang-btn" data-currency="eur" onclick="redirectToRegion('eur')">🇫🇮 EUR</button>
            <button class="mobile-lang-btn" data-currency="sek" onclick="redirectToRegion('sek')">🇸🇪 SEK</button>
            <button class="mobile-lang-btn" data-currency="nok" onclick="redirectToRegion('nok')">🇳🇴 NOK</button>
            <button class="mobile-lang-btn" data-currency="dkk" onclick="redirectToRegion('dkk')">🇩🇰 DKK</button>
            <button class="mobile-lang-btn" data-currency="isk" onclick="redirectToRegion('isk')">🇮🇸 ISK</button>
        </div>
    </div>

    <a href="#pricing" class="nav-btn nav-btn-primary" style="margin-top:1rem;" onclick="toggleMobileMenu()">Get Access
        Now</a>
</div>