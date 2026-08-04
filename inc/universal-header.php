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

    // The currency switcher used to navigate to a language subsite. The site is
    // English-only now, so front-page/js/currency.js owns the whole interaction
    // and repaints prices in place — there is nothing left to duplicate here.
</script>