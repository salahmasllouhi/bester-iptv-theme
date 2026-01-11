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
                            <div class="footer-country-option" onclick="setFooterCurrency('eur')">🇫🇮 EUR</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('sek')">🇸🇪 SEK</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('nok')">🇳🇴 NOK</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('dkk')">🇩🇰 DKK</div>
                            <div class="footer-country-option" onclick="setFooterCurrency('isk')">🇮🇸 ISK</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-col">
                <h4>Quick Links</h4>
                <a href="#features">Features</a>
                <a href="#pricing">Pricing</a>
            </div>
            <div class="footer-col">
                <h4>Support</h4>
                <a href="#">Help Center</a>
                <a href="#">Contact Us</a>
                <a href="#">Setup Guides</a>
            </div>
            <div class="footer-col">
                <h4>Legal</h4>
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
                <a href="#">Refund Policy</a>
            </div>
        </div>
        <div class="footer-bottom">
            © <?php echo date('Y'); ?> Nordic IPTV. All rights reserved.
        </div>
    </div>
</footer>