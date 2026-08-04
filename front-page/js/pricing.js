// Pricing — screen picker drives four plan cards.
//
// The section used to be a two-step configurator ending in one "your total"
// card and one checkout button. It is now four cards, one per length, each with
// its own price and its own checkout link; the screen picker sits above them
// and rewrites all four whenever it changes.
//
// Copy note: every user-visible string this file writes comes from the markup
// PHP already rendered (see readFormat below) rather than being written here.
// Hardcoding them meant the German page reverted to English the moment anyone
// touched the picker.
(function () {
    let selectedDevices = null;

    const deviceGroup = document.getElementById('devices');
    const durationGroup = document.getElementById('durations');

    if (!deviceGroup || !durationGroup) return;

    const deviceCards = Array.from(deviceGroup.querySelectorAll('[data-devices]'));
    const planCards = Array.from(durationGroup.querySelectorAll('[data-duration]'));

    // Get price from window.iptvPrices
    function getPrice(devices, months) {
        if (!window.iptvPrices) return 0;
        const durationMap = { 1: '1_month', 3: '3_months', 6: '6_months', 12: '12_months' };
        const deviceKey = devices === 1 ? '1_device' : devices + '_devices';
        const durationKey = durationMap[months];
        const currency = window.currentCurrency || window.SITE_CURRENCY || 'eur';

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
        return currencyData[window.currentCurrency || window.SITE_CURRENCY || 'eur'];
    }

    function formatPrice(price) {
        const data = getCurrencyData();
        const currency = window.currentCurrency || window.SITE_CURRENCY || 'eur';
        let formatted = (currency === 'usd' || currency === 'eur') ? price.toFixed(2) : Math.round(price).toString();
        return data.position === 'before' ? data.symbol + formatted : formatted + ' ' + data.symbol;
    }

    function setText(id, value) {
        const el = document.getElementById(id);
        if (el) el.textContent = value;
    }

    // Take a string PHP already rendered and turn it into a template, so the
    // language lives in one place. "40 % sparen" becomes "{n} % sparen";
    // "~€5.83/Mon." becomes "~{p}/Mon.".
    function readFormat(id, pattern, token) {
        const el = document.getElementById(id);
        if (!el) return '';
        return el.textContent.trim().replace(pattern, token);
    }

    // Captured once, before anything is overwritten.
    const saveFormat = readFormat('save-12mo', /\d+/, '{n}') || '{n}%';
    const perMonthOne = (function () {
        const el = document.getElementById('per-1mo');
        return el ? el.textContent.trim() : '';
    })();
    const perMonthMulti = readFormat('per-12mo', /~[^/]*/, '~{p}') || '~{p}/mo';

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
                el.textContent = saveFormat.replace('{n}', pct);
                el.classList.remove('is-hidden');
            } else {
                el.classList.add('is-hidden');
            }
        });
    }

    // Repaint every card for the chosen screen count: headline price, per-month
    // line, savings badge and the card's own checkout link.
    function updatePrices(deviceCount) {
        [1, 3, 6, 12].forEach(function (months) {
            const price = getPrice(deviceCount, months);

            setText('price-' + months + 'mo', formatPrice(price));
            setText(
                'per-' + months + 'mo',
                months === 1
                    ? perMonthOne
                    : perMonthMulti.replace('{p}', formatPrice(price / months))
            );

            const cta = document.getElementById('cta-' + months + 'mo');
            if (cta) cta.href = checkoutUrl(deviceCount, months);
        });

        updateSavings(deviceCount);
    }

    // panel/checkout?connections=<1|2|3|4>&duration=<1|3|6|12>
    // The panel derives the price from these two params - nothing else is passed.
    function checkoutUrl(devices, months) {
        const base = window.iptvCheckoutBase || 'https://panel.nordictv.io/checkout';
        return base + '?connections=' + devices + '&duration=' + months;
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

    bindKeys(deviceCards, chooseDevice);

    // Pre-select the picker so every card's price and checkout link is live on
    // load. The cards themselves are not a radiogroup any more — each one is a
    // link, so there is nothing to select.
    const defaultScreens = parseInt(window.iptvDefaultScreens, 10) || 1;

    const defaultDeviceCard = deviceCards.find(function (card) {
        return parseInt(card.dataset.devices, 10) === defaultScreens;
    }) || deviceCards[0];

    if (defaultDeviceCard) chooseDevice(defaultDeviceCard, false);

    // Shadow under the sticky picker, but only once it has actually stuck —
    // otherwise it draws a line across the section on load.
    (function stickyShadow() {
        const bar = document.getElementById('screen-sticky');
        if (!bar || !('IntersectionObserver' in window)) return;

        // A 1px sentinel above the bar: when it leaves the viewport the bar is
        // pinned. Cheaper and steadier than measuring scroll position.
        const sentinel = document.createElement('div');
        sentinel.setAttribute('aria-hidden', 'true');
        sentinel.style.cssText = 'position:absolute;height:1px;width:1px;';
        bar.parentNode.insertBefore(sentinel, bar);

        new IntersectionObserver(function (entries) {
            bar.classList.toggle('is-stuck', !entries[0].isIntersecting);
        }).observe(sentinel);
    })();

    // currency.js calls this after switching currency so the per-month lines,
    // savings badges and checkout links all re-render.
    window.iptvRefreshPricing = function () {
        updatePrices(selectedDevices || defaultScreens);
    };
})();
