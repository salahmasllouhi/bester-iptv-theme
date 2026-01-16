<?php
/**
 * Multi-Currency Pricing Settings (USD as Single Source of Truth)
 * 
 * USD prices are manually set, all other currencies are auto-calculated
 * using conversion rates with psychological pricing rules.
 */

if (!defined('ABSPATH')) {
    exit;
}

class IPTV_Currency_Settings
{
    // Currencies (USD is base, others are calculated)
    private $currencies = array(
        'usd' => array('name' => 'US Dollar', 'symbol' => '$', 'flag' => '🇺🇸', 'code' => 'USD', 'decimals' => true),
        'eur' => array('name' => 'Euro (Finland)', 'symbol' => '€', 'flag' => '🇫🇮', 'code' => 'EUR', 'decimals' => true),
        'sek' => array('name' => 'Swedish Krona', 'symbol' => 'kr', 'flag' => '🇸🇪', 'code' => 'SEK', 'decimals' => false),
        'nok' => array('name' => 'Norwegian Krone', 'symbol' => 'kr', 'flag' => '🇳🇴', 'code' => 'NOK', 'decimals' => false),
        'dkk' => array('name' => 'Danish Krone', 'symbol' => 'kr', 'flag' => '🇩🇰', 'code' => 'DKK', 'decimals' => false),
        'isk' => array('name' => 'Icelandic Króna', 'symbol' => 'kr', 'flag' => '🇮🇸', 'code' => 'ISK', 'decimals' => false),
    );

    private $durations = array(
        '1_month' => '1 Month',
        '3_months' => '3 Months',
        '6_months' => '6 Months',
        '12_months' => '12 Months',
    );

    private $devices = array(
        '1_device' => '1 Device',
        '2_devices' => '2 Devices',
        '3_devices' => '3 Devices',
        '4_devices' => '4 Devices',
    );

    // Default conversion rates (USD to X)
    private $default_rates = array(
        'eur' => 0.92,
        'sek' => 10.5,
        'nok' => 10.8,
        'dkk' => 6.9,
        'isk' => 138,
    );

    // Default USD prices (prefilled)
    private $default_usd = array(
        '1_month' => array('1_device' => 16.99, '2_devices' => 23.99, '3_devices' => 31.99, '4_devices' => 38.99),
        '3_months' => array('1_device' => 29.99, '2_devices' => 46.99, '3_devices' => 53.99, '4_devices' => 88.99),
        '6_months' => array('1_device' => 40.99, '2_devices' => 75.99, '3_devices' => 116.99, '4_devices' => 151.99),
        '12_months' => array('1_device' => 69.99, '2_devices' => 128.99, '3_devices' => 174.99, '4_devices' => 221.99),
    );

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Singleton Instance
     */
    public static function instance()
    {
        static $instance = null;
        if (is_null($instance)) {
            $instance = new self();
        }
        return $instance;
    }

    public function add_admin_menu()
    {
        add_options_page(
            'Pricing & Currencies',
            'Pricing & Currencies',
            'manage_options',
            'iptv-currency-settings',
            array($this, 'render_settings_page')
        );
    }

    public function register_settings()
    {
        register_setting('iptv_currency_settings', 'iptv_usd_prices');
        register_setting('iptv_currency_settings', 'iptv_conversion_rates');
    }

    public static function apply_rounding($price, $currency)
    {
        if ($currency === 'usd' || $currency === 'eur') {
            return ceil($price) - 0.01;
        } else {
            return ceil($price / 10) * 10 - 1;
        }
    }

