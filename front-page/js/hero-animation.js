(function () {
    const liveCounter = document.getElementById('liveCounter');
    if (!liveCounter) return;

    // Configuration
    // Configuration
    const baseViewers = 42500;
    const fluctuationRange = 1500; // Viewers can vary by ±1500
    const updateInterval = 2000; // Update every 2 seconds for liveliness

    // Format number with commas
    function formatNumber(num) {
        return num.toLocaleString();
    }

    // Generate realistic target within range
    function getRealisticTarget(current) {
        // Skew slight upward trend or randomized small jump
        const change = Math.floor(Math.random() * 100) - 40; // -40 to +60 change
        let target = current + change;

        // Keep within bounds
        const min = baseViewers - fluctuationRange;
        const max = baseViewers + fluctuationRange;

        if (target < min) target = min + Math.floor(Math.random() * 100);
        if (target > max) target = max - Math.floor(Math.random() * 100);

        return target;
    }

    // Animate counter from current to target
    function animateCounter(start, end, duration) {
        const startTime = performance.now();
        const diff = end - start;

        function update(currentTime) {
            const elapsed = currentTime - startTime;
            const progress = Math.min(elapsed / duration, 1);

            // Ease out
            const easeOutQuad = 1 - (1 - progress) * (1 - progress);
            const current = Math.floor(start + diff * easeOutQuad);

            liveCounter.textContent = formatNumber(current);

            if (progress < 1) {
                requestAnimationFrame(update);
            }
        }
        requestAnimationFrame(update);
    }

    // Get current displayed value
    function getCurrentValue() {
        return parseInt(liveCounter.textContent.replace(/,/g, '')) || baseViewers;
    }

    // Update counter
    function updateCounter() {
        const currentValue = getCurrentValue();
        const newValue = getRealisticTarget(currentValue);
        // Fast, smooth transition
        animateCounter(currentValue, newValue, 1500);
    }

    // Initial value
    liveCounter.textContent = formatNumber(baseViewers + Math.floor(Math.random() * 500));

    // Start periodic updates
    setInterval(updateCounter, updateInterval);
})();
