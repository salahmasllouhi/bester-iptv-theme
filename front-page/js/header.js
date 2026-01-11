// Header JavaScript - Mobile menu and scroll effects
function toggleMobileMenu() {
    document.getElementById('mobile-menu').classList.toggle('active');
}

// Header scroll effect
window.addEventListener('scroll', function () {
    const header = document.getElementById('site-header');
    if (window.scrollY > 50) {
        header.classList.add('scrolled');
    } else {
        header.classList.remove('scrolled');
    }
});
