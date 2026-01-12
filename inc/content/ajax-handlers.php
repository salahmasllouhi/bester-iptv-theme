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

        // Check if this is "fill missing" mode (only generate empty fields)
        $fill_missing_mode = isset($_POST['fill_missing']) && $_POST['fill_missing'] === 'true';

        // Check if this is "refresh content only" mode (rewrite content based on existing)
        $refresh_content_mode = isset($_POST['refresh_content_only']) && $_POST['refresh_content_only'] === 'true';

        // Get user's custom keyword if provided
        $custom_keyword = isset($_POST['custom_keyword']) ? sanitize_text_field($_POST['custom_keyword']) : '';

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
        if ($refresh_content_mode) {
            // Refresh content only mode - rewrite existing content with a fresh version
            $prompt = "Rewrite this $target_language content with a fresh version while keeping the same topic and SEO focus.\n\n";
            $prompt .= "Current Content (HTML):\n" . $existing_content . "\n\n";

            if ($custom_keyword) {
                $prompt .= "SEO Focus Keyword: \"$custom_keyword\"\n";
            }

            $prompt .= "\nIMPORTANT RULES:\n";
            $prompt .= "1. Create a NEW VERSION of the content about the same topic\n";
            $prompt .= "2. Use different wording and structure but convey the same information\n";
            $prompt .= "3. Preserve ALL HTML tags (h2, h3, p, ul, ol, li, etc.)\n";
            $prompt .= "4. Keep similar length to original\n";
            $prompt .= "5. Maintain the same SEO focus\n";
            $prompt .= "6. Write in $target_language\n\n";

            $prompt .= "Return ONLY the refreshed content as JSON:\n{\n";
            $prompt .= '  "content": "... (new version with HTML tags preserved)"' . "\n";
            $prompt .= "}\n";
        } elseif ($fill_missing_mode) {
            $prompt = "Fill missing SEO fields for $target_language content.\n\n";
            $prompt .= "Source Content (English HTML):\n";
            $prompt .= "Title: " . $post->post_title . "\n";
            $prompt .= "Content:\n" . $post->post_content . "\n\n";

            if ($custom_keyword) {
                $prompt .= "REQUIRED: Use this exact focus keyword: \"$custom_keyword\"\n";
            }

            $prompt .= "Current $target_language fields:\n";
            if ($existing_title)
                $prompt .= "Title: " . $existing_title . " (KEEP)\n";
            if ($existing_content)
                $prompt .= "Content: (EXISTS - KEEP)\n";
            if ($existing_meta_title)
                $prompt .= "Meta Title: " . $existing_meta_title . " (KEEP)\n";
            if ($existing_meta_desc)
                $prompt .= "Meta Description: " . $existing_meta_desc . " (KEEP)\n";
            if ($existing_slug)
                $prompt .= "Slug: " . $existing_slug . " (KEEP)\n";
            if ($existing_image_alt)
                $prompt .= "Image Alt: " . $existing_image_alt . " (KEEP)\n";

            $prompt .= "\nIMPORTANT RULES:\n";
            $prompt .= "1. ONLY generate fields that are NOT marked as (KEEP)\n";
            $prompt .= "2. All generated content must be in $target_language\n";
            $prompt .= "3. Preserve ALL HTML structure in content (h2, h3, p tags)\n";
            if ($custom_keyword) {
                $prompt .= "4. Use EXACTLY this focus keyword: \"$custom_keyword\"\n";
            } else {
                $prompt .= "4. Generate a native $target_language focus keyword\n";
            }

            $prompt .= "\nReturn ONLY missing fields as JSON:\n{\n";
            if (!$custom_keyword)
                $prompt .= '  "focus_keyword": "...",' . "\n";
            if (!$existing_title)
                $prompt .= '  "title": "...",' . "\n";
            if (!$existing_content)
                $prompt .= '  "content": "... (full HTML)",' . "\n";
            if (!$existing_meta_title)
                $prompt .= '  "meta_title": "...",' . "\n";
            if (!$existing_meta_desc)
                $prompt .= '  "meta_description": "...",' . "\n";
            if (!$existing_slug)
                $prompt .= '  "slug": "url-friendly-slug"' . ($existing_image_alt || $featured_image_alt ? ',' : '') . "\n";
            if ($featured_image_alt && !$existing_image_alt)
                $prompt .= '  "image_alt": "..."' . "\n";
            $prompt .= "}\n";
        } else {
            // Full regeneration mode
            $prompt = "Localize this content to $target_language with SEO optimization.\n\n";
            $prompt .= "Original (HTML):\n";
            $prompt .= "Title: " . $post->post_title . "\n";
            $prompt .= "Content:\n" . $post->post_content . "\n";
            if ($featured_image_alt) {
                $prompt .= "Image Alt: " . $featured_image_alt . "\n";
            }

            if ($custom_keyword) {
                $prompt .= "\nREQUIRED: Use this exact focus keyword: \"$custom_keyword\"\n";
            } else {
                $prompt .= "\nGenerate a native $target_language focus keyword (not just a translation).\n";
            }

            $prompt .= "IMPORTANT: Preserve ALL HTML structure including headings (h2, h3), paragraphs (p), lists, and other HTML tags.\n";
            $prompt .= "Rewrite the content and SEO metadata for $target_language audience.\n";
            $prompt .= "The content field MUST be valid HTML with proper tags preserved.\n\n";

            $prompt .= "Return as JSON:\n";
            $prompt .= "{\n";
            $prompt .= '  "focus_keyword": "...",' . "\n";
            $prompt .= '  "title": "...",' . "\n";
            $prompt .= '  "content": "... (full HTML with h2, h3, p tags preserved)",' . "\n";
            $prompt .= '  "meta_title": "...",' . "\n";
            $prompt .= '  "meta_description": "...",' . "\n";
            $prompt .= '  "slug": "url-friendly-slug"';
            if ($featured_image_alt) {
                $prompt .= ",\n";
                $prompt .= '  "image_alt": "..."';
            }
            $prompt .= "\n}\n";
        }

        // Call OpenAI API directly (not translate() which has token limits)
        $translator = new Theme_OpenAI_Translator();
        $api_key = get_option('openai_api_key');
        $model = get_option('openai_model', 'gpt-4o-mini');

        if (empty($api_key)) {
            wp_send_json_error('OpenAI API key not configured');
        }

        // Determine token parameter based on model
        // GPT-5 and o1/preview models use max_completion_tokens
        $token_param = (strpos($model, 'gpt-5') !== false || strpos($model, 'o1-') !== false)
            ? 'max_completion_tokens'
            : 'max_tokens';

        $body_args = array(
            'model' => $model,
            'messages' => array(
                array('role' => 'user', 'content' => $prompt)
            ),
            'temperature' => (strpos($model, 'gpt-5') !== false || strpos($model, 'o1-') !== false) ? 1.0 : 0.7,
        );
        $body_args[$token_param] = 100000; // Maximum token limit

        $response = wp_remote_post('https://api.openai.com/v1/chat/completions', array(
            'timeout' => 120,
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

        // Fix control characters and encoding issues
        // Remove problematic control characters but preserve valid JSON structure
        $result = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $result);

        // Ensure UTF-8 encoding
        if (!mb_check_encoding($result, 'UTF-8')) {
            $result = utf8_encode($result);
        }

        $data = json_decode($result, true);

        // In fill-missing mode, the response might not have all fields (e.g., no focus_keyword if user provided one)
        // So we just check if we got valid JSON with at least one expected field
        if (!$data || (!isset($data['focus_keyword']) && !isset($data['title']) && !isset($data['content']) && !isset($data['meta_title']))) {
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

    /**
     * AJAX handler for getting initial post data (English source)
     */
    public function ajax_get_initial_data()
    {
        check_ajax_referer('iptv_localizer_nonce', 'nonce');

        if (!current_user_can('manage_options')) {
            wp_send_json_error('Unauthorized');
        }

        $post_id = intval($_POST['post_id']);

        // Get original post
        $post = get_post($post_id);
        if (!$post) {
            wp_send_json_error('Post not found');
        }

        // Get Rank Math SEO data
        $focus_keyword = get_post_meta($post_id, 'rank_math_focus_keyword', true);
        $meta_title = get_post_meta($post_id, 'rank_math_title', true);
        $meta_description = get_post_meta($post_id, 'rank_math_description', true);

        // Get featured image
        $featured_image_id = get_post_thumbnail_id($post_id);
        $image_alt = '';
        if ($featured_image_id) {
            $image_alt = get_post_meta($featured_image_id, '_wp_attachment_image_alt', true);
        }

        wp_send_json_success(array(
            'title' => $post->post_title,
            'content' => $post->post_content,
            'slug' => $post->post_name,
            'focus_keyword' => $focus_keyword,
            'meta_title' => $meta_title,
            'meta_description' => $meta_description,
            'image_alt' => $image_alt,
            'featured_image_id' => $featured_image_id
        ));
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
            // For translations: save as draft on main site for later publishing
            // Check if a draft already exists for this language
            $draft_query = new WP_Query(array(
                'post_type' => $post->post_type,
                'post_status' => 'draft',
                'meta_query' => array(
                    array(
                        'key' => '_localized_from_post',
                        'value' => $post_id
                    ),
                    array(
                        'key' => '_localized_target_lang',
                        'value' => $target_lang
                    )
                ),
                'posts_per_page' => 1
            ));

            if ($draft_query->have_posts()) {
                // Update existing draft
                $draft_post = $draft_query->posts[0];
                $draft_id = wp_update_post(array(
                    'ID' => $draft_post->ID,
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
                wp_send_json_error('Failed to save draft');
            }

            // Save meta data
            update_post_meta($draft_id, '_localized_from_post', $post_id);
            update_post_meta($draft_id, '_localized_target_lang', $target_lang);
            update_post_meta($draft_id, 'rank_math_focus_keyword', $focus_keyword);
            update_post_meta($draft_id, 'rank_math_title', $meta_title);
            update_post_meta($draft_id, 'rank_math_description', $meta_description);

            if ($image_alt) {
                update_post_meta($draft_id, '_localized_image_alt', $image_alt);
            }

            wp_send_json_success(array('message' => 'Draft saved for ' . $target_lang));
        }
    }
}
