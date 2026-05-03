<?php
/**
 * Geo-Redirect System for WordPress Multisite
 * 
 * Redirects users from main site homepage to localized sub-sites
 * based on their geolocation (using WooCommerce geolocation).
 * 
 * @package Nordic_IPTV
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Perform geolocation-based redirect on main site homepage
 */
function nordic_iptv_geo_redirect()
{
    // DEBUG MODE: Add ?geo_debug=1 to URL to see what's being detected
    if (isset($_GET['geo_debug']) && $_GET['geo_debug'] === '1') {
        // Prevent caching of debug output
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Pragma: no-cache');

        $debug = array(
            'timestamp' => date('Y-m-d H:i:s'),
            'blog_id' => get_current_blog_id(),
            'is_front_page' => is_front_page(),
            'noredirect_cookie' => isset($_COOKIE['noredirect']) ? $_COOKIE['noredirect'] : 'not set',
            'all_cookies' => $_COOKIE,
            'woocommerce_available' => class_exists('WC_Geolocation'),
            'accept_language' => isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : 'not set',
            'user_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown',
            'cf_ip' => isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : 'not cloudflare',
        );

        // Try WooCommerce geolocation
        if (class_exists('WC_Geolocation')) {
            $geo = WC_Geolocation::geolocate_ip();
            $debug['wc_geo_country'] = isset($geo['country']) ? $geo['country'] : 'empty';
            $debug['wc_geo_full'] = $geo;
        }

        header('Content-Type: application/json');
        echo json_encode($debug, JSON_PRETTY_PRINT);
        exit;
    }

    // Only run on main site (ID 1)
    if (get_current_blog_id() !== 1) {
        return;
    }

    // Only run on homepage
    if (!is_front_page()) {
        return;
    }

    // Tell LiteSpeed Cache to NOT cache this page and vary by noredirect cookie
    // This prevents LiteSpeed from serving cached pages with stale cookie data
    if (!headers_sent()) {
        header('X-LiteSpeed-Cache-Control: no-cache');
        header('X-LiteSpeed-Vary: cookie=noredirect');
    }

    // Don't redirect admin users
    if (is_user_logged_in() && current_user_can('manage_options')) {
        return;
    }

    // Don't redirect if noredirect cookie exists
    if (isset($_COOKIE['noredirect']) && $_COOKIE['noredirect'] === '1') {
        return;
    }

    // Don't redirect bots
    if (nordic_iptv_is_bot()) {
        return;
    }

    // Try WooCommerce geolocation first (most accurate for IP-based)
    $country_code = '';

    if (class_exists('WC_Geolocation')) {
        $geo = WC_Geolocation::geolocate_ip();
        $country_code = isset($geo['country']) ? strtoupper($geo['country']) : '';
    }

    // Fallback: Check Accept-Language header for Swedish browser
    if (empty($country_code)) {
        $accept_lang = isset($_SERVER['HTTP_ACCEPT_LANGUAGE']) ? $_SERVER['HTTP_ACCEPT_LANGUAGE'] : '';

        // Check if Swedish is the primary language
        if (preg_match('/^sv/i', $accept_lang)) {
            $country_code = 'SE'; // Treat Swedish browser as Swedish
        }
    }

    if (empty($country_code)) {
        return;
    }

    // Country to sub-site mapping
    // NOTE: Non-Swedish languages temporarily disabled - see Project_dyali.md "Language Reactivation Guide"
    $redirect_map = array(
        'SE' => '/sv/',  // Sweden
        // LANG-DISABLED: no - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'NO' => '/no/',  // Norway
        // LANG-DISABLED: dk - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'DK' => '/dk/',  // Denmark
        // LANG-DISABLED: fi - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'FI' => '/fi/',  // Finland
        // LANG-DISABLED: is - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'IS' => '/is/',  // Iceland
    );

    // Check if country has a redirect target
    if (!isset($redirect_map[$country_code])) {
        return;
    }

    // Build redirect URL
    $redirect_path = $redirect_map[$country_code];
    $redirect_url = home_url($redirect_path);

    // Perform safe redirect
    wp_safe_redirect($redirect_url, 302);
    exit;
}

// Hook into template_redirect (runs after query is parsed but before template loads)
add_action('template_redirect', 'nordic_iptv_geo_redirect');

/**
 * Check if the current request is from a bot
 * 
 * @return bool True if user agent is a known bot
 */
function nordic_iptv_is_bot()
{
    if (!isset($_SERVER['HTTP_USER_AGENT'])) {
        return false;
    }

    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);

    // Common bot user agents
    $bots = array(
        'googlebot',
        'bingbot',
        'slurp',           // Yahoo
        'duckduckbot',
        'baiduspider',
        'yandexbot',
        'sogou',
        'exabot',
        'facebot',         // Facebook
        'facebookexternalhit',
        'ia_archiver',     // Alexa
        'mj12bot',
        'ahrefsbot',
        'semrushbot',
        'dotbot',
        'rogerbot',
        'screaming frog',
        'gtmetrix',
        'pingdom',
        'uptimerobot',
        'petalbot',
        'applebot',
        'twitterbot',
        'linkedinbot',
        'slackbot',
        'telegrambot',
        'whatsapp',
        'discordbot',
    );

    foreach ($bots as $bot) {
        if (strpos($user_agent, $bot) !== false) {
            return true;
        }
    }

    return false;
}

/**
 * Set noredirect cookie via AJAX or direct call
 */
function nordic_iptv_set_noredirect_cookie()
{
    if (!isset($_COOKIE['noredirect'])) {
        setcookie('noredirect', '1', time() + (30 * DAY_IN_SECONDS), '/', COOKIE_DOMAIN, is_ssl(), true);
    }
}

/**
 * Handle "Switch to Global English" AJAX request
 */
function nordic_iptv_ajax_set_noredirect()
{
    nordic_iptv_set_noredirect_cookie();
    wp_send_json_success(array('message' => 'Cookie set'));
}
add_action('wp_ajax_set_noredirect', 'nordic_iptv_ajax_set_noredirect');
add_action('wp_ajax_nopriv_set_noredirect', 'nordic_iptv_ajax_set_noredirect');

/**
 * Add "Switch to Global English" link to footer (for sub-sites)
 */
function nordic_iptv_global_english_link()
{
    // Only show on sub-sites (not main site)
    if (get_current_blog_id() === 1) {
        return;
    }

    // Get main site URL
    $main_site_url = network_home_url('/');

    ?>
    <div class="global-english-switch"
        style="text-align: center; padding: 1rem; background: #f1f5f9; border-top: 1px solid #e2e8f0;">
        <a href="<?php echo esc_url($main_site_url); ?>?set_global=1"
            onclick="document.cookie='noredirect=1;path=/;max-age=<?php echo 30 * DAY_IN_SECONDS; ?>';"
            style="color: #64748b; font-size: 0.875rem; text-decoration: none;">
            🌐 Switch to Global English
        </a>
    </div>
    <?php
}
add_action('wp_footer', 'nordic_iptv_global_english_link', 100);

/**
 * Handle ?set_global=1 parameter to set cookie on page load
 */
function nordic_iptv_handle_global_switch()
{
    if (isset($_GET['set_global']) && $_GET['set_global'] === '1') {
        nordic_iptv_set_noredirect_cookie();
    }
}
add_action('init', 'nordic_iptv_handle_global_switch');
