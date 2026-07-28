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
                    <?php echo esc_html(iptv_text('footer_desc', 'The leading IPTV service provider — 35,000+ live channels, 150,000+ movies and series, every sport, in 4K and 8K.')); ?>
                </p>

                <!-- Currency selector -->
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
                <h4><?php echo esc_html(iptv_get_menu_title('footer_1', 'Plans')); ?></h4>
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
                <h4><?php echo esc_html(iptv_get_menu_title('footer_2', 'Useful Links')); ?></h4>
                <?php if (has_nav_menu('footer_2')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_2',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <div>
                        <a href="<?php echo home_url('/blog/'); ?>">Blog</a>
                        <a href="<?php echo home_url('/user-guide/'); ?>">Setup Guide</a>
                        <a href="https://panel.nordictv.io/login">My Account</a>
                    </div>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <h4><?php echo esc_html(iptv_get_menu_title('footer_3', 'Legal')); ?></h4>
                <?php if (has_nav_menu('footer_3')): ?>
                    <?php wp_nav_menu(array(
                        'theme_location' => 'footer_3',
                        'container' => false,
                        'fallback_cb' => false,
                    )); ?>
                <?php else: ?>
                    <div>
                        <a href="#">About Us</a>
                        <a href="#">Privacy Policy</a>
                        <a href="#">Terms of Service</a>
                        <a href="#">Return &amp; Refund Policy</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="footer-bottom">
            Nordic IPTV | <?php echo esc_html(iptv_text('footer_copyright', 'All Rights Reserved')); ?>
            <?php echo date('Y'); ?>
        </div>
    </div>
</footer>
