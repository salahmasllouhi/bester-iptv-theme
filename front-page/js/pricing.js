// Pricing JavaScript - Device/Duration configurator
(function () {
    // Product IDs for WooCommerce
    const products = {
        '1-1': 101, '1-3': 102, '1-6': 103, '1-12': 104,
        '2-1': 201, '2-3': 202, '2-6': 203, '2-12': 204,
        '3-1': 301, '3-3': 302, '3-6': 303, '3-12': 304,
        '4-1': 401, '4-3': 402, '4-6': 403, '4-12': 404
    };

    let selectedDevices = null;
    let selectedDuration = null;

    const btn = document.getElementById('checkout-btn');
    const btnText = document.getElementById('button-text');

    // Get price from window.iptvPrices
    function getPrice(devices, months) {
        if (!window.iptvPrices) return 0;
        const durationMap = { 1: '1_month', 3: '3_months', 6: '6_months', 12: '12_months' };
        const deviceKey = devices === 1 ? '1_device' : devices + '_devices';
        const durationKey = durationMap[months];
        const currency = window.currentCurrency || 'usd';

        if (window.iptvPrices[durationKey] && window.iptvPrices[durationKey][deviceKey]) {
            return parseFloat(window.iptvPrices[durationKey][deviceKey][currency]) || 0;
        }
        return 0;
    }

    // Get currency data for formatting
    function getCurrencyData() {
        const currencyData = {
            usd: { symbol: '$', position: 'before' },
            eur: { symbol: '€', position: 'before' },
            sek: { symbol: 'kr', position: 'after' },
            nok: { symbol: 'kr', position: 'after' },
            dkk: { symbol: 'kr', position: 'after' },
            isk: { symbol: 'kr', position: 'after' }
        };
        return currencyData[window.currentCurrency || 'usd'];
    }

    function formatPrice(price) {
        const data = getCurrencyData();
        const currency = window.currentCurrency || 'usd';
        let formatted = (currency === 'usd' || currency === 'eur') ? price.toFixed(2) : Math.round(price).toString();
        return data.position === 'before' ? data.symbol + formatted : formatted + ' ' + data.symbol;
    }

    // Update duration cards with prices based on selected devices
    function updatePrices(deviceCount) {
        const p1 = getPrice(deviceCount, 1);
        const p3 = getPrice(deviceCount, 3);
        const p6 = getPrice(deviceCount, 6);
        const p12 = getPrice(deviceCount, 12);

        const el1 = document.getElementById('price-1mo');
        if (el1) el1.textContent = formatPrice(p1);

        const el3 = document.getElementById('price-3mo');
        if (el3) el3.textContent = formatPrice(p3);

        const el6 = document.getElementById('price-6mo');
        if (el6) el6.textContent = formatPrice(p6);

        const el12 = document.getElementById('price-12mo');
        if (el12) el12.textContent = formatPrice(p12);

        // Update per-month prices
        const per1 = document.getElementById('per-1mo');
        if (per1) per1.textContent = 'per month';

        const per3 = document.getElementById('per-3mo');
        if (per3) per3.textContent = '~' + formatPrice(p3 / 3) + '/mo';

        const per6 = document.getElementById('per-6mo');
        if (per6) per6.textContent = '~' + formatPrice(p6 / 6) + '/mo';

        const per12 = document.getElementById('per-12mo');
        if (per12) per12.textContent = '~' + formatPrice(p12 / 12) + '/mo';
    }

    function updateButton() {
        if (selectedDevices && selectedDuration) {
            const price = getPrice(selectedDevices, selectedDuration);
            btnText.textContent = 'Complete Your Order  -  ' + formatPrice(price);
            btn.href = 'checkout/?add-to-cart=' + products[selectedDevices + '-' + selectedDuration];
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';

            // Update step indicators
            document.getElementById('step2').style.background = 'var(--blue-600)';
            document.getElementById('step2').style.color = 'white';
            document.getElementById('step2-label').style.color = 'var(--text)';
            document.getElementById('step3').style.background = 'var(--blue-600)';
            document.getElementById('step3').style.color = 'white';
            document.getElementById('step3-label').style.color = 'var(--text)';
        } else if (selectedDevices) {
            btnText.textContent = 'Select a plan duration';
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';

            document.getElementById('step2').style.background = 'var(--blue-600)';
            document.getElementById('step2').style.color = 'white';
            document.getElementById('step2-label').style.color = 'var(--text)';
        } else {
            btnText.textContent = 'Complete Your Order';
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
        }
    }

    // Device card click handlers
    document.querySelectorAll('#devices .select-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('#devices .select-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            selectedDevices = parseInt(this.dataset.devices);
            updatePrices(selectedDevices);
            updateButton();
        });
    });

    // Duration card click handlers
    document.querySelectorAll('#durations .select-card').forEach(card => {
        card.addEventListener('click', function () {
            document.querySelectorAll('#durations .select-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            selectedDuration = parseInt(this.dataset.duration);
            updateButton();
        });
    });

    // Initialize button state
    updateButton();
})();
