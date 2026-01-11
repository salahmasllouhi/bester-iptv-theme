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
        $instance = new self();
        $usd_prices = get_option('iptv_usd_prices', array());
        $rates = get_option('iptv_conversion_rates', array());
        
        foreach ($instance->default_rates as $cur => $rate) {
            if (empty($rates[$cur])) $rates[$cur] = $rate;
        }
        
        $all_prices = array();
        foreach ($instance->durations as $dur_key => $dur_label) {
            $all_prices[$dur_key] = array();
            foreach ($instance->devices as $dev_key => $dev_label) {
                $all_prices[$dur_key][$dev_key] = array();
                
                // Get USD price (saved or default)
                $usd_price = (isset($usd_prices[$dur_key][$dev_key]) && $usd_prices[$dur_key][$dev_key] !== '') 
                    ? floatval($usd_prices[$dur_key][$dev_key]) 
                    : $instance->default_usd[$dur_key][$dev_key];
                $all_prices[$dur_key][$dev_key]['usd'] = number_format($usd_price, 2, '.', '');
                
                // Calculate other currencies
                foreach ($instance->currencies as $cur_key => $currency) {
                    if ($cur_key === 'usd') continue;
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
        return $all_prices;
    }

    public function render_settings_page()
    {
        $usd_prices = get_option('iptv_usd_prices', array());
        $rates = get_option('iptv_conversion_rates', array());
        
        foreach ($this->default_rates as $cur => $rate) {
            if (empty($rates[$cur])) $rates[$cur] = $rate;
        }
        
        // Calculate all prices for display
        $all_prices = self::calculate_all_prices();
        ?>
        <div class="wrap">
            <h1>💰 Pricing & Currencies</h1>
            <p><strong>USD is the single source of truth.</strong> Fill USD prices and set conversion rates. All other currencies auto-calculate with psychological pricing (.99 or ending in 9).</p>

            <form method="post" action="options.php">
                <?php settings_fields('iptv_currency_settings'); ?>

                <style>
                    .settings-section { background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 20px; }
                    .settings-section h2 { margin-top: 0; border-bottom: 2px solid #2271b1; padding-bottom: 10px; }
                    .rate-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 20px; }
                    .rate-item { background: #f8f9fa; padding: 15px; border-radius: 8px; text-align: center; }
                    .rate-item label { display: block; font-weight: 600; margin-bottom: 8px; }
                    .rate-item input { width: 80px; text-align: center; padding: 8px; font-size: 14px; }
                    .price-table { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
                    .price-table th, .price-table td { border: 1px solid #ddd; padding: 10px; text-align: center; }
                    .price-table th { background: #2271b1; color: #fff; }
                    .price-table .duration { background: #f0f0f1; font-weight: 600; text-align: left; padding-left: 15px; }
                    .price-table input { width: 70px; text-align: center; padding: 6px; font-weight: 600; }
                    .currency-tabs { display: flex; flex-wrap: wrap; gap: 5px; margin-bottom: 0; }
                    .currency-tab { padding: 10px 15px; background: #f0f0f1; border: 1px solid #ddd; cursor: pointer; border-radius: 5px 5px 0 0; border-bottom: none; }
                    .currency-tab.active { background: #2271b1; color: #fff; }
                    .currency-panel { display: none; background: #fff; border: 1px solid #ddd; padding: 15px; }
                    .currency-panel.active { display: block; }
                    .calculated-table td { background: #e8f5e9 !important; }
                    .calculated-table .price-cell { font-weight: 700; color: #2e7d32; }
                </style>

                <!-- Conversion Rates -->
                <div class="settings-section">
                    <h2>📊 Conversion Rates (1 USD = X)</h2>
                    <div class="rate-grid">
                        <?php foreach ($this->currencies as $cur_key => $currency):
                            if ($cur_key === 'usd') continue;
                        ?>
                            <div class="rate-item">
                                <label><?php echo $currency['flag'] . ' ' . $currency['code']; ?></label>
                                <input type="number" step="0.01" 
                                       name="iptv_conversion_rates[<?php echo $cur_key; ?>]" 
                                       value="<?php echo esc_attr($rates[$cur_key]); ?>" />
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- USD Prices (Editable) -->
                <div class="settings-section">
                    <h2>🇺🇸 USD Prices (Editable)</h2>
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
                            <?php foreach ($this->durations as $dur_key => $dur_label): ?>
                                <tr>
                                    <td class="duration"><?php echo $dur_label; ?></td>
                                    <?php foreach ($this->devices as $dev_key => $dev_label):
                                        // Show saved value or default
                                        $value = (isset($usd_prices[$dur_key][$dev_key]) && $usd_prices[$dur_key][$dev_key] !== '') 
                                            ? $usd_prices[$dur_key][$dev_key] 
                                            : $this->default_usd[$dur_key][$dev_key];
                                    ?>
                                        <td>
                                            $<input type="number" step="0.01" 
                                                   name="iptv_usd_prices[<?php echo $dur_key; ?>][<?php echo $dev_key; ?>]" 
                                                   value="<?php echo esc_attr($value); ?>" />
                                        </td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <!-- All Calculated Prices (Read-Only View) -->
                <div class="settings-section">
                    <h2>💱 Calculated Prices (All Currencies)</h2>
                    <p>These are auto-calculated from USD using your conversion rates. <em>Save to update.</em></p>
                    
                    <div class="currency-tabs">
                        <?php $first = true; foreach ($this->currencies as $cur_key => $currency): ?>
                            <div class="currency-tab <?php echo $first ? 'active' : ''; ?>" onclick="showCurrencyTab('<?php echo $cur_key; ?>')">
                                <?php echo $currency['flag'] . ' ' . $currency['code']; ?>
                            </div>
                        <?php $first = false; endforeach; ?>
                    </div>
                    
                    <?php $first = true; foreach ($this->currencies as $cur_key => $currency): ?>
                    <div id="panel-<?php echo $cur_key; ?>" class="currency-panel calculated-table <?php echo $first ? 'active' : ''; ?>">
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

    public static function get_prices() { return self::calculate_all_prices(); }
    public static function get_currencies() { return (new self())->currencies; }
    public static function get_durations() { return (new self())->durations; }
    public static function get_devices() { return (new self())->devices; }
}

new IPTV_Currency_Settings();