    public static function calculate_all_prices()
    {
        $instance = self::instance();
        $rates = get_option('iptv_conversion_rates', array());

        foreach ($instance->default_rates as $cur => $rate) {
            if (empty($rates[$cur]))
                $rates[$cur] = $rate;
        }

        $all_prices = array();

        // 1. Core Durations (Variable Products)
        foreach ($instance->durations as $dur_key => $dur_label) {
            $all_prices[$dur_key] = array();

            // Try to fetch product by SKU
            $product_id = function_exists('wc_get_product_id_by_sku') ? wc_get_product_id_by_sku($dur_key) : 0;
            $product = $product_id ? wc_get_product($product_id) : null;

            foreach ($instance->devices as $dev_key => $dev_label) {
                $all_prices[$dur_key][$dev_key] = array();

                $usd_price = 0;

                // Attempt to retrieve price from actual product variation
                if ($product && $product->is_type('variable')) {
                    // Extract device count from key (1_device -> 1)
                    $dev_count = intval(explode('_', $dev_key)[0]);

                    // Allow string matching for attribute
                    $children = $product->get_children();
                    if ($children) {
                        foreach ($children as $child_id) {
                            $variation = wc_get_product($child_id);
                            $attributes = $variation->get_attributes();
                            $attr_val = isset($attributes['pa_devices']) ? $attributes['pa_devices'] : '';

                            // Check if variation matches device count
                            if ($attr_val == $dev_count) {
                                $usd_price = floatval($variation->get_regular_price());
                                break;
                            }
                        }
                    }
                }

                // Fallback to default if product not found/setup
                if ($usd_price <= 0) {
                    $usd_price = isset($instance->default_usd[$dur_key][$dev_key]) ? $instance->default_usd[$dur_key][$dev_key] : 0;
                }

                $all_prices[$dur_key][$dev_key]['usd'] = number_format($usd_price, 2, '.', '');

                // Calculate other currencies
                foreach ($instance->currencies as $cur_key => $currency) {
                    if ($cur_key === 'usd')
                        continue;
                    $rate = floatval($rates[$cur_key]);
                    $converted = $usd_price * $rate;
                    $rounded = self::apply_rounding($converted, $cur_key);

                    if ($cur_key === 'eur') {
                        $all_prices[$dur_key][$dev_key][$cur_key] = number_format($rounded, 2, '.', '');
                    } else {
                        $all_prices[$dur_key][$dev_key][$cur_key] = strval(intval($rounded));
                    }
                }
            }
        }

        // 2. Trial Product (Simple)
        $trial_sku = 'trial_24h';
        $trial_id = function_exists('wc_get_product_id_by_sku') ? wc_get_product_id_by_sku($trial_sku) : 0;
        $trial_prod = $trial_id ? wc_get_product($trial_id) : null;

        // Use regular price if set, or sale price if active? 
        // User said: "edit the price from the bulk edit". Bulk edit updates regular and sale.
        // Usually Trial is "Free" or "Cheap". We usually display the "Sale Price" if exists, or just Price.
        // Let's grab the active price.
        $trial_price_usd = $trial_prod ? floatval($trial_prod->get_price()) : 0;

        $all_prices['trial_24h'] = array('usd' => number_format($trial_price_usd, 2, '.', ''));

        foreach ($instance->currencies as $cur_key => $currency) {
            if ($cur_key === 'usd')
                continue;
            $rate = floatval($rates[$cur_key]);
            // Don't apply rounding to 0 or very small trial prices generally, but for consistency we apply it if > 0
            if ($trial_price_usd > 0) {
                $converted = $trial_price_usd * $rate;
                $rounded = self::apply_rounding($converted, $cur_key);
                if ($cur_key === 'eur') {
                    $all_prices['trial_24h'][$cur_key] = number_format($rounded, 2, '.', '');
                } else {
                    $all_prices['trial_24h'][$cur_key] = strval(intval($rounded));
                }
            } else {
                $all_prices['trial_24h'][$cur_key] = '0';
            }
        }

        return $all_prices;
    }

