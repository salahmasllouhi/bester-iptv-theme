<!-- Footer Section (Design v2) -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo/dark logo 500_150.png"
                        alt="Nordic IPTV" class="footer-logo-img">
                </div>
                <p class="footer-desc">
                    <?php echo esc_html(iptv_text('footer_desc', 'The leading IPTV service provider — 40,000+ live channels, 200,000+ movies and series, every sport, in 4K and 8K.')); ?>
                </p>

                <!-- Language selector -->
                <div class="footer-language-selector">
                    <div class="footer-country-selector" id="footerCountrySelector">
                        <button class="footer-country-btn" onclick="toggleFooterDropdown()">
                            <span id="footerSelectedFlag">🇺🇸</span>
                            <span id="footerSelectedCode">English</span>
                            <svg width="10" height="10" viewBox="0 0 10 10">
                                <path d="M1 3L5 7L9 3" stroke="currentColor" stroke-width="1.5" fill="none" />
                            </svg>
                        </button>
                        <div class="footer-country-dropdown" id="footerCountryDropdown">
                            <div class="footer-country-option" onclick="setFooterCurrency('usd')">🇺🇸 English</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('sek')">🇸🇪 Svenska</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('nok')">🇳🇴 Norsk</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('dkk')">🇩🇰 Dansk</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('eur')">🇫🇮 Suomi</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('isk')">🇮🇸 Íslenska</div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            // Get all menu locations
            $locations = get_nav_menu_locations();

            // Helper to get menu name safely
            if (!function_exists('iptv_get_menu_title')) {
                function iptv_get_menu_title($loc, $default)
                {
                    global $locations;
                    if (isset($locations[$loc])) {
                        $menu = wp_get_nav_menu_object($locations[$loc]);
                        if ($menu)
                            return $menu->name;
                    }
                    return $default;
                }
            }
            ?>

            <div class="footer-col">
                <h4><?php echo esc_html(iptv_get_menu_title('footer_1', iptv_text('footer_head_plans', 'Plans'))); ?></h4>
                <?php if (has_nav_menu('footer_1')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_1',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <div>
                        <a href="#pricing">1 Month Plan</a>
                        <a href="#pricing">3 Month Plan</a>
                        <a href="#pricing">6 Month Plan</a>
                        <a href="#pricing">12 Month Plan</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <h4><?php echo esc_html(iptv_get_menu_title('footer_2', iptv_text('footer_head_links', 'Useful Links'))); ?></h4>
                <?php if (has_nav_menu('footer_2')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_2',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <?php
                    // home_url() ignores the current language, so this column used
                    // to send Swedish visitors to the English blog and guide.
                    $home = function_exists('pll_home_url') ? pll_home_url() : home_url('/');
                    iptv_footer_links(array(
                        array('slug' => 'blog', 'key' => 'footer_link_blog', 'label' => 'Blog',
                              'fallback' => trailingslashit($home) . 'blog/'),
                        array('slug' => 'iptv-guide-setup-apps-devices-tips', 'key' => 'footer_link_guide', 'label' => 'Setup Guide'),
                        array('slug' => 'm3u-playlist-convert-your-m3u-url', 'key' => 'footer_link_m3u', 'label' => 'M3U Converter'),
                        array('slug' => 'contact-us', 'key' => 'footer_link_contact', 'label' => 'Contact Us'),
                        array('url' => 'https://panel.nordictv.io/login', 'key' => 'footer_link_account', 'label' => 'My Account'),
                    ));
                    ?>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <h4><?php echo esc_html(iptv_get_menu_title('footer_3', iptv_text('footer_head_legal', 'Legal'))); ?></h4>
                <?php if (has_nav_menu('footer_3')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_3',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <?php
                    // These four all existed as pages while this column pointed
                    // every one of them at '#'.
                    iptv_footer_links(array(
                        array('slug' => 'about-us', 'key' => 'footer_link_about', 'label' => 'About Us'),
                        array('slug' => 'privacy-policy', 'key' => 'footer_link_privacy', 'label' => 'Privacy Policy'),
                        array('slug' => 'terms-of-services', 'key' => 'footer_link_terms', 'label' => 'Terms of Service'),
                        array('slug' => 'return-refund-policy', 'key' => 'footer_link_refund', 'label' => 'Return & Refund Policy'),
                    ));
                    ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            Nordic IPTV | <?php echo esc_html(iptv_text('footer_copyright', 'All Rights Reserved')); ?>
            <?php echo date('Y'); ?>
        </div>
    </div>
</footer>
