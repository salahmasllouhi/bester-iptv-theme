<?php
/**
 * Front Page Content Settings with DeepL Translation
 * 
 * Admin page to manage all front page text content with auto-translation
 */

if (!defined('ABSPATH')) {
    exit;
}

class IPTV_Content_Settings
{
    // Supported languages
    private $languages = array(
        'en' => array('name' => 'English', 'flag' => '🇺🇸', 'deepl' => 'EN'),
        'se' => array('name' => 'Swedish', 'flag' => '🇸🇪', 'deepl' => 'SV'),
        'no' => array('name' => 'Norwegian', 'flag' => '🇳🇴', 'deepl' => 'NB'),
        'dk' => array('name' => 'Danish', 'flag' => '🇩🇰', 'deepl' => 'DA'),
        'fi' => array('name' => 'Finnish', 'flag' => '🇫🇮', 'deepl' => 'FI'),
        'is' => array('name' => 'Icelandic', 'flag' => '🇮🇸', 'deepl' => 'EN'), // Not supported by DeepL
    );

    // Content fields to translate (defaults match front-page.php)
    private $content_fields = array(
        'navigation' => array(
            'label' => 'Navigation Menu',
            'fields' => array(
                'nav_home' => array('label' => 'Home Link', 'type' => 'text', 'default' => 'Home'),
                'nav_features' => array('label' => 'Features Link', 'type' => 'text', 'default' => 'Features'),
                'nav_pricing' => array('label' => 'Pricing Link', 'type' => 'text', 'default' => 'Pricing'),
                'nav_blog' => array('label' => 'Blog Link', 'type' => 'text', 'default' => 'Blog'),
                'nav_user_guide' => array('label' => 'User Guide Link', 'type' => 'text', 'default' => 'User Guide'),
                'nav_contact' => array('label' => 'Contact Link', 'type' => 'text', 'default' => 'Contact'),
                'nav_cta' => array('label' => 'Nav CTA Button', 'type' => 'text', 'default' => 'Get Access Now'),
            )
        ),
        'hero' => array(
            'label' => 'Hero Section',
            'fields' => array(
                'hero_badge' => array('label' => 'Hero Badge', 'type' => 'text', 'default' => '✓ Unlimited Entertainment'),
                'hero_title' => array('label' => 'Hero Title', 'type' => 'text', 'default' => 'Nordic IPTV'),
                'hero_title_span' => array('label' => 'Hero Title Highlight', 'type' => 'textarea', 'default' => 'A Premium Streaming Experience, Seamlessly Delivered'),
                'hero_tagline' => array('label' => 'Hero Tagline', 'type' => 'text', 'default' => 'Others complicate streaming. We simplify it.'),
                'hero_subtitle' => array('label' => 'Hero Subtitle', 'type' => 'textarea', 'default' => 'Nordic IPTV with 35,000+ channels, 150,000+ movies, and zero compromises. No contracts. No hidden fees. Just seamless streaming on every device.'),
                'hero_price' => array('label' => 'Hero Price Text', 'type' => 'text', 'default' => 'Plans start from $5,83/month.'),
                'hero_cta' => array('label' => 'CTA Button', 'type' => 'text', 'default' => 'Get Access Now'),
                'hero_stat_1' => array('label' => 'Stat 1 Label', 'type' => 'text', 'default' => 'Live Channels'),
                'hero_stat_2' => array('label' => 'Stat 2 Label', 'type' => 'text', 'default' => 'Movies & Series'),
                'hero_stat_3' => array('label' => 'Stat 3 Label', 'type' => 'text', 'default' => 'Ultra HD'),
                'hero_stat_4' => array('label' => 'Stat 4 Label', 'type' => 'text', 'default' => 'Support'),
            )
        ),
        'brands' => array(
            'label' => 'Brands Section',
            'fields' => array(
                'brands_title' => array('label' => 'Brands Title', 'type' => 'text', 'default' => 'Stream Your Favorite Channels'),
            )
        ),
        'features' => array(
            'label' => 'Features Section',
            'fields' => array(
                'features_title' => array('label' => 'Features Title', 'type' => 'text', 'default' => 'Streaming Promises'),
                'features_title_span' => array('label' => 'Features Title Highlight', 'type' => 'text', 'default' => 'Delivered'),
                'features_subtitle' => array('label' => 'Features Subtitle', 'type' => 'textarea', 'default' => 'Experience the future of television with our ultra-stable network. No freezing, no buffering—just pure entertainment on your terms.'),
                'feature_1_title' => array('label' => 'Feature 1 Title', 'type' => 'text', 'default' => '4K Ultra HD Streaming'),
                'feature_1_desc' => array('label' => 'Feature 1 Description', 'type' => 'textarea', 'default' => 'Crystal clear picture quality with native support for 4K, FHD, and HD streaming on all your modern devices.'),
                'feature_2_title' => array('label' => 'Feature 2 Title', 'type' => 'text', 'default' => 'Lightning Fast Servers'),
                'feature_2_desc' => array('label' => 'Feature 2 Description', 'type' => 'textarea', 'default' => 'Advanced Anti-buffering technology backed by a 99.9% uptime guarantee for seamless, uninterrupted viewing.'),
                'feature_3_title' => array('label' => 'Feature 3 Title', 'type' => 'text', 'default' => 'All Devices Supported'),
                'feature_3_desc' => array('label' => 'Feature 3 Description', 'type' => 'textarea', 'default' => 'Works flawlessly on Smart TV, Android, iOS, Firestick, MAG, and more. Take your stream anywhere.'),
                'feature_4_title' => array('label' => 'Feature 4 Title', 'type' => 'text', 'default' => 'Worldwide Content'),
                'feature_4_desc' => array('label' => 'Feature 4 Description', 'type' => 'textarea', 'default' => 'Access premium channels from USA, UK, Canada, Europe, Asia, Middle East, and everywhere in between.'),
                'feature_5_title' => array('label' => 'Feature 5 Title', 'type' => 'text', 'default' => 'EPG Guide Included'),
                'feature_5_desc' => array('label' => 'Feature 5 Description', 'type' => 'textarea', 'default' => 'Full Electronic Program Guide keeps you on schedule with complete listings for every channel.'),
                'feature_6_title' => array('label' => 'Feature 6 Title', 'type' => 'text', 'default' => '24/7 Expert Support'),
                'feature_6_desc' => array('label' => 'Feature 6 Description', 'type' => 'textarea', 'default' => 'Our dedicated support team is online around the clock to ensure you never miss a moment.'),
            )
        ),
        'steps' => array(
            'label' => 'Steps Section',
            'fields' => array(
                'steps_title' => array('label' => 'Steps Title', 'type' => 'text', 'default' => 'Start Streaming in'),
                'steps_title_span' => array('label' => 'Steps Title Highlight', 'type' => 'text', 'default' => '3 Simple Steps'),
                'steps_subtitle' => array('label' => 'Steps Subtitle', 'type' => 'text', 'default' => 'Get up and running in minutes, not hours. Our expert team ensures a smooth setup experience for you.'),
                'step_1_badge' => array('label' => 'Step 1 Badge', 'type' => 'text', 'default' => 'Step 01'),
                'step_1_title' => array('label' => 'Step 1 Title', 'type' => 'text', 'default' => 'Choose Your Plan'),
                'step_1_desc' => array('label' => 'Step 1 Description', 'type' => 'textarea', 'default' => 'Browse our flexible subscription packages and select the one that fits your budget and device needs.'),
                'step_2_badge' => array('label' => 'Step 2 Badge', 'type' => 'text', 'default' => 'Step 02'),
                'step_2_title' => array('label' => 'Step 2 Title', 'type' => 'text', 'default' => 'Complete Payment'),
                'step_2_desc' => array('label' => 'Step 2 Description', 'type' => 'textarea', 'default' => 'Checkout securely using our encrypted payment gateway. We accept major cards and crypto options.'),
                'step_3_badge' => array('label' => 'Step 3 Badge', 'type' => 'text', 'default' => 'Step 03'),
                'step_3_title' => array('label' => 'Step 3 Title', 'type' => 'text', 'default' => 'Start Watching'),
                'step_3_desc' => array('label' => 'Step 3 Description', 'type' => 'textarea', 'default' => 'Our team will configure your account and send login credentials via email. Download the app and enjoy!'),
            )
        ),
        'dark_cta' => array(
            'label' => 'Dark CTA (Comparison)',
            'fields' => array(
                'cta_title' => array('label' => 'CTA Title', 'type' => 'text', 'default' => 'Tired of Streaming Hassles?'),
                'cta_title_span' => array('label' => 'CTA Title Highlight', 'type' => 'text', 'default' => 'Break Free with Nordic IPTV'),
                'cta_subtitle' => array('label' => 'CTA Subtitle', 'type' => 'text', 'default' => 'Join thousands of satisfied customers who switched to premium IPTV streaming.'),
                'cta_negative_title' => array('label' => 'Negative Card Title', 'type' => 'text', 'default' => 'The Struggle is Real'),
                'cta_negative_subtitle' => array('label' => 'Negative Card Subtitle', 'type' => 'text', 'default' => 'What you\'re dealing with right now'),
                'cta_positive_title' => array('label' => 'Positive Card Title', 'type' => 'text', 'default' => 'Welcome to Nordic IPTV'),
                'cta_positive_subtitle' => array('label' => 'Positive Card Subtitle', 'type' => 'text', 'default' => 'Your complete streaming freedom awaits'),
            )
        ),
        'pricing' => array(
            'label' => 'Pricing Section',
            'fields' => array(
                'pricing_badge' => array('label' => 'Pricing Badge', 'type' => 'text', 'default' => 'Stream Smarter, Pay Less – Start Today!'),
                'pricing_title' => array('label' => 'Pricing Title', 'type' => 'text', 'default' => 'Unlimited Streaming'),
                'pricing_title_span' => array('label' => 'Pricing Title Highlight', 'type' => 'text', 'default' => 'at a fair price'),
                'pricing_subtitle' => array('label' => 'Pricing Subtitle', 'type' => 'text', 'default' => '35,000+ live channels and 150,000+ movies & series in 4K.'),
                'devices_title' => array('label' => 'Devices Question', 'type' => 'text', 'default' => 'How many devices will you use?'),
                'duration_title' => array('label' => 'Duration Title', 'type' => 'text', 'default' => 'Select your plan duration'),
                'checkout_button' => array('label' => 'Checkout Button', 'type' => 'text', 'default' => 'Complete Your Order'),
                'guarantee_text' => array('label' => 'Guarantee Text', 'type' => 'text', 'default' => '14-day money-back guarantee. No questions asked.'),
                'save_more' => array('label' => 'Save More Text', 'type' => 'text', 'default' => 'Save more'),
                'best_deal' => array('label' => 'Best Deal Text', 'type' => 'text', 'default' => 'Best deal!'),
                'per_month' => array('label' => 'Per Month Text', 'type' => 'text', 'default' => 'per month'),
                'trust_1_title' => array('label' => 'Trust Badge 1 Title', 'type' => 'text', 'default' => 'Transparent pricing'),
                'trust_1_desc' => array('label' => 'Trust Badge 1 Desc', 'type' => 'text', 'default' => 'No contracts. Cancel anytime.'),
                'trust_2_title' => array('label' => 'Trust Badge 2 Title', 'type' => 'text', 'default' => 'Instant activation'),
                'trust_2_desc' => array('label' => 'Trust Badge 2 Desc', 'type' => 'text', 'default' => 'Start watching in minutes.'),
                'trust_3_title' => array('label' => 'Trust Badge 3 Title', 'type' => 'text', 'default' => 'Risk-free'),
                'trust_3_desc' => array('label' => 'Trust Badge 3 Desc', 'type' => 'text', 'default' => '14-day money-back guarantee.'),
            )
        ),
        'contact' => array(
            'label' => 'Contact Section',
            'fields' => array(
                'contact_title' => array('label' => 'Contact Title', 'type' => 'text', 'default' => 'Need Help?'),
            )
        ),
        'footer' => array(
            'label' => 'Footer',
            'fields' => array(
                'footer_copyright' => array('label' => 'Copyright Text', 'type' => 'text', 'default' => 'All rights reserved.'),
            )
        ),
    );


