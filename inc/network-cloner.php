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
        add_action('admin_init', array($this, 'handle_menu_clone_action'));

        // Handle remove action
        add_action('admin_init', array($this, 'handle_remove_action'));
        add_action('admin_init', array($this, 'handle_menu_remove_action'));

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

        // Check if we are on post edit page OR nav menus page
        if (!in_array($pagenow, array('post.php', 'post-new.php', 'nav-menus.php'))) {
            return;
        }

        // Case 1: Nav Menus Page
        if ($pagenow === 'nav-menus.php') {
            // Build the clone URL for Menus
            $clone_url = add_query_arg(array(
                'action' => 'clone_menus_to_network',
                '_wpnonce' => wp_create_nonce('clone_menus_to_network'),
            ), admin_url('nav-menus.php'));

            // Build removal URL
            $remove_url = add_query_arg(array(
                'action' => 'remove_menus_from_network',
                '_wpnonce' => wp_create_nonce('remove_menus_from_network'),
            ), admin_url('nav-menus.php'));

            // Add the Clone button
            $wp_admin_bar->add_node(array(
                'id' => 'clone-menus-to-network',
                'title' => '<span class="ab-icon dashicons dashicons-networking" style="font-family:dashicons;font-size:18px;margin-top:4px;"></span> Clone Menus to Network',
                'href' => $clone_url,
                'meta' => array(
                    'title' => 'Clone ALL menus to all sub-sites (SE, NO, DK, FI, IS)',
                    'class' => 'clone-to-network-btn',
                    'onclick' => 'return confirm("Are you sure? This will OVERWRITE menus on all sub-sites with the current English menus.");',
                ),
            ));

            // Add the Remove button
            $wp_admin_bar->add_node(array(
                'id' => 'remove-menus-from-network',
                'title' => '<span class="ab-icon dashicons dashicons-trash" style="font-family:dashicons;font-size:18px;margin-top:4px;"></span> Delete Menus',
                'href' => $remove_url,
                'meta' => array(
                    'title' => 'Delete ALL menus from sub-sites',
                    'class' => 'remove-from-network-btn',
                    'onclick' => 'return confirm("WARNING: This will DELETE all menus from sub-sites that match the main site menu names. This cannot be undone. Are you sure?");',
                ),
            ));

            // Add inline styles (reuse existing style block later)
            add_action('admin_head', function () {
                echo '<style>
                    #wp-admin-bar-clone-menus-to-network > a {
                        background: linear-gradient(135deg, #22c55e, #16a34a) !important;
                        color: white !important;
                    }
                    #wp-admin-bar-clone-menus-to-network > a:hover {
                        background: linear-gradient(135deg, #16a34a, #15803d) !important;
                    }
                    #wp-admin-bar-clone-menus-to-network .ab-icon {
                        color: white !important;
                    }
                    #wp-admin-bar-remove-menus-from-network > a {
                        background: linear-gradient(135deg, #ef4444, #dc2626) !important;
                        color: white !important;
                    }
                    #wp-admin-bar-remove-menus-from-network > a:hover {
                        background: linear-gradient(135deg, #dc2626, #b91c1c) !important;
                    }
                    #wp-admin-bar-remove-menus-from-network .ab-icon {
                        color: white !important;
                    }
                </style>';
            });

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
    public function remove_from_sites($source_post)
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
    public function clone_to_sites($source_post)
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
            $post_excerpt = $source_post->post_excerpt; // Short Description

            if ($translate_enabled) {
                // Get target language name from OpenAI translator
                $target_language = $translator->get_target_language($site_slug);

                // Translate title and content with OpenAI
                $post_title = $translator->translate($source_post->post_title, $target_language, 'English');
                $post_content = $translator->translate($source_post->post_content, $target_language, 'English');
                if ($post_excerpt) {
                    $post_excerpt = $translator->translate($source_post->post_excerpt, $target_language, 'English');
                }

                // Track if translation happened
                if ($post_title !== $source_post->post_title) {
                    $results['translated'][] = strtoupper($site_slug);
                }
            }

            // Switch to sub-site
            switch_to_blog($site->blog_id);

            try {
                // Check if page with same slug exists
                $expected_post_type = $source_post->post_type;
                $existing = get_page_by_path($source_post->post_name, OBJECT, $expected_post_type);

                // Special handling for Products (check SKU or Slug)
                if ($expected_post_type === 'product' && !$existing) {
                    $sku = get_post_meta($source_post->ID, '_sku', true);
                    if ($sku) {
                        $existing_id = wc_get_product_id_by_sku($sku);
                        if ($existing_id) {
                            $existing = get_post($existing_id);
                        }
                    }
                }

                if ($existing) {
                    // Update existing page
                    $update_result = wp_update_post(array(
                        'ID' => $existing->ID,
                        'post_title' => $post_title,
                        'post_content' => $post_content,
                        'post_excerpt' => $post_excerpt,
                        'post_status' => $source_post->post_status,
                    ));

                    if (is_wp_error($update_result)) {
                        $results['errors'][] = strtoupper($site_slug) . ': ' . $update_result->get_error_message();
                    } else {
                        $results['updated'][] = strtoupper($site_slug);
                        $target_id = $existing->ID;
                    }
                } else {
                    // Create new page
                    $new_post_id = wp_insert_post(array(
                        'post_title' => $post_title,
                        'post_name' => $source_post->post_name,
                        'post_content' => $post_content,
                        'post_excerpt' => $post_excerpt,
                        'post_status' => $source_post->post_status,
                        'post_type' => $source_post->post_type,
                        'post_author' => get_current_user_id(),
                    ));

                    if (is_wp_error($new_post_id)) {
                        $results['errors'][] = strtoupper($site_slug) . ': ' . $new_post_id->get_error_message();
                    } else {
                        $results['created'][] = strtoupper($site_slug);
                        $target_id = $new_post_id;
                    }
                }

                // If success, copy meta/product data
                if (isset($target_id)) {
                    $this->copy_post_meta($source_post->ID, $target_id);

                    if ($source_post->post_type === 'product') {
                        $this->clone_product_data($source_post->ID, $target_id);
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
     * Clone Product Data (Attributes, Variations, Prices)
     * Must be called while switched to target blog
     */
    private function clone_product_data($source_id, $target_id)
    {
        // Switch back to main site to gather source data
        $target_blog_id = get_current_blog_id();
        restore_current_blog(); // Back to ID 1

        $source_product = wc_get_product($source_id);
        $source_attributes = $source_product->get_attributes();
        $source_children = $source_product->get_children();

        // Simple/Parent Product Data
        $parent_data = array(
            'regular_price' => $source_product->get_regular_price(),
            'sale_price' => $source_product->get_sale_price(),
            'sku' => $source_product->get_sku(),
        );

        $variations_data = array();
        foreach ($source_children as $child_id) {
            $variation = wc_get_product($child_id);
            $variations_data[] = array(
                'attributes' => $variation->get_attributes(),
                'regular_price' => $variation->get_regular_price(),
                'sale_price' => $variation->get_sale_price(),
                'status' => $variation->get_status(),
                'sku' => $variation->get_sku(),
            );
        }

        switch_to_blog($target_blog_id); // Back to Target

        $target_product = wc_get_product($target_id);

        // 0. Sync Main Product Price/SKU (Important for Simple Products)
        $target_product->set_regular_price($parent_data['regular_price']);
        $target_product->set_sale_price($parent_data['sale_price']);
        if ($parent_data['sale_price']) {
            $target_product->set_price($parent_data['sale_price']);
        } else {
            $target_product->set_price($parent_data['regular_price']);
        }
        $target_product->set_sku($parent_data['sku']);

        // 1. Sync Attributes (assume global taxonomy exists or creating it if local is tricky, using local for simplicity)
        // For simplicity in this specific project, we know we use 'pa_devices'.
        // Ensure term exists on target
        $target_attributes = array();
        foreach ($source_attributes as $attr) {
            // Re-create attribute object for target
            $new_attr = new WC_Product_Attribute();
            $new_attr->set_id(0); // Custom attribute or taxonomy? 
            $new_attr->set_name($attr->get_name());
            $new_attr->set_options($attr->get_options());
            $new_attr->set_position($attr->get_position());
            $new_attr->set_visible($attr->get_visible());
            $new_attr->set_variation($attr->get_variation());
            $target_attributes[] = $new_attr;

            // If taxonomy, we should ideally register/sync it, but we assume it exists for now based on previous manual check
            // or 'product-setup.php' running on sub-sites (which it should if functions.php is shared)
        }
        $target_product->set_attributes($target_attributes);
        $target_product->save();

        // 2. Sync Variations
        // Remove existing children to ensure clean sync
        $existing_children = $target_product->get_children();
        if ($existing_children) {
            foreach ($existing_children as $child_id) {
                wp_delete_post($child_id, true);
            }
        }

        // Create new variations
        foreach ($variations_data as $v_data) {
            $variation = new WC_Product_Variation();
            $variation->set_parent_id($target_id);
            $variation->set_attributes($v_data['attributes']);
            $variation->set_regular_price($v_data['regular_price']);
            $variation->set_price($v_data['regular_price']);
            $variation->set_sale_price($v_data['sale_price']);
            if ($v_data['sale_price']) {
                $variation->set_price($v_data['sale_price']);
            }
            $variation->set_status($v_data['status']);
            $variation->set_sku($v_data['sku']);
            $variation->save();
        }
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
            '_thumbnail_id',
            '_seo_meta_title',
            '_seo_meta_description',
            '_seo_focus_keyword',
            'rank_math_title',
            'rank_math_description',
            'rank_math_focus_keyword',
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

        if (!$is_cloned && !$is_removed && !isset($_GET['menus_cloned']) && !isset($_GET['menus_removed'])) {
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
        $class = empty($results['errors']) ? 'notice-success' : 'notice-warning';
        $title = $is_removed ? '🗑️ Network Remove Complete!' : '🌐 Network Clone Complete!';

        if (isset($_GET['menus_cloned']) && $_GET['menus_cloned'] === 'true') {
            $title = '🌐 Global Menu Sync Complete!';
        }

        if (isset($_GET['menus_removed']) && $_GET['menus_removed'] === 'true') {
            $title = '🗑️ Global Menu Deletion Complete!';
        }

        echo '<div class="notice ' . $class . ' is-dismissible">';
        echo '<p><strong>' . $title . '</strong></p>';
        echo '<ul style="margin:0 0 10px 20px;list-style:disc;">';
        foreach ($messages as $msg) {
            echo '<li>' . esc_html($msg) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
    /**
     * Handle the menu clone action
     */
    public function handle_menu_clone_action()
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'clone_menus_to_network') {
            return;
        }

        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'clone_menus_to_network')) {
            wp_die('Security check failed');
        }

        // Only super admins
        if (!is_super_admin()) {
            wp_die('Unauthorized - Super Admin required');
        }

        // Clone menus to all sub-sites
        $results = $this->clone_menus_to_sites();

        // Store results for notice
        set_transient('network_clone_results_' . get_current_user_id(), $results, 60);

        // Redirect back
        wp_safe_redirect(add_query_arg(array(
            'menus_cloned' => 'true',
        ), admin_url('nav-menus.php')));
        exit;
    }

    /**
     * Clone all menus to sub-sites
     */
    private function clone_menus_to_sites()
    {
        $results = array(
            'updated' => array(),
            'translated' => array(),
            'errors' => array(),
        );

        // Get OpenAI translator
        $translator = function_exists('get_openai_translator') ? get_openai_translator() : null;
        $translate_enabled = $translator && $translator->is_configured();

        // Get Main Site Home URL (without trailing slash for matching)
        $main_home_url = untrailingslashit(home_url());

        // Get registered menu locations on Main Site
        $locations = get_nav_menu_locations();
        $registered_menus = get_registered_nav_menus(); // 'primary', 'footer_1', etc.

        // Get all sites
        $sites = get_sites(array('number' => 100));

        foreach ($sites as $site) {
            // Skip main site
            if ($site->blog_id == 1) {
                continue;
            }

            // Get site slug
            $path = trim($site->path, '/');
            $path_parts = explode('/', $path);
            $site_slug = end($path_parts);

            // Only process target sites
            if (!in_array($site_slug, $this->target_sites)) {
                continue;
            }

            // Target language for translation
            $target_language = 'English';
            if ($translate_enabled) {
                $target_language = $translator->get_target_language($site_slug);
            }

            // Switch to sub-site
            switch_to_blog($site->blog_id);

            $subsite_locations = get_nav_menu_locations();
            $menus_updated = false;

            foreach ($registered_menus as $location_slug => $location_name) {
                // Check if Main Site has a menu assigned to this location
                // We need to switch back to Main Site briefly to get the Menu Object
                restore_current_blog();

                $main_locations = get_nav_menu_locations();
                if (!isset($main_locations[$location_slug])) {
                    // No menu assigned on main site for this location
                    switch_to_blog($site->blog_id);
                    continue;
                }

                $menu_id = $main_locations[$location_slug];
                $menu_object = wp_get_nav_menu_object($menu_id);
                $menu_items = wp_get_nav_menu_items($menu_id);

                if (!$menu_object) {
                    switch_to_blog($site->blog_id);
                    continue;
                }

                // Switch back to Sub Site
                switch_to_blog($site->blog_id);
                $sub_home_url = untrailingslashit(home_url());

                try {
                    // Check if menu exists on sub-site
                    $sub_menu_obj = wp_get_nav_menu_object($menu_object->name);

                    if (!$sub_menu_obj) {
                        // Create it
                        $sub_menu_id = wp_create_nav_menu($menu_object->name);
                    } else {
                        $sub_menu_id = $sub_menu_obj->term_id;
                        // Clear existing items to ensure sync
                        $existing_items = wp_get_nav_menu_items($sub_menu_id);
                        if ($existing_items) {
                            foreach ($existing_items as $item) {
                                wp_delete_post($item->ID, true);
                            }
                        }
                    }

                    // Assign menu to location
                    $subsite_locations[$location_slug] = $sub_menu_id;

                    // Add items
                    if ($menu_items) {
                        // Build a map of old_db_id => new_db_id for parent/child relationships
                        $id_map = array();

                        foreach ($menu_items as $item) {
                            // Translate Label
                            $title = $item->title;
                            if ($translate_enabled && $target_language !== 'English') {
                                // Simple cache check could be added here, but relying on translator cache
                                $trans_title = $translator->translate($title, $target_language, 'English');
                                if ($trans_title !== $title) {
                                    $title = $trans_title;
                                }
                            }

                            // Rewrite URL: Replace main site home URL with subsite home URL
                            $url = $item->url;
                            if (strpos($url, $main_home_url) === 0) {
                                $url = str_replace($main_home_url, $sub_home_url, $url);
                            }

                            // Prepare item data
                            $args = array(
                                'menu-item-title' => $title,
                                'menu-item-classes' => implode(' ', $item->classes),
                                'menu-item-url' => $url,
                                'menu-item-status' => 'publish',
                                'menu-item-type' => $item->type, // 'custom', 'post_type', etc.
                            );

                            // Handle object linking (pages/posts)
                            // This is tricky across network. For simplicity, we fallback to 'custom' URL if object IDs don't match.
                            // However, since we cloned pages with same slugs, we can try to find the object by slug.
                            if ($item->type === 'post_type') {
                                // Need to find the equivalent post ID on this subsite
                                // Switch context is currently subsite, which is correct
                                $original_object_id = $item->object_id;

                                // We need the slug of the original object. 
                                // We have to look it up on the main site.
                                restore_current_blog(); // Back to main
                                $original_post = get_post($original_object_id);
                                $original_slug = $original_post ? $original_post->post_name : '';
                                switch_to_blog($site->blog_id); // Back to sub

                                if ($original_slug) {
                                    $target_post = get_page_by_path($original_slug, OBJECT, $item->object); // $item->object is post type (page/post)
                                    if ($target_post) {
                                        $args['menu-item-object-id'] = $target_post->ID;
                                        $args['menu-item-object'] = $item->object;
                                    } else {
                                        // Fallback to custom link if page not found
                                        $args['menu-item-type'] = 'custom';
                                        $args['menu-item-url'] = home_url('/' . $original_slug . '/');
                                    }
                                }
                            }

                            // Handle Parent
                            if ($item->menu_item_parent && isset($id_map[$item->menu_item_parent])) {
                                $args['menu-item-parent-id'] = $id_map[$item->menu_item_parent];
                            }

                            $new_item_id = wp_update_nav_menu_item($sub_menu_id, 0, $args);
                            if (!is_wp_error($new_item_id)) {
                                $id_map[$item->db_id] = $new_item_id;
                            }
                        }
                    }

                    $menus_updated = true;

                } catch (Exception $e) {
                    $results['errors'][] = strtoupper($site_slug) . ': ' . $e->getMessage();
                }
            }

            if ($menus_updated) {
                set_theme_mod('nav_menu_locations', $subsite_locations);
                $results['updated'][] = strtoupper($site_slug);
            }

            restore_current_blog();
        }

        return $results;
    }

    /**
     * Handle the menu remove action
     */
    public function handle_menu_remove_action()
    {
        if (!isset($_GET['action']) || $_GET['action'] !== 'remove_menus_from_network') {
            return;
        }

        if (!isset($_GET['_wpnonce']) || !wp_verify_nonce($_GET['_wpnonce'], 'remove_menus_from_network')) {
            wp_die('Security check failed');
        }

        // Only super admins
        if (!is_super_admin()) {
            wp_die('Unauthorized - Super Admin required');
        }

        // Remove menus from all sub-sites
        $results = $this->remove_menus_from_sites();

        // Store results for notice
        set_transient('network_clone_results_' . get_current_user_id(), $results, 60);

        // Redirect back
        wp_safe_redirect(add_query_arg(array(
            'menus_removed' => 'true',
        ), admin_url('nav-menus.php')));
        exit;
    }

    /**
     * Remove all menus from sub-sites that match main site menus
     */
    private function remove_menus_from_sites()
    {
        $results = array(
            'removed' => array(),
            'errors' => array(),
            'not_found' => array(),
        );

        // Get registered menu locations on Main Site
        $registered_menus = get_registered_nav_menus();

        // Get all sites
        $sites = get_sites(array('number' => 100));

        // Get Main Site menu names
        $main_menu_names = array();
        $locations = get_nav_menu_locations();
        foreach ($locations as $loc => $id) {
            $menu = wp_get_nav_menu_object($id);
            if ($menu) {
                $main_menu_names[] = $menu->name;
            }
        }

        foreach ($sites as $site) {
            // Skip main site
            if ($site->blog_id == 1) {
                continue;
            }

            // Get site slug
            $path = trim($site->path, '/');
            $path_parts = explode('/', $path);
            $site_slug = end($path_parts);

            // Only process target sites
            if (!in_array($site_slug, $this->target_sites)) {
                continue;
            }

            switch_to_blog($site->blog_id);

            try {
                $menus_removed = false;

                // We loop through main site menu names and delete counterparts on subsite
                foreach ($main_menu_names as $name) {
                    $sub_menu = wp_get_nav_menu_object($name);
                    if ($sub_menu) {
                        wp_delete_nav_menu($sub_menu->term_id);
                        $menus_removed = true;
                    }
                }

                if ($menus_removed) {
                    $results['removed'][] = strtoupper($site_slug);
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
}

// Initialize the Network Cloner
new Theme_Network_Cloner();
