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
            '📝 Content',
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

        // Build prompt for OpenAI
        $prompt = "You are an SEO expert specializing in content localization.\n\n";
        $prompt .= "Original content:\n";
        $prompt .= "Title: " . $post->post_title . "\n";
        $prompt .= "Content: " . wp_strip_all_tags($post->post_content) . "\n\n";
        $prompt .= "Task:\n";
        $prompt .= "1. Analyze the search intent of this content\n";
        $prompt .= "2. Generate a NEW native focus keyword for $target_language (do NOT just translate)\n";
        $prompt .= "3. Rewrite the title optimized for the new keyword\n";
        $prompt .= "4. Rewrite the content optimized for the new keyword in $target_language\n";
        $prompt .= "5. Create an SEO meta title (max 60 chars)\n";
        $prompt .= "6. Create an SEO meta description (max 155 chars)\n\n";
        $prompt .= "Return ONLY a JSON object with these exact keys:\n";
        $prompt .= "{\n";
        $prompt .= '  "focus_keyword": "native keyword in ' . $target_language . '",';
        $prompt .= "\n";
        $prompt .= '  "title": "post title",';
        $prompt .= "\n";
        $prompt .= '  "content": "full post content",';
        $prompt .= "\n";
        $prompt .= '  "meta_title": "SEO title",';
        $prompt .= "\n";
        $prompt .= '  "meta_description": "SEO description"';
        $prompt .= "\n}\n";

        // Call OpenAI
        $translator = new Theme_OpenAI_Translator();
        $result = $translator->translate($prompt, $target_lang);

        if (!$result) {
            wp_send_json_error('OpenAI translation failed');
        }

        // Try to parse JSON from result
        $result = trim($result);
        // Remove markdown code blocks if present
        $result = preg_replace('/```json\s*(.*?)\s*```/s', '$1', $result);
        $result = preg_replace('/```\s*(.*?)\s*```/s', '$1', $result);

        $data = json_decode($result, true);

        if (!$data || !isset($data['focus_keyword'])) {
            // If JSON parsing failed, return raw result
            wp_send_json_error('Failed to parse OpenAI response: ' . $result);
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
            'message' => 'Post published successfully'
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
        ?>
        <div class="wrap">
            <h1>📝 Front Page Content</h1>
            <p>Manage all translatable text on your front page. Edit English content, then use DeepL to auto-translate to other
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
