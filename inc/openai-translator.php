<?php
/**
 * AI Translation Service
 *
 * Provides secure translation for website content via DeepSeek (default) or
 * OpenAI. Both expose the same /chat/completions contract, so one client covers
 * both — see the $providers map below.
 *
 * API keys are stored in WordPress options (database), never hardcoded.
 *
 * The class name is kept as Theme_OpenAI_Translator because existing callers in
 * inc/network-cloner.php reference it.
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
     * Supported providers.
     *
     * DeepSeek exposes an OpenAI-compatible API — same /chat/completions body,
     * same Bearer auth — so one client covers both. Only the base URL, the key
     * option and the default model differ.
     */
    private $providers = array(
        'deepseek' => array(
            'label'      => 'DeepSeek',
            'base'       => 'https://api.deepseek.com/v1',
            'key_option' => 'deepseek_api_key',
            'default'    => 'deepseek-chat',
            'hint'       => 'deepseek-chat tracks the current DeepSeek V-series model.',
        ),
        'openai' => array(
            'label'      => 'OpenAI',
            'base'       => 'https://api.openai.com/v1',
            'key_option' => 'openai_api_key',
            'default'    => 'gpt-4o-mini',
            'hint'       => 'Any model ID your account can reach.',
        ),
    );

    /**
     * Active provider slug
     */
    private $provider = 'deepseek';

    /**
     * Chat completions endpoint for the active provider
     */
    private $api_url = '';

    /**
     * API Key - retrieved from WordPress options (never hardcoded)
     */
    private $api_key = '';

    /**
     * Selected model. Free-form: whatever the provider accepts.
     */
    private $model = '';

    /**
     * Last error message
     */
    private $last_error = null;

    /**
     * Language mapping (language slug => language name)
     *
     * Polylang uses `sv`; `se` is kept because the multisite cloner still passes
     * blog-path slugs.
     */
    private $language_map = array(
        'sv' => 'Swedish',
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
        $provider = get_option('translator_provider', 'deepseek');
        if (!isset($this->providers[$provider])) {
            $provider = 'deepseek';
        }
        $this->provider = $provider;

        $config = $this->providers[$provider];

        // Key and model stored in the WordPress options table, never hardcoded.
        $this->api_key = get_option($config['key_option'], '');
        $this->api_url = $config['base'] . '/chat/completions';

        // `openai_model` is the pre-provider option name; read it as a fallback so
        // an existing install keeps its model on first load after the upgrade.
        $model = get_option('translator_model', '');
        if ($model === '') {
            $model = get_option('openai_model', '');
        }
        $this->model = $model !== '' ? $model : $config['default'];

        // No whitelist. A model this theme has never heard of is the caller's
        // choice, not an error — silently resetting it is what made new models
        // look broken.

        // Add settings page
        add_action('admin_menu', array($this, 'add_settings_page'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_iptv_fetch_translator_models', array($this, 'ajax_fetch_models'));
    }

    /**
     * Check if API key is configured
     */
    public function is_configured()
    {
        return !empty($this->api_key);
    }

    /**
     * Get target language name for a language / site slug
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
     * Get the active provider slug
     */
    public function get_provider()
    {
        return $this->provider;
    }

    /**
     * Human-readable name of the active provider, for errors and notices
     */
    public function get_provider_label()
    {
        return $this->providers[$this->provider]['label'];
    }

    /**
     * Does the active provider use OpenAI's reasoning-model parameters?
     */
    private function uses_reasoning_params()
    {
        return $this->provider === 'openai'
            && (strpos($this->model, 'gpt-5') !== false || strpos($this->model, 'o1') !== false);
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
        if ($this->uses_reasoning_params()) {
            // OpenAI reasoning models use max_completion_tokens and a fixed temperature
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
            error_log($this->get_provider_label() . ' Translation Error: ' . $response->get_error_message());
            return $text;
        }

        $response_code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($response_code !== 200) {
            $error_msg = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error';
            $this->last_error = $this->get_provider_label() . " API Error ($response_code): $error_msg";
            error_log($this->get_provider_label() . ' API Error - Code: ' . $response_code . ' Body: ' . print_r($body, true));
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
        if ($this->uses_reasoning_params()) {
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

        // A bad key or unknown model returns a well-formed error body with no
        // `choices`, which would otherwise surface as "No content in response".
        $response_code = wp_remote_retrieve_response_code($response);
        if ($response_code !== 200) {
            $error_msg = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown error';
            $this->last_error = $this->get_provider_label() . " API Error ($response_code): $error_msg";
            error_log($this->last_error);
            throw new Exception($this->last_error);
        }

        if (isset($body['choices'][0]['message']['content'])) {
            $translated_json = $body['choices'][0]['message']['content'];
            
            // Strip markdown code blocks if present
            $translated_json = preg_replace('/^```(?:json)?\s*/s', '', $translated_json);
            $translated_json = preg_replace('/\s*```$/s', '', $translated_json);
            $translated_json = trim($translated_json);

            // Sanitize JSON for control characters
            $translated_json = $this->sanitize_json_string($translated_json);

            $translated_array = json_decode($translated_json, true);
            
            if (json_last_error() === JSON_ERROR_NONE && is_array($translated_array)) {
                return array_merge($data, $translated_array);
            } else {
                // FALLBACK: Try regex extraction for each expected key
                error_log('JSON parse failed, attempting regex extraction. Error: ' . json_last_error_msg());
                $extracted = $this->extract_json_values($translated_json, array_keys($data));
                
                if (!empty($extracted)) {
                    error_log('Regex extraction recovered ' . count($extracted) . ' fields');
                    return array_merge($data, $extracted);
                }
                
                $this->last_error = 'JSON parse failed: ' . json_last_error_msg();
                throw new Exception($this->last_error);
            }
        } else {
             $this->last_error = 'No content in response';
             error_log($this->get_provider_label() . ' Error: ' . print_r($body, true));
             throw new Exception($this->last_error);
        }
    }
    
    /**
     * Extract JSON values using string search when json_decode fails.
     * Uses simple string operations instead of regex to avoid backtracking limits on long content.
     */
    private function extract_json_values($json_string, $expected_keys)
    {
        $result = array();
        
        foreach ($expected_keys as $key) {
            // Find the key pattern: "key": "
            $search = '"' . $key . '": "';
            $pos = strpos($json_string, $search);
            
            // Also try without space after colon
            if ($pos === false) {
                $search = '"' . $key . '":"';
                $pos = strpos($json_string, $search);
            }
            
            if ($pos === false) {
                continue;
            }
            
            // Move past the key pattern to start of value
            $value_start = $pos + strlen($search);
            
            // Scan for the closing quote (accounting for escaped quotes)
            $value = '';
            $i = $value_start;
            $len = strlen($json_string);
            
            while ($i < $len) {
                $char = $json_string[$i];
                
                if ($char === '\\' && $i + 1 < $len) {
                    // Escape sequence - take both chars
                    $next = $json_string[$i + 1];
                    
                    // Convert escape sequences
                    switch ($next) {
                        case 'n': $value .= "\n"; break;
                        case 'r': $value .= "\r"; break;
                        case 't': $value .= "\t"; break;
                        case '"': $value .= '"'; break;
                        case '\\': $value .= '\\'; break;
                        default: $value .= $char . $next; break;
                    }
                    $i += 2;
                } elseif ($char === '"') {
                    // End of string
                    break;
                } else {
                    $value .= $char;
                    $i++;
                }
            }
            
            if (!empty($value)) {
                $result[$key] = $value;
            }
        }
        
        return $result;
    }

    /**
     * Sanitize a JSON string by escaping control characters inside string values.
     * VERSION 4 - Uses regex callback to find and fix string contents.
     * 
     * @param string $json Raw JSON string
     * @return string Sanitized JSON string
     */
    public function sanitize_json_string($json)
    {
        // First, remove truly invalid control chars (0x00-0x08, 0x0B, 0x0C, 0x0E-0x1F)
        // These are NEVER valid in JSON anywhere
        $json = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $json);
        
        // Now handle tabs/newlines/CR which are valid OUTSIDE strings but must be escaped INSIDE strings
        // Use a callback to process each JSON string value
        $result = preg_replace_callback(
            '/"((?:[^"\\\\]|\\\\.)*)"/s',
            function($match) {
                $content = $match[1];
                // Escape unescaped control chars inside the string
                $content = str_replace(
                    array("\t", "\n", "\r"),
                    array('\t', '\n', '\r'),
                    $content
                );
                return '"' . $content . '"';
            },
            $json
        );
        
        return $result !== null ? $result : $json;
    }

    /**
     * Add settings page to WordPress admin
     */
    public function add_settings_page()
    {
        add_options_page(
            'AI Translation Settings',
            '🤖 AI Translation',
            'manage_options',
            // Slug kept from the OpenAI-only version so existing bookmarks work.
            'openai-api-settings',
            array($this, 'render_settings_page')
        );
    }

    /**
     * Register settings
     */
    public function register_settings()
    {
        register_setting('openai_api_settings', 'translator_provider', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        register_setting('openai_api_settings', 'translator_model', array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
        ));
        foreach ($this->providers as $config) {
            register_setting('openai_api_settings', $config['key_option'], array(
                'type' => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ));
        }
    }

    /**
     * Ask the active provider which models the key can reach.
     *
     * Both APIs expose GET /models, so this works for either provider and means
     * the theme never has to ship a model whitelist.
     */
    public function ajax_fetch_models()
    {
        if (!current_user_can('manage_options')) {
            wp_send_json_error('Insufficient permissions.', 403);
        }
        check_ajax_referer('iptv_fetch_models', 'nonce');

        $provider = isset($_POST['provider']) ? sanitize_text_field(wp_unslash($_POST['provider'])) : $this->provider;
        if (!isset($this->providers[$provider])) {
            wp_send_json_error('Unknown provider.');
        }

        $config = $this->providers[$provider];

        // Prefer the key typed into the form so it can be tested before saving.
        $key = isset($_POST['api_key']) ? sanitize_text_field(wp_unslash($_POST['api_key'])) : '';
        if ($key === '') {
            $key = get_option($config['key_option'], '');
        }
        if ($key === '') {
            wp_send_json_error('No API key for ' . $config['label'] . '.');
        }

        $response = wp_remote_get($config['base'] . '/models', array(
            'timeout' => 30,
            'headers' => array('Authorization' => 'Bearer ' . $key),
        ));

        if (is_wp_error($response)) {
            wp_send_json_error($config['label'] . ': ' . $response->get_error_message());
        }

        $code = wp_remote_retrieve_response_code($response);
        $body = json_decode(wp_remote_retrieve_body($response), true);

        if ($code !== 200) {
            $message = isset($body['error']['message']) ? $body['error']['message'] : 'HTTP ' . $code;
            wp_send_json_error($config['label'] . ': ' . $message);
        }

        $models = array();
        if (!empty($body['data']) && is_array($body['data'])) {
            foreach ($body['data'] as $model) {
                if (!empty($model['id'])) {
                    $models[] = $model['id'];
                }
            }
        }
        sort($models);

        wp_send_json_success($models);
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
        if (isset($_POST['translator_provider']) && check_admin_referer('openai_settings_nonce')) {
            $posted_provider = sanitize_text_field(wp_unslash($_POST['translator_provider']));
            if (isset($this->providers[$posted_provider])) {
                update_option('translator_provider', $posted_provider);
                $this->provider = $posted_provider;
            }

            $config = $this->providers[$this->provider];

            if (isset($_POST['translator_api_key'])) {
                update_option($config['key_option'], sanitize_text_field(wp_unslash($_POST['translator_api_key'])));
            }
            if (isset($_POST['translator_model'])) {
                update_option('translator_model', sanitize_text_field(wp_unslash($_POST['translator_model'])));
            }

            echo '<div class="notice notice-success"><p>✅ Settings saved successfully!</p></div>';

            $this->api_key = get_option($config['key_option'], '');
            $this->api_url = $config['base'] . '/chat/completions';
            $model = get_option('translator_model', '');
            $this->model = $model !== '' ? $model : $config['default'];
        }

        $config = $this->providers[$this->provider];
        ?>
        <div class="wrap">
            <h1>🤖 AI Translation Settings</h1>
            <p>Choose a provider and model for automatic translation. Keys are stored in your WordPress database, one per provider.</p>

            <div style="background:#fff;padding:20px;border-radius:8px;max-width:600px;margin-top:20px;border:1px solid #ddd;">
                <form method="post">
                    <?php wp_nonce_field('openai_settings_nonce'); ?>

                    <table class="form-table">
                        <tr>
                            <th scope="row">
                                <label for="translator_provider">Provider</label>
                            </th>
                            <td>
                                <select name="translator_provider" id="translator_provider" class="regular-text">
                                    <?php foreach ($this->providers as $slug => $p): ?>
                                        <option value="<?php echo esc_attr($slug); ?>" <?php selected($this->provider, $slug); ?>>
                                            <?php echo esc_html($p['label']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="description">
                                    Switching providers reloads the key field. Each provider keeps its own key.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="translator_api_key"><?php echo esc_html($config['label']); ?> API Key</label>
                            </th>
                            <td>
                                <input type="password" name="translator_api_key" id="translator_api_key"
                                    value="<?php echo esc_attr($this->api_key); ?>" class="regular-text" placeholder="sk-..." />
                                <p class="description">
                                    <?php if ($this->provider === 'deepseek'): ?>
                                        Get your key from <a href="https://platform.deepseek.com/api_keys" target="_blank" rel="noopener">DeepSeek Platform</a>
                                    <?php else: ?>
                                        Get your key from <a href="https://platform.openai.com/api-keys" target="_blank" rel="noopener">OpenAI Dashboard</a>
                                    <?php endif; ?>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">
                                <label for="translator_model">Model</label>
                            </th>
                            <td>
                                <input type="text" name="translator_model" id="translator_model" list="translator_model_list"
                                    value="<?php echo esc_attr($this->model); ?>" class="regular-text"
                                    placeholder="<?php echo esc_attr($config['default']); ?>" />
                                <datalist id="translator_model_list"></datalist>
                                <button type="button" class="button" id="fetch-models-btn">Fetch available models</button>
                                <span id="fetch-models-status" style="margin-left:8px;"></span>
                                <p class="description">
                                    Any model ID the provider accepts — nothing is whitelisted here, so new
                                    models work as soon as they ship.
                                    <?php echo esc_html($config['hint']); ?>
                                </p>
                            </td>
                        </tr>
                    </table>

                    <p>
                        <button type="submit" class="button button-primary">Save Settings</button>
                    </p>
                </form>

                <script>
                    (function () {
                        var btn = document.getElementById('fetch-models-btn');
                        if (!btn) return;

                        btn.addEventListener('click', function () {
                            var status = document.getElementById('fetch-models-status');
                            var list = document.getElementById('translator_model_list');
                            status.textContent = 'Fetching…';

                            var body = new URLSearchParams({
                                action: 'iptv_fetch_translator_models',
                                nonce: '<?php echo esc_js(wp_create_nonce('iptv_fetch_models')); ?>',
                                provider: document.getElementById('translator_provider').value,
                                api_key: document.getElementById('translator_api_key').value
                            });

                            fetch(ajaxurl, { method: 'POST', body: body, credentials: 'same-origin' })
                                .then(function (r) { return r.json(); })
                                .then(function (res) {
                                    if (!res.success) {
                                        status.textContent = '⚠️ ' + res.data;
                                        return;
                                    }
                                    list.innerHTML = '';
                                    res.data.forEach(function (id) {
                                        var opt = document.createElement('option');
                                        opt.value = id;
                                        list.appendChild(opt);
                                    });
                                    status.textContent = '✅ ' + res.data.length + ' models — click the field to pick one.';
                                })
                                .catch(function (e) { status.textContent = '⚠️ ' + e.message; });
                        });
                    })();
                </script>

                <?php if ($this->is_configured()): ?>
                    <div style="margin-top:15px;padding:10px;background:#d4edda;border-radius:4px;color:#155724;">
                        ✅ <?php echo esc_html($config['label']); ?> configured | Model: <strong><?php echo esc_html($this->model); ?></strong>
                    </div>
                <?php else: ?>
                    <div style="margin-top:15px;padding:10px;background:#fff3cd;border-radius:4px;color:#856404;">
                        ⚠️ No <?php echo esc_html($config['label']); ?> API key configured. Translation will not work.
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

            <!-- Testing Section -->
            <div style="margin-top:20px;background:#fff;padding:20px;border-radius:8px;max-width:600px;border:1px solid #ddd;">
                <h3 style="margin-top:0;">🧪 Test Translation</h3>
                <p>Enter text below to test the connection and translation logic.</p>
                <form method="post">
                    <textarea name="test_translation_text" rows="3" class="large-text code" placeholder="Enter text to translate..."></textarea>
                    <p>
                        <button type="submit" name="run_test_translation" class="button">Test Translate (to Swedish)</button>
                    </p>
                </form>

                <?php
                if (isset($_POST['run_test_translation']) && !empty($_POST['test_translation_text'])) {
                    $text = stripslashes($_POST['test_translation_text']);
                    echo '<div style="margin-top:15px;padding:10px;background:#f9f9f9;border:1px solid #ccc;">';
                    echo '<strong>Original:</strong> ' . esc_html($text) . '<br><br>';
                    
                    // Force a fresh instance to be sure
                    $translator = new Theme_OpenAI_Translator();
                    if (!$translator->is_configured()) {
                         echo '<span style="color:red;">Error: API Key not configured.</span>';
                    } else {
                        $start = microtime(true);
                        // Try Batch method with single item if possible, or standard
                        if (method_exists($translator, 'translate_batch')) {
                             $res = $translator->translate_batch(array('test'=>$text), 'Swedish');
                             $translated = $res['test'] ?? 'ERROR';
                        } else {
                             $translated = $translator->translate($text, 'Swedish', 'English');
                        }
                        $duration = round(microtime(true) - $start, 2);
                        
                        echo '<strong>Translated (Swedish):</strong> ' . esc_html($translated) . '<br>';
                        echo '<em>Time: ' . $duration . 's</em><br>';
                        
                        if ($translator->get_last_error()) {
                             echo '<br><strong style="color:red;">Error Log:</strong> ' . esc_html($translator->get_last_error());
                        }
                    }
                    echo '</div>';
                }
                ?>
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