    // Website-specific glossary overrides (prevents literal translations)
    private $glossary = array(
        'se' => array(  // Swedish
            'Home' => 'Hem',  // Keep as Hem or change to 'Startsida' if preferred
            'Features' => 'Funktioner',
            'Pricing' => 'Priser',
            'Blog' => 'Blogg',
            'User Guide' => 'Användarguide',
            'Contact' => 'Kontakt',
            'Get Access Now' => 'Få tillgång nu',
            'Live Channels' => 'Livekanaler',
            'Movies & Series' => 'Filmer & Serier',
            'Ultra HD' => 'Ultra HD',
            'Support' => 'Support',
        ),
        'no' => array(  // Norwegian
            'Home' => 'Hjem',
            'Features' => 'Funksjoner',
            'Pricing' => 'Priser',
            'Blog' => 'Blogg',
            'User Guide' => 'Brukerveiledning',
            'Contact' => 'Kontakt',
            'Get Access Now' => 'Få tilgang nå',
        ),
        'dk' => array(  // Danish
            'Home' => 'Hjem',
            'Features' => 'Funktioner',
            'Pricing' => 'Priser',
            'Blog' => 'Blog',
            'User Guide' => 'Brugervejledning',
            'Contact' => 'Kontakt',
            'Get Access Now' => 'Få adgang nu',
        ),
        'fi' => array(  // Finnish
            'Home' => 'Etusivu',
            'Features' => 'Ominaisuudet',
            'Pricing' => 'Hinnoittelu',
            'Blog' => 'Blogi',
            'User Guide' => 'Käyttöohje',
            'Contact' => 'Yhteystiedot',
            'Get Access Now' => 'Hanki pääsy nyt',
        ),
    );

