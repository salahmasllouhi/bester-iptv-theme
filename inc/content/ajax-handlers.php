<?php
/**
 * AJAX Handlers for IPTV Content Settings
 * 
 * This file contains all AJAX handler methods extracted from the main
 * IPTV_Content_Settings class for better code organization.
 * 
 * @package IPTV_Theme
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

trait IPTV_Content_AJAX_Handlers
{
    /**
     * AJAX handler for saving homepage content
     */
    public function ajax_save_content()
    {
        check_ajax_referer('iptv_content_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $all_content = get_option('iptv_content', array());
        $target_lang = sanitize_text_field($_POST['target_lang']);

        // Sanitize submitted content
        $content = array();
        if (isset($_POST['content']) && is_array($_POST['content'])) {
            foreach ($_POST['content'] as $key => $value) {
                $content[sanitize_text_field($key)] = sanitize_textarea_field($value);
            }
        }

        $all_content[$target_lang] = $content;
        update_option('iptv_content', $all_content);

        wp_send_json_success(array(
            'message' => 'Content saved for ' . $this->languages[$target_lang]['name']
        ));
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

    /**
     * AJAX handler for DeepL translation
     */
    public function ajax_translate_content()
    {
        check_ajax_referer('iptv_content_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        // Increase processing time for AI operations
        set_time_limit(300); // 5 minutes
        ignore_user_abort(true); // Continue even if user closes connection

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

        // Support for single field translation (Client-side iteration)
        $field_key_param = isset($_POST['field_key']) ? sanitize_text_field($_POST['field_key']) : null;
        if ($field_key_param && isset($english_to_translate[$field_key_param])) {
            // Only translate this specific field
            $english_to_translate = array($field_key_param => $english_to_translate[$field_key_param]);
        }

        if (empty($english_to_translate)) {
            wp_send_json_error('No content to translate');
        }

        // Get OpenAI translator (uses secure API key from WordPress options)
        $translator = get_openai_translator();
        if (!$translator || !$translator->is_configured()) {
            wp_send_json_error('OpenAI API not configured. Go to Settings → 🤖 OpenAI API to add your API key.');
        }

        // Get target language information
        $target_language = $translator->get_target_language($target_lang);

        // Get Target Currency for Pricing Context
        $languages = $this->languages; // Access the class property via local var
        $target_currency = isset($languages[$target_lang]['currency']) ? $languages[$target_lang]['currency'] : 'USD ($)';
        $currency_context = "";

        // Only add currency context if target is NOT English
        if ($target_lang !== 'en') {
            $currency_context = "CONTEXT: Target Currency is {$target_currency}.\n";
            $currency_context .= "INSTRUCTION: If the text contains prices (like '$10', '$5.99'), CONVERT them to {$target_currency} using standard marketing pricing (approximate). Example: '$5.83' -> '59 kr' (or appropriate local equivalent). Use the correct symbol.";
        }

        // Translate all fields using OpenAI
        $translated = array();
        foreach ($english_to_translate as $field_key => $english_text) {
            if (!empty($english_text)) {
                // First translate with OpenAI (strict translation, no extra words)
                // Pass currency context as 4th argument
                $translation = $translator->translate($english_text, $target_language, 'English', $currency_context);

                // Then apply glossary overrides for exact matches (if needed)
                if (isset($this->glossary[$target_lang][$english_text])) {
                    $translation = $this->glossary[$target_lang][$english_text];
                }

                $translated[$field_key] = $translation;
            }
        }

        // Check for API errors
        $last_error = $translator->get_last_error();
        if ($last_error) {
            wp_send_json_error('Translation Partial/Failed: ' . $last_error . ' (Check your API Key and Model setting)');
        }

        // Save translated content
        if ($field_key_param) {
            // Merge single field update to avoid overwriting other fields
            if (!isset($content[$target_lang])) {
                $content[$target_lang] = array();
            }
            // Update only the specific field
            if (isset($translated[$field_key_param])) {
                $content[$target_lang][$field_key_param] = $translated[$field_key_param];
            }
        } else {
            // Legacy: Overwrite all content for this language (used for "Translate All" single request)
            $content[$target_lang] = $translated;
        }

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

        // Check if this is "fill missing" mode (only generate empty fields)
        $fill_missing_mode = isset($_POST['fill_missing']) && $_POST['fill_missing'] === 'true';

        // Check if this is "refresh content only" mode (rewrite content based on existing)
        $refresh_content_mode = isset($_POST['refresh_content_only']) && $_POST['refresh_content_only'] === 'true';

        // Get user's custom keyword if provided
        $custom_keyword = isset($_POST['custom_keyword']) ? sanitize_text_field($_POST['custom_keyword']) : '';

        // Debug log for refresh content mode
        if ($refresh_content_mode) {
            error_log("REFRESH CONTENT MODE - Keyword: '$custom_keyword', Content length: " . strlen($_POST['existing_content'] ?? ''));
        }

        // Get existing field values if in fill-missing mode
        $existing_title = isset($_POST['existing_title']) ? sanitize_text_field($_POST['existing_title']) : '';
        $existing_content = isset($_POST['existing_content']) ? wp_kses_post($_POST['existing_content']) : '';
        $existing_meta_title = isset($_POST['existing_meta_title']) ? sanitize_text_field($_POST['existing_meta_title']) : '';
        $existing_meta_desc = isset($_POST['existing_meta_desc']) ? sanitize_textarea_field($_POST['existing_meta_desc']) : '';
        $existing_slug = isset($_POST['existing_slug']) ? sanitize_text_field($_POST['existing_slug']) : '';
        $existing_image_alt = isset($_POST['existing_image_alt']) ? sanitize_text_field($_POST['existing_image_alt']) : '';

        // Get original post
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error('Post not found');
        }

        // Language mapping - MUST match frontend codes from content-settings.php
        $lang_map = array(
            'en' => 'English',
            'se' => 'Swedish',    // Frontend uses 'se', not 'sv'
            'sv' => 'Swedish',    // Also support 'sv' for backwards compatibility
            'no' => 'Norwegian',
            'dk' => 'Danish',     // Frontend uses 'dk', not 'da'
            'da' => 'Danish',     // Also support 'da' for backwards compatibility
            'fi' => 'Finnish',
            'is' => 'Icelandic'
        );

        $target_language = isset($lang_map[$target_lang]) ? $lang_map[$target_lang] : 'English';

        // Debug log to verify language is being set correctly
        error_log("IPTV Generate Content - Target Lang Code: $target_lang, Target Language: $target_language");

        // Get featured image and alt text
        $featured_image_id = get_post_thumbnail_id($post_id);
        $featured_image_url = '';
        $featured_image_alt = '';

        if ($featured_image_id) {
            $featured_image_url = wp_get_attachment_url($featured_image_id);
            $featured_image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
        }

        // Build prompt for OpenAI - PRO SEO EXPERT MODE
        if ($refresh_content_mode) {
            // Simple, direct prompt that forces exact keyword usage
            $prompt = "You are an EXPERT SEO Content Rewriter. Rewrite this article in $target_language to achieve a 100/100 Rank Math SEO Score.\n\n";

            if ($custom_keyword) {
                // Use find/replace instruction - this is clearer for LLMs
                $prompt .= "IMPORTANT: Replace ALL mentions of 'Firestick', 'Fire Stick', 'Fire TV Stick', 'Amazon Firestick', 'Firestick IPTV setup', or any similar term with exactly: \"$custom_keyword\"\n\n";

                $prompt .= "═══════════════════════════════════════════════════════════════\n";
                $prompt .= "         STRICT KEYWORD RULES (RANK MATH SEO PRO)\n";
                $prompt .= "═══════════════════════════════════════════════════════════════\n";
                $prompt .= "The phrase \"$custom_keyword\" MUST appear:\n";
                $prompt .= "✓ In the FIRST sentence of the first paragraph (CRITICAL)\n";
                $prompt .= "✓ In at least 3 H2 headings\n";
                $prompt .= "✓ In at least 2 H3 headings\n";
                $prompt .= "✓ Keyword Density: 1.5% - 2.5% (Approx 15-20 times total)\n";
                $prompt .= "✓ In the LAST paragraph\n";
                $prompt .= "✓ Bolded with <strong> tags at least 5 times\n\n";
            }

            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "         CONTENT READABILITY & STRUCTURE RULES \n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "✓ Paragraphs: Short! Max 150 words per paragraph. (Preferred 3-4 lines)\n";
            $prompt .= "✓ Sentence Length: Short! distinct sentences. Max 20 words per sentence.\n";
            $prompt .= "✓ Transition Words: Use in >30% of sentences (e.g., 'However', 'Furthermore', 'Therefore').\n";
            $prompt .= "✓ Active Voice: Use active voice in >90% of sentences.\n";
            $prompt .= "✓ Formatting: Use bullet points (<ul>) and numbered lists (<ol>) frequently only where appropriate.\n";
            $prompt .= "✓ Subheadings: Use H2 and H3 tags to break up content clearly (every 300 words).\n";
            $prompt .= "✓ Tone: Engaging, helpful, and professional.\n\n";

            $prompt .= "Article to rewrite:\n";
            $prompt .= "---\n";
            $prompt .= $existing_content . "\n";
            $prompt .= "---\n\n";

            $prompt .= "Output format: Return ONLY a JSON object with a single 'content' key containing the HTML.\n";
            $prompt .= "Use proper HTML tags: <h2>, <h3>, <p>, <ul>, <ol>, <li>, <strong>\n\n";

            $prompt .= "Example output:\n";
            $prompt .= "{\"content\": \"<p>The <strong>$custom_keyword</strong> is a great device...</p><h2>How to set up your $custom_keyword</h2>...\"}\n";

        } elseif ($fill_missing_mode) {
            // Fill missing mode with STRICT SEO optimization
            $prompt = "You are an EXPERT SEO Content Writer. Generate ONLY the missing fields following Rank Math 100/100 guidelines.\n\n";

            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "★★★ CRITICAL: ALL OUTPUT MUST BE IN $target_language ★★★\n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "Do NOT write in English. Write EVERYTHING in native $target_language.\n";
            $prompt .= "This includes: title, content, meta_title, meta_description, focus_keyword.\n\n";

            $prompt .= "=== SOURCE CONTENT (English - TRANSLATE & ADAPT FULLY) ===\n";
            $prompt .= "Title: " . $post->post_title . "\n";
            $prompt .= "Content:\n" . $post->post_content . "\n\n";

            if ($custom_keyword) {
                $prompt .= "★★★ PRIMARY FOCUS KEYWORD (USE EXACTLY): \"$custom_keyword\" ★★★\n\n";
            }

            $prompt .= "=== EXISTING FIELDS (DO NOT REGENERATE) ===\n";
            if ($existing_title)
                $prompt .= "✓ Title: " . $existing_title . " (KEEP - do NOT include in output)\n";
            if ($existing_content)
                $prompt .= "✓ Content: (EXISTS - KEEP - do NOT include in output)\n";
            if ($existing_meta_title)
                $prompt .= "✓ Meta Title: " . $existing_meta_title . " (KEEP - do NOT include in output)\n";
            if ($existing_meta_desc)
                $prompt .= "✓ Meta Description: " . $existing_meta_desc . " (KEEP - do NOT include in output)\n";
            if ($existing_slug)
                $prompt .= "✓ Slug: " . $existing_slug . " (KEEP - do NOT include in output)\n";
            if ($existing_image_alt)
                $prompt .= "✓ Image Alt: " . $existing_image_alt . " (KEEP - do NOT include in output)\n";

            $prompt .= "\n═══════════════════════════════════════════════════════════════\n";
            $prompt .= "           STRICT SEO RULES FOR MISSING FIELDS ONLY\n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n\n";

            $prompt .= "【1】FOCUS KEYWORD:\n";
            $prompt .= "   ✓ 2-4 words, native $target_language\n";
            $prompt .= "   ✓ What people actually search for\n\n";

            $prompt .= "【2】SEO TITLE (meta_title):\n";
            $prompt .= "   ✓ EXACTLY 50-60 characters (count carefully!)\n";
            $prompt .= "   ✓ Keyword MUST appear in first 50 characters\n";
            $prompt .= "   ✓ Include power word (Ultimate, Best, Complete, Guide, Easy)\n\n";

            $prompt .= "【3】META DESCRIPTION:\n";
            $prompt .= "   ✓ EXACTLY 120-160 characters (count carefully!)\n";
            $prompt .= "   ✓ Keyword MUST appear in FIRST sentence\n";
            $prompt .= "   ✓ Include call-to-action\n\n";

            $prompt .= "【4】URL SLUG:\n";
            $prompt .= "   ✓ Include ALL keyword words\n";
            $prompt .= "   ✓ Lowercase with hyphens only\n";
            $prompt .= "   ✓ Short and clean (3-5 words)\n\n";

            $prompt .= "【5】CONTENT (if missing):\n";
            $prompt .= "   ✓ CRITICAL: TRANSLATE FULL ARTICLE. Do NOT summarize.\n";
            $prompt .= "   ✓ LENGTH: Must match source content length (approx same number of paragraphs).\n";
            $prompt .= "   ✓ STRUCTURE: Keep all H2/H3 headings and sections from source.\n";
            $prompt .= "   ✓ Keyword in FIRST paragraph (first 10%)\n";
            $prompt .= "   ✓ Keyword in at least ONE <h2> heading\n";
            $prompt .= "   ✓ 1-2.5% keyword density\n";
            $prompt .= "   ✓ Short paragraphs (<120 words)\n";
            $prompt .= "   ✓ Proper HTML structure\n\n";

            $prompt .= "【6】IMAGE ALT:\n";
            $prompt .= "   ✓ Descriptive with keyword included\n";
            $prompt .= "   ✓ Under 125 characters\n\n";

            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "              OUTPUT: ONLY MISSING FIELDS AS JSON\n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n\n";

            $prompt .= "Return ONLY fields that are MISSING (not marked KEEP above):\n{\n";
            if (!$custom_keyword)
                $prompt .= '  "focus_keyword": "native keyword 2-4 words",' . "\n";
            if (!$existing_title)
                $prompt .= '  "title": "H1 title with keyword naturally",' . "\n";
            if (!$existing_content)
                $prompt .= '  "content": "<p>Keyword in first paragraph...</p><h2>Keyword in heading</h2>...",' . "\n";
            if (!$existing_meta_title)
                $prompt .= '  "meta_title": "Keyword First | Power Word - 50-60 chars exact",' . "\n";
            if (!$existing_meta_desc)
                $prompt .= '  "meta_description": "Keyword in first sentence. 120-160 chars exact.",' . "\n";
            if (!$existing_slug)
                $prompt .= '  "slug": "keyword-words-here"' . (($featured_image_alt && !$existing_image_alt) ? ',' : '') . "\n";
            if ($featured_image_alt && !$existing_image_alt)
                $prompt .= '  "image_alt": "descriptive alt with keyword"' . "\n";
            $prompt .= "}\n";

        } else {
            // Full regeneration mode - STRICT SEO optimization for Rank Math 100/100
            $prompt = "You are an EXPERT SEO Content Writer creating content that MUST achieve Rank Math 100/100 score.\n\n";

            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "★★★ CRITICAL: ALL OUTPUT MUST BE IN $target_language ★★★\n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "Do NOT write in English. Write EVERYTHING in native $target_language.\n";
            $prompt .= "This includes: focus_keyword, title, content, meta_title, meta_description, slug, image_alt.\n\n";

            $prompt .= "=== SOURCE CONTENT (English - for reference only) ===\n";
            $prompt .= "Title: " . $post->post_title . "\n";
            $prompt .= "Content:\n" . $post->post_content . "\n";
            if ($featured_image_alt) {
                $prompt .= "Image Alt: " . $featured_image_alt . "\n";
            }
            $prompt .= "\n";

            if ($custom_keyword) {
                $prompt .= "★★★ PRIMARY FOCUS KEYWORD (MUST USE EXACTLY): \"$custom_keyword\" ★★★\n\n";
            } else {
                $prompt .= "Generate a native $target_language focus keyword (2-4 words, what people actually search for in that language).\n\n";
            }

            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "               MANDATORY RANK MATH SEO REQUIREMENTS\n";
            $prompt .= "               YOU MUST FOLLOW EVERY SINGLE RULE BELOW\n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n\n";

            $prompt .= "【1】FOCUS KEYWORD PLACEMENT - CRITICAL ✓\n";
            $prompt .= "   ✓ The EXACT focus keyword MUST appear in:\n";
            $prompt .= "     • meta_title - within first 50 characters\n";
            $prompt .= "     • meta_description - in the FIRST sentence\n";
            $prompt .= "     • slug - all keyword words included\n";
            $prompt .= "     • title (H1) - naturally included\n";
            $prompt .= "     • First paragraph of content (first 10%)\n";
            $prompt .= "     • At least ONE H2 subheading\n";
            $prompt .= "     • Content body multiple times (1-2.5% density)\n\n";

            $prompt .= "【2】SEO TITLE (meta_title) - STRICT FORMAT ✓\n";
            $prompt .= "   ✓ Length: EXACTLY 50-60 characters (count carefully!)\n";
            $prompt .= "   ✓ Focus keyword MUST appear in first 50 characters\n";
            $prompt .= "   ✓ Include ONE power word: Ultimate, Best, Complete, Essential, Guide, Easy, Fast, Simple, Proven, Free\n";
            $prompt .= "   ✓ Use pipe (|) or dash (-) as separator\n";
            $prompt .= "   ✓ Example: \"[Keyword]: Ultimate Guide | [Brand]\" or \"Best [Keyword] - Complete Guide 2026\"\n\n";

            $prompt .= "【3】META DESCRIPTION - STRICT FORMAT ✓\n";
            $prompt .= "   ✓ Length: EXACTLY 120-160 characters (count carefully!)\n";
            $prompt .= "   ✓ Focus keyword MUST appear in FIRST sentence\n";
            $prompt .= "   ✓ Include a clear call-to-action (Learn, Discover, Find out, Get, Try)\n";
            $prompt .= "   ✓ Describe the benefit to the reader\n";
            $prompt .= "   ✓ Make it compelling for clicks\n\n";

            $prompt .= "【4】URL SLUG - STRICT FORMAT ✓\n";
            $prompt .= "   ✓ Include ALL words from focus keyword\n";
            $prompt .= "   ✓ Lowercase only, hyphens between words\n";
            $prompt .= "   ✓ Short and readable (3-5 words max)\n";
            $prompt .= "   ✓ No special characters, no stop words (the, a, an)\n\n";

            $prompt .= "【5】POST TITLE (H1) - REQUIRED ✓\n";
            $prompt .= "   ✓ Include focus keyword naturally\n";
            $prompt .= "   ✓ Engaging and click-worthy\n";
            $prompt .= "   ✓ Can be different from meta_title (can be longer)\n\n";

            $prompt .= "【6】CONTENT BODY - STRICT STRUCTURE ✓\n";
            $prompt .= "   ✓ Focus keyword in FIRST paragraph (mandatory!)\n";
            $prompt .= "   ✓ Focus keyword in at least ONE <h2> heading\n";
            $prompt .= "   ✓ Keyword density: 1-2.5% (use keyword 5-15 times naturally)\n";
            $prompt .= "   ✓ Minimum 1000 words (longer = better for SEO)\n";
            $prompt .= "   ✓ Use <strong> tags to bold the keyword 2-3 times\n";
            $prompt .= "   ✓ Short paragraphs: MAX 120 words per <p> tag\n";
            $prompt .= "   ✓ Add H2/H3 subheading every 300 words\n";
            $prompt .= "   ✓ Use bullet points <ul><li> and numbered lists <ol><li>\n";
            $prompt .= "   ✓ Proper hierarchy: H2 → H3 → H4 (never skip levels)\n\n";

            $prompt .= "【7】HTML FORMATTING - STRICT ✓\n";
            $prompt .= "   ✓ All text MUST be in proper HTML tags\n";
            $prompt .= "   ✓ Use: <h2>, <h3>, <p>, <ul>, <ol>, <li>, <strong>, <em>\n";
            $prompt .= "   ✓ NO naked text (everything wrapped in tags)\n";
            $prompt .= "   ✓ Clean, valid HTML only\n\n";

            $prompt .= "【8】IMAGE ALT TEXT ✓\n";
            $prompt .= "   ✓ Include focus keyword naturally\n";
            $prompt .= "   ✓ Descriptive of image content\n";
            $prompt .= "   ✓ Under 125 characters\n\n";

            $prompt .= "═══════════════════════════════════════════════════════════════\n";
            $prompt .= "                      JSON OUTPUT FORMAT\n";
            $prompt .= "═══════════════════════════════════════════════════════════════\n\n";

            $prompt .= "Return ONLY valid JSON (no markdown, no explanation).\n";
            $prompt .= "CRITICAL: Ensure all newlines in content are escaped as \\n. No literal control characters.\n";
            $prompt .= "{\n";
            $prompt .= '  "focus_keyword": "exact native ' . $target_language . ' keyword 2-4 words",' . "\n";
            $prompt .= '  "title": "H1 title with keyword naturally included",' . "\n";
            $prompt .= '  "content": "<p>First paragraph with <strong>keyword</strong>...</p><h2>Heading with keyword</h2><p>...</p>",' . "\n";
            $prompt .= '  "meta_title": "Keyword First | Power Word - 50-60 chars exact",' . "\n";
            $prompt .= '  "meta_description": "Keyword in first sentence. Benefit to reader. Call to action. 120-160 chars exact.",' . "\n";
            $prompt .= '  "slug": "keyword-words-here"';
            if ($featured_image_alt) {
                $prompt .= ",\n";
                $prompt .= '  "image_alt": "descriptive alt with keyword naturally"';
            }
            $prompt .= "\n}\n\n";

            $prompt .= "★★★ SELF-CHECK BEFORE RESPONDING ★★★\n";
            $prompt .= "Before returning JSON, verify:\n";
            $prompt .= "□ Is keyword in meta_title within first 50 chars? YES/NO\n";
            $prompt .= "□ Is meta_title 50-60 characters? YES/NO\n";
            $prompt .= "□ Is keyword in first sentence of meta_description? YES/NO\n";
            $prompt .= "□ Is meta_description 120-160 characters? YES/NO\n";
            $prompt .= "□ Does slug contain keyword words? YES/NO\n";
            $prompt .= "□ Is keyword in first paragraph of content? YES/NO\n";
            $prompt .= "□ Is keyword in at least one H2 heading? YES/NO\n";
            $prompt .= "□ Is content 1000+ words? YES/NO\n";
            $prompt .= "\nALL must be YES. Fix any NO before responding.\n";
        }

        // Call OpenAI API directly (not translate() which has token limits)
        $translator = new Theme_OpenAI_Translator();
        $api_key = get_option('openai_api_key');
        $model = $translator->get_model();

        if (empty($api_key)) {
            wp_send_json_error('OpenAI API key not configured');
        }

        // Determine token parameter based on model
        // GPT-5 and o1/preview models use max_completion_tokens
        $is_gpt5_or_o1 = (strpos($model, 'gpt-5') !== false || strpos($model, 'o1-') !== false);
        $token_param = $is_gpt5_or_o1 ? 'max_completion_tokens' : 'max_tokens';

        // Set reasonable token limits based on model
        $token_limit = $is_gpt5_or_o1 ? 32000 : 4096;

        $body_args = array(
            'model' => $model,
            'messages' => array(
                array('role' => 'user', 'content' => $prompt)
            ),
        );

        // GPT-5 and o1 models don't support temperature parameter
        if (!$is_gpt5_or_o1) {
            $body_args['temperature'] = 0.7;
        }

        $body_args[$token_param] = $token_limit;

        // Log the request for debugging
        error_log('OpenAI Request - Model: ' . $model . ', Token Param: ' . $token_param . ', Limit: ' . $token_limit . ', Prompt Length: ' . strlen($prompt));

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'timeout' => 180, // Increased timeout for GPT-5
            'headers' => array(
                'Authorization' => 'Bearer ' . $api_key,
                'Content-Type' => 'application/json',
            ),
            'body' => json_encode($body_args),
        ));

        if (is_wp_error($response)) {
            wp_send_json_error('OpenAI request failed: ' . $response->get_error_message());
        }

        $body = json_decode(wp_remote_retrieve_body($response), true);

        // Check for API errors
        if (isset($body['error'])) {
            $error_msg = isset($body['error']['message']) ? $body['error']['message'] : 'Unknown OpenAI error';
            wp_send_json_error('OpenAI Error: ' . $error_msg);
        }

        if (!isset($body['choices'][0]['message']['content'])) {
            // Log full response for debugging
            error_log('OpenAI Critical Error: ' . print_r($body, true));
            wp_send_json_error('Invalid structure. Raw response: ' . substr(json_encode($body), 0, 300));
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

        // Sanitize the JSON string using UTF-8 safe sanitizer from translator
        // This handles control characters without corrupting multi-byte chars (Swedish ö, ä, å)
        $translator = new Theme_OpenAI_Translator();
        $result = $translator->sanitize_json_string($result);

        $data = json_decode($result, true);

        // FALLBACK: If JSON parse fails, try string-based extraction
        if (json_last_error() !== JSON_ERROR_NONE || !$data) {
            error_log('Primary JSON parse failed: ' . json_last_error_msg() . '. Attempting string extraction.');

            // Extract values using string operations (avoids regex backtracking limits)
            $expected_keys = array('focus_keyword', 'title', 'content', 'meta_title', 'meta_description');
            $data = array();

            foreach ($expected_keys as $key) {
                // Find the key pattern: "key": "
                $search = '"' . $key . '": "';
                $pos = strpos($result, $search);

                if ($pos === false) {
                    $search = '"' . $key . '":"';
                    $pos = strpos($result, $search);
                }

                if ($pos === false)
                    continue;

                // Move past the key pattern
                $value_start = $pos + strlen($search);

                // Scan for closing quote
                $value = '';
                $i = $value_start;
                $len = strlen($result);

                while ($i < $len) {
                    $char = $result[$i];

                    if ($char === '\\' && $i + 1 < $len) {
                        $next = $result[$i + 1];
                        switch ($next) {
                            case 'n':
                                $value .= "\n";
                                break;
                            case 'r':
                                $value .= "\r";
                                break;
                            case 't':
                                $value .= "\t";
                                break;
                            case '"':
                                $value .= '"';
                                break;
                            case '\\':
                                $value .= '\\';
                                break;
                            default:
                                $value .= $char . $next;
                                break;
                        }
                        $i += 2;
                    } elseif ($char === '"') {
                        break;
                    } else {
                        $value .= $char;
                        $i++;
                    }
                }

                if (!empty($value)) {
                    $data[$key] = $value;
                }
            }

            if (!empty($data)) {
                error_log('String extraction recovered ' . count($data) . ' fields for Fill Missing');
            }
        }

        // In fill-missing mode, the response might not have all fields
        if (!$data || (!isset($data['focus_keyword']) && !isset($data['title']) && !isset($data['content']) && !isset($data['meta_title']))) {
            // Enhanced debugging - find control characters
            $control_chars = array();
            for ($i = 0; $i < min(500, strlen($result)); $i++) {
                $ord = ord($result[$i]);
                if ($ord < 32 && $ord !== 9 && $ord !== 10 && $ord !== 13) {
                    $control_chars[] = "pos:$i=0x" . dechex($ord);
                }
            }
            $error_msg = 'Failed to parse OpenAI response. ';
            $error_msg .= 'JSON Error: ' . json_last_error_msg() . '. ';
            $error_msg .= 'Length: ' . strlen($result) . '. ';
            if (!empty($control_chars)) {
                $error_msg .= 'Bad chars: ' . implode(',', array_slice($control_chars, 0, 5)) . '. ';
            }
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

    /**
     * AJAX handler for getting initial post data (English source + all subsites)
     */
    public function ajax_get_initial_data()
    {
        check_ajax_referer('iptv_localizer_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = intval($_POST['post_id']);

        // Always get original English post from main site (blog 1)
        if (is_multisite()) {
            switch_to_blog(1);
        }

        // Get original post from main site
        $post = get_post($post_id);
        if (!$post) {
            if (is_multisite()) {
                restore_current_blog();
            }
            wp_send_json_error('Post not found');
        }

        // Get Rank Math SEO data for English
        $focus_keyword = get_post_meta($post_id, 'rank_math_focus_keyword', true);
        $meta_title = get_post_meta($post_id, 'rank_math_title', true);
        $meta_description = get_post_meta($post_id, 'rank_math_description', true);

        // Get featured image
        $featured_image_id = get_post_thumbnail_id($post_id);
        $image_alt = '';
        if ($featured_image_id) {
            $image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
        }

        // Store post type for later use
        $post_type = $post->post_type;

        // Prepare response with English data
        $response = array(
            'en' => array(
                'title' => $post->post_title,
                'content' => $post->post_content,
                'slug' => $post->post_name,
                'focus_keyword' => $focus_keyword,
                'meta_title' => $meta_title,
                'meta_description' => $meta_description,
                'image_alt' => $image_alt,
            ),
            'featured_image_id' => $featured_image_id
        );

        if (is_multisite()) {
            restore_current_blog();
        }

        // Subsite mapping - lang code => blog_id
        $subsites = array(
            'se' => 2,
            'no' => 3,
            'dk' => 4,
            'fi' => 5,
            'is' => 6
        );

        // Fetch data from each subsite
        if (is_multisite()) {
            foreach ($subsites as $lang_code => $blog_id) {
                switch_to_blog($blog_id);

                // Find the localized post by meta
                $localized_query = new WP_Query(array(
                    'post_type' => $post_type,
                    'post_status' => array('publish', 'draft'),
                    'meta_key' => '_localized_from_post',
                    'meta_value' => $post_id,
                    'posts_per_page' => 1
                ));

                if ($localized_query->have_posts()) {
                    $localized_post = $localized_query->posts[0];

                    // Get Rank Math SEO data
                    $loc_focus_keyword = get_post_meta($localized_post->ID, 'rank_math_focus_keyword', true);
                    $loc_meta_title = get_post_meta($localized_post->ID, 'rank_math_title', true);
                    $loc_meta_description = get_post_meta($localized_post->ID, 'rank_math_description', true);

                    // Get featured image alt text
                    $loc_image_alt = '';
                    $loc_featured_image_id = get_post_thumbnail_id($localized_post->ID);
                    if ($loc_featured_image_id) {
                        $loc_image_alt = get_post_meta($loc_featured_image_id, '_wp_attachment_image_alt', true);
                    }

                    $response[$lang_code] = array(
                        'title' => $localized_post->post_title,
                        'content' => $localized_post->post_content,
                        'slug' => $localized_post->post_name,
                        'focus_keyword' => $loc_focus_keyword,
                        'meta_title' => $loc_meta_title,
                        'meta_description' => $loc_meta_description,
                        'image_alt' => $loc_image_alt,
                        'post_id' => $localized_post->ID,
                        'status' => $localized_post->post_status
                    );
                }

                restore_current_blog();
            }
        }

        wp_send_json_success($response);
    }

    /**
     * AJAX handler for saving localized content
     */
    public function ajax_save_localized_content()
    {
        check_ajax_referer('iptv_localizer_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        // Sanitize inputs
        $post_id = intval($_POST['post_id']);
        $target_lang = sanitize_text_field($_POST['target_lang']);
        $focus_keyword = isset($_POST['focus_keyword']) ? sanitize_text_field($_POST['focus_keyword']) : '';
        $meta_title = isset($_POST['meta_title']) ? sanitize_text_field($_POST['meta_title']) : '';
        $meta_description = isset($_POST['meta_description']) ? sanitize_textarea_field($_POST['meta_description']) : '';
        $post_slug = isset($_POST['post_slug']) ? sanitize_text_field($_POST['post_slug']) : '';
        $image_alt = isset($_POST['image_alt']) ? sanitize_text_field($_POST['image_alt']) : '';
        $post_title = isset($_POST['post_title']) ? sanitize_text_field($_POST['post_title']) : '';
        $post_content = isset($_POST['post_content']) ? wp_kses_post($_POST['post_content']) : '';
        $featured_image_id = isset($_POST['featured_image_id']) ? intval($_POST['featured_image_id']) : 0;

        // Get original post
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error('Post not found');
        }

        if ($target_lang === 'en') {
            // Update the original English post
            $update_data = array(
                'ID' => $post_id,
            );

            if ($post_title) {
                $update_data['post_title'] = $post_title;
            }
            if ($post_content) {
                $update_data['post_content'] = $post_content;
            }
            if ($post_slug) {
                $update_data['post_name'] = $post_slug;
            }

            $result = wp_update_post($update_data);

            if (is_wp_error($result)) {
                wp_send_json_error('Failed to update post: ' . $result->get_error_message());
            }

            // Update Rank Math meta
            if ($focus_keyword) {
                update_post_meta($post_id, 'rank_math_focus_keyword', $focus_keyword);
            }
            if ($meta_title) {
                update_post_meta($post_id, 'rank_math_title', $meta_title);
            }
            if ($meta_description) {
                update_post_meta($post_id, 'rank_math_description', $meta_description);
            }

            // Update featured image alt text
            if ($featured_image_id && $image_alt) {
                update_post_meta($featured_image_id, '_wp_attachment_image_alt', $image_alt);
            }

            wp_send_json_success(array('message' => 'English post updated successfully'));
        } else {
            // For translations: save as draft on TARGET subsite
            $target_blog_id = isset($_POST['target_blog_id']) ? intval($_POST['target_blog_id']) : 0;

            // Get source image path while still on main blog
            $source_image_path = '';
            if ($featured_image_id) {
                $source_image_path = get_attached_file($featured_image_id);
            }

            if ($target_blog_id && is_multisite()) {
                switch_to_blog($target_blog_id);
            }

            // Check if a draft or published post already exists for this language linked to original post
            $existing_query = new WP_Query(array(
                'post_type' => $post->post_type,
                'post_status' => array('publish', 'draft', 'pending', 'future'), // Check all statuses
                'meta_query' => array(
                    array(
                        'key' => '_localized_from_post',
                        'value' => $post_id
                    )
                ),
                'posts_per_page' => 1
            ));

            if ($existing_query->have_posts()) {
                // Update existing post/draft
                $existing_post = $existing_query->posts[0];
                $draft_id = wp_update_post(array(
                    'ID' => $existing_post->ID,
                    'post_title' => $post_title,
                    'post_content' => $post_content,
                    'post_name' => $post_slug
                ));
            } else {
                // Create new draft
                $draft_id = wp_insert_post(array(
                    'post_title' => $post_title,
                    'post_content' => $post_content,
                    'post_name' => $post_slug,
                    'post_status' => 'draft',
                    'post_type' => $post->post_type
                ));
            }

            if (is_wp_error($draft_id) || !$draft_id) {
                if ($target_blog_id && is_multisite()) {
                    restore_current_blog();
                }
                wp_send_json_error('Failed to save draft on subsite ' . $target_blog_id);
            }

            // Save meta data
            update_post_meta($draft_id, '_localized_from_post', $post_id);
            update_post_meta($draft_id, '_localized_target_lang', $target_lang);

            // Rank Math SEO
            if ($focus_keyword)
                update_post_meta($draft_id, 'rank_math_focus_keyword', $focus_keyword);
            if ($meta_title)
                update_post_meta($draft_id, 'rank_math_title', $meta_title);
            if ($meta_description)
                update_post_meta($draft_id, 'rank_math_description', $meta_description);

            if ($image_alt) {
                update_post_meta($draft_id, '_localized_image_alt', $image_alt);
            }
            // Handle Featured Image Duplication/Update
            if ($featured_image_id && $source_image_path && file_exists($source_image_path)) {

                // If post already has a thumbnail, just update its alt text
                // Wait - verify if existing thumbnail is valid or unrelated. 
                // For now, if thumbnail exists, we assume it is the correct one to avoid duplicates.
                if (has_post_thumbnail($draft_id)) {
                    $thumb_id = get_post_thumbnail_id($draft_id);
                    if ($image_alt) {
                        update_post_meta($thumb_id, '_wp_attachment_image_alt', $image_alt);
                    }
                } else {
                    // Upload image to localized site's media library if missing
                    require_once(ABSPATH . 'wp-admin/includes/file.php');
                    require_once(ABSPATH . 'wp-admin/includes/media.php');
                    require_once(ABSPATH . 'wp-admin/includes/image.php');

                    // Read content safely
                    $image_data = file_get_contents($source_image_path);

                    if ($image_data) {
                        $upload_file = wp_upload_bits(basename($source_image_path), null, $image_data);

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
                                $attach_data = wp_generate_attachment_metadata($attach_id, $upload_file['file']);
                                wp_update_attachment_metadata($attach_id, $attach_data);

                                // Set Alt Text
                                if ($image_alt) {
                                    update_post_meta($attach_id, '_wp_attachment_image_alt', $image_alt);
                                }

                                // Set as Featured Image
                                set_post_thumbnail($draft_id, $attach_id);
                            }
                        }
                    }
                }
            }

            if ($target_blog_id && is_multisite()) {
                restore_current_blog();
            }

            wp_send_json_success(array('message' => 'Draft saved for ' . $target_lang));
        }
    }

    /**
     * AJAX Handler: Clone to Network (Bulk or Single)
     * 
     * Now supports full product data cloning via Theme_Network_Cloner.
     */
    public function ajax_clone_to_network()
    {
        // verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iptv_localizer_nonce')) {
            wp_send_json_error('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $target_blog_id = intval($_POST['target_blog_id']);
        $raw_translate = isset($_POST['translate']) ? $_POST['translate'] : 'not set';
        $do_translate = ($raw_translate === 'true' || $raw_translate === true);

        if (!$post_id || !$target_blog_id) {
            wp_send_json_error('Missing parameters');
        }

        // Must run in context of potentially finding the classes
        if (!class_exists('Theme_Network_Cloner')) {
            // Try to load it if not found (it should be loaded by functions.php)
            // fallback or error
            error_log('IPTV Clone Error: Theme_Network_Cloner class not found.');
            wp_send_json_error('Cloner class not found');
        }

        $cloner = new Theme_Network_Cloner();
        $source_post = get_post($post_id);

        if (!$source_post) {
            wp_send_json_error('Source post not found');
        }

        // Perform Cloning
        // clone_to_site($source_post, $target_blog_id, $do_translate)
        $result = $cloner->clone_to_site($source_post, $target_blog_id, $do_translate);

        if ($result['success']) {
            wp_send_json_success(array(
                'message' => 'Cloned successfully.',
                'processed' => array(
                    array(
                        'original_id' => $post_id,
                        'target_id' => $result['target_id'],
                        'action' => $result['action'],
                        'translated' => $result['translated'],
                        'translation_status' => $result['translated'] ? 'success' : 'skipped',
                        'translation_error' => '' // We could enhance cloner to return specific error if needed
                    )
                ),
                'translated' => $result['translated'],
                'debug' => isset($result['debug']) ? $result['debug'] : array()
            ));
        } else {
            wp_send_json_error(array(
                'message' => $result['message'] ? $result['message'] : 'Cloning failed',
                'debug' => isset($result['debug']) ? $result['debug'] : array()
            ));
        }
    }

    /**
     * AJAX Handler: Remove from Network
     */
    public function ajax_remove_from_network()
    {
        // verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iptv_localizer_nonce')) {
            wp_send_json_error('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
        $target_blog_id = intval($_POST['target_blog_id']);

        if (!$post_id || !$target_blog_id) {
            wp_send_json_error('Missing parameters');
        }

        $source_post = get_post($post_id);
        if (!$source_post) {
            wp_send_json_error('Source post not found');
        }
        $source_slug = $source_post->post_name;
        $post_type = $source_post->post_type;

        if (is_multisite()) {
            switch_to_blog($target_blog_id);
        }

        // Try to find the linked post
        // 1. By Meta Tag (Best)
        $args = array(
            'post_type' => $post_type,
            'meta_key' => '_localized_from_post',
            'meta_value' => $post_id,
            'posts_per_page' => 1,
            'post_status' => 'any'
        );
        $query = new WP_Query($args);

        $post_to_delete = null;

        if ($query->have_posts()) {
            $post_to_delete = $query->posts[0];
        } else {
            // 2. Fallback: By Slug (Legacy clones without meta)
            // Warning: Slug might have changed or be translated, but legacy cloner kept English slug usually.
            $existing = get_page_by_path($source_slug, OBJECT, $post_type);
            if ($existing) {
                $post_to_delete = $existing;
            }
        }

        if ($post_to_delete) {
            $deleted = wp_delete_post($post_to_delete->ID, true); // Force delete (bypass trash)

            if (is_multisite()) {
                restore_current_blog();
            }

            if ($deleted) {
                wp_send_json_success(array('message' => 'Removed successfully'));
            } else {
                wp_send_json_error('Failed to delete post from subsite');
            }
        } else {
            if (is_multisite()) {
                restore_current_blog();
            }
            wp_send_json_error('Post not found on target subsite (is it already removed?)');
        }
    }

    /**
     * AJAX Handler: Clone Menus to Network (Single Subsite)
     */
    public function ajax_clone_menus_to_network()
    {
        // verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iptv_localizer_nonce')) {
            wp_send_json_error('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $target_blog_id = intval($_POST['target_blog_id']);
        $raw_translate = isset($_POST['translate']) ? $_POST['translate'] : 'true';
        $do_translate = ($raw_translate === 'true' || $raw_translate === true || $raw_translate === '1');

        if (!$target_blog_id) {
            wp_send_json_error('Missing target blog ID');
        }

        if (!class_exists('Theme_Network_Cloner')) {
            wp_send_json_error('Network Cloner class not found');
        }

        $cloner = new Theme_Network_Cloner();

        // Call the single-site clone method
        $result = $cloner->clone_menus_to_site($target_blog_id, $do_translate);

        if ($result['success']) {
            wp_send_json_success(array(
                'message' => 'Menus cloned successfully',
                'translated' => $result['translated'],
                'menus_count' => $result['menus_count'] ?? 0
            ));
        } else {
            wp_send_json_error($result['message'] ?? 'Failed to clone menus');
        }
    }

    /**
     * AJAX Handler: Remove Menus from Network (Single Subsite)
     */
    public function ajax_remove_menus_from_network()
    {
        // verify nonce
        if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'iptv_localizer_nonce')) {
            wp_send_json_error('Invalid security nonce');
        }

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $target_blog_id = intval($_POST['target_blog_id']);

        if (!$target_blog_id) {
            wp_send_json_error('Missing target blog ID');
        }

        if (!class_exists('Theme_Network_Cloner')) {
            wp_send_json_error('Network Cloner class not found');
        }

        $cloner = new Theme_Network_Cloner();

        // Call the single-site remove method
        $result = $cloner->remove_menus_from_site($target_blog_id);

        if ($result['success']) {
            wp_send_json_success(array(
                'message' => 'Menus removed successfully',
                'removed_count' => $result['removed_count'] ?? 0
            ));
        } else {
            wp_send_json_error($result['message'] ?? 'Failed to remove menus');
        }
    }
}
