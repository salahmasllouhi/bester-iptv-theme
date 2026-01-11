<?php
/**
 * DeepL Translation Service
 * 
 * Provides automatic translation for network cloning.
 * 
 * @package Nordic_IPTV
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Theme_DeepL_Translator
{

    /**
     * DeepL API endpoint (free tier)
     */
    private $api_url = 'https://api-free.deepl.com/v2/translate';

    /**
     * API Key - set in WordPress options
     */
    private $api_key = '';

    /**
     * Language mapping (site slug => DeepL language code)
     */
    private $language_map = array(
        'se' => 'SV',  // Swedish
        'no' => 'NB',  // Norwegian (Bokmål)
        'dk' => 'DA',  // Danish
        'fi' => 'FI',  // Finnish
        'is' => 'EN',  // Icelandic (DeepL doesn't support IS, keep English)
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->api_key = get_option('deepl_api_key', '');

        // Add settings page
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
    }

    /**
     * Check if API key is configured
     */
    public function is_configured()
    {
        return !empty($this->api_key);
    }

    /**
     * Get target language for a site slug
     */
    public function get_target_language($site_slug)
    {
        return isset($this->language_map[$site_slug]) ? $this->language_map[$site_slug] : 'EN';
    }

    /**
     * Translate text to target language
     */
    public function translate($text, $target_lang, $source_lang = 'EN')
    {
        if (empty($text) || empty($this->api_key)) {
            return $text;
        }

        // Skip if target is same as source
        if ($target_lang === $source_lang) {
            return $text;
        }

        // Skip if target is English (for unsupported languages like Icelandic)
        if ($target_lang === 'EN') {
            return $text;
        }

        // For very long content, split into chunks (DeepL has limits)
        $max_chars = 30000; // DeepL free limit per request
        if (strlen($text) > $max_chars) {
            return $this->translate_long_content($text, $target_lang, $source_lang);
        }

        $response = wp_remote_post($this->api_url, array(
            'timeout' => 60, // Increased timeout for longer content
            'headers' => array(
                'Authorization' => 'DeepL-Auth-Key ' . $this->api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'text' => array($text),
                'source_lang' => $source_lang,
                'target_lang' => $target_lang,
                'tag_handling' => 'html',
                'preserve_formatting' => true,
            )),
        ));

        if (is_wp_error($response)) {
            error_log('DeepL Translation Error: ' . $response->get_error_message());
            return $text;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        // Log errors for debugging
        if ($response_code !== 200) {
            error_log('DeepL API Error - Code: ' . $response_code . ' Body: ' . print_r($body, true));
            return $text;
        }

        if (isset($body['translations'][0]['text'])) {
            return $body['translations'][0]['text'];
        }

        error_log('DeepL Translation: No translation in response - ' . print_r($body, true));
        return $text;
    }

    /**
     * Translate long content by splitting into paragraphs
     */
    private function translate_long_content($text, $target_lang, $source_lang)
    {
        // Split by double newlines (paragraphs) or block markers
        $chunks = preg_split('/(\n\n|\r\n\r\n|<!-- wp:)/', $text, -1, PREG_SPLIT_DELIM_CAPTURE);

        $translated_chunks = array();
        $current_chunk = '';

        foreach ($chunks as $chunk) {
            // If adding this chunk would exceed limit, translate current batch
            if (strlen($current_chunk) + strlen($chunk) > 25000 && !empty($current_chunk)) {
                $translated_chunks[] = $this->translate($current_chunk, $target_lang, $source_lang);
                $current_chunk = $chunk;
            } else {
                $current_chunk .= $chunk;
            }
        }

        // Translate remaining content
        if (!empty($current_chunk)) {
            $translated_chunks[] = $this->translate($current_chunk, $target_lang, $source_lang);
        }

        return implode('', $translated_chunks);
    }

    /**
     * Translate post content for a specific site
     * Handles WordPress Gutenberg blocks
     */
    public function translate_post_content($content, $site_slug)
    {
        $target_lang = $this->get_target_language($site_slug);

        // Check if content has Gutenberg blocks
        if (strpos($content, '<!-- wp:') !== false) {
            return $this->translate_gutenberg_content($content, $target_lang);
        }

        return $this->translate($content, $target_lang);
    }

    /**
     * Translate Gutenberg block content
     */
    private function translate_gutenberg_content($content, $target_lang)
    {
        // Parse blocks and translate each one
        $blocks = parse_blocks($content);
        $translated_content = '';

        foreach ($blocks as $block) {
            $translated_content .= $this->translate_block($block, $target_lang);
        }

        return $translated_content;
    }

    /**
     * Translate a single Gutenberg block
     */
    private function translate_block($block, $target_lang)
    {
        // Skip empty blocks
        if (empty($block['blockName']) && empty(trim($block['innerHTML']))) {
            return $block['innerHTML'];
        }

        // Get the inner HTML content
        $inner_html = $block['innerHTML'];

        // Skip blocks with no translatable content
        if (empty(trim(strip_tags($inner_html)))) {
            return $inner_html;
        }

        // Translate the inner HTML
        $translated_html = $this->translate($inner_html, $target_lang);

        // Handle inner blocks recursively
        if (!empty($block['innerBlocks'])) {
            foreach ($block['innerBlocks'] as $inner_block) {
                $translated_html .= $this->translate_block($inner_block, $target_lang);
            }
        }

        return $translated_html;
    }

    /**
     * Translate post title for a specific site
     */
    public function translate_post_title($title, $site_slug)
    {
        $target_lang = $this->get_target_language($site_slug);
        return $this->translate($title, $target_lang);
    }

    /**
     * Add settings page to admin menu (left sidebar)
     */
    public function add_settings_page()
    {
        // Add as top-level menu item
        add_menu_page(
            'DeepL Translation',           // Page title
            '🌐 DeepL',                     // Menu title
            'manage_options',               // Capability
            'deepl-settings',               // Menu slug
            array($this, 'render_settings_page'), // Callback
            'dashicons-translation',        // Icon
            80                              // Position
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting('deepl_settings', 'deepl_api_key', array(
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }

    /**
     * Render settings page
     */
    public function render_settings_page()
    {
        if (isset($_POST['deepl_api_key']) && check_admin_referer('deepl_settings_nonce')) {
            update_option('deepl_api_key', sanitize_text_field($_POST['deepl_api_key']));
            $this->api_key = get_option('deepl_api_key', '');
            echo '<div class="notice notice-success"><p>✅ API Key saved!</p></div>';
        }

        $api_key = get_option('deepl_api_key', '');
        ?>
        <div class="wrap">
            <h1>🌐 DeepL Translation Settings</h1>

            <div style="background:#fff;padding:20px;border:1px solid #ccc;border-radius:8px;max-width:600px;margin-top:20px;">
                <h2>API Configuration</h2>
                <p>Enter your DeepL API key to enable automatic translation when cloning pages.</p>

                <form method="post">
                    <?php wp_nonce_field('deepl_settings_nonce'); ?>

                    <table class="form-table">
                        <tr>
                            <th><label for="deepl_api_key">DeepL API Key</label></th>
                            <td>
                                <input type="password" id="deepl_api_key" name="deepl_api_key"
                                    value="<?php echo esc_attr($api_key); ?>" class="regular-text"
                                    placeholder="xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx:fx">
                                <p class="description">
                                    Get your free API key from <a href="https://www.deepl.com/pro-api" target="_blank">DeepL
                                        API</a> (500k chars/month free)
                                </p>
                            </td>
                        </tr>
                    </table>

                    <p class="submit">
                        <button type="submit" class="button button-primary">Save API Key</button>
                    </p>
                </form>

                <hr style="margin:20px 0;">

                <h3>Supported Languages</h3>
                <table class="widefat" style="max-width:400px;">
                    <thead>
                        <tr>
                            <th>Site</th>
                            <th>Language</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>/se/</td>
                            <td>Swedish</td>
                            <td>✅ Supported</td>
                        </tr>
                        <tr>
                            <td>/no/</td>
                            <td>Norwegian</td>
                            <td>✅ Supported</td>
                        </tr>
                        <tr>
                            <td>/dk/</td>
                            <td>Danish</td>
                            <td>✅ Supported</td>
                        </tr>
                        <tr>
                            <td>/fi/</td>
                            <td>Finnish</td>
                            <td>✅ Supported</td>
                        </tr>
                        <tr>
                            <td>/is/</td>
                            <td>Icelandic</td>
                            <td>⚠️ Not supported (keeps English)</td>
                        </tr>
                    </tbody>
                </table>

                <?php if (!empty($api_key)): ?>
                    <hr style="margin:20px 0;">
                    <h3>Test Translation</h3>
                    <p>API Key is configured. When you use "Clone to Network", content will be automatically translated.</p>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }
}

// Initialize the translator
global $theme_deepl_translator;
$theme_deepl_translator = new Theme_DeepL_Translator();

/**
 * Helper function to get translator instance
 */
function get_deepl_translator()
{
    global $theme_deepl_translator;
    return $theme_deepl_translator;
}