    public function __construct()
    {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_iptv_translate_content', array($this, 'ajax_translate_content'));
        add_action('wp_ajax_iptv_erase_content', array($this, 'ajax_erase_content'));
        // Post Localizer AJAX handlers
        add_action('wp_ajax_iptv_generate_localized_content', array($this, 'ajax_generate_localized_content'));
        add_action('wp_ajax_iptv_publish_localized_post', array($this, 'ajax_publish_localized_post'));
    }

    /**
     * AJAX handler for erasing content translations
     */
    public function ajax_erase_content()
    {
        check_ajax_referer('iptv_content_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $target_lang = sanitize_text_field($_POST['target_lang']);

        // Get content and remove the target language
        $content = get_option('iptv_content', array());

        if (isset($content[$target_lang])) {
            unset($content[$target_lang]);
            update_option('iptv_content', $content);
        }

        wp_send_json_success(array(
            'message' => 'All ' . $this->languages[$target_lang]['name'] . ' translations erased'
        ));
    }

    public function add_admin_menu()
    {
        add_menu_page(
            'Front Page Content',
            '🌍 Content Localizing',
            'manage_options',
            'iptv-content-settings',
            array($this, 'render_settings_page'),
            'dashicons-edit-page',
            30
        );
    }

    public function register_settings()
    {
        register_setting('iptv_content_settings', 'iptv_content');
    }

    /**
     * AJAX handler for DeepL translation
     */
    public function ajax_translate_content()
    {
        check_ajax_referer('iptv_content_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $target_lang = sanitize_text_field($_POST['target_lang']);
        $deepl_code = $this->languages[$target_lang]['deepl'] ?? 'EN';

        // Get English content - use defaults if no saved content
        $content = get_option('iptv_content', array());
        $english_content = $content['en'] ?? array();

        // Build English content from saved values or defaults
        $english_to_translate = array();
        foreach ($this->content_fields as $section_key => $section) {
            foreach ($section['fields'] as $field_key => $field) {
                $english_to_translate[$field_key] = !empty($english_content[$field_key])
                    ? $english_content[$field_key]
                    : $field['default'];
            }
        }

        if (empty($english_to_translate)) {
            wp_send_json_error('No content to translate');
        }

        // Get OpenAI translator (uses secure API key from WordPress options)
        $translator = get_openai_translator();
        if (!$translator || !$translator->is_configured()) {
            wp_send_json_error('OpenAI API not configured. Go to Settings → 🤖 OpenAI API to add your API key.');
        }

        // Get target language name for OpenAI
        $target_language = $translator->get_target_language($target_lang);

        // Translate all fields using OpenAI
        $translated = array();
        foreach ($english_to_translate as $field_key => $english_text) {
            if (!empty($english_text)) {
                // First translate with OpenAI (strict translation, no extra words)
                $translation = $translator->translate($english_text, $target_language, 'English');

                // Then apply glossary overrides for exact matches (if needed)
                if (isset($this->glossary[$target_lang][$english_text])) {
                    $translation = $this->glossary[$target_lang][$english_text];
                }

                $translated[$field_key] = $translation;
            }
        }

        // Save translated content
        $content[$target_lang] = $translated;
        update_option('iptv_content', $content);

        wp_send_json_success(array(
            'message' => 'Translated to ' . $this->languages[$target_lang]['name'],
            'content' => $translated
        ));
    }

    /**
     * AJAX handler for generating localized content with OpenAI
     */
    public function ajax_generate_localized_content()
    {
        check_ajax_referer('iptv_localizer_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = intval($_POST['post_id']);
        $target_lang = sanitize_text_field($_POST['target_lang']);

        // Get original post
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error('Post not found');
        }

        // Language mapping
        $lang_map = array(
            'sv' => 'Swedish',
            'no' => 'Norwegian',
            'da' => 'Danish',
            'fi' => 'Finnish',
            'is' => 'Icelandic'
        );

        $target_language = isset($lang_map[$target_lang]) ? $lang_map[$target_lang] : 'English';

        // Get featured image and alt text
        $featured_image_id = get_post_thumbnail_id($post_id);
        $featured_image_url = '';
        $featured_image_alt = '';

        if ($featured_image_id) {
            $featured_image_url = wp_get_attachment_url($featured_image_id);
            $featured_image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
        }

        // Build prompt for OpenAI
        $prompt = "Localize this content to $target_language with SEO optimization.\n\n";
        $prompt .= "Original:\n";
        $prompt .= "Title: " . $post->post_title . "\n";
        $prompt .= "Content: " . wp_strip_all_tags($post->post_content) . "\n";
        if ($featured_image_alt) {
            $prompt .= "Image Alt: " . $featured_image_alt . "\n";
        }

        $prompt .= "\nGenerate a native $target_language focus keyword (not just a translation).\n";
        $prompt .= "Rewrite the content and SEO metadata for $target_language audience.\n\n";

        $prompt .= "Return as JSON:\n";
        $prompt .= "{\n";
        $prompt .= '  "focus_keyword": "...",';
        $prompt .= "\n";
        $prompt .= '  "title": "...",';
        $prompt .= "\n";
        $prompt .= '  "content": "...",';
        $prompt .= "\n";
        $prompt .= '  "meta_title": "...",';
        $prompt .= "\n";
        $prompt .= '  "meta_description": "..."';
        if ($featured_image_alt) {
            $prompt .= ",\n";
            $prompt .= '  "image_alt": "..."';
        }
        $prompt .= "\n}\n";

        // Call OpenAI API directly (not translate() which has token limits)
        $translator = new Theme_OpenAI_Translator();
        $api_key = get_option('iptv_openai_api_key');
        $model = get_option('iptv_openai_model', 'gpt-4o');

        if (empty($api_key)) {
            wp_send_json_error('OpenAI API key not configured');
        }

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'timeout' => 120,
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode(array(
                'model' => $model,
                'messages' => array(
                    array('role' => 'user', 'content' => $prompt)
                ),
                'temperature' => 0.7,
                'max_tokens' => 100000, // Maximum token limit
            )),
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('OpenAI request failed: ' . $response->get_error_message());
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        if (!isset($body['choices'][0]['message']['content'])) {
            wp_send_json_error('Invalid OpenAI response');
        }

        $result = trim($body['choices'][0]['message']['content']);

        // Try to parse JSON from result
        $result = trim($result);

        // Remove markdown code blocks if present (be more aggressive)
        if (strpos($result, '```') !== false) {
            // Remove ```json and ``` markers
            $result = preg_replace('/^```json\s*/s', '', $result);
            $result = preg_replace('/^```\s*/s', '', $result);
            $result = preg_replace('/\s*```$/s', '', $result);
            $result = trim($result);
        }

        // Fix control characters and encoding issues
        // Remove problematic control characters but preserve valid JSON structure
        $result = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $result);

        // Ensure UTF-8 encoding
        if (!mb_check_encoding($result, 'UTF-8')) {
            $result = utf8_encode($result);
        }

        $data = json_decode($result, true);

        if (!$data || !isset($data['focus_keyword'])) {
            // Enhanced debugging
            $error_msg = 'Failed to parse OpenAI response. ';
            $error_msg .= 'JSON Error: ' . json_last_error_msg() . '. ';
            $error_msg .= 'Length: ' . strlen($result) . '. ';
            $error_msg .= 'First 300 chars: ' . substr($result, 0, 300);
            wp_send_json_error($error_msg);
        }

        // Add featured image info to response
        if ($featured_image_id) {
            $data['featured_image_url'] = $featured_image_url;
            $data['featured_image_id'] = $featured_image_id;
        }

        wp_send_json_success($data);
    }

    /**
     * AJAX handler for publishing localized post to subsite
     */
    public function ajax_publish_localized_post()
    {
        check_ajax_referer('iptv_localizer_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $original_post_id = intval($_POST['original_post_id']);
        $target_blog_id = intval($_POST['target_blog_id']);
        $post_title = sanitize_text_field($_POST['post_title']);
        $post_content = wp_kses_post($_POST['post_content']);
        $focus_keyword = sanitize_text_field($_POST['focus_keyword']);
        $meta_title = sanitize_text_field($_POST['meta_title']);
        $meta_description = sanitize_textarea_field($_POST['meta_description']);
        $image_alt = isset($_POST['image_alt']) ? sanitize_text_field($_POST['image_alt']) : '';
        $featured_image_id = isset($_POST['featured_image_id']) ? intval($_POST['featured_image_id']) : 0;

        // Get original post type
        $original_post = get_post($original_post_id);
        if (!$original_post) {
            wp_send_json_error('Original post not found');
        }

        // Switch to target blog
        if (is_multisite()) {
            switch_to_blog($target_blog_id);
        }

        // Check if post already exists (by meta)
        $existing_query = new WP_Query(array(
            'post_type' => $original_post->post_type,
            'meta_key' => '_localized_from_post',
            'meta_value' => $original_post_id,
            'posts_per_page' => 1
        ));

        if ($existing_query->have_posts()) {
            // Update existing post
            $existing_post = $existing_query->posts[0];
            $new_post_id = wp_update_post(array(
                'ID' => $existing_post->ID,
                'post_title' => $post_title,
                'post_content' => $post_content
            ));
        } else {
            // Create new post
            $new_post_id = wp_insert_post(array(
                'post_title' => $post_title,
                'post_content' => $post_content,
                'post_status' => 'publish',
                'post_type' => $original_post->post_type
            ));
        }

        if (is_wp_error($new_post_id) || !$new_post_id) {
            if (is_multisite()) {
                restore_current_blog();
            }
            wp_send_json_error('Failed to create post');
        }

        // Copy featured image to target subsite
        if ($featured_image_id) {
            // Switch back to main blog to get image
            if (is_multisite()) {
                restore_current_blog();
                switch_to_blog(1); // Main site
            }

            $image_url = wp_get_attachment_url($featured_image_id);
            $image_path = get_attached_file($featured_image_id);

            if ($image_path && file_exists($image_path)) {
                // Switch to target blog
                if (is_multisite()) {
                    restore_current_blog();
                    switch_to_blog($target_blog_id);
                }

                // Upload image to target site's media library
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');
                require_once(ABSPATH . 'wp-admin/includes/image.php');

                $upload_file = wp_upload_bits(basename($image_path), null, file_get_contents($image_path));

                if (!$upload_file['error']) {
                    $wp_filetype = wp_check_filetype($upload_file['file'], null);

                    $attachment_data = array(
                        'post_mime_type' => $wp_filetype['type'],
                        'post_title' => sanitize_file_name(pathinfo($upload_file['file'], PATHINFO_FILENAME)),
                        'post_content' => '',
                        'post_status' => 'inherit'
                    );

                    $attach_id = wp_insert_attachment($attachment_data, $upload_file['file']);

                    if ($attach_id) {
                        // Generate attachment metadata
                        $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                        wp_update_attachment_metadata($attach_id, $attach_data);

                        // Set localized alt text
                        if ($image_alt) {
                            update_post_meta($attach_id, '_wp_attachment_image_alt', $image_alt);
                        }

                        // Set as featured image
                        set_post_thumbnail($new_post_id, $attach_id);
                    }
                }
            }
        }

        // Ensure we're on target blog for meta updates
        if (is_multisite() && get_current_blog_id() != $target_blog_id) {
            switch_to_blog($target_blog_id);
        }

        // Add Rank Math meta
        update_post_meta($new_post_id, 'rank_math_focus_keyword', $focus_keyword);
        update_post_meta($new_post_id, 'rank_math_title', $meta_title);
        update_post_meta($new_post_id, 'rank_math_description', $meta_description);

        // Link to original
        update_post_meta($new_post_id, '_localized_from_post', $original_post_id);
        update_post_meta($new_post_id, '_localized_from_blog', 1);

        if (is_multisite()) {
            restore_current_blog();
        }

        wp_send_json_success(array(
            'post_id' => $new_post_id,
            'message' => 'Post published successfully',
            'edit_link' => admin_url('post.php?post=' . $new_post_id . '&action=edit')
        ));
    }

    public function render_settings_page()
    {
        // Handle save
        if (isset($_POST['iptv_content']) && check_admin_referer('iptv_content_nonce')) {
            $content = get_option('iptv_content', array());
            $lang = sanitize_text_field($_POST['current_lang']);
            $content[$lang] = array_map('sanitize_textarea_field', $_POST['iptv_content']);
            update_option('iptv_content', $content);
            echo '<div class="notice notice-success"><p>✅ Content saved!</p></div>';
        }

        $content = get_option('iptv_content', array());
        $current_lang = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : 'en';
        $current_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'homepage';
        ?>
        <div class="wrap">
            <!-- Main Tabs -->
            <h1>🌍 Content Localizing</h1>
            <div class="nav-tab-wrapper" style="margin-bottom: 20px;">
                <a href="?page=iptv-content-settings&tab=homepage"
                    class="nav-tab <?php echo $current_tab === 'homepage' ? 'nav-tab-active' : ''; ?>">
                    🏠 Home Page
                </a>
                <a href="?page=iptv-content-settings&tab=posts"
                    class="nav-tab <?php echo $current_tab === 'posts' ? 'nav-tab-active' : ''; ?>">
                    📝 Posts
                </a>
                <a href="?page=iptv-content-settings&tab=pages"
                    class="nav-tab <?php echo $current_tab === 'pages' ? 'nav-tab-active' : ''; ?>">
                    📄 Pages
                </a>
            </div>

            <?php if ($current_tab === 'posts'): ?>
                <?php $this->render_posts_localizer_tab(); ?>
            <?php elseif ($current_tab === 'pages'): ?>
                <?php $this->render_pages_localizer_tab(); ?>
            <?php else: ?>
                <!-- Home Page Content Editor Tab -->
                <p>Manage all translatable text on your front page. Edit English content, then use OpenAI to auto-translate to other
                    languages.</p>

                <style>
                    .lang-tabs {
                        display: flex;
                        gap: 5px;
                        margin: 20px 0 0 0;
                        border-bottom: 2px solid #2271b1;
                    }

                    .lang-tab {
                        padding: 10px 20px;
                        background: #f0f0f1;
                        border: 1px solid #ddd;
                        border-bottom: none;
                        cursor: pointer;
                        border-radius: 5px 5px 0 0;
                        text-decoration: none;
                        color: #1d2327;
                    }

                    .lang-tab.active {
                        background: #2271b1;
                        color: #fff;
                        border-color: #2271b1;
                    }

                    .lang-tab:hover {
                        background: #ddd;
                    }

                    .lang-tab.active:hover {
                        background: #2271b1;
                    }

                    .content-section {
                        background: #fff;
                        padding: 20px;
                        border: 1px solid #ddd;
                        margin-bottom: 20px;
                        border-radius: 8px;
                    }

                    .content-section h2 {
                        margin-top: 0;
                        border-bottom: 1px solid #eee;
                        padding-bottom: 10px;
                    }

                    .field-row {
                        margin-bottom: 15px;
                    }

                    .field-row label {
                        display: block;
                        font-weight: 600;
                        margin-bottom: 5px;
                    }

                    .field-row input[type="text"] {
                        width: 100%;
                        padding: 8px;
                    }

                    .field-row textarea {
                        width: 100%;
                        height: 80px;
                        padding: 8px;
                    }

                    .translate-btn {
                        background: #0066cc;
                        color: #fff;
                        padding: 10px 20px;
                        border: none;
                        border-radius: 5px;
                        cursor: pointer;
                        margin: 10px 0;
                    }

                    .translate-btn:hover {
                        background: #0052a3;
                    }

                    .translate-btn:disabled {
                        background: #ccc;
                        cursor: not-allowed;
                    }

                    .translate-status {
                        margin-left: 15px;
                        font-style: italic;
                    }
                </style>

                <!-- Language Tabs -->
                <div class="lang-tabs">
                    <?php foreach ($this->languages as $lang_key => $lang): ?>
                        <a href="?page=iptv-content-settings&lang=<?php echo $lang_key; ?>"
                            class="lang-tab <?php echo $current_lang === $lang_key ? 'active' : ''; ?>">
                            <?php echo $lang['flag'] . ' ' . $lang['name']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>

                <form method="post" id="content-form">
                    <?php wp_nonce_field('iptv_content_nonce'); ?>
                    <input type="hidden" name="current_lang" value="<?php echo esc_attr($current_lang); ?>">

                    <?php if ($current_lang !== 'en'): ?>
                        <div style="background: #e8f4fd; padding: 15px; border-radius: 5px; margin: 20px 0; border: 1px solid #b8daff;">
                            <strong>🤖 Auto-Translate from English</strong><br>
                            <div style="display: flex; gap: 10px; align-items: center; margin-top: 10px;">
                                <button type="button" class="translate-btn" id="translate-btn" data-lang="<?php echo $current_lang; ?>">
                                    Translate All to <?php echo $this->languages[$current_lang]['name']; ?> with OpenAI
                                </button>
                                <button type="button" class="translate-btn" id="erase-btn" data-lang="<?php echo $current_lang; ?>"
                                    style="background: #dc3545;">
                                    🗑️ Erase All <?php echo $this->languages[$current_lang]['name']; ?> Translations
                                </button>
                            </div>
                            <span class="translate-status" id="translate-status"></span>
                        </div>
                    <?php endif; ?>

                    <?php foreach ($this->content_fields as $section_key => $section): ?>
                        <div class="content-section">
                            <h2>
                                <?php echo $section['label']; ?>
                            </h2>
                            <?php foreach ($section['fields'] as $field_key => $field):
                                $value = $content[$current_lang][$field_key] ?? ($current_lang === 'en' ? $field['default'] : '');
                                ?>
                                <div class="field-row">
                                    <label for="<?php echo $field_key; ?>">
                                        <?php echo $field['label']; ?>
                                    </label>
                                    <?php if ($field['type'] === 'textarea'): ?>
                                        <textarea name="iptv_content[<?php echo $field_key; ?>]"
                                            id="<?php echo $field_key; ?>"><?php echo esc_textarea($value); ?></textarea>
                                    <?php else: ?>
                                        <input type="text" name="iptv_content[<?php echo $field_key; ?>]" id="<?php echo $field_key; ?>"
                                            value="<?php echo esc_attr($value); ?>">
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>

                    <p>
                        <button type="submit" class="button button-primary button-large">Save Changes</button>
                    </p>
                </form>
            </div>

            <script>
                document.getElementById('translate-btn')?.addEventListener('click', function () {
                    const btn = this;
                    const status = document.getElementById('translate-status');
                    const lang = btn.dataset.lang;

                    btn.disabled = true;
                    status.textContent = 'Translating...';

                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'iptv_translate_content',
                            nonce: '<?php echo wp_create_nonce('iptv_content_nonce'); ?>',
                            target_lang: lang
                        })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                status.textContent = '✅ ' + data.data.message;
                                // Update form fields with translated content
                                for (const [key, value] of Object.entries(data.data.content)) {
                                    const field = document.getElementById(key);
                                    if (field) field.value = value;
                                }
                            } else {
                                status.textContent = '❌ ' + data.data;
                            }
                            btn.disabled = false;
                        })
                        .catch(err => {
                            status.textContent = '❌ Error: ' + err.message;
                            btn.disabled = false;
                        });
                });

                // Erase button handler
                document.getElementById('erase-btn')?.addEventListener('click', function () {
                    const btn = this;
                    const status = document.getElementById('translate-status');
                    const lang = btn.dataset.lang;

                    if (!confirm('Are you sure you want to erase all ' + lang.toUpperCase() + ' translations? This cannot be undone.')) {
                        return;
                    }

                    btn.disabled = true;
                    status.textContent = 'Erasing...';

                    fetch(ajaxurl, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                        body: new URLSearchParams({
                            action: 'iptv_erase_content',
                            nonce: '<?php echo wp_create_nonce('iptv_content_nonce'); ?>',
                            target_lang: lang
                        })
                    })
                        .then(r => r.json())
                        .then(data => {
                            if (data.success) {
                                status.textContent = '✅ ' + data.data.message;
                                // Clear all form fields
                                document.querySelectorAll('#content-form input[type="text"], #content-form textarea').forEach(field => {
                                    field.value = '';
                                });
                            } else {
                                status.textContent = '❌ ' + data.data;
                            }
                            btn.disabled = false;
                        })
                        .catch(err => {
                            status.textContent = '❌ Error: ' + err.message;
                            btn.disabled = false;
                        });
                });
            </script>
        <?php endif; // End of tab conditional ?>
        </div>
        <?php
    }

    /**
     * Render the Posts Localizer tab
     */
    private function render_posts_localizer_tab()
    {
        // Get posts from Main Site
        if (is_multisite()) {
            switch_to_blog(1);
        }

        $posts = get_posts(array(
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 50,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if (is_multisite()) {
            restore_current_blog();
        }

        // Subsite mapping
        $subsites = array(
            2 => array('name' => 'Sweden 🇸🇪', 'lang' => 'sv'),
            3 => array('name' => 'Norway 🇳🇴', 'lang' => 'no'),
            4 => array('name' => 'Denmark 🇩🇰', 'lang' => 'da'),
            5 => array('name' => 'Finland 🇫🇮', 'lang' => 'fi'),
            6 => array('name' => 'Iceland 🇮🇸', 'lang' => 'is')
        );
        ?>
        <div class="post-localizer-wrapper">
            <p>Localize posts from Main Site to subsites with AI-powered SEO optimization.</p>

            <div style="margin: 20px 0; background: #f0f0f1; padding: 15px; border-radius: 5px;">
                <label><strong>Target Subsite:</strong></label>
                <select id="target-subsite" style="padding: 5px 10px; font-size: 14px; margin-left: 10px;">
                    <?php foreach ($subsites as $blog_id => $site): ?>
                        <option value="<?php echo $blog_id; ?>" data-lang="<?php echo $site['lang']; ?>">
                            <?php echo $site['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Posts Section -->
            <div class="content-section">
                <div style="margin: 10px 0;">
                    <button class="button" id="select-all-posts">Select All</button>
                    <button class="button button-primary" id="clone-posts-to-network">Clone Selected to Network</button>
                    <button class="button" id="remove-posts-from-network"
                        style="background: #dc3545; color: white; border-color: #dc3545;">Remove Selected from Network</button>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="check-all-posts" /></th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($posts as $post): ?>
                            <tr>
                                <td><input type="checkbox" class="post-checkbox" value="<?php echo $post->ID; ?>" /></td>
                                <td><strong><?php echo esc_html($post->post_title); ?></strong></td>
                                <td><?php echo date('Y-m-d', strtotime($post->post_date)); ?></td>
                                <td>
                                    <button class="button button-primary localize-btn" data-post-id="<?php echo $post->ID; ?>">
                                        Localize
                                    </button>
                                    <span class="localize-status-<?php echo $post->ID; ?>" style="margin-left: 10px;"></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        $this->render_localizer_modal_and_script();
    }

    /**
     * Render the Pages Localizer tab
     */
    private function render_pages_localizer_tab()
    {
        // Get pages from Main Site
        if (is_multisite()) {
            switch_to_blog(1);
        }

        $pages = get_posts(array(
            'post_type' => 'page',
            'post_status' => 'publish',
            'posts_per_page' => 50,
            'orderby' => 'date',
            'order' => 'DESC'
        ));

        if (is_multisite()) {
            restore_current_blog();
        }

        // Subsite mapping
        $subsites = array(
            2 => array('name' => 'Sweden 🇸🇪', 'lang' => 'sv'),
            3 => array('name' => 'Norway 🇳🇴', 'lang' => 'no'),
            4 => array('name' => 'Denmark 🇩🇰', 'lang' => 'da'),
            5 => array('name' => 'Finland 🇫🇮', 'lang' => 'fi'),
            6 => array('name' => 'Iceland 🇮🇸', 'lang' => 'is')
        );
        ?>
        <div class="post-localizer-wrapper">
            <p>Localize pages from Main Site to subsites with AI-powered SEO optimization.</p>

            <div style="margin: 20px 0; background: #f0f0f1; padding: 15px; border-radius: 5px;">
                <label><strong>Target Subsite:</strong></label>
                <select id="target-subsite" style="padding: 5px 10px; font-size: 14px; margin-left: 10px;">
                    <?php foreach ($subsites as $blog_id => $site): ?>
                        <option value="<?php echo $blog_id; ?>" data-lang="<?php echo $site['lang']; ?>">
                            <?php echo $site['name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Pages Section -->
            <div class="content-section">
                <div style="margin: 10px 0;">
                    <button class="button" id="select-all-pages">Select All</button>
                    <button class="button button-primary" id="clone-pages-to-network">Clone Selected to Network</button>
                    <button class="button" id="remove-pages-from-network"
                        style="background: #dc3545; color: white; border-color: #dc3545;">Remove Selected from Network</button>
                </div>

                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th style="width: 40px;"><input type="checkbox" id="check-all-pages" /></th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pages as $page): ?>
                            <tr>
                                <td><input type="checkbox" class="page-checkbox" value="<?php echo $page->ID; ?>" /></td>
                                <td><strong>
                                        <?php echo esc_html($page->post_title); ?>
                                    </strong></td>
                                <td>
                                    <?php echo date('Y-m-d', strtotime($page->post_date)); ?>
                                </td>
                                <td>
                                    <button class="button button-primary localize-btn" data-post-id="<?php echo $page->ID; ?>">
                                        Localize
                                    </button>
                                    <span class="localize-status-<?php echo $page->ID; ?>" style="margin-left: 10px;"></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        $this->render_localizer_modal();
        $this->render_localizer_script();
    }

    /**
     * Render shared modal and JavaScript for both Posts and Pages tabs
     */
    private function render_localizer_modal_and_script()
    {
        // The modal method already includes the script, so just call it once
        $this->render_localizer_modal();
    }

    /**
     * Shared localizer modal HTML
     */
    private function render_localizer_modal()
    {
        ?>
        <!-- Review Modal with Rank Math Style -->
        <div id="localize-modal"
            style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 0; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 10000; width: 90%; max-width: 950px; max-height: 85vh; overflow: hidden;">
            <!-- Modal Header -->
            <div
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px 30px; border-radius: 8px 8px 0 0;">
                <h2 style="margin: 0; font-size: 20px;">🌍 Review & Optimize Localized Content</h2>
                <p style="margin: 5px 0 0 0; opacity: 0.9; font-size: 14px;">AI-generated content ready for review. Edit as
                    needed, then publish.</p>
            </div>

            <div id="modal-content" style="padding: 30px; overflow-y: auto; max-height: calc(85vh - 140px);">
                <!-- SEO Score Preview (Rank Math style) -->
                <div id="seo-score-section"
                    style="background: #f8f9fa; border-left: 4px solid #667eea; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <div id="seo-score-circle"
                            style="width: 60px; height: 60px; border-radius: 50%; background: conic-gradient(#10b981 0deg, #10b981 270deg, #e5e7eb 270deg); display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 18px; color: #10b981;">
                            75
                        </div>
                        <div>
                            <strong style="display: block; font-size: 16px;">SEO Score: Good</strong>
                            <small style="color: #666;">Focus keyword density optimal • Title length perfect</small>
                        </div>
                    </div>
                </div>

                <!-- Rank Math Style Fields -->
                <div style="display: grid; gap: 20px;">
                    <!-- Focus Keyword -->
                    <div class="rank-math-field">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                            <span style="color: #dc2626;">*</span> Focus Keyword
                        </label>
                        <input type="text" id="focus-keyword"
                            style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px; transition: border-color 0.2s;"
                            placeholder="Enter your target keyword" />
                        <small style="display: block; margin-top: 5px; color: #64748b;">The main keyword you're targeting for
                            SEO</small>
                    </div>

                    <!-- SEO Title -->
                    <div class="rank-math-field">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                            SEO Title <span id="meta-title-counter"
                                style="float: right; color: #64748b; font-weight: normal;">0/60</span>
                        </label>
                        <input type="text" id="meta-title" maxlength="60"
                            style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px;" />
                        <div id="title-preview"
                            style="margin-top: 8px; padding: 10px; background: #f1f5f9; border-radius: 4px; font-size: 13px;">
                            <div style="color: #1e40af; font-size: 18px; line-height: 1.3; margin-bottom: 2px;"
                                id="title-preview-text">Your SEO Title Here</div>
                            <div style="color: #22c55e;">https://yoursite.com/page-url</div>
                        </div>
                    </div>

                    <!-- SEO Description -->
                    <div class="rank-math-field">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                            Meta Description <span id="meta-desc-counter"
                                style="float: right; color: #64748b; font-weight: normal;">0/155</span>
                        </label>
                        <textarea id="meta-description" maxlength="155"
                            style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 14px; height: 70px; resize: vertical;"></textarea>
                        <div id="desc-preview"
                            style="margin-top: 8px; padding: 10px; background: #f1f5f9; border-radius: 4px; font-size: 13px;">
                            <div style="color: #64748b; line-height: 1.5;" id="desc-preview-text">Your meta description will
                                appear here...</div>
                        </div>
                    </div>

                    <hr style="border: none; border-top: 1px solid #e2e8f0; margin: 10px 0;" />

                    <!-- Post Title -->
                    <div class="rank-math-field">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                            <span style="color: #dc2626;">*</span> Post Title
                        </label>
                        <input type="text" id="post-title"
                            style="width: 100%; padding: 10px 12px; border: 2px solid #e2e8f0; border-radius: 6px; font-size: 16px; font-weight: 500;" />
                    </div>

                    <!-- Post Content -->
                    <div class="rank-math-field">
                        <label style="display: block; font-weight: 600; margin-bottom: 8px; color: #1e293b;">
                            Post Content
                        </label>
                        <textarea id="post-content"
                            style="width: 100%; padding: 12px; border: 2px solid #e2e8f0; border-radius: 6px; height: 250px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 14px; line-height: 1.6; resize: vertical;"></textarea>
                        <small style="display: block; margin-top: 5px; color: #64748b;">Word count: <span
                                id="word-count">0</span> words</small>
                    </div>
                </div>

                <input type="hidden" id="original-post-id" />
                <input type="hidden" id="target-blog-id" />
            </div>

            <!-- Modal Footer -->
            <div
                style="background: #f8f9fa; padding: 20px 30px; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <button class="button" id="close-modal-btn" style="margin-right: 10px;">Cancel</button>
                    <button class="button button-primary" id="publish-btn"
                        style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; padding: 8px 20px; font-weight: 600;">
                        📤 Publish to Subsite
                    </button>
                    <span id="publish-status" style="margin-left: 15px; font-weight: 500;"></span>
                </div>
                <button class="button" id="edit-in-rankmath-btn"
                    style="display:none; background: #0073aa; color: white; border: none;">
                    ✏️ Continue in Rank Math Editor
                </button>
            </div>
        </div>
        <div id="modal-overlay"
            style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
        </div>

        <script>
            jQuery(document).ready(function ($) {
                const nonce = '<?php echo wp_create_nonce('iptv_localizer_nonce'); ?>';

                // Character counters
                $('#meta-title').on('input', function () {
                    $('#meta-title-counter').text($(this).val().length + '/60');
                });
                $('#meta-description').on('input', function () {
                    $('#meta-desc-counter').text($(this).val().length + '/155');
                });

                // Select all checkboxes
                $('#check-all-posts').on('change', function () {
                    $('.post-checkbox').prop('checked', $(this).is(':checked'));
                });
                $('#check-all-pages').on('change', function () {
                    $('.page-checkbox').prop('checked', $(this).is(':checked'));
                });
                $('#select-all-posts').on('click', function () {
                    $('.post-checkbox').prop('checked', true);
                });
                $('#select-all-pages').on('click', function () {
                    $('.page-checkbox').prop('checked', true);
                });

                // Clone to Network
                $('#clone-posts-to-network, #clone-pages-to-network').on('click', function () {
                    const isPost = $(this).attr('id') === 'clone-posts-to-network';
                    const selectedIds = [];
                    $(isPost ? '.post-checkbox:checked' : '.page-checkbox:checked').each(function () {
                        selectedIds.push($(this).val());
                    });

                    if (selectedIds.length === 0) {
                        alert('Please select at least one item');
                        return;
                    }

                    if (!confirm('Clone ' + selectedIds.length + ' items to ALL subsites?')) {
                        return;
                    }

                    alert('Clone to Network feature coming soon! Selected IDs: ' + selectedIds.join(', '));
                });

                // Remove from Network
                $('#remove-posts-from-network, #remove-pages-from-network').on('click', function () {
                    const isPost = $(this).attr('id') === 'remove-posts-from-network';
                    const selectedIds = [];
                    $(isPost ? '.post-checkbox:checked' : '.page-checkbox:checked').each(function () {
                        selectedIds.push($(this).val());
                    });

                    if (selectedIds.length === 0) {
                        alert('Please select at least one item');
                        return;
                    }

                    if (!confirm('Remove ' + selectedIds.length + ' items from ALL subsites? This cannot be undone!')) {
                        return;
                    }

                    alert('Remove from Network feature coming soon! Selected IDs: ' + selectedIds.join(', '));
                });

                // Localize button click
                $('.localize-btn').on('click', function () {
                    const btn = $(this);
                    const postId = btn.data('post-id');
                    const targetSubsite = $('#target-subsite');
                    const targetLang = targetSubsite.find(':selected').data('lang');
                    const targetBlogId = targetSubsite.val();
                    const statusSpan = $('.localize-status-' + postId);

                    btn.prop('disabled', true);
                    statusSpan.text('🔄 Generating...');

                    $.post(ajaxurl, {
                        action: 'iptv_generate_localized_content',
                        nonce: nonce,
                        post_id: postId,
                        target_lang: targetLang
                    }).done(function (response) {
                        if (response.success) {
                            // Populate modal
                            $('#focus-keyword').val(response.data.focus_keyword || '');
                            $('#meta-title').val(response.data.meta_title || '').trigger('input');
                            $('#meta-description').val(response.data.meta_description || '').trigger('input');
                            $('#post-title').val(response.data.title || '');
                            $('#post-content').val(response.data.content || '');
                            $('#original-post-id').val(postId);
                            $('#target-blog-id').val(targetBlogId);

                            // Show modal
                            $('#localize-modal, #modal-overlay').fadeIn();
                            statusSpan.text('✅ Ready to review');
                        } else {
                            statusSpan.text('❌ ' + response.data);
                        }
                        btn.prop('disabled', false);
                    }).fail(function (xhr) {
                        statusSpan.text('❌ Error: ' + xhr.responseText);
                        btn.prop('disabled', false);
                    });
                });

                // Close modal
                $('#close-modal-btn, #modal-overlay').on('click', function () {
                    $('#localize-modal, #modal-overlay').fadeOut();
                });

                // Publish button
                $('#publish-btn').on('click', function () {
                    const btn = $(this);
                    const status = $('#publish-status');

                    btn.prop('disabled', true);
                    status.text('🔄 Publishing...');

                    $.post(ajaxurl, {
                        action: 'iptv_publish_localized_post',
                        nonce: nonce,
                        original_post_id: $('#original-post-id').val(),
                        target_blog_id: $('#target-blog-id').val(),
                        post_title: $('#post-title').val(),
                        post_content: $('#post-content').val(),
                        focus_keyword: $('#focus-keyword').val(),
                        meta_title: $('#meta-title').val(),
                        meta_description: $('#meta-description').val()
                    }).done(function (response) {
                        if (response.success) {
                            status.text('✅ ' + response.data.message);
                            setTimeout(function () {
                                $('#localize-modal, #modal-overlay').fadeOut();
                            }, 2000);
                        } else {
                            status.text('❌ ' + response.data);
                        }
                        btn.prop('disabled', false);
                    }).fail(function (xhr) {
                        status.text('❌ Error');
                        btn.prop('disabled', false);
                    });
                });
            });
        </script>
        <?php
    }

    /**
     * Get text for current language
     * Note: In Multisite, content is stored on the MAIN site and shared to all subsites
     */
    public static function get_text($key, $default = '')
    {
        // In Multisite, get content from the MAIN site (blog_id 1) so all translations are shared
        if (is_multisite()) {
            switch_to_blog(1); // Switch to main site
        }

        // Get content from main site's database
        $content = get_option('iptv_content', array());

        if (is_multisite()) {
            restore_current_blog(); // Switch back to current site
        }

        // Detect current language - try multiple methods
        $lang = 'en'; // Default to English

        // Method 1: Check REQUEST_URI for language code
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
        $path_parts = explode('/', trim($request_uri, '/'));
        $first_segment = isset($path_parts[0]) ? $path_parts[0] : '';

        if (in_array($first_segment, array('se', 'no', 'dk', 'fi', 'is'))) {
            $lang = $first_segment;
        }

        // Method 2: Check WordPress blog path (for Multisite)
        if ($lang === 'en' && function_exists('get_blog_details')) {
            $blog_details = get_blog_details();
            if ($blog_details && !empty($blog_details->path)) {
                $blog_path = trim($blog_details->path, '/');
                if (in_array($blog_path, array('se', 'no', 'dk', 'fi', 'is'))) {
                    $lang = $blog_path;
                }
            }
        }

        // Debug: Add this to footer to see what's happening (remove in production)
        // add_action('wp_footer', function() use ($lang, $request_uri, $key) {
        //     echo "<!-- DEBUG: lang=$lang, uri=$request_uri, key=$key -->";
        // });

        // Return language-specific content or English fallback
        if (isset($content[$lang][$key]) && !empty($content[$lang][$key])) {
            return $content[$lang][$key];
        }
        if (isset($content['en'][$key]) && !empty($content['en'][$key])) {
            return $content['en'][$key];
        }

        return $default;
    }
}

// Initialize
new IPTV_Content_Settings();

/**
 * Helper function to get translated text
 */
function iptv_text($key, $default = '')
{
    return IPTV_Content_Settings::get_text($key, $default);
}