    public function render_settings_page()
    {
        $usd_prices = get_option('iptv_usd_prices', array());
        $rates = get_option('iptv_conversion_rates', array());

        foreach ($this->default_rates as $cur => $rate) {
            if (empty($rates[$cur]))
                $rates[$cur] = $rate;
        }

        // Calculate all prices for display
        $all_prices = self::calculate_all_prices();
        ?>
        <div class="wrap">
            <h1>💰 Pricing & Currencies</h1>
            <p><strong>USD Prices are now managed in the Bulk Editor or Products menu.</strong></p>
            <p>Here you can set conversion rates. All other currencies auto-calculate from the USD base price set on the
                products.</p>
            <a href="<?php echo admin_url('admin.php?page=iptv-bulk-editor'); ?>" class="button button-primary"
                style="margin-bottom:20px;">Go to Bulk Editor</a>

            <form method="post" action="options.php">
                <?php settings_fields('iptv_currency_settings'); ?>

                <style>
                    .settings-section {
                        background: #fff;
                        padding: 20px;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        margin-bottom: 20px;
                    }

                    .settings-section h2 {
                        margin-top: 0;
                        border-bottom: 2px solid #2271b1;
                        padding-bottom: 10px;
                    }

                    .rate-grid {
                        display: grid;
                        grid-template-columns: repeat(5, 1fr);
                        gap: 15px;
                        margin-bottom: 20px;
                    }

                    .rate-item {
                        background: #f8f9fa;
                        padding: 15px;
                        border-radius: 8px;
                        text-align: center;
                    }

                    .rate-item label {
                        display: block;
                        font-weight: 600;
                        margin-bottom: 8px;
                    }

                    .rate-item input {
                        width: 80px;
                        text-align: center;
                        padding: 8px;
                        font-size: 14px;
                    }

                    .price-table {
                        border-collapse: collapse;
                        width: 100%;
                        margin-bottom: 20px;
                    }

                    .price-table th,
                    .price-table td {
                        border: 1px solid #ddd;
                        padding: 10px;
                        text-align: center;
                    }

                    .price-table th {
                        background: #2271b1;
                        color: #fff;
                    }

                    .price-table .duration {
                        background: #f0f0f1;
                        font-weight: 600;
                        text-align: left;
                        padding-left: 15px;
                    }

                    .price-table input {
                        width: 70px;
                        text-align: center;
                        padding: 6px;
                        font-weight: 600;
                    }

                    .currency-tabs {
                        display: flex;
                        flex-wrap: wrap;
                        gap: 5px;
                        margin-bottom: 0;
                    }

                    .currency-tab {
                        padding: 10px 15px;
                        background: #f0f0f1;
                        border: 1px solid #ddd;
                        cursor: pointer;
                        border-radius: 5px 5px 0 0;
                        border-bottom: none;
                    }

                    .currency-tab.active {
                        background: #2271b1;
                        color: #fff;
                    }

                    .currency-panel {
                        display: none;
                        background: #fff;
                        border: 1px solid #ddd;
                        padding: 15px;
                    }

                    .currency-panel.active {
                        display: block;
                    }

                    .calculated-table td {
                        background: #e8f5e9 !important;
                    }

                    .calculated-table .price-cell {
                        font-weight: 700;
                        color: #2e7d32;
                    }
                </style>

                <!-- Conversion Rates -->
                <div class="settings-section">
                    <h2>📊 Conversion Rates (1 USD = X)</h2>
                    <div class="rate-grid">
                        <?php foreach ($this->currencies as $cur_key => $currency):
                            if ($cur_key === 'usd')
                                continue;
                            ?>
                            <div class="rate-item">
                                <label><?php echo $currency['flag'] . ' ' . $currency['code']; ?></label>
                                <input type="number" step="0.01" name="iptv_conversion_rates[<?php echo $cur_key; ?>]"
                                    value="<?php echo esc_attr($rates[$cur_key]); ?>" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- USD Prices (Managed via Bulk Editor) -->
                <div class="settings-section">
                    <h2>🇺🇸 USD Prices</h2>
                    <p>Price management has been moved to the <a
                            href="<?php echo admin_url('admin.php?page=iptv-bulk-editor'); ?>">Bulk Editor</a>. Prices set there
                        are automatically used as the base for conversions below.</p>
                </div>

                <!-- All Calculated Prices (Read-Only View) -->
                <div class="settings-section">
                    <h2>💱 Calculated Prices (All Currencies)</h2>
                    <p>These are auto-calculated from USD using your conversion rates. <em>Save to update.</em></p>

                    <div class="currency-tabs">
                        <?php $first = true;
                        foreach ($this->currencies as $cur_key => $currency): ?>
                            <div class="currency-tab <?php echo $first ? 'active' : ''; ?>"
                                onclick="showCurrencyTab('<?php echo $cur_key; ?>')">
                                <?php echo $currency['flag'] . ' ' . $currency['code']; ?>
                            </div>
                            <?php $first = false; endforeach; ?>
                    </div>

                    <?php $first = true;
                    foreach ($this->currencies as $cur_key => $currency): ?>
                        <div id="panel-<?php echo $cur_key; ?>"
                            class="currency-panel calculated-table <?php echo $first ? 'active' : ''; ?>">
                            <table class="price-table">
                                <thead>
                                    <tr>
                                        <th>Duration</th>
                                        <?php foreach ($this->devices as $dev_label): ?>
                                            <th><?php echo $dev_label; ?></th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <!-- Trial Row -->
                                    <tr>
                                        <td class="duration">24h Trial (Risk Free)</td>
                                        <td class="price-cell" onclick="alert('Trial is single device only')">
                                            <?php
                                            $price = $all_prices['trial_24h'][$cur_key];
                                            $symbol = $currency['symbol'];
                                            echo $currency['decimals'] ? $symbol . $price : $price . ' ' . $symbol;

                                            // Debug/Info: Show if purely based on Sale Price
                                            if ($cur_key === 'usd' && $trial_prod) {
                                                $reg = $trial_prod->get_regular_price();
                                                $active = $trial_prod->get_price();
                                                if (floatval($active) == 0 && floatval($reg) > 0) {
                                                    echo '<br><span style="font-size:10px; color:red; font-weight:normal;">(Sale Price: $0.00 active)</span>';
                                                }
                                            }
                                            ?>
                                        </td>
                                        <td colspan="3" style="color:#999;font-size:12px;">(Single Device)</td>
                                    </tr>

                                    <!-- Regular Rows -->
                                    <?php foreach ($this->durations as $dur_key => $dur_label): ?>
                                        <tr>
                                            <td class="duration"><?php echo $dur_label; ?></td>
                                            <?php foreach ($this->devices as $dev_key => $dev_label):
                                                $price = $all_prices[$dur_key][$dev_key][$cur_key];
                                                $symbol = $currency['symbol'];
                                                $display = $currency['decimals'] ? $symbol . $price : $price . ' ' . $symbol;
                                                ?>
                                                <td class="price-cell"><?php echo $display; ?></td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php $first = false; endforeach; ?>

                    <script>
                        function showCurrencyTab(cur) {
                            document.querySelectorAll('.currency-tab').forEach(t => t.classList.remove('active'));
                            document.querySelectorAll('.currency-panel').forEach(p => p.classList.remove('active'));
                            document.getElementById('panel-' + cur).classList.add('active');
                            event.target.classList.add('active');
                        }
                    </script>
                </div>

                <p>
                    <?php submit_button('Save All Prices', 'primary', 'submit', false); ?>
                </p>
            </form>
        </div>
        <?php
    }

    public static function get_prices()
    {
        return self::calculate_all_prices();
    }
    public static function get_currencies()
    {
        return self::instance()->currencies;
    }
    public static function get_durations()
    {
        return self::instance()->durations;
    }
    public static function get_devices()
    {
        return self::instance()->devices;
    }
}

// Initialize
IPTV_Currency_Settings::instance();
