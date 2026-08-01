<!-- Universal Header - Includes front-page header for consistency -->
<?php
/**
 * Universal Header
 * 
 * This file includes the front-page header to ensure
 * consistent navigation across all pages.
 */
include get_template_directory() . '/front-page/sections/header.php';
?>

<!-- Header JavaScript for scroll effects and mobile menu -->
<script>
    // Header JavaScript - Mobile menu and scroll effects
    function toggleMobileMenu() {
        document.getElementById('mobile-menu').classList.toggle('active');
    }

    function toggleCountryDropdown() {
        const dropdown = document.getElementById('countryDropdown');
        if (dropdown) dropdown.classList.toggle('active');
    }

    // Close mobile menu when a link is clicked
    document.addEventListener('DOMContentLoaded', function () {
        const mobileMenu = document.getElementById('mobile-menu');
        if (mobileMenu) {
            mobileMenu.addEventListener('click', function (e) {
                if (e.target.tagName === 'A') {
                    toggleMobileMenu();
                }
            });
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function (e) {
            if (!e.target.closest('.country-selector')) {
                const dropdown = document.getElementById('countryDropdown');
                if (dropdown) dropdown.classList.remove('active');
            }
        });
    });

    // Header scroll effect - makes header sticky on scroll
    window.addEventListener('scroll', function () {
        const header = document.getElementById('site-header');
        if (header) {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        }
    });

    // Redirect to region subsite (syncs with currency.js)
    function redirectToRegion(currency) {
        const countryUrls = {
            usd: '/',
            eur: '/fi/',
            sek: '/sv/',
            nok: '/no/',
            dkk: '/dk/',
            isk: '/is/'
        };

        const targetPath = countryUrls[currency];
        if (targetPath) {
            // Remember the choice for next visit. window.nordictvLang is printed
            // by inc/language-preference.php, which also reads the cookie back.
            const cfg = window.nordictvLang;
            if (cfg && cfg.byCurrency && cfg.byCurrency[currency]) {
                document.cookie = cfg.cookie + '=' + encodeURIComponent(cfg.byCurrency[currency]) +
                    ';path=/;max-age=' + (cfg.days * 24 * 60 * 60) + ';samesite=lax';
            }

            const baseUrl = window.location.origin;
            window.location.href = baseUrl + targetPath + '?nolangredirect=1';
        }
    }
</script>