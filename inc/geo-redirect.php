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
    // Only run on main site (ID 1)
    if (get_current_blog_id() !== 1) {
        return;
    }

    // Only run on homepage
    if (!is_front_page()) {
        return;
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

    // Check if WooCommerce geolocation is available
    if (!class_exists('WC_Geolocation')) {
        return;
    }

    // Get geolocation data
    $geo = WC_Geolocation::geolocate_ip();
    $country_code = isset($geo['country']) ? strtoupper($geo['country']) : '';

    if (empty($country_code)) {
        return;
    }

    // Country to sub-site mapping
    // NOTE: Non-Swedish languages temporarily disabled - see Project_dyali.md "Language Reactivation Guide"
    $redirect_map = array(
        'SE' => '/se/',  // Sweden
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
