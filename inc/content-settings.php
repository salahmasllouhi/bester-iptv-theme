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
    // NOTE: Non-Swedish languages temporarily disabled - see Project_dyali.md "Language Reactivation Guide"
    private $languages = array(
        'en' => array('name' => 'English', 'flag' => '🇺🇸', 'deepl' => 'EN', 'currency' => 'USD ($)'),
        'se' => array('name' => 'Swedish', 'flag' => '🇸🇪', 'deepl' => 'SV', 'currency' => 'SEK (kr)'),
        // LANG-DISABLED: no - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'no' => array('name' => 'Norwegian', 'flag' => '🇳🇴', 'deepl' => 'NB', 'currency' => 'NOK (kr)'),
        // LANG-DISABLED: dk - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'dk' => array('name' => 'Danish', 'flag' => '🇩🇰', 'deepl' => 'DA', 'currency' => 'DKK (kr)'),
        // LANG-DISABLED: fi - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'fi' => array('name' => 'Finnish', 'flag' => '🇫🇮', 'deepl' => 'FI', 'currency' => 'EUR (€)'),
        // LANG-DISABLED: is - See Project_dyali.md "Language Reactivation Guide" to revert
        // 'is' => array('name' => 'Icelandic', 'flag' => '🇮🇸', 'deepl' => 'EN', 'currency' => 'ISK (kr)'),
    );

    // Content fields to translate (defaults match front-page.php)
    private $content_fields = array(

        'hero' => array(
            'label' => 'Hero Section',
            'fields' => array(
                'hero_badge' => array('label' => 'Hero Badge', 'type' => 'text', 'default' => '★★★★★ 4.8/5 from 53,000+ customers'),
                'hero_savings_badge' => array('label' => 'Savings Badge', 'type' => 'text', 'default' => 'Save Over $1,500 Annually!'),
                'hero_title' => array('label' => 'Hero Title Line 1', 'type' => 'text', 'default' => 'Watch Everything.'),
                'hero_title_span' => array('label' => 'Hero Title Highlight', 'type' => 'text', 'default' => 'One Subscription.'),
                'hero_title_3' => array('label' => 'Hero Title Line 3', 'type' => 'text', 'default' => 'Seamlessly Delivered'),
                'hero_title_4' => array('label' => 'Hero Title Line 4', 'type' => 'text', 'default' => 'Zero Limits.'),
                'hero_subtitle' => array('label' => 'Hero Subtitle', 'type' => 'textarea', 'default' => 'Stop paying for <strong>6+ streaming services</strong>. Get every sport, every show, every PPV event in one place. <strong>No blackouts. No restrictions.</strong>'),
                'hero_cta' => array('label' => 'CTA Button', 'type' => 'text', 'default' => 'Start Streaming Now'),
                // New Hero Stats
                'hero_stat_1_val' => array('label' => 'Stat 1 Value', 'type' => 'text', 'default' => '42,537'),
                'hero_stat_1_desc' => array('label' => 'Stat 1 Description', 'type' => 'text', 'default' => 'active viewers'),
                'hero_stat_2_val' => array('label' => 'Stat 2 Value', 'type' => 'text', 'default' => '$0'),
                'hero_stat_2_label' => array('label' => 'Stat 2 Label', 'type' => 'text', 'default' => 'PPV Events'),
                'hero_stat_2_desc' => array('label' => 'Stat 2 Description', 'type' => 'text', 'default' => 'UFC, Boxing - All Included'),
                'hero_stat_3_val' => array('label' => 'Stat 3 Value', 'type' => 'text', 'default' => '35,000+'),
                'hero_stat_3_label' => array('label' => 'Stat 3 Label', 'type' => 'text', 'default' => 'Live Channels'),
                'hero_stat_3_desc' => array('label' => 'Stat 3 Description', 'type' => 'text', 'default' => 'From 198 countries'),
            )
        ),
        'unlock' => array(
            'label' => 'Unlock Devices Section',
            'fields' => array(
                'device_list' => array('label' => 'Device List (Comma Separated)', 'type' => 'textarea', 'default' => 'Apple TV, Firestick, Smart TV, Roku, Android, iPhone/iPad, Browser, Shield, MAG Box, Chromecast, Windows, MacOS'),
            )
        ),
        'brands' => array(
            'label' => 'Brands Section',
            'fields' => array(
                'logos_label' => array('label' => 'Logos Label', 'type' => 'text', 'default' => 'Access Premium Content From'),
                'brands_title' => array('label' => 'Brands Title', 'type' => 'text', 'default' => 'Stream Your Favorite Channels'),
                'brands_subtitle' => array('label' => 'Brands Subtitle', 'type' => 'textarea', 'default' => 'Access premium content from top networks worldwide with crystal-clear quality'),
            )
        ),
        'features' => array(
            'label' => 'Features Section',
            'fields' => array(
                'features_tag' => array('label' => 'Features Tag', 'type' => 'text', 'default' => 'Features'),
                'features_title' => array('label' => 'Features Title', 'type' => 'text', 'default' => 'Everything You Need in'),
                'features_title_span' => array('label' => 'Features Title Highlight', 'type' => 'text', 'default' => 'One Place'),
                'features_subtitle' => array('label' => 'Features Subtitle', 'type' => 'textarea', 'default' => 'No more juggling multiple subscriptions. Get it all with Nordic IPTV.'),
                'feature_1_title' => array('label' => 'Feature 1 Title', 'type' => 'text', 'default' => '35,000+ Live Channels'),
                'feature_1_desc' => array('label' => 'Feature 1 Description', 'type' => 'textarea', 'default' => 'Sports, news, entertainment, kids content — from 198 countries worldwide.'),
                'feature_2_title' => array('label' => 'Feature 2 Title', 'type' => 'text', 'default' => '$0 PPV Events'),
                'feature_2_desc' => array('label' => 'Feature 2 Description', 'type' => 'textarea', 'default' => 'UFC, Boxing, Wrestling — all Pay-Per-View events included free. Save $70+ per event!'),
                'feature_3_title' => array('label' => 'Feature 3 Title', 'type' => 'text', 'default' => 'All Sports Live'),
                'feature_3_desc' => array('label' => 'Feature 3 Description', 'type' => 'textarea', 'default' => 'EPL, La Liga, Champions League, NFL, NBA, F1 — every game, live.'),
                'feature_4_title' => array('label' => 'Feature 4 Title', 'type' => 'text', 'default' => '150K+ Movies & Shows'),
                'feature_4_desc' => array('label' => 'Feature 4 Description', 'type' => 'textarea', 'default' => 'Massive VOD library with latest releases. New content added daily.'),
                'feature_5_title' => array('label' => 'Feature 5 Title', 'type' => 'text', 'default' => 'Crystal Clear 4K'),
                'feature_5_desc' => array('label' => 'Feature 5 Description', 'type' => 'textarea', 'default' => 'Ultra HD quality on all devices. No buffering, no lag.'),
                'feature_6_title' => array('label' => 'Feature 6 Title', 'type' => 'text', 'default' => '24/7 Support'),
                'feature_6_desc' => array('label' => 'Feature 6 Description', 'type' => 'textarea', 'default' => 'Real humans, real help via live chat or WhatsApp anytime.'),
                'features_cta' => array('label' => 'Features CTA Button', 'type' => 'text', 'default' => 'Get Access Now'),
            )
        ),
        'comparison' => array(
            'label' => 'Comparison Table',
            'fields' => array(
                // Left Column Content
                'comp_badge' => array('label' => 'Badge Text (Yellow)', 'type' => 'text', 'default' => '⚡ Save Thousands Yearly'),
                'comp_title_main' => array('label' => 'Main Title', 'type' => 'text', 'default' => 'Stop Overpaying for Cable.'),
                'comp_title_sub' => array('label' => 'Title Line 2', 'type' => 'text', 'default' => 'Switch to Premium IPTV.'),
                'comp_desc' => array('label' => 'Description Text', 'type' => 'textarea', 'default' => 'Why waste money? Get instant access to 33,000+ live channels and 150,000+ movies & shows in stunning 4K. No contracts, no hidden fees—just pure entertainment.'),
                'comp_cta_text' => array('label' => 'CTA Button Text', 'type' => 'text', 'default' => 'Start Watching Now →'),
                'comp_cta_link' => array('label' => 'CTA Button Link', 'type' => 'text', 'default' => '#pricing'),

                // Table Header
                'comp_th_feature' => array('label' => 'Feature Column Header', 'type' => 'text', 'default' => 'Feature'),

                // Right Column (Table Header)
                'comp_col_1' => array('label' => 'Competitor Name', 'type' => 'text', 'default' => 'Traditional Cable'),
                'comp_col_2' => array('label' => 'Our Name', 'type' => 'text', 'default' => 'Nordic IPTV'),

                // Row 1
                'comp_row_1_label' => array('label' => 'Row 1 Label', 'type' => 'text', 'default' => 'Netflix'),
                'comp_row_1_val_1' => array('label' => 'Row 1 Competitor', 'type' => 'text', 'default' => '$17.99/mo'),
                'comp_row_1_val_2' => array('label' => 'Row 1 Us', 'type' => 'text', 'default' => '35,000+'),

                // Row 2
                'comp_row_2_label' => array('label' => 'Row 2 Label', 'type' => 'text', 'default' => 'Disney+'),
                'comp_row_2_val_1' => array('label' => 'Row 2 Competitor', 'type' => 'text', 'default' => '$12.99/mo'),
                'comp_row_2_val_2' => array('label' => 'Row 2 Us', 'type' => 'text', 'default' => '150,000+ titles'),

                // Row 3
                'comp_row_3_label' => array('label' => 'Row 3 Label', 'type' => 'text', 'default' => 'HBO Max'),
                'comp_row_3_val_1' => array('label' => 'Row 3 Competitor', 'type' => 'text', 'default' => '$14.99/mo'),
                'comp_row_3_val_2' => array('label' => 'Row 3 Us', 'type' => 'text', 'default' => 'Included'),
                'comp_price' => array('label' => 'Annual Price (Us)', 'type' => 'text', 'default' => '$69.99'),

                // Row 4
                'comp_row_4_label' => array('label' => 'Row 4 Label', 'type' => 'text', 'default' => 'Sports Package'),
                'comp_row_4_val_1' => array('label' => 'Row 4 Competitor', 'type' => 'text', 'default' => '$35.00/mo'),
                'comp_row_4_val_2' => array('label' => 'Row 4 Us', 'type' => 'text', 'default' => 'Included'),

                // Row 5
                'comp_row_5_label' => array('label' => 'Row 5 Label', 'type' => 'text', 'default' => 'PPV Events (yearly)'),
                'comp_row_5_val_1' => array('label' => 'Row 5 Competitor', 'type' => 'text', 'default' => '$700+/yr'),
                'comp_row_5_val_2' => array('label' => 'Row 5 Us', 'type' => 'text', 'default' => 'Included'),

                // Row 6
                'comp_row_6_label' => array('label' => 'Row 6 Label', 'type' => 'text', 'default' => 'All Streaming Content'),
                'comp_row_6_val_1' => array('label' => 'Row 6 Competitor', 'type' => 'text', 'default' => '$5-$20 each'),
                'comp_row_6_val_2' => array('label' => 'Row 6 Us', 'type' => 'text', 'default' => 'Included'),

                // Row 7
                'comp_row_7_label' => array('label' => 'Row 7 Label', 'type' => 'text', 'default' => 'All Live Sports'),
                'comp_row_7_val_1' => array('label' => 'Row 7 Competitor', 'type' => 'text', 'default' => 'Required'),
                'comp_row_7_val_2' => array('label' => 'Row 7 Us', 'type' => 'text', 'default' => 'Included'),

                // Row 8
                'comp_row_8_label' => array('label' => 'Row 8 Label', 'type' => 'text', 'default' => 'All PPV Events'),
                'comp_row_8_val_1' => array('label' => 'Row 8 Competitor', 'type' => 'text', 'default' => 'Extra Fees'),
                'comp_row_8_val_2' => array('label' => 'Row 8 Us', 'type' => 'text', 'default' => '$0 Extra'),

                // Row 9
                'comp_row_9_label' => array('label' => 'Row 9 Label', 'type' => 'text', 'default' => 'Channels'),
                'comp_row_9_val_1' => array('label' => 'Row 9 Competitor', 'type' => 'text', 'default' => 'Limited'),
                'comp_row_9_val_2' => array('label' => 'Row 9 Us', 'type' => 'text', 'default' => '20,000+'),

                // Row 10
                'comp_row_10_label' => array('label' => 'Row 10 Label', 'type' => 'text', 'default' => 'Quality'),
                'comp_row_10_val_1' => array('label' => 'Row 10 Competitor', 'type' => 'text', 'default' => 'HD only'),
                'comp_row_10_val_2' => array('label' => 'Row 10 Us', 'type' => 'text', 'default' => '4K Included'),

                // Totals & Savings
                'comp_total_label' => array('label' => 'Competitor Total Label', 'type' => 'text', 'default' => 'Annual Cost'),
                'comp_total_val_1' => array('label' => 'Competitor Annual Cost', 'type' => 'text', 'default' => '$1,200+'),
                'comp_price_label' => array('label' => 'Our Price Label', 'type' => 'text', 'default' => 'Nordic IPTV Annual Cost'),
                'comp_price_sub' => array('label' => 'Our Price Subtext', 'type' => 'text', 'default' => 'Just ~$5.83/month'),
                'comp_savings_label' => array('label' => 'Savings Banner Label', 'type' => 'text', 'default' => 'Your Annual Savings'),
                'comp_savings_val' => array('label' => 'Savings Amount', 'type' => 'text', 'default' => '$1,100+'),
                'comp_cta' => array('label' => 'Comparison CTA Button', 'type' => 'text', 'default' => 'Start Saving Today'),
            )
        ),
        'steps' => array(
            'label' => 'Steps Section',
            'fields' => array(
                'steps_tag' => array('label' => 'Steps Tag', 'type' => 'text', 'default' => 'Easy Setup'),
                'steps_title' => array('label' => 'Steps Title', 'type' => 'text', 'default' => 'Start Streaming in'),
                'steps_title_span' => array('label' => 'Steps Title Highlight', 'type' => 'text', 'default' => '3 Steps'),
                'steps_subtitle' => array('label' => 'Steps Subtitle', 'type' => 'text', 'default' => 'Get up and running in minutes, not hours.'),
                'step_1_badge' => array('label' => 'Step 1 Badge', 'type' => 'text', 'default' => '1'),
                'step_1_title' => array('label' => 'Step 1 Title', 'type' => 'text', 'default' => 'Choose Your Plan'),
                'step_1_desc' => array('label' => 'Step 1 Description', 'type' => 'textarea', 'default' => 'Browse our flexible subscription packages and select the one that fits your budget and device needs.'),
                'step_2_badge' => array('label' => 'Step 2 Badge', 'type' => 'text', 'default' => '2'),
                'step_2_title' => array('label' => 'Step 2 Title', 'type' => 'text', 'default' => 'Complete Payment'),
                'step_2_desc' => array('label' => 'Step 2 Description', 'type' => 'textarea', 'default' => 'Checkout securely using our encrypted payment gateway. We accept major cards and crypto options.'),
                'step_3_badge' => array('label' => 'Step 3 Badge', 'type' => 'text', 'default' => '3'),
                'step_3_title' => array('label' => 'Step 3 Title', 'type' => 'text', 'default' => 'Start Watching'),
                'step_3_desc' => array('label' => 'Step 3 Description', 'type' => 'textarea', 'default' => 'Our team will configure your account and send login credentials via email. Download the app and enjoy!'),
                'steps_cta' => array('label' => 'Steps CTA Button', 'type' => 'text', 'default' => 'Get Started Now'),
            )
        ),
        'unlock' => array(
            'label' => 'Devices Section',
            'fields' => array(
                'devices_tag' => array('label' => 'Section Tag', 'type' => 'text', 'default' => 'Compatibility'),
                'devices_title' => array('label' => 'Title (First Part)', 'type' => 'text', 'default' => 'Watch On'),
                'devices_title_span' => array('label' => 'Title Highlight', 'type' => 'text', 'default' => 'Any Device'),
                'devices_subtitle' => array('label' => 'Subtitle', 'type' => 'textarea', 'default' => 'Works flawlessly on Smart TV, Android, iOS, Firestick, MAG, and more.'),
            )
        ),
        'dark_cta' => array(
            'label' => 'Dark CTA (Comparison)',
            'fields' => array(
                'cta_title' => array('label' => 'CTA Title', 'type' => 'text', 'default' => 'Ready to Start Streaming?'),
                'cta_subtitle' => array('label' => 'CTA Subtitle', 'type' => 'text', 'default' => 'Join thousands of satisfied customers who switched to premium IPTV streaming.'),
                'cta_btn_text' => array('label' => 'CTA Button Text', 'type' => 'text', 'default' => 'Start Streaming Now'),
                'cta_f1' => array('label' => 'Feature 1', 'type' => 'text', 'default' => '256-bit SSL Encryption'),
                'cta_f2' => array('label' => 'Feature 2', 'type' => 'text', 'default' => 'Instant Activation'),
                'cta_f3' => array('label' => 'Feature 3', 'type' => 'text', 'default' => '24/7 Customer Support'),

                // Original Dark CTA fields (negative/positive cards) - keeping them just in case, but they seem unused in current template
                'cta_title_span' => array('label' => 'CTA Title Highlight', 'type' => 'text', 'default' => 'Break Free with Nordic IPTV'),
                'cta_negative_title' => array('label' => 'Negative Card Title', 'type' => 'text', 'default' => 'The Struggle is Real'),
                'cta_negative_subtitle' => array('label' => 'Negative Card Subtitle', 'type' => 'text', 'default' => 'What you\'re dealing with right now'),
                'neg_1_title' => array('label' => 'Negative Point 1 Title', 'type' => 'text', 'default' => 'Buffering when it matters most'),
                'neg_1_desc' => array('label' => 'Negative Point 1 Desc', 'type' => 'text', 'default' => 'Nothing kills the vibe like loading screens interrupting your show.'),
                'neg_2_title' => array('label' => 'Negative Point 2 Title', 'type' => 'text', 'default' => 'Sky-high bills for channels you don\'t watch'),
                'neg_2_desc' => array('label' => 'Negative Point 2 Desc', 'type' => 'text', 'default' => 'Paying premium prices for bloated packages filled with filler.'),
                'neg_3_title' => array('label' => 'Negative Point 3 Title', 'type' => 'text', 'default' => 'Same old content, same old reruns'),
                'neg_3_desc' => array('label' => 'Negative Point 3 Desc', 'type' => 'text', 'default' => 'Limited selection keeps you stuck in a small box.'),
                'neg_4_title' => array('label' => 'Negative Point 4 Title', 'type' => 'text', 'default' => 'Blurry, pixelated picture quality'),
                'neg_4_desc' => array('label' => 'Negative Point 4 Desc', 'type' => 'text', 'default' => 'Your expensive TV deserves better than fuzzy streams.'),
                'cta_positive_title' => array('label' => 'Positive Card Title', 'type' => 'text', 'default' => 'Welcome to Nordic IPTV'),
                'cta_positive_subtitle' => array('label' => 'Positive Card Subtitle', 'type' => 'text', 'default' => 'Your complete streaming freedom awaits'),
                'pos_1_title' => array('label' => 'Positive Point 1 Title', 'type' => 'text', 'default' => 'Silky-smooth, uninterrupted streaming'),
                'pos_1_desc' => array('label' => 'Positive Point 1 Desc', 'type' => 'text', 'default' => 'Crystal clear playback, even when everyone\'s watching.'),
                'pos_2_title' => array('label' => 'Positive Point 2 Title', 'type' => 'text', 'default' => 'Smart pricing that doesn\'t break the bank'),
                'pos_2_desc' => array('label' => 'Positive Point 2 Desc', 'type' => 'text', 'default' => 'Premium entertainment at a fraction of traditional costs.'),
                'pos_3_title' => array('label' => 'Positive Point 3 Title', 'type' => 'text', 'default' => '30,000+ channels & 150,000+ movies'),
                'pos_3_desc' => array('label' => 'Positive Point 3 Desc', 'type' => 'text', 'default' => 'The entire world of entertainment at your fingertips.'),
                'pos_4_title' => array('label' => 'Positive Point 4 Title', 'type' => 'text', 'default' => 'Stunning 4K quality picture'),
                'pos_4_desc' => array('label' => 'Positive Point 4 Desc', 'type' => 'text', 'default' => 'Cinema-quality viewing that honors your home investment.'),
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

                // Dynamic Pricing Labels
                'month_1_label' => array('label' => '1 Month Label', 'type' => 'text', 'default' => '1 Month'),
                'month_3_label' => array('label' => '3 Months Label', 'type' => 'text', 'default' => '3 Months'),
                'month_6_label' => array('label' => '6 Months Label', 'type' => 'text', 'default' => '6 Months'),
                'month_12_label' => array('label' => '12 Months Label', 'type' => 'text', 'default' => '12 Months'),

                'save_40_text' => array('label' => 'Save 40% Text', 'type' => 'text', 'default' => 'Save 40%'),
                'save_58_text' => array('label' => 'Save 58% Text', 'type' => 'text', 'default' => 'Save 58%'),
                'best_value_text' => array('label' => 'Best Value Badge', 'type' => 'text', 'default' => 'Best Value'),

                'device_singular' => array('label' => 'Device (Singular)', 'type' => 'text', 'default' => 'Device'),
                'device_plural' => array('label' => 'Devices (Plural)', 'type' => 'text', 'default' => 'Devices'),

                'step_1_label' => array('label' => 'Step 1 Label (Pricing)', 'type' => 'text', 'default' => 'Select Devices'),
                'step_2_label' => array('label' => 'Step 2 Label (Pricing)', 'type' => 'text', 'default' => 'Choose Plan'),
                'step_3_label' => array('label' => 'Step 3 Label (Pricing)', 'type' => 'text', 'default' => 'Complete Order'),
            )
        ),
        'reviews' => array(
            'label' => 'Reviews Section',
            'fields' => array(
                'reviews_tag' => array('label' => 'Reviews Tag', 'type' => 'text', 'default' => 'Customer Reviews'),
                'reviews_title' => array('label' => 'Section Title', 'type' => 'text', 'default' => 'Rated 4.9/5 on Trustpilot'),
                'reviews_subtitle' => array('label' => 'Section Subtitle', 'type' => 'text', 'default' => 'Based on 2,500+ reviews'),

                // Review 1
                'review_1_text' => array('label' => 'Review 1 Text', 'type' => 'textarea', 'default' => 'Best IPTV service I\'ve ever used. Crystal clear picture and no buffering at all. Highly recommend!'),
                'review_1_author' => array('label' => 'Review 1 Author', 'type' => 'text', 'default' => 'John D.'),

                // Review 2
                'review_2_text' => array('label' => 'Review 2 Text', 'type' => 'textarea', 'default' => 'Amazing content library and great customer support. Setup was super easy on my Firestick.'),
                'review_2_author' => array('label' => 'Review 2 Author', 'type' => 'text', 'default' => 'Sarah M.'),

                // Review 3
                'review_3_text' => array('label' => 'Review 3 Text', 'type' => 'textarea', 'default' => 'Finally cut the cord! Saving so much money and have access to way more channels than cable.'),
                'review_3_author' => array('label' => 'Review 3 Author', 'type' => 'text', 'default' => 'Mike R.'),

                // Review 4
                'review_4_text' => array('label' => 'Review 4 Text', 'type' => 'textarea', 'default' => 'The 4K server is incredible. Watched the entire game without a single freeze.'),
                'review_4_author' => array('label' => 'Review 4 Author', 'type' => 'text', 'default' => 'David K.'),

                // Review 5
                'review_5_text' => array('label' => 'Review 5 Text', 'type' => 'textarea', 'default' => 'Setup guide was very clear. I was up and running in 5 minutes. Support answered my WhatsApp immediately.'),
                'review_5_author' => array('label' => 'Review 5 Author', 'type' => 'text', 'default' => 'Emma L.'),

                // Review 6
                'review_6_text' => array('label' => 'Review 6 Text', 'type' => 'textarea', 'default' => 'Better than my previous provider. The EPG actually works and the catch-up feature is a lifesaver.'),
                'review_6_author' => array('label' => 'Review 6 Author', 'type' => 'text', 'default' => 'Thomas B.'),

                // CTA
                'reviews_cta_text' => array('label' => 'CTA Button Text', 'type' => 'text', 'default' => 'Get Started Now'),
                'reviews_cta_link' => array('label' => 'CTA Button Link', 'type' => 'text', 'default' => '#pricing'),
            )
        ),
        'contact' => array(
            'label' => 'Contact Section',
            'fields' => array(
                'contact_title' => array('label' => 'Contact Title', 'type' => 'text', 'default' => 'Need Help?'),
                'contact_subtitle' => array('label' => 'Contact Subtitle', 'type' => 'text', 'default' => 'Our support team is here for you 24/7'),
                'contact_email' => array('label' => 'Email Label', 'type' => 'text', 'default' => 'Email Support'),
                'contact_email_text' => array('label' => 'Email Address Display', 'type' => 'text', 'default' => 'support@nordictv.com'),
                'contact_email_link' => array('label' => 'Email Link (mailto:)', 'type' => 'text', 'default' => 'mailto:support@nordictv.com'),
                'contact_whatsapp' => array('label' => 'WhatsApp Label', 'type' => 'text', 'default' => 'WhatsApp Support'),
                'contact_whatsapp_text' => array('label' => 'WhatsApp Number Display', 'type' => 'text', 'default' => '+1 234 567 890'),
                'contact_whatsapp_link' => array('label' => 'WhatsApp Link (wa.me)', 'type' => 'text', 'default' => 'https://wa.me/1234567890'),
            )
        ),
        'sports' => array(
            'label' => 'Sports Section',
            'fields' => array(
                'sports_tag' => array('label' => 'Sports Tag', 'type' => 'text', 'default' => 'Sports'),
                'sports_title' => array('label' => 'Sports Title', 'type' => 'text', 'default' => 'Never Miss a'),
                'sports_title_span' => array('label' => 'Sports Title Highlight', 'type' => 'text', 'default' => 'Game'),
                'sports_desc' => array('label' => 'Sports Description', 'type' => 'text', 'default' => 'All your favorite leagues and tournaments, live and on-demand.'),
                'sport_live_text' => array('label' => 'Live Badge Text', 'type' => 'text', 'default' => 'LIVE NOW'),

                // Sport 1: Soccer
                'sport_1_name' => array('label' => 'Sport 1 Name', 'type' => 'text', 'default' => 'Soccer'),
                'sport_1_subtitle' => array('label' => 'Sport 1 Subtitle', 'type' => 'text', 'default' => 'Premier League, Champions League, World Cup'),

                // Sport 2: NBA
                'sport_2_name' => array('label' => 'Sport 2 Name', 'type' => 'text', 'default' => 'NBA'),
                'sport_2_subtitle' => array('label' => 'Sport 2 Subtitle', 'type' => 'text', 'default' => 'Regular Season, Playoffs & Finals'),

                // Sport 3: MLB
                'sport_3_name' => array('label' => 'Sport 3 Name', 'type' => 'text', 'default' => 'MLB'),
                'sport_3_subtitle' => array('label' => 'Sport 3 Subtitle', 'type' => 'text', 'default' => 'Full Season, World Series'),

                // Sport 4: NFL
                'sport_4_name' => array('label' => 'Sport 4 Name', 'type' => 'text', 'default' => 'NFL'),
                'sport_4_subtitle' => array('label' => 'Sport 4 Subtitle', 'type' => 'text', 'default' => 'Regular Season, Super Bowl'),

                // Sport 5: F1
                'sport_5_name' => array('label' => 'Sport 5 Name', 'type' => 'text', 'default' => 'F1'),
                'sport_5_subtitle' => array('label' => 'Sport 5 Subtitle', 'type' => 'text', 'default' => 'All Grand Prix Races'),

                // Sport 6: UFC/Boxing
                'sport_6_name' => array('label' => 'Sport 6 Name', 'type' => 'text', 'default' => 'UFC/Boxing'),
                'sport_6_subtitle' => array('label' => 'Sport 6 Subtitle', 'type' => 'text', 'default' => 'All PPV Events Free'),
                'sports_cta' => array('label' => 'Sports CTA Button', 'type' => 'text', 'default' => 'Watch All Sports Now'),
            )
        ),
        'footer' => array(
            'label' => 'Footer',
            'fields' => array(
                'footer_desc' => array('label' => 'Footer Description', 'type' => 'textarea', 'default' => 'Premium IPTV streaming service with 35,000+ channels worldwide.'),
                'footer_copyright' => array('label' => 'Copyright Text', 'type' => 'text', 'default' => 'All rights reserved.'),
            )
        ),
        'faq' => array(
            'label' => 'FAQ Section',
            'fields' => array(
                'faq_title' => array('label' => 'FAQ Title', 'type' => 'text', 'default' => 'Got Questions?'),
                'faq_subtitle' => array('label' => 'FAQ Subtitle', 'type' => 'text', 'default' => 'Find answers to commonly asked questions.'),

                // Q1
                'faq_q_1' => array('label' => 'Question 1', 'type' => 'text', 'default' => 'What is IPTV?'),
                'faq_a_1' => array('label' => 'Answer 1', 'type' => 'textarea', 'default' => 'IPTV (Internet Protocol Television) is a modern way to watch TV channels, movies, and series using an internet connection instead of traditional cable or satellite services.'),

                // Q2
                'faq_q_2' => array('label' => 'Question 2', 'type' => 'text', 'default' => 'What is NordicTV?'),
                'faq_a_2' => array('label' => 'Answer 2', 'type' => 'textarea', 'default' => 'NordicTV is a premium IPTV service offering 35,000+ live channels, 150,000+ movies & series, stunning 4K Ultra HD quality, and 24/7 customer support, accessible on multiple devices.'),

                // Q3
                'faq_q_3' => array('label' => 'Question 3', 'type' => 'text', 'default' => 'How do I subscribe to NordicTV?'),
                'faq_a_3' => array('label' => 'Answer 3', 'type' => 'textarea', 'default' => 'Choose a subscription plan on nordictv.io, complete your order, and you will receive your activation details by email with clear setup instructions.'),

                // Q4
                'faq_q_4' => array('label' => 'Question 4', 'type' => 'text', 'default' => 'Which devices are supported?'),
                'faq_a_4' => array('label' => 'Answer 4', 'type' => 'textarea', 'default' => 'NordicTV works on most popular devices, including: Smart TVs, Android TV & Android phones, iPhone & iPad, Amazon Firestick / Fire TV, MAG boxes, Windows & macOS. If you need help setting up, our support team is available 24/7.'),

                // Q5
                'faq_q_5' => array('label' => 'Question 5', 'type' => 'text', 'default' => 'How many devices can I use at the same time?'),
                'faq_a_5' => array('label' => 'Answer 5', 'type' => 'textarea', 'default' => 'Each subscription allows up to 4 simultaneous connections. You can watch on multiple devices at the same time within this limit.'),

                // Q6
                'faq_q_6' => array('label' => 'Question 6', 'type' => 'text', 'default' => 'What kind of content do you offer?'),
                'faq_a_6' => array('label' => 'Answer 6', 'type' => 'textarea', 'default' => 'NordicTV provides: 35K+ Live TV Channels (sports, entertainment, news, international), 150K+ Movies & TV Series, 4K Ultra HD and HD quality streams, and a constantly updated content library.'),

                // Q7
                'faq_q_7' => array('label' => 'Question 7', 'type' => 'text', 'default' => 'How will I receive my subscription details?'),
                'faq_a_7' => array('label' => 'Answer 7', 'type' => 'textarea', 'default' => 'After payment confirmation, your login details (username, password, or playlist) will be sent to your email. Delivery usually takes a few minutes, but can take up to 8 hours in some cases.'),

                // Q8
                'faq_q_8' => array('label' => 'Question 8', 'type' => 'text', 'default' => 'Do you offer sports and premium channels?'),
                'faq_a_8' => array('label' => 'Answer 8', 'type' => 'textarea', 'default' => 'Yes. NordicTV includes a wide selection of sports, premium entertainment, and international channels, including live events and major leagues.'),

                // Q9
                'faq_q_9' => array('label' => 'Question 9', 'type' => 'text', 'default' => 'What payment methods do you accept?'),
                'faq_a_9' => array('label' => 'Answer 9', 'type' => 'textarea', 'default' => 'We accept secure payments via Credit / Debit Cards and PayPal (where available). All payments are processed through secure gateways.'),

                // Q10
                'faq_q_10' => array('label' => 'Question 10', 'type' => 'text', 'default' => 'Do you offer refunds?'),
                'faq_a_10' => array('label' => 'Answer 10', 'type' => 'textarea', 'default' => 'Customer satisfaction is important to us. If you experience serious issues with the service, please contact our support team and we will do our best to assist you.'),

                // Q11
                'faq_q_11' => array('label' => 'Question 11', 'type' => 'text', 'default' => 'How can I contact support?'),
                'faq_a_11' => array('label' => 'Answer 11', 'type' => 'textarea', 'default' => 'You can reach our support team 24/7 at: <a href="mailto:support@nordictv.io">support@nordictv.io</a>'),

                // Q12
                'faq_q_12' => array('label' => 'Question 12', 'type' => 'text', 'default' => 'Can I become a reseller?'),
                'faq_a_12' => array('label' => 'Answer 12', 'type' => 'textarea', 'default' => 'Yes, reseller opportunities are available. Please contact us at <a href="mailto:support@nordictv.io">support@nordictv.io</a> for more information.'),
            )
        ),
        'showcase' => array(
            'label' => 'Content Showcase',
            'fields' => array(
                'showcase_tag' => array('label' => 'Tagline', 'type' => 'text', 'default' => 'Unlimited Entertainment'),
                'showcase_title' => array('label' => 'Title (First Part)', 'type' => 'text', 'default' => 'What\'s on'),
                'showcase_title_span' => array('label' => 'Title (Highlight)', 'type' => 'text', 'default' => 'Nordic TV?'),
                'showcase_subtitle' => array('label' => 'Description', 'type' => 'textarea', 'default' => 'From the latest blockbusters to timeless classics, we have it all. Enjoy seamless streaming of your favorite content.'),
                'showcase_f1' => array('label' => 'Feature 1', 'type' => 'text', 'default' => '150,000+ Movies & Series'),
                'showcase_f2' => array('label' => 'Feature 2', 'type' => 'text', 'default' => '4K & 8K Ultra HD Quality'),
                'showcase_f3' => array('label' => 'Feature 3', 'type' => 'text', 'default' => 'Multi-Language Subtitles'),
                'showcase_f4' => array('label' => 'Feature 4', 'type' => 'text', 'default' => 'Daily Updates'),
                'showcase_cta' => array('label' => 'CTA Button Text', 'type' => 'text', 'default' => 'Get Access Now'),
            )
        ),
        'footer' => array(
            'label' => 'Footer',
            'fields' => array(
                'footer_desc' => array('label' => 'Footer Description', 'type' => 'textarea', 'default' => 'Premium IPTV streaming service with 35,000+ channels worldwide.'),
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
        add_action('wp_ajax_iptv_clone_to_network', array($this, 'ajax_clone_to_network'));
        add_action('wp_ajax_iptv_remove_from_network', array($this, 'ajax_remove_from_network'));
        // Menu Localizer AJAX handlers
        add_action('wp_ajax_iptv_clone_menus_to_network', array($this, 'ajax_clone_menus_to_network'));
        add_action('wp_ajax_iptv_remove_menus_from_network', array($this, 'ajax_remove_menus_from_network'));
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
            $content[$lang] = array_map('sanitize_textarea_field', wp_unslash($_POST['iptv_content']));
            update_option('iptv_content', $content);
            echo '<div class="notice notice-success"><p>✅ Content saved!</p></div>';
        }

        // One-time migration for Comparison Table (if user has old defaults saved)
        $content = get_option('iptv_content', array());
        if (isset($content['en']['comp_row_1_label']) && $content['en']['comp_row_1_label'] === 'Live Channels') {
            $defaults = array(
                'comp_row_1_label' => 'Netflix',
                'comp_row_1_val_1' => '$17.99/mo',
                'comp_row_2_label' => 'Disney+',
                'comp_row_2_val_1' => '$12.99/mo',
                'comp_row_3_label' => 'HBO Max',
                'comp_row_3_val_1' => '$14.99/mo',
                'comp_row_4_label' => 'Sports Package',
                'comp_row_4_val_1' => '$35.00/mo',
                'comp_row_5_label' => 'PPV Events (yearly)',
                'comp_row_5_val_1' => '$700+',
                'comp_row_6_label' => 'All Streaming Content',
                'comp_row_6_val_2' => 'Included',
                'comp_row_7_label' => 'All Live Sports',
                'comp_row_7_val_2' => 'Included',
                'comp_row_8_label' => 'All PPV Events',
                'comp_row_8_val_2' => '$0 Extra',
                'comp_row_9_label' => '20,000+ Channels',
                'comp_row_9_val_2' => 'Included',
                'comp_row_10_label' => '4K Quality',
                'comp_row_10_val_2' => 'Included',
            );

            foreach ($defaults as $key => $val) {
                $content['en'][$key] = $val;
            }
            update_option('iptv_content', $content);
            echo '<div class="notice notice-info"><p>✅ Comparison table content automatically updated to new defaults.</p></div>';
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
                <a href="?page=iptv-content-settings&tab=menus"
                    class="nav-tab <?php echo $current_tab === 'menus' ? 'nav-tab-active' : ''; ?>">
                    📋 Menus
                </a>
            </div>

            <?php if ($current_tab === 'posts'): ?>
                <?php $this->render_posts_localizer_tab(); ?>
            <?php elseif ($current_tab === 'pages'): ?>
                <?php $this->render_pages_localizer_tab(); ?>
            <?php elseif ($current_tab === 'menus'): ?>
                <?php $this->render_menus_localizer_tab(); ?>
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

                <!-- Global Translation Section -->
                <?php if ($current_lang === 'en'): ?>
                    <div
                        style="margin-bottom: 20px; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #c3c4c7; display: flex; align-items: center; justify-content: space-between; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                        <div>
                            <h2 style="margin: 0 0 10px 0; font-size: 1.3em;">🌍 Global Auto-Translate</h2>
                            <p style="margin: 0; color: #646970;">Automatically translate all content to <strong>Swedish,
                                    Norwegian, Danish, Finnish, and Icelandic</strong> using OpenAI.</p>
                            <p style="margin: 5px 0 0 0; font-size: 0.9em; color: #8c8f94;">⚠️ This process may take a few
                                minutes. Please do not close this tab.</p>
                        </div>
                        <div style="display: flex; flex-direction: column; align-items: flex-end; gap: 10px;">
                            <label
                                style="display: flex; align-items: center; gap: 5px; font-size: 13px; color: #475569; cursor: pointer;">
                                <input type="checkbox" id="global-fill-missing-checkbox"> Only fill empty fields
                            </label>
                            <div style="display: flex; gap: 10px;">
                                <button type="button" class="button button-primary button-hero" id="global-translate-btn">
                                    🚀 Translate Everything
                                </button>
                                <button type="button" class="button button-hero" id="global-erase-btn"
                                    style="background: #d63638; color: #fff; border-color: #d63638;">
                                    🗑️ Delete Everything
                                </button>
                            </div>
                        </div>
                    </div>

                    <div id="global-progress-container"
                        style="display: none; margin-bottom: 20px; background: #fff; padding: 20px; border-radius: 8px; border: 1px solid #c3c4c7;">
                        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
                            <h3 style="margin: 0;">Global Progress</h3>
                            <span id="global-progress-percentage" style="font-weight: bold; color: #2271b1;">0%</span>
                        </div>
                        <div
                            style="background: #f0f0f1; height: 25px; border-radius: 12.5px; overflow: hidden; margin-bottom: 10px; box-shadow: inset 0 1px 2px rgba(0,0,0,.1);">
                            <div id="global-progress-bar"
                                style="background: #2271b1; width: 0%; height: 100%; transition: width 0.3s ease-in-out; background-image: linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent); background-size: 1rem 1rem;">
                            </div>
                        </div>
                        <p id="global-progress-text" style="margin: 0; font-weight: 500; color: #1d2327;">Initializing...</p>
                        <div id="global-log"
                            style="max-height: 150px; overflow-y: auto; background: #f6f7f7; border: 1px solid #dcdcde; padding: 10px; margin-top: 15px; font-family: monospace; font-size: 12px; border-radius: 4px;">
                        </div>
                    </div>
                <?php endif; ?>

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
                                <div style="display: flex; flex-direction: column; gap: 5px;">
                                    <label
                                        style="display: flex; align-items: center; gap: 5px; font-size: 13px; color: #475569; cursor: pointer;">
                                        <input type="checkbox" id="single-fill-missing-checkbox"> Only fill empty fields
                                    </label>
                                    <div style="display: flex; gap: 10px;">
                                        <button type="button" class="translate-btn" id="translate-btn"
                                            data-lang="<?php echo $current_lang; ?>">
                                            Translate All to <?php echo $this->languages[$current_lang]['name']; ?> with OpenAI
                                        </button>
                                        <button type="button" class="translate-btn" id="erase-btn"
                                            data-lang="<?php echo $current_lang; ?>" style="background: #dc3545;">
                                            🗑️ Erase All <?php echo $this->languages[$current_lang]['name']; ?> Translations
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Single Language Progress Bar -->
                        <div id="single-progress-container"
                            style="display: none; margin-top: 15px; background: #fff; padding: 15px; border-radius: 8px; border: 1px solid #c3c4c7;">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <span id="translate-status" style="font-weight: 500;">Starting...</span>
                                <span id="single-progress-percentage" style="font-weight: bold; color: #2271b1;">0%</span>
                            </div>
                            <div
                                style="background: #f0f0f1; height: 10px; border-radius: 5px; overflow: hidden; box-shadow: inset 0 1px 2px rgba(0,0,0,.1);">
                                <div id="single-progress-bar"
                                    style="background: #2271b1; width: 0%; height: 100%; transition: width 0.3s ease-in-out;">
                                </div>
                            </div>
                        </div>
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
                document.addEventListener('DOMContentLoaded', function () {
                    console.log('Admin Script Loaded');
                    const ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';

                    // ---------------------------------------------------------
                    // SINGLE TRANSLATE HANDLER
                    // ---------------------------------------------------------
                    document.getElementById('translate-btn')?.addEventListener('click', async function () {
                        const btn = this;
                        const status = document.getElementById('translate-status');
                        const lang = btn.dataset.lang;
                        const nonce = '<?php echo wp_create_nonce('iptv_content_nonce'); ?>';
                        const bar = document.getElementById('single-progress-bar');
                        const percentText = document.getElementById('single-progress-percentage');
                        const container = document.getElementById('single-progress-container');
                        const fillMissing = document.getElementById('single-fill-missing-checkbox')?.checked;

                        if (container) container.style.display = 'block';
                        btn.disabled = true;
                        status.textContent = 'Initializing...';

                        // Gather all fields to translate
                        const inputs = document.querySelectorAll('input[name^="iptv_content["], textarea[name^="iptv_content["]');
                        const fields = Array.from(inputs).map(input => {
                            const matches = input.name.match(/\[(.*?)\]/);
                            return matches ? matches[1] : null;
                        }).filter(k => k);

                        let completed = 0;
                        let errors = 0;

                        // Iterate and translate sequentially
                        for (const fieldKey of fields) {
                            // Check if skipping non-empty fields
                            if (fillMissing) {
                                const input = document.getElementById(fieldKey);
                                if (input && input.value.trim() !== '') {
                                    completed++;
                                    continue; // Skip without error
                                }
                            }

                            status.textContent = `Translating ${completed + 1}/${fields.length}...`;

                            try {
                                const formData = new URLSearchParams({
                                    action: 'iptv_translate_content',
                                    nonce: nonce,
                                    target_lang: lang,
                                    field_key: fieldKey
                                });

                                const res = await fetch(ajaxurl, {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: formData
                                });

                                const text = await res.text();
                                let json;
                                try {
                                    json = JSON.parse(text);
                                } catch (e) {
                                    throw new Error('Invalid JSON: ' + text.substring(0, 50));
                                }

                                if (json.success) {
                                    const input = document.getElementById(fieldKey);
                                    if (input && json.data.content && json.data.content[fieldKey]) {
                                        input.value = json.data.content[fieldKey];
                                        input.dispatchEvent(new Event('input', { bubbles: true }));
                                    }
                                } else {
                                    errors++;
                                    console.error(`Error translating ${fieldKey}:`, json);
                                }
                            } catch (e) {
                                errors++;
                                console.error(`Exception translating ${fieldKey}:`, e);
                            }

                            completed++;
                            const percent = Math.round((completed / fields.length) * 100);
                            if (bar) bar.style.width = percent + '%';
                            if (percentText) percentText.textContent = percent + '%';
                            status.textContent = `Translating ${completed}/${fields.length}...`;
                        }

                        status.textContent = `Done! ${completed - errors} Translated, ${errors} Failed.`;
                        status.style.color = errors > 0 ? '#d63638' : '#2271b1';
                        if (errors === 0) {
                            setTimeout(() => window.location.reload(), 1500);
                        } else {
                            btn.disabled = false;
                            btn.textContent = 'Retry Failed';
                        }
                    });

                    // ---------------------------------------------------------
                    // SINGLE ERASE HANDLER
                    // ---------------------------------------------------------
                    document.getElementById('erase-btn')?.addEventListener('click', function () {
                        const btn = this;
                        const status = document.getElementById('translate-status');
                        const lang = btn.dataset.lang;
                        const container = document.getElementById('single-progress-container');
                        const bar = document.getElementById('single-progress-bar');
                        const percentText = document.getElementById('single-progress-percentage');

                        if (!confirm('Are you sure you want to erase all ' + lang.toUpperCase() + ' translations? This cannot be undone.')) {
                            return;
                        }

                        btn.disabled = true;
                        if (status) status.textContent = 'Erasing...';

                        if (container) container.style.display = 'block';
                        if (bar) {
                            bar.style.width = '100%';
                            bar.style.backgroundImage = 'linear-gradient(45deg,rgba(255,255,255,.15) 25%,transparent 25%,transparent 50%,rgba(255,255,255,.15) 50%,rgba(255,255,255,.15) 75%,transparent 75%,transparent)';
                            bar.style.backgroundSize = '1rem 1rem';
                        }
                        if (percentText) percentText.textContent = '';

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
                                    if (status) status.textContent = '✅ ' + data.data.message;
                                    if (bar) bar.style.background = '#16a34a';
                                    if (percentText) percentText.textContent = 'Done';
                                    document.querySelectorAll('#content-form input[type="text"], #content-form textarea').forEach(field => {
                                        field.value = '';
                                    });
                                } else {
                                    if (status) status.textContent = '❌ ' + data.data;
                                    if (bar) bar.style.background = '#d63638';
                                }

                                setTimeout(() => {
                                    if (data.success) window.location.reload();
                                    else btn.disabled = false;
                                }, 1000);
                            })
                            .catch(err => {
                                if (status) status.textContent = '❌ Error: ' + err.message;
                                btn.disabled = false;
                            });
                    });

                    // ---------------------------------------------------------
                    // GLOBAL TRANSLATE HANDLER
                    // ---------------------------------------------------------
                    // ---------------------------------------------------------
                    // GLOBAL TRAN            SLATE HANDLER (FIELD-BY-FIELD ROBUST)
                    // ---------------------------------------------------------
                    document.getElementById('global-translate-btn')?.addEventListener('click', async function () {
                        if (!confirm('⚠️ Start Global Translation?\n\nThis will translate every field individually to avoid timeouts.\n\n1. Ensure you have SAVED your English content first.\n2. This process is slower but 100% reliable.\n3. Please keep this tab open.')) return;

                        const btn = this;
                        const container = document.getElementById('global-progress-container');
                        const bar = document.getElementById('global-progress-bar');
                        const text = document.getElementById('global-progress-text');
                        const percentText = document.getElementById('global-progress-percentage');
                        const log = document.getElementById('global-log');
                        const nonce = '<?php echo wp_create_nonce('iptv_content_nonce'); ?>';

                        // 1. Get Target Languages
                        const langTabs = document.querySelectorAll('.lang-tab');
                        const targetLangs = Array.from(langTabs)
                            .map(tab => new URL(tab.href).searchParams.get('lang'))
                            .filter(l => l && l !== 'en');

                        if (targetLangs.length === 0) {
                            alert('No target languages found!');
                            return;
                        }

                        // 2. Get All Content Fields
                        const inputs = document.querySelectorAll('input[name^="iptv_content["], textarea[name^="iptv_content["]');
                        const fields = Array.from(inputs).map(input => {
                            const matches = input.name.match(/\[(.*?)\]/);
                            return matches ? matches[1] : null;
                        }).filter(f => f);

                        if (fields.length === 0) {
                            alert('No content fields found!');
                            return;
                        }

                        btn.disabled = true;
                        btn.innerHTML = '🚀 Translating Line-by-Line...';
                        if (container) container.style.display = 'block';

                        if (log) {
                            log.innerHTML = '<div style="color:#2271b1">ℹ️ Starting Robust Global Translation...</div>';
                            log.innerHTML += `<div>ℹ️ Languages: ${targetLangs.length} | Fields per Lang: ${fields.length}</div>`;
                        }

                        if (bar) bar.style.width = '0%';
                        if (percentText) percentText.textContent = '0%';

                        let completedTasks = 0;
                        let totalTasks = targetLangs.length * fields.length;

                        // Sequential Loop: Languages -> Fields
                        for (const lang of targetLangs) {
                            if (log) {
                                log.innerHTML += `<div style="font-weight:bold; margin-top:10px; border-top:1px solid #ddd; padding-top:5px;">Processing Language: ${lang.toUpperCase()}...</div>`;
                                log.scrollTop = log.scrollHeight;
                            }

                            for (const field of fields) {
                                if (text) text.textContent = `[${lang.toUpperCase()}] Translating field: ${field}...`;

                                try {
                                    const formData = new URLSearchParams({
                                        action: 'iptv_translate_content',
                                        nonce: nonce,
                                        target_lang: lang,
                                        field_key: field, // Translating one field at a time
                                        fill_missing: $('#global-fill-missing-checkbox').is(':checked') ? 'true' : 'false'
                                    });

                                    const res = await fetch(ajaxurl, {
                                        method: 'POST',
                                        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                        body: formData
                                    });

                                    const json = await res.json();

                                    if (!json.success) {
                                        throw new Error(json.data);
                                    }
                                    // Optional: log success for every field is too verbose, maybe only errors?
                                    // if (log) log.innerHTML += `<div style="color:#aaa; font-size:12px;">✓ ${field} done</div>`;

                                } catch (e) {
                                    if (log) log.innerHTML += `<div style="color:#d63638">❌ Error [${lang} - ${field}]: ${e.message}</div>`;
                                }

                                completedTasks++;
                                const percent = Math.round((completedTasks / totalTasks) * 100);
                                if (bar) bar.style.width = percent + '%';
                                if (percentText) percentText.textContent = percent + '%';
                                if (log) log.scrollTop = log.scrollHeight;

                                // Tiny delay to breathe
                                await new Promise(r => setTimeout(r, 50));
                            }

                            if (log) log.innerHTML += `<div style="color:#16a34a">✅ Finished ${lang.toUpperCase()}.</div>`;
                        }

                        if (bar) bar.style.width = '100%';
                        if (percentText) percentText.textContent = '100%';
                        if (text) text.innerHTML = '<span style="color:#16a34a">✨ Global Translation Complete! Refreshing...</span>';

                        setTimeout(() => window.location.reload(), 2000);
                    });

                    // ---------------------------------------------------------
                    // GLOBAL ERASE HANDLER (FIXED)
                    // ---------------------------------------------------------
                    document.getElementById('global-erase-btn')?.addEventListener('click', async function () {
                        if (!confirm('⚠️ Are you sure you want to DELETE ALL Translations?\n\nThis will permanently erase content for SE, NO, DK, FI, and IS.\n(English content will be safe).')) return;

                        const btn = this;
                        const container = document.getElementById('global-progress-container');
                        const bar = document.getElementById('global-progress-bar');
                        const text = document.getElementById('global-progress-text');
                        const percentText = document.getElementById('global-progress-percentage');
                        const log = document.getElementById('global-log');
                        const nonce = '<?php echo wp_create_nonce('iptv_content_nonce'); ?>';

                        const langTabs = document.querySelectorAll('.lang-tab');
                        const targetLangs = Array.from(langTabs)
                            .map(tab => new URL(tab.href).searchParams.get('lang'))
                            .filter(l => l && l !== 'en');

                        if (targetLangs.length === 0) {
                            alert('No target languages found!');
                            return;
                        }

                        btn.disabled = true;
                        btn.innerHTML = '🗑️ Erasing...';
                        if (container) container.style.display = 'block';
                        if (log) log.innerHTML = '';

                        // Reset Progress
                        if (bar) {
                            bar.style.width = '0%';
                            bar.style.background = '#d63638';
                        }
                        if (percentText) percentText.textContent = '0%';

                        let completedTasks = 0;
                        let totalTasks = targetLangs.length;

                        for (const lang of targetLangs) {
                            if (log) {
                                log.innerHTML += `<div style="font-weight:bold; margin-top:5px;">Erasing Language: ${lang.toUpperCase()}...</div>`;
                                log.scrollTop = log.scrollHeight;
                            }
                            if (text) text.textContent = `Erasing ${lang.toUpperCase()} content...`;

                            try {
                                const formData = new URLSearchParams({
                                    action: 'iptv_erase_content',
                                    nonce: nonce,
                                    target_lang: lang
                                });

                                const res = await fetch(ajaxurl, {
                                    method: 'POST',
                                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                                    body: formData
                                });

                                const json = await res.json();

                                if (json.success) {
                                    if (log) log.innerHTML += `<div style="color:#d63638">🗑️ Deleted ${lang.toUpperCase()}.</div>`;
                                } else {
                                    throw new Error(json.data);
                                }

                            } catch (e) {
                                if (log) log.innerHTML += `<div style="color:#d63638">❌ Error [${lang}]: ${e.message}</div>`;
                            }

                            completedTasks++;
                            const percent = Math.round((completedTasks / totalTasks) * 100);
                            if (bar) bar.style.width = percent + '%';
                            if (percentText) percentText.textContent = percent + '%';
                            if (log) log.scrollTop = log.scrollHeight;
                        }

                        if (bar) bar.style.width = '100%';
                        if (percentText) percentText.textContent = '100%';
                        if (text) text.innerHTML = '<span style="color:#d63638">🗑️ Global Erasure Complete! Refreshing...</span>';

                        setTimeout(() => window.location.reload(), 1500);
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
            // LANG-DISABLED: See Project_dyali.md "Language Reactivation Guide" to revert
            // 3 => array('name' => 'Norway 🇳🇴', 'lang' => 'no'),
            // 4 => array('name' => 'Denmark 🇩🇰', 'lang' => 'da'),
            // 5 => array('name' => 'Finland 🇫🇮', 'lang' => 'fi'),
            // 6 => array('name' => 'Iceland 🇮🇸', 'lang' => 'is')
        );
        ?>
        <div class="post-localizer-wrapper">
            <p>Localize posts from Main Site to subsites with AI-powered SEO optimization.</p>

            <div style="margin: 20px 0; background: #f0f0f1; padding: 15px; border-radius: 5px;">
                <div style="margin-bottom: 10px;">
                    <label><strong>Target Subsite:</strong></label>
                    <select id="target-subsite" style="padding: 5px 10px; font-size: 14px; margin-left: 10px;">
                        <?php foreach ($subsites as $blog_id => $site): ?>
                            <option value="<?php echo $blog_id; ?>" data-lang="<?php echo $site['lang']; ?>">
                                <?php echo $site['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 500;">
                        <input type="checkbox" id="translate-on-clone" checked>
                        <span>Translate content to target language using OpenAI (slower)</span>
                    </label>
                </div>

                <!-- Progress Bar -->
                <div id="clone-progress-container"
                    style="display: none; margin-top: 15px; background: white; padding: 15px; border-radius: 4px; border: 1px solid #ddd;">
                    <div
                        style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; font-weight: 600;">
                        <span id="clone-status-text">preparing...</span>
                        <span id="clone-percentage">0%</span>
                    </div>
                    <div style="background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div id="clone-progress-bar"
                            style="background: #2271b1; width: 0%; height: 100%; transition: width 0.3s;"></div>
                    </div>
                </div>
            </div>

            <!-- Posts Section -->
            <div class="content-section">
                <div style="margin: 10px 0;">
                    <button class="button" id="select-all-posts">Select All</button>
                    <button class="button button-primary" id="clone-posts-to-network">Clone Selected to Network</button>
                    <button class="button" id="remove-posts-from-network"
                        style="background: #dc3545; color: white; border-color: #dc3545;">Remove Selected from
                        Network</button>
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
            // LANG-DISABLED: See Project_dyali.md "Language Reactivation Guide" to revert
            // 3 => array('name' => 'Norway 🇳🇴', 'lang' => 'no'),
            // 4 => array('name' => 'Denmark 🇩🇰', 'lang' => 'da'),
            // 5 => array('name' => 'Finland 🇫🇮', 'lang' => 'fi'),
            // 6 => array('name' => 'Iceland 🇮🇸', 'lang' => 'is')
        );
        ?>
        <div class="post-localizer-wrapper">
            <p>Localize pages from Main Site to subsites with AI-powered SEO optimization.</p>

            <div style="margin: 20px 0; background: #f0f0f1; padding: 15px; border-radius: 5px;">
                <div style="margin-bottom: 10px;">
                    <label><strong>Target Subsite:</strong></label>
                    <select id="target-subsite" style="padding: 5px 10px; font-size: 14px; margin-left: 10px;">
                        <?php foreach ($subsites as $blog_id => $site): ?>
                            <option value="<?php echo $blog_id; ?>" data-lang="<?php echo $site['lang']; ?>">
                                <?php echo $site['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 500;">
                        <input type="checkbox" id="translate-on-clone" checked>
                        <span>Translate content to target language using OpenAI (slower)</span>
                    </label>
                </div>

                <!-- Progress Bar -->
                <div id="clone-progress-container"
                    style="display: none; margin-top: 15px; background: white; padding: 15px; border-radius: 4px; border: 1px solid #ddd;">
                    <div
                        style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; font-weight: 600;">
                        <span id="clone-status-text">preparing...</span>
                        <span id="clone-percentage">0%</span>
                    </div>
                    <div style="background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div id="clone-progress-bar"
                            style="background: #2271b1; width: 0%; height: 100%; transition: width 0.3s;"></div>
                    </div>
                </div>
            </div>

            <!-- Pages Section -->
            <div class="content-section">
                <div style="margin: 10px 0;">
                    <button class="button" id="select-all-pages">Select All</button>
                    <button class="button button-primary" id="clone-pages-to-network">Clone Selected to Network</button>
                    <button class="button" id="remove-pages-from-network"
                        style="background: #dc3545; color: white; border-color: #dc3545;">Remove Selected from
                        Network</button>
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
     * Render the Menus Localizer tab
     */
    private function render_menus_localizer_tab()
    {
        // Get menus from Main Site
        if (is_multisite()) {
            switch_to_blog(1);
        }

        $nav_menus = wp_get_nav_menus();
        $locations = get_nav_menu_locations();
        $registered_menus = get_registered_nav_menus();

        if (is_multisite()) {
            restore_current_blog();
        }

        // Subsite mapping
        $subsites = array(
            2 => array('name' => 'Sweden 🇸🇪', 'lang' => 'sv'),
            // LANG-DISABLED: See Project_dyali.md "Language Reactivation Guide" to revert
            // 3 => array('name' => 'Norway 🇳🇴', 'lang' => 'no'),
            // 4 => array('name' => 'Denmark 🇩🇰', 'lang' => 'da'),
            // 5 => array('name' => 'Finland 🇫🇮', 'lang' => 'fi'),
            // 6 => array('name' => 'Iceland 🇮🇸', 'lang' => 'is')
        );

        $nonce = wp_create_nonce('iptv_localizer_nonce');
        ?>
        <div class="menu-localizer-wrapper">
            <p>Clone navigation menus from Main Site to subsites with translation and proper URL rewriting.</p>

            <div style="margin: 20px 0; background: #f0f0f1; padding: 15px; border-radius: 5px;">
                <div style="margin-bottom: 10px;">
                    <label><strong>Target Subsite:</strong></label>
                    <select id="menu-target-subsite" style="padding: 5px 10px; font-size: 14px; margin-left: 10px;">
                        <?php foreach ($subsites as $blog_id => $site): ?>
                            <option value="<?php echo $blog_id; ?>" data-lang="<?php echo $site['lang']; ?>">
                                <?php echo $site['name']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display: flex; align-items: center; gap: 8px; font-weight: 500;">
                        <input type="checkbox" id="menu-translate-on-clone" checked>
                        <span>Translate menu labels to target language using OpenAI</span>
                    </label>
                </div>

                <!-- Progress Bar -->
                <div id="menu-progress-container"
                    style="display: none; margin-top: 15px; background: white; padding: 15px; border-radius: 4px; border: 1px solid #ddd;">
                    <div
                        style="display: flex; justify-content: space-between; margin-bottom: 5px; font-size: 13px; font-weight: 600;">
                        <span id="menu-status-text">preparing...</span>
                        <span id="menu-percentage">0%</span>
                    </div>
                    <div style="background: #e2e8f0; height: 10px; border-radius: 5px; overflow: hidden;">
                        <div id="menu-progress-bar"
                            style="background: #2271b1; width: 0%; height: 100%; transition: width 0.3s;"></div>
                    </div>
                </div>
            </div>

            <!-- Menus Section -->
            <div class="content-section" style="background: #fff; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
                <div style="margin: 10px 0;">
                    <button class="button button-primary" id="clone-menus-to-network">🌐 Clone Menus to Network</button>
                    <button class="button" id="remove-menus-from-network"
                        style="background: #dc3545; color: white; border-color: #dc3545;">🗑️ Remove Menus from Network</button>
                </div>

                <h3>Registered Menu Locations</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Location</th>
                            <th>Assigned Menu</th>
                            <th>Items Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_multisite()) {
                            switch_to_blog(1);
                        }
                        foreach ($registered_menus as $location_slug => $location_name):
                            $menu_id = isset($locations[$location_slug]) ? $locations[$location_slug] : 0;
                            $menu = $menu_id ? wp_get_nav_menu_object($menu_id) : null;
                            $items = $menu_id ? wp_get_nav_menu_items($menu_id) : array();
                            ?>
                            <tr>
                                <td><strong><?php echo esc_html($location_name); ?></strong><br><small
                                        style="color: #666;"><?php echo esc_html($location_slug); ?></small></td>
                                <td><?php echo $menu ? esc_html($menu->name) : '<em style="color: #999;">Not assigned</em>'; ?></td>
                                <td><?php echo $menu ? count($items) . ' items' : '-'; ?></td>
                            </tr>
                        <?php endforeach;
                        if (is_multisite()) {
                            restore_current_blog();
                        }
                        ?>
                    </tbody>
                </table>

                <h3 style="margin-top: 30px;">All Navigation Menus</h3>
                <table class="wp-list-table widefat fixed striped">
                    <thead>
                        <tr>
                            <th>Menu Name</th>
                            <th>Items Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (is_multisite()) {
                            switch_to_blog(1);
                        }
                        if ($nav_menus):
                            foreach ($nav_menus as $menu):
                                $items = wp_get_nav_menu_items($menu->term_id);
                                ?>
                                <tr>
                                    <td><strong><?php echo esc_html($menu->name); ?></strong></td>
                                    <td><?php echo $items ? count($items) . ' items' : '0 items'; ?></td>
                                </tr>
                                <?php
                            endforeach;
                        else: ?>
                            <tr>
                                <td colspan="2"><em>No menus found on main site.</em></td>
                            </tr>
                        <?php endif;
                        if (is_multisite()) {
                            restore_current_blog();
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <script>
            jQuery(document).ready(function ($) {
                const menuNonce = '<?php echo $nonce; ?>';

                // Clone Menus to Network
                $('#clone-menus-to-network').on('click', function (e) {
                    e.preventDefault();
                    const targetId = $('#menu-target-subsite').val();
                    const blogName = $('#menu-target-subsite option:selected').text().trim();
                    const doTranslate = $('#menu-translate-on-clone').is(':checked');

                    let confirmMsg = 'Clone all menus to ' + blogName + '?';
                    if (doTranslate) {
                        confirmMsg += '\n\nMenu labels will be translated using OpenAI.';
                    }
                    confirmMsg += '\n\nThis will OVERWRITE existing menus on the target subsite.';

                    if (!confirm(confirmMsg)) return;

                    const btn = $(this);
                    btn.prop('disabled', true).text('Cloning...');

                    $('#menu-progress-container').show();
                    $('#menu-progress-bar').css('width', '50%').css('background', '#2271b1');
                    $('#menu-percentage').text('50%');
                    $('#menu-status-text').text('Cloning menus' + (doTranslate ? ' with translation' : '') + '...');

                    $.post(ajaxurl, {
                        action: 'iptv_clone_menus_to_network',
                        nonce: menuNonce,
                        target_blog_id: targetId,
                        translate: doTranslate
                    }).done(function (response) {
                        $('#menu-progress-bar').css('width', '100%');
                        $('#menu-percentage').text('100%');

                        if (response.success) {
                            $('#menu-progress-bar').css('background', '#46b450');
                            $('#menu-status-text').text('Completed!');

                            let msg = 'Menus cloned successfully to ' + blogName + '!';
                            if (response.data.translated) {
                                msg += '\n\nMenu labels were translated.';
                            }

                            setTimeout(function () {
                                alert(msg);
                                $('#menu-progress-container').hide();
                            }, 500);
                        } else {
                            $('#menu-progress-bar').css('background', '#dc3545');
                            $('#menu-status-text').text('Failed!');
                            alert('Error: ' + (response.data || 'Unknown error'));
                        }
                    }).fail(function (xhr, status, error) {
                        $('#menu-progress-bar').css('width', '100%').css('background', '#dc3545');
                        $('#menu-status-text').text('Failed!');
                        alert('Request failed: ' + error);
                    }).always(function () {
                        btn.prop('disabled', false).text('🌐 Clone Menus to Network');
                    });
                });

                // Remove Menus from Network
                $('#remove-menus-from-network').on('click', function (e) {
                    e.preventDefault();
                    const targetId = $('#menu-target-subsite').val();
                    const blogName = $('#menu-target-subsite option:selected').text().trim();

                    if (!confirm('Are you sure you want to REMOVE all menus from ' + blogName + '?\n\nThis cannot be undone.')) {
                        return;
                    }

                    const btn = $(this);
                    btn.prop('disabled', true).text('Removing...');

                    $('#menu-progress-container').show();
                    $('#menu-progress-bar').css('width', '50%').css('background', '#dc3545');
                    $('#menu-percentage').text('50%');
                    $('#menu-status-text').text('Removing menus...');

                    $.post(ajaxurl, {
                        action: 'iptv_remove_menus_from_network',
                        nonce: menuNonce,
                        target_blog_id: targetId
                    }).done(function (response) {
                        $('#menu-progress-bar').css('width', '100%');
                        $('#menu-percentage').text('100%');

                        if (response.success) {
                            $('#menu-status-text').text('Completed!');
                            setTimeout(function () {
                                alert('Menus removed from ' + blogName + '!');
                                $('#menu-progress-container').hide();
                            }, 500);
                        } else {
                            $('#menu-status-text').text('Failed!');
                            alert('Error: ' + (response.data || 'Unknown error'));
                        }
                    }).fail(function (xhr, status, error) {
                        $('#menu-progress-bar').css('width', '100%');
                        $('#menu-status-text').text('Failed!');
                        alert('Request failed: ' + error);
                    }).always(function () {
                        btn.prop('disabled', false).text('🗑️ Remove Menus from Network');
                    });
                });
            });
        </script>
        <?php
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
            style="display:none !important; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 0; border-radius: 8px; box-shadow: 0 10px 40px rgba(0,0,0,0.5); z-index: 100001; width: 95%; max-width: 1200px; max-height: 90vh; overflow: hidden; flex-direction: column;">

            <!-- Modal Header -->
            <div
                style="background: linear-gradient(135deg, #1e293b 0%, #334155 100%); color: white; padding: 15px 25px; flex-shrink: 0; display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h2 style="margin: 0; font-size: 18px; color: white;">🌍 Network Content Localizer</h2>
                    <p style="margin: 2px 0 0 0; opacity: 0.8; font-size: 12px;">Manage localized content for all
                        subsites in
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
                                <!-- Focus Keywords (Multiple like Rank Math Pro) -->
                                <div class="rank-math-field">
                                    <label
                                        style="display: block; font-weight: 600; margin-bottom: 5px; font-size: 13px; color: #475569;">Focus
                                        Keywords <span style="font-weight: normal; color: #94a3b8; font-size: 11px;">(Press
                                            Enter to
                                            add)</span></label>
                                    <div class="keywords-container" id="keywords-container-<?php echo $code; ?>"
                                        style="display: flex; flex-wrap: wrap; gap: 6px; padding: 8px; border: 1px solid #cbd5e1; border-radius: 4px; min-height: 40px; background: white; align-items: center;">
                                        <input type="text" class="keyword-input" id="keyword-input-<?php echo $code; ?>"
                                            data-lang="<?php echo $code; ?>"
                                            style="border: none; outline: none; flex: 1; min-width: 120px; padding: 4px; font-size: 13px;"
                                            placeholder="Add keyword and press Enter" />
                                    </div>
                                    <!-- Hidden input to store all keywords -->
                                    <input type="hidden" id="focus-keyword-<?php echo $code; ?>" class="seo-input"
                                        data-lang="<?php echo $code; ?>" />
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
                style="padding: 15px 25px; background: white; border-top: 1px solid #e2e8f0; display: flex; flex-direction: column; gap: 10px; flex-shrink: 0;">

                <!-- Progress Bar -->
                <div id="progress-container" style="display: none; width: 100%;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 4px;">
                        <span id="progress-label" style="font-size: 12px; color: #475569;">Processing...</span>
                        <span id="progress-percent" style="font-size: 12px; font-weight: 600; color: #3b82f6;">0%</span>
                    </div>
                    <div style="width: 100%; height: 8px; background: #e2e8f0; border-radius: 4px; overflow: hidden;">
                        <div id="progress-bar"
                            style="width: 0%; height: 100%; background: linear-gradient(90deg, #3b82f6, #8b5cf6); border-radius: 4px; transition: width 0.3s ease;">
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <span id="global-status" style="font-weight: 600; color: #475569;"></span>
                    </div>
                    <div style="display: flex; gap: 10px; align-items: center;">
                        <label
                            style="display: flex; align-items: center; gap: 5px; font-size: 13px; color: #475569; cursor: pointer; margin-right: 5px;">
                            <input type="checkbox" id="fill-missing-all-checkbox"> Only fill empty fields
                        </label>
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
                style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(15, 23, 42, 0.5); z-index: 100000;">
            </div>

            <script>
                // v2.1 - Updated Fill Missing logic
                jQuery(document).ready(function ($) {
                    const nonce = '<?php echo wp_create_nonce('iptv_localizer_nonce'); ?>';
                    const languages = ['en', 'se', 'no', 'dk', 'fi', 'is']; // All languages including English
                    const translateLanguages = ['se', 'no', 'dk', 'fi', 'is']; // Translate targets only

                    let currentActiveLang = 'en'; // Track current active language

                    // ==========================================
                    // MULTI-KEYWORD TAG SYSTEM (like Rank Math Pro)
                    // ==========================================

                    function createKeywordTag(keyword, lang) {
                        const tag = $('<span class="keyword-tag" style="display: inline-flex; align-items: center; background: linear-gradient(135deg, #3b82f6, #6366f1); color: white; padding: 4px 8px 4px 10px; border-radius: 20px; font-size: 12px; font-weight: 500;"></span>');
                        tag.append('<span class="keyword-text">' + $('<div>').text(keyword).html() + '</span>');
                        tag.append('<button type="button" class="remove-keyword" style="background: rgba(255,255,255,0.25); border: none; color: white; margin-left: 6px; width: 16px; height: 16px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 11px; line-height: 1; padding: 0;" title="Remove">&times;</button>');

                        tag.find('.remove-keyword').on('click', function () {
                            tag.remove();
                            updateHiddenKeywordInput(lang);
                        });

                        return tag;
                    }

                    function updateHiddenKeywordInput(lang) {
                        const container = $('#keywords-container-' + lang);
                        const keywords = [];
                        container.find('.keyword-tag .keyword-text').each(function () {
                            keywords.push($(this).text().trim());
                        });
                        $('#focus-keyword-' + lang).val(keywords.join(', ')).trigger('input');
                    }

                    function addKeyword(keyword, lang) {
                        keyword = keyword.trim();
                        if (!keyword) return false;

                        // Check for duplicates
                        const container = $('#keywords-container-' + lang);
                        let exists = false;
                        container.find('.keyword-tag .keyword-text').each(function () {
                            if ($(this).text().toLowerCase() === keyword.toLowerCase()) {
                                exists = true;
                                return false;
                            }
                        });

                        if (exists) return false;

                        // Add tag before input
                        const tag = createKeywordTag(keyword, lang);
                        container.find('.keyword-input').before(tag);
                        updateHiddenKeywordInput(lang);
                        return true;
                    }

                    function setKeywords(keywordString, lang) {
                        const container = $('#keywords-container-' + lang);
                        // Clear existing tags
                        container.find('.keyword-tag').remove();

                        if (!keywordString) return;

                        // Add each keyword
                        const keywords = keywordString.split(',');
                        keywords.forEach(function (kw) {
                            const keyword = kw.trim();
                            if (keyword) {
                                const tag = createKeywordTag(keyword, lang);
                                container.find('.keyword-input').before(tag);
                            }
                        });
                        updateHiddenKeywordInput(lang);
                    }

                    // Handle Enter key to add keyword
                    $(document).on('keydown', '.keyword-input', function (e) {
                        if (e.key === 'Enter' || e.keyCode === 13) {
                            e.preventDefault();
                            const lang = $(this).data('lang');
                            const keyword = $(this).val().trim();
                            if (addKeyword(keyword, lang)) {
                                $(this).val('');
                            }
                        }
                    });

                    // Handle comma to add keyword
                    $(document).on('input', '.keyword-input', function () {
                        const lang = $(this).data('lang');
                        const val = $(this).val();
                        if (val.includes(',')) {
                            const parts = val.split(',');
                            parts.forEach(function (part, i) {
                                if (i < parts.length - 1) {
                                    addKeyword(part, lang);
                                }
                            });
                            $(this).val(parts[parts.length - 1]);
                        }
                    });

                    // Click on container focuses input
                    $(document).on('click', '.keywords-container', function (e) {
                        if ($(e.target).hasClass('keywords-container')) {
                            $(this).find('.keyword-input').focus();
                        }
                    });

                    // ==========================================
                    // PROGRESS BAR & CANCEL SYSTEM
                    // ==========================================

                    let isGenerating = false;
                    let abortController = null;
                    let pendingRequests = [];

                    function showProgress(label = 'Processing...') {
                        $('#progress-container').fadeIn(200);
                        $('#progress-label').text(label);
                        $('#progress-bar').css('width', '0%');
                        $('#progress-percent').text('0%');
                        isGenerating = true;
                        // Change Cancel button style to indicate it can stop
                        $('.cancel-btn').css({
                            'background': '#ef4444',
                            'color': 'white',
                            'border-color': '#ef4444'
                        }).text('⏹ Stop');
                    }

                    function updateProgress(current, total, itemLabel = '') {
                        const percent = Math.round((current / total) * 100);
                        $('#progress-bar').css('width', percent + '%');
                        $('#progress-percent').text(percent + '%');
                        if (itemLabel) {
                            $('#progress-label').text(itemLabel + ' (' + current + '/' + total + ')');
                        }
                    }

                    function hideProgress() {
                        $('#progress-container').fadeOut(200);
                        isGenerating = false;
                        // Reset Cancel button
                        $('.cancel-btn').css({
                            'background': '',
                            'color': '#475569',
                            'border-color': '#cbd5e1'
                        }).text('Cancel');
                    }

                    function cancelGeneration() {
                        if (isGenerating) {
                            // Abort all pending requests
                            pendingRequests.forEach(xhr => {
                                if (xhr && xhr.abort) {
                                    xhr.abort();
                                }
                            });
                            pendingRequests = [];
                            isGenerating = false;
                            hideProgress();
                            $('#global-status').text('⏹ Generation cancelled').css('color', '#f59e0b');
                            return true; // Was generating, now cancelled
                        }
                        return false; // Was not generating
                    }

                    // Cancel button handler
                    $(document).on('click', '.cancel-btn', function () {
                        if (isGenerating) {
                            cancelGeneration();
                        } else {
                            // Destroy all editors before closing
                            languages.forEach(lang => {
                                destroyEditor(lang);
                            });
                            // Reset current active lang
                            currentActiveLang = 'en';
                            // Close modal
                            $('#localize-modal, #modal-overlay').fadeOut();
                        }
                    });

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

                        // Move to body to ensure z-index works correctly (escape parent stacking contexts)
                        $('body').append($('#modal-overlay'));
                        $('body').append($('#localize-modal'));

                        $('#modal-overlay').fadeIn();

                        // Fix logic to handle !important and ensure flex layout
                        var modal = $('#localize-modal');
                        // Remove strict display:none if present
                        var currentStyle = modal.attr('style') || '';
                        if (currentStyle.includes('display:none !important')) {
                            modal.attr('style', currentStyle.replace('display:none !important', ''));
                        }
                        // Force flex display then animate
                        modal.css('display', 'flex').hide().fadeIn();


                        $('#global-status').text('Loading...');

                        // Clear all fields
                        languages.forEach(lang => {
                            setKeywords('', lang); // Clear keyword tags
                            $('#meta-title-' + lang).val('');
                            $('#meta-description-' + lang).val('');
                            $('#post-slug-' + lang).val('');
                            $('#image-alt-' + lang).val('');
                            $('#post-title-' + lang).val('');
                            $('#post-content-' + lang).val('');
                        });

                        // Fetch initial data (all languages including subsites)
                        $.post(ajaxurl, {
                            action: 'iptv_get_initial_data',
                            nonce: nonce,
                            post_id: postId
                        }).done(function (response) {
                            console.log('Initial data response:', response); // Debug log
                            if (response.success) {
                                const data = response.data;

                                // Set featured image ID
                                $('#featured-image-id').val(data.featured_image_id || 0);

                                // Populate ALL language tabs with their data
                                languages.forEach(lang => {
                                    if (data[lang]) {
                                        const langData = data[lang];
                                        console.log('Loading data for ' + lang + ':', langData); // Debug log
                                        setKeywords(langData.focus_keyword || '', lang);
                                        $('#meta-title-' + lang).val(langData.meta_title || '').trigger('input');
                                        $('#meta-description-' + lang).val(langData.meta_description || '').trigger('input');
                                        $('#post-slug-' + lang).val(langData.slug || '');
                                        $('#image-alt-' + lang).val(langData.image_alt || '');
                                        $('#post-title-' + lang).val(langData.title || '');
                                        $('#post-content-' + lang).val(langData.content || '');

                                        // Update status indicator for this language
                                        if (langData.status === 'publish') {
                                            $('.status-indicator-' + lang).text('✅');
                                        } else if (langData.status === 'draft') {
                                            $('.status-indicator-' + lang).text('📝');
                                        }

                                        // Update SEO score
                                        updateSeoScore(lang);
                                    }
                                });

                                $('#global-status').text('');

                                // Initialize TinyMCE for English tab (default active tab)
                                initializeEditor('en');
                            } else {
                                $('#global-status').text('⚠️ ' + (response.data || 'Unknown error'));
                                console.error('Error loading data:', response);
                            }
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            console.error('AJAX failed:', textStatus, errorThrown);
                            $('#global-status').text('⚠️ Request failed: ' + textStatus);
                        });
                    });


                    // Close Modal (only X button, cancel is handled separately with stop logic)
                    $(document).on('click', '#close-modal-btn', function () {
                        // Cancel any ongoing generation
                        if (typeof cancelGeneration === 'function') {
                            cancelGeneration();
                        }

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
                            target_blog_id: $('#target-blog-id-' + lang).val(), // Critical: Send blog ID for multisite
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

                        // Show progress bar for single language
                        showProgress('Filling missing fields for ' + lang.toUpperCase() + '...');
                        updateProgress(0, 1, 'Processing ' + lang.toUpperCase());

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

                        generateContent(lang, function () {
                            updateProgress(1, 1, 'Complete');
                            hideProgress();
                        }, true, existingData); // true = fill missing mode
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

                        // Show progress bar for refresh
                        showProgress('Refreshing content for ' + lang.toUpperCase() + '...');
                        updateProgress(0, 1, 'Rewriting content');

                        const keywordValue = $('#focus-keyword-' + lang).val();
                        console.log('Refresh Content - Keyword from hidden input:', keywordValue);
                        console.log('Refresh Content - Content length:', currentContent.length);

                        const refreshData = {
                            custom_keyword: keywordValue,
                            existing_content: currentContent,
                            refresh_content_only: 'true'
                        };

                        console.log('Refresh Content - Full request data:', refreshData);

                        generateContent(lang, function () {
                            updateProgress(1, 1, 'Complete');
                            hideProgress();
                        }, false, refreshData); // false = not fill missing, use refresh mode
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
                            image_alt: $('#image-alt-' + lang).val(),
                            featured_image_id: $('#featured-image-id').val()
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
                        const fillMissing = $('#fill-missing-all-checkbox').is(':checked');

                        // Show progress bar
                        showProgress('Generating content for all languages' + (fillMissing ? ' (Filling Missing Only)' : '') + '...');
                        const totalLangs = translateLanguages.length;

                        // Trigger sequential generation for all translate languages
                        let index = 0;
                        function next() {
                            // Check if cancelled
                            if (!isGenerating) {
                                return; // Stop if cancelled
                            }

                            if (index < translateLanguages.length) {
                                const lang = translateLanguages[index];

                                let existingData = {};
                                if (fillMissing) {
                                    existingData = {
                                        custom_keyword: $('#focus-keyword-' + lang).val(),
                                        existing_title: $('#post-title-' + lang).val(),
                                        existing_content: $('#post-content-' + lang).val(),
                                        existing_meta_title: $('#meta-title-' + lang).val(),
                                        existing_meta_desc: $('#meta-description-' + lang).val(),
                                        existing_slug: $('#post-slug-' + lang).val(),
                                        existing_image_alt: $('#image-alt-' + lang).val()
                                    };

                                    if (typeof tinymce !== 'undefined') {
                                        const editor = tinymce.get('post-content-' + lang);
                                        if (editor && !editor.isHidden()) {
                                            existingData.existing_content = editor.getContent();
                                        }
                                    }
                                }

                                updateProgress(index, totalLangs, 'Generating ' + lang.toUpperCase());
                                generateContent(lang, function () {
                                    index++;
                                    updateProgress(index, totalLangs, index < totalLangs ? 'Generating ' + translateLanguages[index]?.toUpperCase() : 'Finishing...');
                                    next();
                                }, fillMissing, existingData);
                            } else {
                                updateProgress(totalLangs, totalLangs, 'Complete');
                                hideProgress();
                                $('#global-status').text('✅ All languages generated!').css('color', '#22c55e');
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

                        // If refresh content mode, also add the existingData (custom_keyword, existing_content, refresh_content_only)
                        if (existingData && existingData.refresh_content_only === 'true') {
                            $.extend(requestData, existingData);
                        }

                        // Track the request for cancellation
                        const xhr = $.post(ajaxurl, requestData).done(function (response) {
                            if (response.success) {
                                const data = response.data;

                                // In fill-missing mode, only update fields that were originally empty
                                if (fillMissing) {
                                    // Only update fields if they were empty BEFORE
                                    if (!existingData.custom_keyword && data.focus_keyword !== undefined) setKeywords(data.focus_keyword, lang);
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
                                } else if (existingData && existingData.refresh_content_only === 'true') {
                                    // Refresh content mode: ONLY update content, keep everything else
                                    console.log('Refresh Content Mode - only updating content, preserving all other fields');
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
                                    // DO NOT update focus_keyword, title, meta_title, meta_desc, slug, image_alt
                                } else {
                                    // Full regeneration: update all fields
                                    if (data.focus_keyword !== undefined) setKeywords(data.focus_keyword, lang);
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
                        }).fail(function (jqXHR, status, error) {
                            // Only show error if not aborted
                            if (status !== 'abort') {
                                statusIndicator.text('❌');
                                $('#global-status').text('❌ Failed for ' + lang.toUpperCase());
                                console.error('AJAX Error:', status, error);
                            }
                            if (callback) callback();
                        }).always(function () {
                            // Remove from pending requests
                            const index = pendingRequests.indexOf(xhr);
                            if (index > -1) {
                                pendingRequests.splice(index, 1);
                            }
                        });

                        // Add to pending requests for cancellation
                        pendingRequests.push(xhr);
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
                            image_alt: $('#image-alt-' + lang).val(),
                            featured_image_id: $('#featured-image-id').val()
                        }).done(function () {
                            if (callback) callback();
                        });
                    }

                    // ==========================================
                    //  BULK ACTIONS FOR POSTS/PAGES TABLE
                    // ==========================================

                    // Select All Posts
                    $(document).on('click', '#select-all-posts, #check-all-posts', function (e) {
                        if (this.id === 'select-all-posts') e.preventDefault();
                        // If it's the button, force select all. If checkbox, toggle.
                        const isChecked = (this.id === 'select-all-posts') ? true : $(this).prop('checked');

                        $('.post-checkbox').prop('checked', isChecked);
                        $('#check-all-posts').prop('checked', isChecked);
                    });

                    // Select All Pages
                    $(document).on('click', '#select-all-pages, #check-all-pages', function (e) {
                        if (this.id === 'select-all-pages') e.preventDefault();
                        const isChecked = (this.id === 'select-all-pages') ? true : $(this).prop('checked');

                        $('.page-checkbox').prop('checked', isChecked);
                        $('#check-all-pages').prop('checked', isChecked);
                    });

                    // Clone Posts to Network
                    $(document).on('click', '#clone-posts-to-network', function (e) {
                        e.preventDefault();
                        const selected = [];
                        $('.post-checkbox:checked').each(function () {
                            selected.push($(this).val());
                        });
                        // Use the target-subsite dropdown from the POSTS tab container
                        const targetId = $(this).closest('.post-localizer-wrapper').find('#target-subsite').val();
                        handleBulkClone(selected, targetId);
                    });

                    // Clone Pages to Network
                    $(document).on('click', '#clone-pages-to-network', function (e) {
                        e.preventDefault();
                        const selected = [];
                        $('.page-checkbox:checked').each(function () {
                            selected.push($(this).val());
                        });
                        // Use the target-subsite dropdown from the PAGES tab container
                        const targetId = $(this).closest('.post-localizer-wrapper').find('#target-subsite').val();
                        handleBulkClone(selected, targetId);
                    });

                    // Remove Posts from Network
                    $(document).on('click', '#remove-posts-from-network', function (e) {
                        e.preventDefault();
                        const selected = [];
                        $('.post-checkbox:checked').each(function () {
                            selected.push($(this).val());
                        });
                        const targetId = $(this).closest('.post-localizer-wrapper').find('#target-subsite').val();
                        handleBulkRemove(selected, targetId);
                    });

                    // Remove Pages from Network
                    $(document).on('click', '#remove-pages-from-network', function (e) {
                        e.preventDefault();
                        const selected = [];
                        $('.page-checkbox:checked').each(function () {
                            selected.push($(this).val());
                        });
                        const targetId = $(this).closest('.post-localizer-wrapper').find('#target-subsite').val();
                        handleBulkRemove(selected, targetId);
                    });

                    function handleBulkRemove(ids, targetBlogId) {
                        if (ids.length === 0) {
                            alert('Please select items to remove first.');
                            return;
                        }
                        if (!targetBlogId) {
                            alert('Please select a target subsite.');
                            return;
                        }

                        const blogName = $('option[value="' + targetBlogId + '"]').text().trim() || 'Subsite ' + targetBlogId;

                        if (!confirm('Are you sure you want to PERMANENTLY REMOVE ' + ids.length + ' item(s) from ' + blogName + '?\n\nThis cannot be undone.')) {
                            return;
                        }

                        // UI Setup
                        const btn = $(document.activeElement);
                        const originalText = btn.text();
                        btn.prop('disabled', true).text('Removing...');

                        // Reuse clone progress UI but with different text context
                        $('#clone-progress-container').show();
                        $('#clone-progress-bar').css('width', '0%').css('background', '#dc3545'); // Red for delete
                        $('#clone-percentage').text('0%');
                        $('#clone-status-text').text('Starting removal...');

                        let processed = 0;
                        let successes = 0;
                        let failed = 0;
                        let warnings = [];

                        function processNext(index) {
                            if (index >= ids.length) {
                                // Done
                                btn.prop('disabled', false).text(originalText);
                                $('#clone-status-text').text('Removal Completed!');

                                let alertMsg = 'Removal complete!\n\nSuccessfully removed: ' + successes + '\nFailed: ' + failed;
                                if (warnings.length > 0) {
                                    alertMsg += '\n\nWarnings:\n' + [...new Set(warnings)].join('\n');
                                }

                                setTimeout(function () {
                                    alert(alertMsg);
                                    window.location.reload();
                                }, 500);
                                return;
                            }

                            const pid = ids[index];
                            const currentNum = index + 1;
                            const total = ids.length;
                            const percent = Math.round((currentNum / total) * 100);

                            $('#clone-status-text').text('Removing item ' + currentNum + ' of ' + total + '...');
                            $('#clone-progress-bar').css('width', percent + '%');
                            $('#clone-percentage').text(percent + '%');

                            $.post(ajaxurl, {
                                action: 'iptv_remove_from_network',
                                nonce: nonce,
                                post_id: pid, // Original ID
                                target_blog_id: targetBlogId
                            }).done(function (response) {
                                if (response.success) {
                                    successes++;
                                } else {
                                    failed++;
                                    const errorMsg = response.data || 'Unknown error';
                                    warnings.push("Failed to remove ID " + pid + ": " + errorMsg);
                                    console.error('Failed to remove post ' + pid + ': ' + errorMsg);
                                }
                            }).fail(function (xhr, status, error) {
                                failed++;
                                warnings.push("Ajax failed for ID " + pid + ": " + error);
                            }).always(function () {
                                processed++;
                                processNext(index + 1);
                            });
                        }

                        processNext(0);
                    }

                    function handleBulkClone(ids, targetBlogId) {
                        if (ids.length === 0) {
                            alert('Please select items to clone first.');
                            return;
                        }
                        if (!targetBlogId) {
                            alert('Please select a target subsite.');
                            return;
                        }

                        // Human readable text for blog
                        const blogName = $('option[value="' + targetBlogId + '"]').text().trim() || 'Subsite ' + targetBlogId;
                        const doTranslate = $('#translate-on-clone').is(':checked');

                        let confirmMsg = 'Are you sure you want to clone ' + ids.length + ' item(s) to ' + blogName + '?';
                        if (doTranslate) {
                            confirmMsg += '\n\nContent will be translated using OpenAI (this may take some time).';
                        }

                        if (!confirm(confirmMsg)) {
                            return;
                        }

                        // UI Setup
                        const btn = $(document.activeElement);
                        const originalText = btn.text();
                        btn.prop('disabled', true).text('Cloning...');

                        // Show progress
                        $('#clone-progress-container').show();
                        $('#clone-progress-bar').css('width', '0%');
                        $('#clone-percentage').text('0%');
                        $('#clone-status-text').text('Starting...');

                        let processed = 0;
                        let successes = 0;
                        let failed = 0;
                        let translatedCount = 0;

                        let warnings = [];

                        // Process sequentially
                        function processNext(index) {
                            if (index >= ids.length) {
                                // Done
                                btn.prop('disabled', false).text(originalText);
                                $('#clone-status-text').text('Completed!');
                                $('#clone-progress-bar').css('background', '#46b450');

                                let alertMsg = 'Cloning complete!\n\nSuccessfully cloned: ' + successes + '\nFailed: ' + failed + '\nTranslated: ' + translatedCount;
                                if (warnings.length > 0) {
                                    alertMsg += '\n\nWarnings:\n' + [...new Set(warnings)].join('\n');
                                }

                                setTimeout(function () {
                                    alert(alertMsg);
                                    window.location.reload();
                                }, 500);
                                return;
                            }

                            const pid = ids[index];
                            const currentNum = index + 1;
                            const total = ids.length;
                            const percent = Math.round((currentNum / total) * 100);

                            $('#clone-status-text').text('Cloning item ' + currentNum + ' of ' + total + (doTranslate ? ' (Translating...)' : '...'));
                            $('#clone-progress-bar').css('width', percent + '%');
                            $('#clone-percentage').text(percent + '%');

                            $.post(ajaxurl, {
                                action: 'iptv_clone_to_network',
                                nonce: nonce,
                                post_id: pid, // Send single ID
                                target_blog_id: targetBlogId,
                                translate: doTranslate
                            }).done(function (response) {
                                if (response.success) {
                                    successes++;
                                    // Check translation status
                                    if (response.data.processed && response.data.processed.length > 0) {
                                        const item = response.data.processed[0];
                                        if (item.translated) {
                                            translatedCount++;
                                        } else if (doTranslate && item.translation_error) {
                                            // Requested translation but failed
                                            warnings.push("Translation failed for ID " + pid + ": " + item.translation_error);
                                            console.warn("Translation warning:", item.translation_error);
                                        }
                                    }
                                } else {
                                    failed++;
                                    const errorMsg = response.data || 'Unknown error';
                                    warnings.push("Failed to clone ID " + pid + ": " + errorMsg);
                                    console.error('Failed to clone post ' + pid + ': ' + errorMsg);
                                }
                            }).fail(function (xhr, status, error) {
                                failed++;
                                warnings.push("Ajax failed for ID " + pid + ": " + error);
                                console.error('Ajax failed for post ' + pid, error);
                            }).always(function () {
                                processed++;
                                // Next
                                processNext(index + 1);
                            });
                        }

                        // Start
                        processNext(0);
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
        // ACF field name map: iptv_text key => ACF field name (for keys where they differ)
        $acf_key_map = array(
            'hero_title_span' => 'hero_title_gradient_text',
        );

        // Check ACF first (field attached to the front page post)
        if (function_exists('get_field')) {
            $front_page_id = get_option('page_on_front');
            $acf_field_name = isset($acf_key_map[$key]) ? $acf_key_map[$key] : $key;
            $acf_value = $front_page_id ? get_field($acf_field_name, $front_page_id) : get_field($acf_field_name);
            if ($acf_value !== null && $acf_value !== '') {
                return $acf_value;
            }
        }

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