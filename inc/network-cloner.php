<?php
/**
 * Network Content Cloner
 * 
 * Adds a "Clone to Network" button to admin bar for syncing pages across multisite.
 * 
 * @package Nordic_IPTV
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Theme_Network_Cloner
{

    /**
     * Target sub-sites (path slugs)
     */
    private $target_sites = array('se', 'no', 'dk', 'fi', 'is');

    /**
     * Constructor
     */
    public function __construct()
    {
        // Only run on multisite
        if (!is_multisite()) {
            return;
        }

        // Only run on main site
        if (get_current_blog_id() != 1) {
            return;
        }

        // Add admin bar button
        add_action('admin_bar_menu', array($this, 'add_clone_button'), 100);

        // Add row action to Pages/Posts list
        add_filter('page_row_actions', array($this, 'add_clone_row_action'), 10, 2);
        add_filter('post_row_actions', array($this, 'add_clone_row_action'), 10, 2);

        // Handle clone action
        add_action('admin_init', array($this, 'handle_clone_action'));

        // Handle remove action
        add_action('admin_init', array($this, 'handle_remove_action'));

        // Admin notices
        add_action('admin_notices', array($this, 'show_clone_notice'));
    }

    /**
     * Add "Clone to Network" and "Remove from Network" links to row actions in Pages/Posts list
     */
    public function add_clone_row_action($actions, $post)
    {
        // Only super admins can use network actions
        if (!is_super_admin()) {
            return $actions;
        }

        // Clone to Network
        $clone_url = add_query_arg(array(
            'action' => 'clone_to_network',
            'post_id' => $post->ID,
            '_wpnonce' => wp_create_nonce('clone_to_network_' . $post->ID),
        ), admin_url('edit.php'));

        $actions['clone_network'] = sprintf(
            '<a href="%s" style="color:#16a34a;font-weight:600;">🌐 Clone to Network</a>',
            esc_url($clone_url)
        );

        // Remove from Network
        $remove_url = add_query_arg(array(
            'action' => 'remove_from_network',
            'post_id' => $post->ID,
            '_wpnonce' => wp_create_nonce('remove_from_network_' . $post->ID),
        ), admin_url('edit.php'));

        $actions['remove_network'] = sprintf(
            '<a href="%s" style="color:#dc2626;font-weight:600;" onclick="return confirm(\'Are you sure you want to remove this page from ALL sub-sites? This cannot be undone.\');">🗑️ Remove from Network</a>',
            esc_url($remove_url)
        );

        return $actions;
    }

    /**
     * Add "Clone to Network" button to admin bar
     */
    public function add_clone_button($wp_admin_bar)
    {
        // Only show on main site
        if (get_current_blog_id() != 1) {
            return;
        }

        // Only show when editing a page/post
        global $pagenow;

        if (!in_array($pagenow, array('post.php', 'post-new.php'))) {
            return;
        }

        // Get post ID from URL or global
        $post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;

        if (!$post_id) {
            global $post;
            if (isset($post) && $post) {
                $post_id = $post->ID;
            }
        }

        if (!$post_id) {
            return;
        }

        // Only for admins
        if (!current_user_can('manage_options')) {
            return;
        }

        // Build the clone URL
        $clone_url = add_query_arg(array(
            'action' => 'clone_to_network',
            'post_id' => $post_id,
            '_wpnonce' => wp_create_nonce('clone_to_network_' . $post_id),
        ), admin_url('post.php'));

        // Add the button
        $wp_admin_bar->add_node(array(
            'id' => 'clone-to-network',
            'title' => '<span class="ab-icon dashicons dashicons-networking" style="font-family:dashicons;font-size:18px;margin-top:4px;"></span> Clone to Network',
            'href' => $clone_url,
            'meta' => array(
                'title' => 'Clone this page to all sub-sites (SE, NO, DK, FI, IS)',
                'class' => 'clone-to-network-btn',
            ),
        ));

        // Add inline styles
        add_action('admin_head', function () {
            echo '<style>
                #wp-admin-bar-clone-to-network > a {
                    background: linear-gradient(135deg, #22c55e, #16a34a) !important;
                    color: white !important;
                }
                #wp-admin-bar-clone-to-network > a:hover {
                    background: linear-gradient(135deg, #16a34a, #15803d) !important;
                }
                #wp-admin-bar-clone-to-network .ab-icon {
                    color: white !important;
                }
            </style>';
        });
    }

    /**
     * Handle the clone action
     */
    public function handle_clone_action()
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'clone_to_network') {
            return;
        }

        if (!isset($_GET['post_id']) || !isset($_GET['_wpnonce'])) {
            return;
        }

        $post_id = intval($_GET['post_id']);

        // Verify nonce
        if (!wp_verify_nonce($_GET['_wpnonce'], 'clone_to_network_' . $post_id)) {
            wp_die('Security check failed');
        }

        // Only super admins
        if (!is_super_admin()) {
            wp_die('Unauthorized - Super Admin required');
        }

        // Get the source post
        $source_post = get_post($post_id);

        if (!$source_post) {
            wp_die('Post not found');
        }

        // Clone to all sub-sites
        $results = $this->clone_to_sites($source_post);

        // Store results for notice
        set_transient('network_clone_results_' . get_current_user_id(), $results, 60);

        // Redirect back to pages list
        wp_safe_redirect(add_query_arg(array(
            'post_type' => $source_post->post_type,
            'cloned' => 'true',
        ), admin_url('edit.php')));
        exit;
    }

    /**
     * Handle the remove from network action
     */
    public function handle_remove_action()
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'remove_from_network') {
            return;
        }

        if (!isset($_GET['post_id']) || !isset($_GET['_wpnonce'])) {
            return;
        }

        $post_id = intval($_GET['post_id']);

        // Verify nonce
        if (!wp_verify_nonce($_GET['_wpnonce'], 'remove_from_network_' . $post_id)) {
            wp_die('Security check failed');
        }

        // Only super admins
        if (!is_super_admin()) {
            wp_die('Unauthorized - Super Admin required');
        }

        // Get the source post to get its slug
        $source_post = get_post($post_id);

        if (!$source_post) {
            wp_die('Post not found');
        }

        // Remove from all sub-sites
        $results = $this->remove_from_sites($source_post);

        // Store results for notice
        set_transient('network_clone_results_' . get_current_user_id(), $results, 60);

        // Redirect back to pages list
        wp_safe_redirect(add_query_arg(array(
            'post_type' => $source_post->post_type,
            'removed' => 'true',
        ), admin_url('edit.php')));
        exit;
    }

    /**
     * Remove page from all sub-sites
     */
    private function remove_from_sites($source_post)
    {
        $results = array(
            'removed' => array(),
            'not_found' => array(),
            'errors' => array(),
        );

        // Get all sites
        $sites = get_sites(array('number' => 100));

        foreach ($sites as $site) {
            // Skip main site
            if ($site->blog_id == 1) {
                continue;
            }

            // Get site path
            $path = trim($site->path, '/');
            $path_parts = explode('/', $path);
            $site_slug = end($path_parts);

            // Only process target sites
            if (!in_array($site_slug, $this->target_sites)) {
                continue;
            }

            // Switch to sub-site
            switch_to_blog($site->blog_id);

            try {
                // Check if page with same slug exists
                $existing = get_page_by_path($source_post->post_name);

                if ($existing) {
                    // Delete the page (move to trash)
                    $delete_result = wp_trash_post($existing->ID);

                    if ($delete_result) {
                        $results['removed'][] = strtoupper($site_slug);
                    } else {
                        $results['errors'][] = strtoupper($site_slug) . ': Failed to delete';
                    }
                } else {
                    $results['not_found'][] = strtoupper($site_slug);
                }
            } catch (Exception $e) {
                $results['errors'][] = strtoupper($site_slug) . ': ' . $e->getMessage();
            }

            restore_current_blog();
        }

        return $results;
    }

    /**
     * Clone post to all sub-sites with automatic translation
     */
    private function clone_to_sites($source_post)
    {
        $results = array(
            'created' => array(),
            'updated' => array(),
            'translated' => array(),
            'errors' => array(),
        );

        // Get OpenAI translator (uses secure API key from WordPress options)
        $translator = function_exists('get_openai_translator') ? get_openai_translator() : null;
        $translate_enabled = $translator && $translator->is_configured();

        // Get all sites
        $sites = get_sites(array('number' => 100));

        foreach ($sites as $site) {
            // Skip main site
            if ($site->blog_id == 1) {
                continue;
            }

            // Get site path
            $path = trim($site->path, '/');
            $path_parts = explode('/', $path);
            $site_slug = end($path_parts);

            // Only process target sites
            if (!in_array($site_slug, $this->target_sites)) {
                continue;
            }

            // Prepare content (translate if enabled)
            $post_title = $source_post->post_title;
            $post_content = $source_post->post_content;

            if ($translate_enabled) {
                // Get target language name from OpenAI translator
                $target_language = $translator->get_target_language($site_slug);

                // Translate title and content with OpenAI
                $post_title = $translator->translate($source_post->post_title, $target_language, 'English');
                $post_content = $translator->translate($source_post->post_content, $target_language, 'English');

                // Track if translation happened
                if ($post_title !== $source_post->post_title || $post_content !== $source_post->post_content) {
                    $results['translated'][] = strtoupper($site_slug);
                }
            }

            // Switch to sub-site
            switch_to_blog($site->blog_id);

            try {
                // Check if page with same slug exists
                $existing = get_page_by_path($source_post->post_name);

                if ($existing) {
                    // Update existing page
                    $update_result = wp_update_post(array(
                        'ID' => $existing->ID,
                        'post_title' => $post_title,
                        'post_content' => $post_content,
                        'post_status' => $source_post->post_status,
                    ));

                    if (is_wp_error($update_result)) {
                        $results['errors'][] = strtoupper($site_slug) . ': ' . $update_result->get_error_message();
                    } else {
                        $results['updated'][] = strtoupper($site_slug);

                        // Also copy meta data
                        $this->copy_post_meta($source_post->ID, $existing->ID);
                    }
                } else {
                    // Create new page
                    $new_post_id = wp_insert_post(array(
                        'post_title' => $post_title,
                        'post_name' => $source_post->post_name,
                        'post_content' => $post_content,
                        'post_status' => $source_post->post_status,
                        'post_type' => $source_post->post_type,
                        'post_author' => get_current_user_id(),
                    ));

                    if (is_wp_error($new_post_id)) {
                        $results['errors'][] = strtoupper($site_slug) . ': ' . $new_post_id->get_error_message();
                    } else {
                        $results['created'][] = strtoupper($site_slug);

                        // Copy meta data
                        $this->copy_post_meta($source_post->ID, $new_post_id);
                    }
                }
            } catch (Exception $e) {
                $results['errors'][] = strtoupper($site_slug) . ': ' . $e->getMessage();
            }

            restore_current_blog();
        }

        return $results;
    }

    /**
     * Copy post meta from source to target
     */
    private function copy_post_meta($source_id, $target_id)
    {
        // Switch to main site to get source meta
        $current_blog = get_current_blog_id();
        switch_to_blog(1);

        $meta_keys = array(
            '_seo_meta_title',
            '_seo_meta_description',
            '_seo_focus_keyword',
        );

        $meta_values = array();
        foreach ($meta_keys as $key) {
            $meta_values[$key] = get_post_meta($source_id, $key, true);
        }

        restore_current_blog();

        // Now apply to target
        switch_to_blog($current_blog);

        foreach ($meta_values as $key => $value) {
            if (!empty($value)) {
                update_post_meta($target_id, $key, $value);
            }
        }
    }

    /**
     * Show admin notice with clone/remove results
     */
    public function show_clone_notice()
    {
        $is_cloned = isset($_GET['cloned']) && $_GET['cloned'] === 'true';
        $is_removed = isset($_GET['removed']) && $_GET['removed'] === 'true';

        if (!$is_cloned && !$is_removed) {
            return;
        }

        $results = get_transient('network_clone_results_' . get_current_user_id());

        if (!$results) {
            return;
        }

        delete_transient('network_clone_results_' . get_current_user_id());

        $messages = array();

        if (!empty($results['created'])) {
            $messages[] = '✅ Created on: ' . implode(', ', $results['created']);
        }

        if (!empty($results['updated'])) {
            $messages[] = '🔄 Updated on: ' . implode(', ', $results['updated']);
        }

        if (!empty($results['translated'])) {
            $messages[] = '🌐 Translated: ' . implode(', ', $results['translated']);
        }

        if (!empty($results['removed'])) {
            $messages[] = '🗑️ Removed from: ' . implode(', ', $results['removed']);
        }

        if (!empty($results['not_found'])) {
            $messages[] = '⚠️ Not found on: ' . implode(', ', $results['not_found']);
        }

        if (!empty($results['errors'])) {
            $messages[] = '❌ Errors: ' . implode('; ', $results['errors']);
        }

        if (empty($messages)) {
            $messages[] = 'No sub-sites found to process.';
        }

        $class = empty($results['errors']) ? 'notice-success' : 'notice-warning';
        $title = $is_removed ? '🗑️ Network Remove Complete!' : '🌐 Network Clone Complete!';

        echo '<div class="notice ' . $class . ' is-dismissible">';
        echo '<p><strong>' . $title . '</strong></p>';
        echo '<ul style="margin:0 0 10px 20px;list-style:disc;">';
        foreach ($messages as $msg) {
            echo '<li>' . esc_html($msg) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}

// Initialize the Network Cloner
new Theme_Network_Cloner();
