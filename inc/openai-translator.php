<?php
/**
 * OpenAI Translation Service
 * 
 * Provides secure translation for website content.
 * API key stored in WordPress options (database), never hardcoded.
 * 
 * @package Nordic_IPTV
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Theme_OpenAI_Translator
{
    /**
     * OpenAI API endpoint
     */
    private $api_url = 'https://api.openai.com/v1/chat/completions';

    /**
     * API Key - retrieved from WordPress options (never hardcoded)
     */
    private $api_key = '';

    /**
     * Selected model
     */
    private $model = 'gpt-4o-mini';

    /**
     * Last error message
     */
    private $last_error = null;

    /**
     * Available models
     */
    private $available_models = array(
        'gpt-4o' => 'GPT-4o (High Quality)',
        'gpt-4o-mini' => 'GPT-4o Mini (Fast, Affordable)',
        'gpt-4-turbo' => 'GPT-4 Turbo',
        'gpt-3.5-turbo' => 'GPT-3.5 Turbo (Legacy)',
    );

    /**
     * Language mapping (site slug => language name)
     */
    private $language_map = array(
        'se' => 'Swedish',
        'no' => 'Norwegian',
        'dk' => 'Danish',
        'fi' => 'Finnish',
        'is' => 'Icelandic',
    );

    /**
     * Constructor
     */
    public function __construct()
    {
        // API key and model stored securely in WordPress options table
        $this->api_key = get_option('openai_api_key', '');
        $this->model = get_option('openai_model', 'gpt-4o-mini');

        // Fallback if selected model is no longer available (e.g. invalid 'gpt-5' values)
        if (!array_key_exists($this->model, $this->available_models)) {
            $this->model = 'gpt-4o-mini';
        }

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
     * Get target language name for a site slug
     */
    public function get_target_language($site_slug)
    {
        return isset($this->language_map[$site_slug]) ? $this->language_map[$site_slug] : 'English';
    }

    /**
     * Get the currently configured model
     */
    public function get_model()
    {
        return $this->model;
    }

    /**
     * Get the last error message
     */
    public function get_last_error()
    {
        return $this->last_error;
    }

    /**
     * Translate text to target language using strict translation rules
     * with proper WEBSITE CONTEXT to ensure correct UI translations
     */
    public function translate($text, $target_lang, $source_lang = 'English', $extra_context = '')
    {
        if (empty($text) || empty($this->api_key)) {
            return $text;
        }

        // Skip if target is same as source
        if ($target_lang === $source_lang) {
            return $text;
        }

        // Build simple, strict translation prompt
        $system_prompt = "You are a professional translator. Translate ALL text from {$source_lang} to {$target_lang}.
{$extra_context}

STRICT RULES:
1. Translate EVERYTHING including slogans, titles, buttons, and badges.
2. If the input contains HTML, translate ONLY the visible text. 
   - PRESERVE all HTML tags, attributes (href, src, class, etc.), and scripts EXACTLY as they are.
   - Do NOT translate text inside HTML attributes (like img alt='Translate This' is okay, but NO class names).
3. Do NOT leave English text unless it is a strict proper noun (like 'Android', 'iOS').
4. Even short phrases like 'Unlimited Entertainment' MUST be translated.
5. Use natural, fluent {$target_lang}.
6. Return ONLY the translated content with HTML structure intact.

EXCEPTIONS (Keep Unchanged):
- The brand name 'Nordic IPTV'
- Technical product names (e.g. 'Firestick', 'MAG')
- Numbers and symbols (e.g. '35K+', '24/7')

Do NOT explain. Do NOT add quotes. Do NOT allow markdown blocks (```html).";

        $user_prompt = "Translate to {$target_lang}:\n\n{$text}";

        $body_args = array(
            'model' => $this->model,
            'messages' => array(
                array('role' => 'system', 'content' => $system_prompt),
                array('role' => 'user', 'content' => $user_prompt),
            ),
        );

        // Add model-specific parameters
        if (strpos($this->model, 'gpt-5') !== false || strpos($this->model, 'o1') !== false) {
            // Newer reasoning models use max_completion_tokens and usually fixed temperature
            $body_args['max_completion_tokens'] = 1000;
        } else {
            // Standard models
            $body_args['max_tokens'] = 1000;
            $body_args['temperature'] = 0; // Deterministic
        }
        
        $request_body = json_encode($body_args);

        $response = wp_remote_post($this->api_url, array(
                'timeout' => 120,
                'headers' => array(
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type' => 'application/json',
                ),
                'body' => $request_body,
            ));

        if (is_wp_error($response)) {
            $this->last_error = 'WordPress HTTP Error: ' . $response->get_error_message();
            error_log('OpenAI Translation Error: ' . $response->get_error_message());
            return $text;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($response_code !== 200) {
            $error_msg = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error';
            $this->last_error = "OpenAI API Error ($response_code): $error_msg";
            error_log('OpenAI API Error - Code: ' . $response_code . ' Body: ' . print_r($body, true));
            return $text;
        }

        if (isset($body['choices'][0]['message']['content'])) {
            return trim($body['choices'][0]['message']['content']);
        }

        return $text;
    }

    /**
     * Batch Translate an array of text strings
     * 
     * @param array $data Associative array of text to translate
     * @param string $target_lang Target language
     * @param string $source_lang Source language
     * @return array Translated array
     */
    public function translate_batch($data, $target_lang, $source_lang = 'English')
    {
        if (empty($data) || empty($this->api_key)) {
            return $data;
        }

        // Filter out empty values to save tokens
        $items_to_translate = array_filter($data, function($val) {
            return !empty($val);
        });

        if (empty($items_to_translate)) {
            return $data; // Nothing to translate
        }

        // JSON encode the data
        $json_input = json_encode($items_to_translate, JSON_UNESCAPED_UNICODE);

        // Build system prompt for JSON handling
        $system_prompt = "You are a professional translator. Translate the VALUES of the provided JSON object from {$source_lang} to {$target_lang}.

STRICT RULES:
1. Return a valid JSON object.
2. Maintain the exact same keys.
3. Translate ONLY the values.
4. If a value contains HTML, PRESERVE tags/attributes and translate only visible text.
5. Do NOT translate brand names ('Nordic IPTV'), technical terms ('Firestick', 'MAG'), or numbers ('35K+', '24/7').
6. Use natural, fluent {$target_lang}.

Return ONLY the JSON.";

        $user_prompt = "Translate this JSON:\n\n{$json_input}";

        $body_args = array(
            'model' => $this->model,
            'messages' => array(
                array('role' => 'system', 'content' => $system_prompt),
                array('role' => 'user', 'content' => $user_prompt),
            ),
            'response_format' => array('type' => 'json_object'), // Force JSON mode
        );

        // Add model-specific parameters
        if (strpos($this->model, 'gpt-5') !== false || strpos($this->model, 'o1') !== false) {
             $body_args['max_completion_tokens'] = 4000; // ample space for JSON
        } else {
             $body_args['max_tokens'] = 4000;
             $body_args['temperature'] = 0;
        }
        
        $request_body = json_encode($body_args);

        // Increased timeout for large batches
        $response = wp_remote_post($this->api_url, array(
                'timeout' => 120,
                'headers' => array(
                    'Authorization' => 'Bearer ' . $this->api_key,
                    'Content-Type' => 'application/json',
                ),
                'body' => $request_body,
            ));

        if (is_wp_error($response)) {
            $this->last_error = 'Batch Error: ' . $response->get_error_message();
            error_log('Batch Translate Error: ' . $this->last_error);
            return $data; // Fallback to original
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);
        
        if (isset($body['choices'][0]['message']['content'])) {
            $translated_json = $body['choices'][0]['message']['content'];
            $translated_array = json_decode($translated_json, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($translated_array)) {
                // Merge translated items back into original data (preserves empty keys)
                return array_merge($data, $translated_array);
            } else {
                $this->last_error = 'Invalid JSON response from OpenAI';
                error_log('Invalid JSON from OpenAI: ' . $translated_json);
            }
        } else {
             $this->last_error = 'No content in response';
             error_log('OpenAI Error: ' . print_r($body, true));
        }

        return $data; // Fallback
    }

    /**
     * Add settings page to WordPress admin
     */
    public function add_settings_page()
    {
        add_options_page(
            'OpenAI API Settings',
            '🤖 OpenAI API',
            'manage_options',
            'openai-api-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting('openai_api_settings', 'openai_api_key', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('openai_api_settings', 'openai_model', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ));
    }

    /**
     * Render the settings page
     */
    public function render_settings_page()
    {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }

        // Save settings
        if (isset($_POST['openai_api_key']) && check_admin_referer('openai_settings_nonce')) {
            update_option('openai_api_key', sanitize_text_field($_POST['openai_api_key']));
            update_option('openai_model', sanitize_text_field($_POST['openai_model']));
            echo '<div class="notice notice-success"><p>✅ Settings saved successfully!</p></div>';
            $this->api_key = get_option('openai_api_key', '');
            $this->model = get_option('openai_model', 'gpt-4o-mini');
        }
        ?>
        <div class="wrap">
            <h1>🤖 OpenAI API Settings</h1>
            <p>Configure OpenAI for automatic translation. API key is stored securely in your WordPress database.</p>

            <div style="background:#fff;padding:20px;border-radius:8px;max-width:600px;margin-top:20px;border:1px solid #ddd;">
                <form method="post">
                    <?php wp_nonce_field('openai_settings_nonce'); ?>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="openai_api_key">API Key</label>
                            </th>
                            <td>
                                <input type="password" name="openai_api_key" id="openai_api_key"
                                    value="<?php echo esc_attr($this->api_key); ?>" class="regular-text" placeholder="sk-..." />
                                <p class="description">
                                    Get your API key from <a href="https://platform.openai.com/api-keys" target="_blank">OpenAI Dashboard</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="openai_model">Model</label>
                            </th>
                            <td>
                                <select name="openai_model" id="openai_model" class="regular-text">
                                    <?php foreach ($this->available_models as $model_id => $model_name): ?>
                                        <option value="<?php echo esc_attr($model_id); ?>" <?php selected($this->model, $model_id); ?>>
                                            <?php echo esc_html($model_name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description">
                                    GPT-4o Mini is recommended for fast, cost-effective translations.
                                </p>
                            </td>
                        </tr>
                    </table>

                    <p>
                        <button type="submit" class="button button-primary">Save Settings</button>
                    </p>
                </form>

                <?php if ($this->is_configured()): ?>
                    <div style="margin-top:15px;padding:10px;background:#d4edda;border-radius:4px;color:#155724;">
                        ✅ OpenAI API configured | Model: <strong><?php echo esc_html($this->available_models[$this->model] ?? $this->model); ?></strong>
                    </div>
                <?php else: ?>
                    <div style="margin-top:15px;padding:10px;background:#fff3cd;border-radius:4px;color:#856404;">
                        ⚠️ No API key configured. Translation will not work.
                    </div>
                <?php endif; ?>
            </div>

            <div style="margin-top:20px;background:#e8f4fd;padding:15px;border-radius:8px;max-width:600px;border:1px solid #b8daff;">
                <h3 style="margin-top:0;">📝 Website Context in Translations</h3>
                <p style="margin-bottom:0;">The translator is configured with website context so:</p>
                <ul style="margin:10px 0 0 20px;padding:0;">
                    <li><strong>"Home"</strong> → translated as homepage (not physical home)</li>
                    <li><strong>"Features"</strong> → product features on a website</li>
                    <li><strong>"Pricing"</strong> → subscription plans/prices</li>
                    <li><strong>Brand names</strong> → kept unchanged (Nordic IPTV)</li>
                    <li><strong>Numbers</strong> → kept as-is (35K+, 24/7)</li>
                </ul>
            </div>

            <div style="margin-top:20px;background:#f0f0f1;padding:15px;border-radius:8px;max-width:600px;">
                <h3 style="margin-top:0;">🔒 Security Notes</h3>
                <ul style="margin:0;padding-left:20px;">
                    <li>Your API key is stored in the WordPress database, never in code</li>
                    <li>The key is only used server-side, never exposed to browsers</li>
                    <li>Only administrators can view or change this setting</li>
                </ul>
            </div>
        </div>
        <?php
    }
}

// Initialize
$openai_translator = new Theme_OpenAI_Translator();

/**
 * Helper function to get translator instance
 */
function get_openai_translator()
{
    global $openai_translator;
    return $openai_translator;
}
