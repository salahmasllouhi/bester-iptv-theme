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
            // Set noredirect cookie to prevent PHP geo-redirect from overriding (30 days)
            document.cookie = 'noredirect=1;path=/;max-age=' + (30 * 24 * 60 * 60);
            localStorage.setItem('iptv_manual_switch', 'true');

            const baseUrl = window.location.origin;
            window.location.href = baseUrl + targetPath;
        }
    }
</script>