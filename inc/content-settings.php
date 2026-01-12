<?php
/**
 * IPTV Content Settings - Main Class
 * Manages homepage content and post/page localization
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Load AJAX handlers trait
require_once plugin_dir_path(__FILE__) . 'content/ajax-handlers.php';

class IPTV_Content_Settings
{
    use IPTV_Content_AJAX_Handlers;

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
        add_action('admin_enqueue_scripts', array($this, 'enqueue_editor_scripts'));
        add_action('wp_ajax_iptv_translate_content', array($this, 'ajax_translate_content'));
        add_action('wp_ajax_iptv_erase_content', array($this, 'ajax_erase_content'));
        // Post Localizer AJAX handlers
        add_action('wp_ajax_iptv_get_initial_data', array($this, 'ajax_get_initial_data'));
        add_action('wp_ajax_iptv_save_localized_content', array($this, 'ajax_save_localized_content'));
        add_action('wp_ajax_iptv_generate_localized_content', array($this, 'ajax_generate_localized_content'));
        add_action('wp_ajax_iptv_publish_localized_post', array($this, 'ajax_publish_localized_post'));
    }

    public function enqueue_editor_scripts($hook)
    {
        // Only load on our settings page
        if ($hook !== 'toplevel_page_iptv-content-settings') {
            return;
        }

        // Enqueue WordPress editor (TinyMCE)
        wp_enqueue_editor();
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
    /**
     * Shared localizer modal HTML
     */
    private function render_localizer_modal()
    {
        $languages = array(
            'en' => array('name' => 'English (Source)', 'flag' => '🇬🇧', 'blog_id' => 1),
            'se' => array('name' => 'Sweden 🇸🇪', 'flag' => '🇸🇪', 'blog_id' => 2),
            'no' => array('name' => 'Norway 🇳🇴', 'flag' => '🇳🇴', 'blog_id' => 3),
            'dk' => array('name' => 'Denmark 🇩🇰', 'flag' => '🇩🇰', 'blog_id' => 4),
            'fi' => array('name' => 'Finland 🇫🇮', 'flag' => '🇫🇮', 'blog_id' => 5),
            'is' => array('name' => 'Iceland 🇮🇸', 'flag' => '🇮🇸', 'blog_id' => 6),
        );
        ?>
        <!-- Review Modal with Rank Math Style -->
        <div id="localize-modal"
            style="display:none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 0; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.2); z-index: 10000; width: 95%; max-width: 1200px; max-height: 90vh; overflow: hidden; display: flex; flex-direction: column;">

            <!-- Modal Header -->
            <div
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; padding: 15px 25px; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; font-size: 18px; color: white;">🌍 Network Content Localizer</h2>
                    <p style="margin: 2px 0 0 0; opacity: 0.8; font-size: 12px;">Manage localized content for all subsites in
                        one place.</p>
                </div>
                <button id="close-modal-btn"
                    style="background: rgba(255,255,255,0.1); border: none; color: white; width: 30px; height: 30px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px;">&times;</button>
            </div>

            <!-- Language Tabs -->
            <div
                style="background: #f1f5f9; padding: 0 20px; border-bottom: 1px solid #e2e8f0; display: flex; gap: 5px; flex-shrink: 0;">
                <?php $first = true;
                foreach ($languages as $code => $lang): ?>
                    <button class="lang-tab-btn <?php echo $first ? 'active' : ''; ?>" data-lang="<?php echo $code; ?>"
                        style="padding: 12px 20px; border: none; background: <?php echo $first ? 'white' : 'transparent'; ?>; border-bottom: 2px solid <?php echo $first ? '#3b82f6' : 'transparent'; ?>; font-weight: 600; color: <?php echo $first ? '#1e293b' : '#64748b'; ?>; cursor: pointer; transition: all 0.2s;">
                        <?php echo $lang['flag']; ?>             <?php echo $lang['name']; ?>
                        <span class="status-indicator-<?php echo $code; ?>" style="font-size: 10px; margin-left: 5px;"></span>
                    </button>
                    <!-- Hidden inputs for target IDs -->
                    <input type="hidden" id="target-blog-id-<?php echo $code; ?>" value="<?php echo $lang['blog_id']; ?>">
                    <?php $first = false; endforeach; ?>
            </div>

            <div id="modal-content" style="flex-grow: 1; overflow-y: auto; background: #f8f9fa;">
                <input type="hidden" id="original-post-id" />
                <input type="hidden" id="featured-image-id" />

                <?php $first = true;
                foreach ($languages as $code => $lang): ?>
                    <div id="tab-content-<?php echo $code; ?>" class="tab-content"
                        style="display: <?php echo $first ? 'block' : 'none'; ?>; padding: 25px;">

                        <!-- SEO Score Section -->
                        <div id="seo-score-section-<?php echo $code; ?>"
                            style="background: white; border: 1px solid #e2e8f0; padding: 15px; margin-bottom: 20px; border-radius: 8px; box-shadow: 0 1px 3px rgba(0,0,0,0.05); display: flex; align-items: center; gap: 15px;">
                            <div id="seo-score-circle-<?php echo $code; ?>"
                                style="width: 50px; height: 50px; border-radius: 50%; background: #e5e7eb; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 16px; color: white; flex-shrink: 0;">
                                <span id="seo-score-value-<?php echo $code; ?>">0</span>
                            </div>
                            <div>
                                <strong id="seo-score-text-<?php echo $code; ?>"
                                    style="display: block; font-size: 15px; color: #64748b;">SEO Score: Checking...</strong>
                                <small style="color: #64748b;">Real-time optimization analysis</small>
                            </div>
                            <div style="margin-left: auto; display: flex; gap: 8px;">
                                <button class="button save-single-btn" data-lang="<?php echo $code; ?>"
                                    style="display: flex; align-items: center; gap: 5px;">
                                    <span>💾</span> Save
                                </button>
                                <button class="button fill-missing-btn" data-lang="<?php echo $code; ?>"
                                    style="display: flex; align-items: center; gap: 5px;">
                                    <span>✨</span> Fill Missing
                                </button>
                                <button class="button refresh-content-btn" data-lang="<?php echo $code; ?>"
                                    style="display: flex; align-items: center; gap: 5px;">
                                    <span>🔄</span> Refresh Content
                                </button>
                                <?php if ($code === 'en'): ?>
                                    <button class="button generate-single-btn" data-lang="<?php echo $code; ?>"
                                        style="display: flex; align-items: center; gap: 5px;">
                                        <span>⚡️</span> Regenerate All
                                    </button>
                                <?php else: ?>
                                    <button class="button button-primary publish-single-btn" data-lang="<?php echo $code; ?>"
                                        style="display: flex; align-items: center; gap: 5px;">
                                        <span>🚀</span> Publish
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                            <!-- Column 1: Metadata -->
                            <div style="display: grid; gap: 15px; align-content: start;">
                                <!-- Focus Keyword -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">Focus
                                        Keyword</label>
                                    <input type="text" id="focus-keyword-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px;"
                                        placeholder="Target keyword" />
                                </div>

                                <!-- SEO Title -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">
                                        SEO Title <span id="meta-title-counter-<?php echo $code; ?>"
                                            style="float: right; font-weight: normal; color: #94a3b8;">0/60</span>
                                    </label>
                                    <input type="text" id="meta-title-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>" maxlength="60"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px;" />
                                </div>

                                <!-- SEO Description -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">
                                        Meta Description <span id="meta-desc-counter-<?php echo $code; ?>"
                                            style="float: right; font-weight: normal; color: #94a3b8;">0/160</span>
                                    </label>
                                    <textarea id="meta-description-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>" maxlength="160"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; height: 80px; resize: vertical;"></textarea>
                                </div>

                                <!-- Permalink (Slug) -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">
                                        Permalink (Slug)</label>
                                    <input type="text" id="post-slug-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px;"
                                        placeholder="url-friendly-slug" />
                                </div>

                                <!-- Image Alt Text -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">
                                        Featured Image Alt Text</label>
                                    <input type="text" id="image-alt-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px;"
                                        placeholder="Descriptive alt text" />
                                </div>
                            </div>

                            <!-- Column 2: Content -->
                            <div style="display: grid; gap: 15px;">
                                <!-- Post Title -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">Post
                                        Title (H1)</label>
                                    <input type="text" id="post-title-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>"
                                        style="width: 100%; padding: 8px 10px; border: 1px solid #cbd5e1; border-radius: 4px; font-weight: 600;" />
                                </div>

                                <!-- Post Content -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">Post
                                        Content</label>

                                    <!-- Editor Mode Tabs (WordPress style) -->
                                    <div class="wp-editor-tabs" id="editor-tabs-<?php echo $code; ?>"
                                        style="border-bottom: 1px solid #cbd5e1; margin-bottom: 0;">
                                        <button type="button" class="wp-switch-editor switch-tmce" data-lang="<?php echo $code; ?>"
                                            data-mode="visual"
                                            style="background: #f9fafb; border: 1px solid #cbd5e1; border-bottom: none; padding: 5px 10px; cursor: pointer; font-size: 13px; border-radius: 4px 4px 0 0; margin-right: 4px; color: #0073aa;">
                                            Visual
                                        </button>
                                        <button type="button" class="wp-switch-editor switch-html" data-lang="<?php echo $code; ?>"
                                            data-mode="text"
                                            style="background: #fff; border: 1px solid #cbd5e1; border-bottom: none; padding: 5px 10px; cursor: pointer; font-size: 13px; border-radius: 4px 4px 0 0; color: #555;">
                                            Text
                                        </button>
                                    </div>

                                    <div class="wp-editor-container" id="wp-post-content-<?php echo $code; ?>-wrap"
                                        style="border: 1px solid #cbd5e1; border-top: none;">
                                        <textarea id="post-content-<?php echo $code; ?>" class="seo-input"
                                            data-lang="<?php echo $code; ?>"
                                            style="width: 100%; padding: 10px; border: none; height: 300px; font-family: monospace; resize: vertical;"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php $first = false; endforeach; ?>
            </div>

            <!-- Modal Footer -->
            <div
                style="padding: 15px 25px; background: white; border-top: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center; flex-shrink: 0;">
                <div style="display: flex; gap: 10px; align-items: center;">
                    <span id="global-status" style="font-weight: 600; color: #475569;"></span>
                </div>
                <div style="display: flex; gap: 10px;">
                    <button class="button cancel-btn" style="border: 1px solid #cbd5e1; color: #475569;">Cancel</button>
                    <button id="generate-all-btn" class="button button-secondary"
                        style="display: flex; align-items: center; gap: 5px;">
                        <span>✨</span> Generate All Missing
                    </button>
                    <button id="publish-all-btn" class="button button-primary"
                        style="background: #2563eb; border: none; padding: 0 20px; font-weight: 600;">
                        🚀 Publish All Checked
                    </button>
                </div>
            </div>
        </div>
        <div id="modal-overlay"
            style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(2px); z-index: 9999;">
        </div>

        <script>
            // v2.1 - Updated Fill Missing logic
            jQuery(document).ready(function ($) {
                const nonce = '<?php echo wp_create_nonce('iptv_localizer_nonce'); ?>';
                const languages = ['en', 'se', 'no', 'dk', 'fi', 'is']; // All languages including English
                const translateLanguages = ['se', 'no', 'dk', 'fi', 'is']; // Translate targets only

                let currentActiveLang = 'en'; // Track current active language

                // Initialize TinyMCE for a specific language
                function initializeEditor(lang) {
                    const editorId = 'post-content-' + lang;

                    // Check if editor already exists
                    if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                        // Editor exists, just show it
                        tinymce.get(editorId).show();
                        $('#' + editorId).hide();
                        return;
                    }

                    // Initialize TinyMCE directly
                    if (typeof tinymce !== 'undefined') {
                        tinymce.init({
                            selector: '#' + editorId,
                            menubar: false,
                            branding: false,
                            height: 300,
                            plugins: 'lists link paste',
                            toolbar: 'undo redo | bold italic underline | bullist numlist | link | removeformat',
                            paste_as_text: false,
                            content_css: false,
                            init_instance_callback: function (editor) {
                                // Hide textarea when editor is ready
                                $('#' + editorId).hide();
                            },
                            setup: function (editor) {
                                editor.on('change keyup', function () {
                                    editor.save(); // Save to textarea
                                    updateSeoScore(lang);
                                });
                            }
                        });
                    }
                }

                // Destroy TinyMCE editor for a language
                function destroyEditor(lang) {
                    const editorId = 'post-content-' + lang;
                    if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                        tinymce.get(editorId).save(); // Save content to textarea
                        tinymce.get(editorId).remove(); // Remove editor instance
                    }
                }

                // Visual/Text Mode Switcher
                $(document).on('click', '.wp-switch-editor', function () {
                    const lang = $(this).data('lang');
                    const mode = $(this).data('mode');
                    const editorId = 'post-content-' + lang;

                    // Update button styles
                    $('#editor-tabs-' + lang + ' .wp-switch-editor').css({
                        'background': '#fff',
                        'color': '#555'
                    });
                    $(this).css({
                        'background': '#f9fafb',
                        'color': '#0073aa'
                    });

                    if (mode === 'visual') {
                        // Switch to Visual mode
                        if (typeof tinymce !== 'undefined' && !tinymce.get(editorId)) {
                            // Initialize editor if not exists
                            initializeEditor(lang);
                        } else if (tinymce.get(editorId)) {
                            // Show existing editor
                            tinymce.get(editorId).show();
                        }
                        $('#' + editorId).hide(); // Hide textarea
                    } else {
                        // Switch to Text mode
                        if (typeof tinymce !== 'undefined' && tinymce.get(editorId)) {
                            tinymce.get(editorId).save(); // Save to textarea
                            tinymce.get(editorId).hide(); // Hide visual editor
                        }
                        $('#' + editorId).show(); // Show textarea
                    }
                });

                // Tab Switching with editor management
                $('.lang-tab-btn').on('click', function () {
                    const lang = $(this).data('lang');

                    // Don't reinitialize if clicking same tab
                    if (lang === currentActiveLang) {
                        return;
                    }

                    // Save current editor content before switching
                    if (typeof tinymce !== 'undefined' && tinymce.get('post-content-' + currentActiveLang)) {
                        tinymce.get('post-content-' + currentActiveLang).save();
                    }

                    // Update tabs
                    $('.lang-tab-btn').css({
                        'background': 'transparent',
                        'border-bottom-color': 'transparent',
                        'color': '#64748b'
                    });
                    $(this).css({
                        'background': 'white',
                        'border-bottom-color': '#3b82f6',
                        'color': '#1e293b'
                    });

                    // Show content
                    $('.tab-content').hide();
                    $('#tab-content-' + lang).show();

                    // Initialize editor for new tab
                    initializeEditor(lang);
                    currentActiveLang = lang;
                });

                // SEO Score Logic (enhanced with slug and color coding)
                function updateSeoScore(lang) {
                    let score = 0;
                    const keyword = $('#focus-keyword-' + lang).val()?.toLowerCase() || '';
                    const title = $('#meta-title-' + lang).val()?.toLowerCase() || '';
                    const desc = $('#meta-description-' + lang).val()?.toLowerCase() || '';
                    const content = $('#post-content-' + lang).val()?.toLowerCase() || '';
                    const slug = $('#post-slug-' + lang).val()?.toLowerCase() || '';

                    if (!keyword) {
                        updateScoreDisplay(lang, 0);
                        return;
                    }

                    if (title.includes(keyword)) score += 20;
                    if (desc.includes(keyword)) score += 20;
                    if (content.includes(keyword)) score += 20;
                    if (slug.includes(keyword)) score += 10;

                    if (title.length >= 40 && title.length <= 60) score += 10;
                    if (desc.length >= 120 && desc.length <= 160) score += 10;
                    if (content.split(' ').length > 300) score += 10;

                    updateScoreDisplay(lang, score);
                }

                function updateScoreDisplay(lang, score) {
                    $('#seo-score-value-' + lang).text(score);
                    // Enhanced with Rank Math colors and labels
                    let color = '#dc2626', label = 'Poor'; // Red
                    if (score >= 80) {
                        color = '#16a34a'; // Green
                        label = 'Great';
                    } else if (score >= 50) {
                        color = '#f59e0b'; // Orange
                        label = 'OK';
                    }
                    $('#seo-score-circle-' + lang).css('background', color);
                    $('#seo-score-text-' + lang).text('SEO Score: ' + label + ' (' + score + '/100)');
                }

                // Input listeners for SEO
                $('.seo-input').on('input', function () {
                    const lang = $(this).data('lang');
                    updateSeoScore(lang);
                    // Update counters
                    if (this.id.includes('meta-title')) $('#meta-title-counter-' + lang).text(this.value.length + '/60');
                    if (this.id.includes('meta-description')) $('#meta-desc-counter-' + lang).text(this.value.length + '/160');
                });

                // Open Modal Logic (enhanced with initial data fetch)
                $('.localize-btn').on('click', function () {
                    const btn = $(this);
                    const postId = btn.data('post-id');
                    $('#original-post-id').val(postId);
                    $('#featured-image-id').val('');
                    $('#modal-overlay').fadeIn();
                    $('#localize-modal').fadeIn();
                    $('#global-status').text('Loading...');

                    // Clear all fields
                    languages.forEach(lang => {
                        $('#focus-keyword-' + lang).val('');
                        $('#meta-title-' + lang).val('');
                        $('#meta-description-' + lang).val('');
                        $('#post-slug-' + lang).val('');
                        $('#image-alt-' + lang).val('');
                        $('#post-title-' + lang).val('');
                        $('#post-content-' + lang).val('');
                    });

                    // Fetch initial data (English source)
                    $.post(ajaxurl, {
                        action: 'iptv_get_initial_data',
                        nonce: nonce,
                        post_id: postId
                    }).done(function (response) {
                        if (response.success) {
                            const data = response.data;
                            $('#focus-keyword-en').val(data.focus_keyword || '').trigger('input');
                            $('#meta-title-en').val(data.meta_title || '').trigger('input');
                            $('#meta-description-en').val(data.meta_description || '').trigger('input');
                            $('#post-slug-en').val(data.slug || '');
                            $('#image-alt-en').val(data.image_alt || '');
                            $('#post-title-en').val(data.title || '');
                            $('#post-content-en').val(data.content);
                            $('#featured-image-id').val(data.featured_image_id || 0);
                            $('#global-status').text('');

                            // Initialize TinyMCE for English tab (default active tab)
                            initializeEditor('en');
                        } else {
                            $('#global-status').text('⚠️ ' + response.data);
                        }
                    });
                });


                // Close Modal
                $('#close-modal-btn, .cancel-btn, #modal-overlay').on('click', function () {
                    // Destroy all editors before closing
                    languages.forEach(lang => {
                        destroyEditor(lang);
                    });

                    // Reset current active lang
                    currentActiveLang = 'en';

                    $('#localize-modal, #modal-overlay').fadeOut();
                });

                // Save per-language button handler
                $('.save-single-btn').on('click', function () {
                    const lang = $(this).data('lang');
                    const postId = $('#original-post-id').val();
                    $('#global-status').text('Saving ' + lang.toUpperCase() + '...');

                    $.post(ajaxurl, {
                        action: 'iptv_save_localized_content',
                        nonce: nonce,
                        post_id: postId,
                        target_lang: lang,
                        focus_keyword: $('#focus-keyword-' + lang).val(),
                        meta_title: $('#meta-title-' + lang).val(),
                        meta_description: $('#meta-description-' + lang).val(),
                        post_slug: $('#post-slug-' + lang).val(),
                        image_alt: $('#image-alt-' + lang).val(),
                        post_title: $('#post-title-' + lang).val(),
                        post_content: $('#post-content-' + lang).val(),
                        featured_image_id: $('#featured-image-id').val()
                    }).done(function (response) {
                        if (response.success) {
                            $('#global-status').text('✅ Saved ' + lang.toUpperCase());
                            setTimeout(() => $('#global-status').text(''), 2000);
                        } else {
                            alert('Error saving: ' + response.data);
                            $('#global-status').text('');
                        }
                    }).fail(function () {
                        alert('Request failed');
                        $('#global-status').text('');
                    });
                });

                // Fill Missing per-language button handler
                $('.fill-missing-btn').on('click', function () {
                    const lang = $(this).data('lang');

                    // Get existing field values
                    const existingData = {
                        custom_keyword: $('#focus-keyword-' + lang).val(), // Use user's entered keyword
                        existing_title: $('#post-title-' + lang).val(),
                        existing_content: $('#post-content-' + lang).val(),
                        existing_meta_title: $('#meta-title-' + lang).val(),
                        existing_meta_desc: $('#meta-description-' + lang).val(),
                        existing_slug: $('#post-slug-' + lang).val(),
                        existing_image_alt: $('#image-alt-' + lang).val()
                    };

                    // Check TinyMCE content
                    if (typeof tinymce !== 'undefined') {
                        const editor = tinymce.get('post-content-' + lang);
                        if (editor && !editor.isHidden()) {
                            existingData.existing_content = editor.getContent();
                        }
                    }

                    generateContent(lang, null, true, existingData); // true = fill missing mode
                });

                // Refresh Content button handler (rewrites content only)
                $('.refresh-content-btn').on('click', function () {
                    const lang = $(this).data('lang');
                    
                    // Get current content
                    let currentContent = $('#post-content-' + lang).val();
                    
                    // Check TinyMCE
                    if (typeof tinymce !== 'undefined') {
                        const editor = tinymce.get('post-content-' + lang);
                        if (editor && !editor.isHidden()) {
                            currentContent = editor.getContent();
                        }
                    }
                    
                    if (!currentContent) {
                        alert('No content to refresh! Please add some content first.');
                        return;
                    }
                    
                    const refreshData = {
                        custom_keyword: $('#focus-keyword-' + lang).val(),
                        existing_content: currentContent,
                        refresh_content_only: 'true'
                    };
                    
                    generateContent(lang, null, false, refreshData); // false = not fill missing, use refresh mode
                });

                // Publish per-language button handler
                $('.publish-single-btn').on('click', function () {
                    const lang = $(this).data('lang');
                    const postId = $('#original-post-id').val();
                    const blogId = $('#target-blog-id-' + lang).val();

                    if (!$('#post-title-' + lang).val() || !$('#post-content-' + lang).val()) {
                        alert('Please generate or fill content for ' + lang.toUpperCase() + ' before publishing');
                        return;
                    }

                    if (!confirm('Publish to ' + lang.toUpperCase() + ' site?')) return;

                    $('#global-status').text('Publishing to ' + lang.toUpperCase() + '...');

                    $.post(ajaxurl, {
                        action: 'iptv_publish_localized_post',
                        nonce: nonce,
                        original_post_id: postId,
                        target_blog_id: blogId,
                        post_title: $('#post-title-' + lang).val(),
                        post_content: $('#post-content-' + lang).val(),
                        focus_keyword: $('#focus-keyword-' + lang).val(),
                        meta_title: $('#meta-title-' + lang).val(),
                        meta_description: $('#meta-description-' + lang).val(),
                        post_slug: $('#post-slug-' + lang).val(),
                        image_alt: $('#image-alt-' + lang).val()
                    }).done(function (response) {
                        if (response.success) {
                            $('#global-status').text('🚀 Published to ' + lang.toUpperCase() + '!');
                            setTimeout(() => $('#global-status').text(''), 3000);
                        } else {
                            alert('Error publishing: ' + response.data);
                            $('#global-status').text('');
                        }
                    }).fail(function () {
                        alert('Request failed');
                        $('#global-status').text('');
                    });
                });

                // GENERATE SINGLE Logic
                $('.generate-single-btn').on('click', function () {
                    const lang = $(this).data('lang');
                    if (lang === 'en') {
                        // For English, regenerate ALL translation languages
                        if (!confirm('Regenerate ALL translation languages?')) return;
                        let index = 0;
                        function next() {
                            if (index < translateLanguages.length) {
                                generateContent(translateLanguages[index], next);
                                index++;
                            } else {
                                $('#global-status').text('✅ All languages generated!');
                            }
                        }
                        next();
                    } else {
                        generateContent(lang);
                    }
                });

                // GENERATE ALL Logic
                $('#generate-all-btn').on('click', function () {
                    // Trigger sequential generation for all translate languages
                    let index = 0;
                    function next() {
                        if (index < translateLanguages.length) {
                            generateContent(translateLanguages[index], next);
                            index++;
                        } else {
                            $('#global-status').text('✅ All languages generated!');
                        }
                    }
                    next();
                });

                function generateContent(lang, callback = null, fillMissing = false, existingData = {}) {
                    const postId = $('#original-post-id').val();
                    const statusIndicator = $('.status-indicator-' + lang);

                    statusIndicator.text('🔄');
                    $('#global-status').text((fillMissing ? 'Filling missing for ' : 'Generating for ') + lang.toUpperCase() + '...');

                    const requestData = {
                        action: 'iptv_generate_localized_content',
                        nonce: nonce,
                        post_id: postId,
                        target_lang: lang
                    };

                    // If fill missing mode, send existing data and custom keyword
                    if (fillMissing) {
                        requestData.fill_missing = 'true';
                        $.extend(requestData, existingData);
                    }

                    $.post(ajaxurl, requestData).done(function (response) {
                        if (response.success) {
                            const data = response.data;

                            // In fill-missing mode, only update fields that were originally empty
                            if (fillMissing) {
                                // Only update fields if they were empty BEFORE
                                if (!existingData.custom_keyword && data.focus_keyword !== undefined) $('#focus-keyword-' + lang).val(data.focus_keyword).trigger('input');
                                if (!existingData.existing_meta_title && data.meta_title !== undefined) $('#meta-title-' + lang).val(data.meta_title).trigger('input');
                                if (!existingData.existing_meta_desc && data.meta_description !== undefined) $('#meta-description-' + lang).val(data.meta_description).trigger('input');
                                if (!existingData.existing_slug && (data.slug !== undefined || data.post_slug !== undefined)) $('#post-slug-' + lang).val(data.slug || data.post_slug || '');
                                if (!existingData.existing_image_alt && data.image_alt !== undefined) $('#image-alt-' + lang).val(data.image_alt);
                                if (!existingData.existing_title && data.title !== undefined) $('#post-title-' + lang).val(data.title);

                                // Only update content if it was empty
                                if (!existingData.existing_content && data.content !== undefined) {
                                    $('#post-content-' + lang).val(data.content);

                                    // Also update TinyMCE if it exists and is visible
                                    if (typeof tinymce !== 'undefined') {
                                        const editor = tinymce.get('post-content-' + lang);
                                        if (editor && !editor.isHidden()) {
                                            editor.setContent(data.content);
                                        }
                                    }

                                    $('#post-content-' + lang).trigger('input');
                                }
                            } else {
                                // Full regeneration: update all fields
                                if (data.focus_keyword !== undefined) $('#focus-keyword-' + lang).val(data.focus_keyword).trigger('input');
                                if (data.meta_title !== undefined) $('#meta-title-' + lang).val(data.meta_title).trigger('input');
                                if (data.meta_description !== undefined) $('#meta-description-' + lang).val(data.meta_description).trigger('input');
                                if (data.slug !== undefined || data.post_slug !== undefined) $('#post-slug-' + lang).val(data.slug || data.post_slug || '');
                                if (data.image_alt !== undefined) $('#image-alt-' + lang).val(data.image_alt);
                                if (data.title !== undefined) $('#post-title-' + lang).val(data.title);

                                // Update content
                                if (data.content !== undefined) {
                                    $('#post-content-' + lang).val(data.content);

                                    // Also update TinyMCE if it exists and is visible
                                    if (typeof tinymce !== 'undefined') {
                                        const editor = tinymce.get('post-content-' + lang);
                                        if (editor && !editor.isHidden()) {
                                            editor.setContent(data.content);
                                        }
                                    }

                                    $('#post-content-' + lang).trigger('input');
                                }
                            }

                            statusIndicator.text('✅');
                            $('#global-status').text('✅ ' + (fillMissing ? 'Filled missing for ' : 'Generated for ') + lang.toUpperCase() + '!');
                        } else {
                            statusIndicator.text('❌');
                            $('#global-status').text('❌ Error for ' + lang.toUpperCase());
                            alert('Error ' + lang + ': ' + response.data);
                        }
                        if (callback) callback();
                    }).fail(function (xhr, status, error) {
                        statusIndicator.text('❌');
                        $('#global-status').text('❌ Failed for ' + lang.toUpperCase());
                        console.error('AJAX Error:', status, error);
                        if (callback) callback();
                    });
                }

                // PUBLISH ALL Logic
                $('#publish-all-btn').on('click', function () {
                    const postId = $('#original-post-id').val();
                    if (!confirm('Publish all valid content to subsites?')) return;

                    let index = 0;
                    function nextPublish() {
                        if (index < translateLanguages.length) {
                            const lang = translateLanguages[index];
                            // Check if content exists before publishing
                            if ($('#post-title-' + lang).val()) {
                                publishContent(lang, postId, nextPublish);
                            } else {
                                nextPublish(); // Skip empty
                            }
                            index++;
                        } else {
                            $('#global-status').text('🚀 All Checked Content Published!');
                            setTimeout(() => { $('#global-status').text(''); }, 2000);
                        }
                    }
                    nextPublish();
                });

                function publishContent(lang, originalPostId, callback) {
                    $('#global-status').text('Publishing ' + lang.toUpperCase() + '...');
                    const blogId = $('#target-blog-id-' + lang).val();

                    $.post(ajaxurl, {
                        action: 'iptv_publish_localized_post',
                        nonce: nonce,
                        original_post_id: originalPostId,
                        target_blog_id: blogId,
                        post_title: $('#post-title-' + lang).val(),
                        post_content: $('#post-content-' + lang).val(),
                        focus_keyword: $('#focus-keyword-' + lang).val(),
                        meta_title: $('#meta-title-' + lang).val(),
                        meta_description: $('#meta-description-' + lang).val(),
                        post_slug: $('#post-slug-' + lang).val(),
                        image_alt: $('#image-alt-' + lang).val()
                    }).done(function () {
                        if (callback) callback();
                    });
                }
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