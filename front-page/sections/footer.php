<!-- Footer Section -->
<footer class="site-footer">
    <div class="container">
        <div class="footer-grid">
            <div>
                <div class="footer-brand">
                    <img src="<?php echo get_template_directory_uri(); ?>/images/logo/light logo 500_150.png"
                        alt="Nordic IPTV" class="footer-logo-img">
                </div>
                <p class="footer-desc">Premium IPTV streaming service with 35,000+ channels worldwide.</p>

                <!-- Language Selector Dropdown in Footer -->
                <div class="footer-language-selector">
                    <div class="footer-country-selector" id="footerCountrySelector">
                        <button class="footer-country-btn" onclick="toggleFooterDropdown()">
                            <span id="footerSelectedFlag">🇺🇸</span>
                            <span id="footerSelectedCode">USD</span>
                            <svg width="10" height="10" viewBox="0 0 10 10">
                                <path d="M1 3L5 7L9 3" stroke="currentColor" stroke-width="1.5" fill="none" />
                            </svg>
                        </button>
                        <div class="footer-country-dropdown" id="footerCountryDropdown">
                            <div class="footer-country-option" onclick="setFooterCurrency('usd')">🇺🇸 USD</div>
                            <!-- LANG-DISABLED: eur - See Project_dyali.md "Language Reactivation Guide" to revert
                            <div class="footer-country-option" onclick="setFooterCurrency('eur')">🇫🇮 EUR</div>
                            -->
                            <div class="footer-country-option" onclick="setFooterCurrency('sek')">🇸🇪 SEK</div>
                            <!-- LANG-DISABLED: nok, dkk, isk - See Project_dyali.md "Language Reactivation Guide" to revert
                            <div class="footer-country-option" onclick="setFooterCurrency('nok')">🇳🇴 NOK</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('dkk')">🇩🇰 DKK</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('isk')">🇮🇸 ISK</div>
                            -->
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
                <h4><?php echo esc_html(iptv_get_menu_title('footer_1', 'Quick Links')); ?></h4>
                <?php if (has_nav_menu('footer_1')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_1',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <a href="#features">Features</a>
                    <a href="#pricing">Pricing</a>
                <?php endif; ?>
            </div>
            <?php if (has_nav_menu('footer_2')): ?>
                <div class="footer-col">
                    <h4><?php echo esc_html(iptv_get_menu_title('footer_2', 'Support')); ?></h4>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_2',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                </div>
            <?php endif; ?>
            <div class="footer-col">
                <h4><?php echo esc_html(iptv_get_menu_title('footer_3', 'Legal')); ?></h4>
                <?php if (has_nav_menu('footer_3')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_3',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Refund Policy</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            © <?php echo date('Y'); ?> Nordic IPTV. All rights reserved.
        </div>
    </div>
</footer>