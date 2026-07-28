// Pricing JavaScript - Screen/Duration configurator
(function () {
    // Get variation ID from dynamically generated map (from pricing.php)
    function getVariationId(devices, duration) {
        if (window.iptvVariationIds) {
            return window.iptvVariationIds[devices + '-' + duration] || null;
        }
        return null;
    }

    let selectedDevices = null;
    let selectedDuration = null;

    const btn = document.getElementById('checkout-btn');
    const btnText = document.getElementById('button-text');
    const deviceGroup = document.getElementById('devices');
    const durationGroup = document.getElementById('durations');

    if (!btn || !btnText || !deviceGroup || !durationGroup) return;

    const deviceCards = Array.from(deviceGroup.querySelectorAll('[data-devices]'));
    const durationCards = Array.from(durationGroup.querySelectorAll('[data-duration]'));

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

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    // Savings badges are derived from the live prices so the claim stays true
    // when the screen count (and therefore the price ladder) changes.
    function updateSavings(deviceCount) {
        const base = getPrice(deviceCount, 1);

        [3, 6, 12].forEach(function (months) {
            const el = document.getElementById('save-' + months + 'mo');
            if (!el) return;

            const total = getPrice(deviceCount, months);
            if (!base || !total) {
                el.classList.add('is-hidden');
                return;
            }

            const pct = Math.round((1 - (total / months) / base) * 100);
            if (pct > 0) {
                el.textContent = 'Save ' + pct + '%';
                el.classList.remove('is-hidden');
            } else {
                el.classList.add('is-hidden');
            }
        });
    }

    // Update duration cards with prices based on selected screen count
    function updatePrices(deviceCount) {
        [1, 3, 6, 12].forEach(function (months) {
            const price = getPrice(deviceCount, months);
            setText('price-' + months + 'mo', formatPrice(price));
            setText(
                'per-' + months + 'mo',
                months === 1 ? 'per month' : '~' + formatPrice(price / months) + '/mo'
            );
        });

        updateSavings(deviceCount);
    }

    function updateButton() {
        if (selectedDevices && selectedDuration) {
            const price = getPrice(selectedDevices, selectedDuration);
            const variationId = getVariationId(selectedDevices, selectedDuration);
            btnText.textContent = 'Complete Your Order  -  ' + formatPrice(price);
            const mainSiteUrl = (window.iptvMainSiteUrl || '').replace(/\/$/, '');
            // Redirect to panel instead of WooCommerce checkout
            btn.href = 'https://panel.nordictv.io/';
            btn.style.opacity = '1';
            btn.style.pointerEvents = 'auto';
            /*
            if (variationId) {
                btn.href = mainSiteUrl + '/checkout/?add-to-cart=' + variationId + '&variation_id=' + variationId + '&attribute_devices=' + selectedDevices;
                btn.style.opacity = '1';
                btn.style.pointerEvents = 'auto';
            } else {
                // Variation not found - still go to checkout, WooCommerce will handle it
                btn.href = mainSiteUrl + '/checkout/';
                btn.style.opacity = '0.7';
                btn.style.pointerEvents = 'auto';
                console.warn('Variation ID not found for:', selectedDevices, selectedDuration);
            }
            */
        } else if (selectedDevices) {
            btnText.textContent = 'Select a plan duration';
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
        } else {
            btnText.textContent = 'Complete Your Order';
            btn.style.opacity = '0.6';
            btn.style.pointerEvents = 'none';
        }
    }

    // Reflect selection on a radiogroup: one checked item, roving tabindex.
    function select(cards, chosen) {
        cards.forEach(function (card) {
            const isChosen = card === chosen;
            card.classList.toggle('active', isChosen);
            card.setAttribute('aria-checked', isChosen ? 'true' : 'false');
            if (card.hasAttribute('aria-pressed')) {
                card.setAttribute('aria-pressed', isChosen ? 'true' : 'false');
            }
            card.tabIndex = isChosen ? 0 : -1;
        });
    }

    function chooseDevice(card, focus) {
        select(deviceCards, card);
        selectedDevices = parseInt(card.dataset.devices, 10);
        updatePrices(selectedDevices);
        updateButton();
        if (focus) card.focus();
    }

    function chooseDuration(card, focus) {
        select(durationCards, card);
        selectedDuration = parseInt(card.dataset.duration, 10);
        updateButton();
        if (focus) card.focus();
    }

    // Arrow-key navigation, as expected of a radiogroup.
    function bindKeys(cards, choose) {
        cards.forEach(function (card, index) {
            card.addEventListener('keydown', function (e) {
                let next = null;
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    next = cards[(index + 1) % cards.length];
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    next = cards[(index - 1 + cards.length) % cards.length];
                } else if (e.key === 'Home') {
                    next = cards[0];
                } else if (e.key === 'End') {
                    next = cards[cards.length - 1];
                }
                if (next) {
                    e.preventDefault();
                    choose(next, true);
                }
            });
        });
    }

    deviceCards.forEach(function (card) {
        card.addEventListener('click', function () { chooseDevice(card, false); });
    });
    durationCards.forEach(function (card) {
        card.addEventListener('click', function () { chooseDuration(card, false); });
    });

    bindKeys(deviceCards, chooseDevice);
    bindKeys(durationCards, chooseDuration);

    // Pre-select the default screen count so prices are live on load.
    const defaultScreens = parseInt(window.iptvDefaultScreens, 10) || 1;
    const defaultCard = deviceCards.find(function (card) {
        return parseInt(card.dataset.devices, 10) === defaultScreens;
    }) || deviceCards[0];

    if (defaultCard) {
        chooseDevice(defaultCard, false);
    } else {
        updateButton();
    }

    // currency.js calls this after switching currency so the per-month lines,
    // savings badges and CTA price all re-render, not just the headline price.
    window.iptvRefreshPricing = function () {
        updatePrices(selectedDevices || defaultScreens);
        updateButton();
    };
})();
