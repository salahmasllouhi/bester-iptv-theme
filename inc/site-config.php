<?php
/**
 * Site Config – ACF Options Page
 *
 * Holds the values that are configuration rather than copy: checkout URLs and the
 * numeric defaults the pricing configurator boots with. These used to be routed
 * through iptv_text(), which meant a URL and the number "12" were being handed to
 * a translation function.
 *
 * Read with: iptv_config('key', 'fallback')
 */

if (!defined('ABSPATH')) {
    exit;
}

add_action('acf/init', function () {
    if (!function_exists('acf_add_options_page')) {
        return;
    }

    acf_add_options_page(array(
        'page_title'  => 'IPTV Anbieter Site Config',
        'menu_title'  => 'Site Config',
        'menu_slug'   => 'nordictv-site-config',
        'parent_slug' => 'options-general.php',
        'capability'  => 'manage_options',
        'redirect'    => false,
        'autoload'    => true,
    ));
});

if (!function_exists('iptv_site_currency')) {
    /**
     * The one currency this site prices in.
     *
     * There used to be a switcher and a currency per language subsite. Both are
     * gone, so every price — server-rendered and JS-rendered alike — resolves
     * through here. The stored price table still carries a column per currency,
     * so changing this value is enough to reprice the whole site.
     *
     * @return string Lowercase key into the price table: usd, eur, sek, nok, dkk or isk.
     */
    function iptv_site_currency()
    {
        $currency = strtolower((string) apply_filters('iptv_site_currency', 'eur'));

        return isset(iptv_currency_formats()[$currency]) ? $currency : 'eur';
    }
}

if (!function_exists('iptv_currency_formats')) {
    /**
     * Symbol, placement and decimals per currency.
     *
     * Mirrors currencyData in front-page/js/currency.js — the two have to agree
     * or the pre-JS paint and the repaint disagree by a symbol.
     *
     * @return array<string,array{symbol:string,before:bool,decimals:int}>
     */
    function iptv_currency_formats()
    {
        return array(
            'usd' => array('symbol' => '$',  'before' => true,  'decimals' => 2),
            'eur' => array('symbol' => '€',  'before' => true,  'decimals' => 2),
            'sek' => array('symbol' => 'kr', 'before' => false, 'decimals' => 0),
            'nok' => array('symbol' => 'kr', 'before' => false, 'decimals' => 0),
            'dkk' => array('symbol' => 'kr', 'before' => false, 'decimals' => 0),
            'isk' => array('symbol' => 'kr', 'before' => false, 'decimals' => 0),
        );
    }
}

if (!function_exists('iptv_price')) {
    /**
     * Format an amount in the site currency.
     *
     * @param float|string $amount
     * @param bool         $round Force whole units regardless of the currency.
     * @return string
     */
    function iptv_price($amount, $round = false)
    {
        $formats = iptv_currency_formats();
        $format  = $formats[iptv_site_currency()];

        $decimals = $round ? 0 : $format['decimals'];
        $value    = number_format((float) $amount, $decimals, '.', '');

        return $format['before']
            ? $format['symbol'] . $value
            : $value . ' ' . $format['symbol'];
    }
}

if (!function_exists('iptv_config')) {
    /**
     * Read a Site Config value, falling back to the theme default.
     *
     * Unlike iptv_text() these values are configuration, not copy.
     *
     * @param string $key      Field name on the Site Config options page.
     * @param mixed  $fallback Returned when the field is empty or ACF is inactive.
     * @return mixed
     */
    function iptv_config($key, $fallback = '')
    {
        if (!function_exists('get_field')) {
            return $fallback;
        }

        $value = get_field($key, 'option');

        return ($value === null || $value === '') ? $fallback : $value;
    }
}
