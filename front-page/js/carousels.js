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
