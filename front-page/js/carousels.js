// Carousels JavaScript - Sports and Brands auto-scroll
document.addEventListener('DOMContentLoaded', function () {
    // Sports Carousel
    const slider = document.getElementById('sportsCarousel');
    if (slider) {
        let isDown = false;
        let startX;
        let scrollLeft;
        let isPaused = false;

        // Clone items for infinite loop
        const originalChildren = Array.from(slider.children);
        originalChildren.forEach(child => {
            const clone = child.cloneNode(true);
            clone.setAttribute('aria-hidden', 'true');
            slider.appendChild(clone);
        });

        // Auto Scroll
        let speed = 0.8;
        function animate() {
            if (!isPaused && !isDown) {
                slider.scrollLeft += speed;
                if (slider.scrollLeft >= (slider.scrollWidth / 2)) {
                    slider.scrollLeft = 0;
                }
            }
            requestAnimationFrame(animate);
        }
        requestAnimationFrame(animate);

        // Mouse Events
        slider.addEventListener('mousedown', (e) => {
            isDown = true;
            slider.classList.add('active');
            startX = e.pageX - slider.offsetLeft;
            scrollLeft = slider.scrollLeft;
            isPaused = true;
        });

        slider.addEventListener('mouseleave', () => {
            isDown = false;
            slider.classList.remove('active');
            isPaused = false;
        });

        slider.addEventListener('mouseup', () => {
            isDown = false;
            slider.classList.remove('active');
            isPaused = false;
        });

        slider.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - slider.offsetLeft;
            const walk = (x - startX) * 2;
            slider.scrollLeft = scrollLeft - walk;
        });

        // Touch Events
        slider.addEventListener('touchstart', () => { isPaused = true; }, { passive: true });
        slider.addEventListener('touchend', () => { isPaused = false; });
    }

    // Brands Carousel - Manual drag control
    const carousel = document.getElementById('brands-carousel');
    if (carousel) {
        let isDown = false;
        let startX;
        let scrollLeftPos;

        carousel.addEventListener('mousedown', (e) => {
            isDown = true;
            carousel.style.animationPlayState = 'paused';
            startX = e.pageX - carousel.offsetLeft;
            scrollLeftPos = carousel.style.transform ? parseFloat(carousel.style.transform.replace('translateX(', '')) : 0;
        });

        carousel.addEventListener('mouseleave', () => { isDown = false; });

        carousel.addEventListener('mouseup', () => {
            isDown = false;
            setTimeout(() => { carousel.style.animationPlayState = 'running'; }, 1000);
        });

        carousel.addEventListener('mousemove', (e) => {
            if (!isDown) return;
            e.preventDefault();
            const x = e.pageX - carousel.offsetLeft;
            const walk = (x - startX) * 2;
            carousel.style.transform = `translateX(${scrollLeftPos + walk}px)`;
        });
    }
});

// Review rail — arrow paging.
//
// Replaces the CSS marquee that scrolled on a loop. Scrolling by one card's
// width plus the gap keeps the rail on a snap point, so a press always lands a
// card flush against the edge rather than mid-card.
(function reviewRail() {
    const rail = document.getElementById('review-rail');
    if (!rail) return;

    const wrap = rail.closest('.dv2-review-rail-wrap');
    if (!wrap) return;

    const prev = wrap.querySelector('.dv2-review-arrow--prev');
    const next = wrap.querySelector('.dv2-review-arrow--next');
    if (!prev || !next) return;

    function step() {
        const card = rail.querySelector('.dv2-review-card');
        if (!card) return rail.clientWidth;
        const gap = parseFloat(getComputedStyle(rail).columnGap || getComputedStyle(rail).gap) || 16;
        return card.getBoundingClientRect().width + gap;
    }

    function sync() {
        // 2px of slack: sub-pixel widths mean scrollLeft rarely hits the exact
        // maximum, which would otherwise leave "next" enabled at the end.
        const max = rail.scrollWidth - rail.clientWidth - 2;
        prev.disabled = rail.scrollLeft <= 2;
        next.disabled = rail.scrollLeft >= max;
    }

    prev.addEventListener('click', function () { rail.scrollBy({ left: -step(), behavior: 'smooth' }); });
    next.addEventListener('click', function () { rail.scrollBy({ left: step(), behavior: 'smooth' }); });

    rail.addEventListener('scroll', sync, { passive: true });
    window.addEventListener('resize', sync);
    sync();
})();
