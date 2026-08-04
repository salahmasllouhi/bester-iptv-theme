// Price rendering
//
// This file used to own a currency/language switcher in the header, footer and
// mobile menu. The site is English-only and prices in one currency, so the
// switcher markup is gone and what remains is the code that paints prices:
// setCurrency() fixes the currency once on load, updateAllPrices() renders it.
//
// SITE_CURRENCY is the single place to change if the site ever prices in
// something other than USD. The other entries are kept because the stored price
// table (IPTV_Currency_Settings) still holds a column per currency.

const SITE_CURRENCY = 'usd';

const currencyData = {
    usd: { symbol: '$', flag: '🇺🇸', code: 'USD', name: 'USD', position: 'before' },
    eur: { symbol: '€', flag: '🇫🇮', code: 'EUR', name: 'EUR', position: 'before' },
    sek: { symbol: 'kr', flag: '🇸🇪', code: 'SEK', name: 'SEK', position: 'after' },
    nok: { symbol: 'kr', flag: '🇳🇴', code: 'NOK', name: 'NOK', position: 'after' },
    dkk: { symbol: 'kr', flag: '🇩🇰', code: 'DKK', name: 'DKK', position: 'after' },
    isk: { symbol: 'kr', flag: '🇮🇸', code: 'ISK', name: 'ISK', position: 'after' }
};

// Set the currency everything else prices in, and repaint.
function setCurrency(currency) {
    if (!currencyData[currency]) return;

    window.currentCurrency = currency;
    updateAllPrices();
}

// Update all prices based on selected device count and currency
function updateAllPrices() {
    if (!window.iptvPrices) return;

    const currency = window.currentCurrency || 'usd';
    const data = currencyData[currency];

    // pricing.js marks the chosen card with .active; .selected kept for safety.
    const selectedDevice = document.querySelector('.select-card.active[data-devices], .select-card.selected[data-devices]');
    let deviceKey = '1_device';
    if (selectedDevice) {
        const deviceNum = parseInt(selectedDevice.dataset.devices);
        deviceKey = deviceNum === 1 ? '1_device' : deviceNum + '_devices';
    }

    const durationMap = { '1': '1_month', '3': '3_months', '6': '6_months', '12': '12_months' };

    document.querySelectorAll('.duration-card').forEach(card => {
        const duration = card.dataset.duration;
        const durationKey = durationMap[duration];
        const priceEl = card.querySelector('.duration-price');

        if (priceEl && durationKey && window.iptvPrices[durationKey] && window.iptvPrices[durationKey][deviceKey]) {
            const price = window.iptvPrices[durationKey][deviceKey][currency];
            if (price) {
                priceEl.textContent = data.position === 'before'
                    ? data.symbol + price
                    : price + ' ' + data.symbol;
            }
        }
    });

    // Update Comparison Table "Annual Price"
    const compPriceEl = document.getElementById('comp-annual-price');
    if (compPriceEl && window.iptvPrices && window.iptvPrices['12_months'] && window.iptvPrices['12_months']['1_device']) {
        const price = window.iptvPrices['12_months']['1_device'][currency];
        if (price) {
            compPriceEl.textContent = data.position === 'before'
                ? data.symbol + price
                : price + ' ' + data.symbol;
        }
    }

    // Let the configurator re-render per-month lines, savings badges and CTA.
    if (typeof window.iptvRefreshPricing === 'function') {
        window.iptvRefreshPricing();
    }
}

// There is nothing for the visitor to pick any more, so this just fixes the
// site currency before the first paint of the pricing configurator.
document.addEventListener('DOMContentLoaded', function () {
    setCurrency(SITE_CURRENCY);
});
