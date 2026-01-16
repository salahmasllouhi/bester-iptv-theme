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

// Header scroll effect
window.addEventListener('scroll', function () {
    const header = document.getElementById('site-header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
