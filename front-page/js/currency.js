// Currency Selector JavaScript v2
function toggleCountryDropdown() {
    const dropdown = document.getElementById('countryDropdown');
    dropdown.classList.toggle('active');
}

// Footer dropdown toggle
function toggleFooterDropdown() {
    const dropdown = document.getElementById('footerCountryDropdown');
    if (dropdown) dropdown.classList.toggle('active');
}

// Close dropdown when clicking outside
document.addEventListener('click', function (e) {
    const selector = document.getElementById('countrySelector');
    const dropdown = document.getElementById('countryDropdown');
    if (selector && !selector.contains(e.target)) {
        if (dropdown) dropdown.classList.remove('active');
    }

    // Footer dropdown
    const footerSelector = document.getElementById('footerCountrySelector');
    const footerDropdown = document.getElementById('footerCountryDropdown');
    if (footerSelector && !footerSelector.contains(e.target)) {
        if (footerDropdown) footerDropdown.classList.remove('active');
    }
});

// Currency data. `name` is the switcher label — the currency code, since the
// switcher no longer changes language; `code` is what price formatting reads.
const currencyData = {
    usd: { symbol: '$', flag: '🇺🇸', code: 'USD', name: 'USD', position: 'before' },
    eur: { symbol: '€', flag: '🇫🇮', code: 'EUR', name: 'EUR', position: 'before' },
    sek: { symbol: 'kr', flag: '🇸🇪', code: 'SEK', name: 'SEK', position: 'after' },
    nok: { symbol: 'kr', flag: '🇳🇴', code: 'NOK', name: 'NOK', position: 'after' },
    dkk: { symbol: 'kr', flag: '🇩🇰', code: 'DKK', name: 'DKK', position: 'after' },
    isk: { symbol: 'kr', flag: '🇮🇸', code: 'ISK', name: 'ISK', position: 'after' }
};

// The site is English-only, so there is no language prefix to read a currency
// out of any more. The visitor's own choice is the only signal, and USD is the
// default until they make one.
function getDefaultCurrency() {
    const stored = localStorage.getItem('iptv_currency');
    return currencyData[stored] ? stored : 'usd';
}

// Update UI and prices for selected currency
function setCurrency(currency) {
    const data = currencyData[currency];
    if (!data) return;

    // Update header dropdown
    const headerFlag = document.getElementById('selectedFlag');
    const headerCode = document.getElementById('selectedCode');
    if (headerFlag) headerFlag.textContent = data.flag;
    if (headerCode) headerCode.textContent = data.name;

    // Update footer dropdown
    const footerFlag = document.getElementById('footerSelectedFlag');
    const footerCode = document.getElementById('footerSelectedCode');
    if (footerFlag) footerFlag.textContent = data.flag;
    if (footerCode) footerCode.textContent = data.name;

    document.querySelectorAll('.country-option').forEach(opt => {
        opt.classList.remove('selected');
        if (opt.dataset.currency === currency) {
            opt.classList.add('selected');
        }
    });

    window.currentCurrency = currency;
    updateAllPrices();
    localStorage.setItem('iptv_currency', currency);

    const headerDropdown = document.getElementById('countryDropdown');
    const footerDropdown = document.getElementById('footerCountryDropdown');
    if (headerDropdown) headerDropdown.classList.remove('active');
    if (footerDropdown) footerDropdown.classList.remove('active');
}

// Footer currency setter (syncs with header — setCurrency repaints both)
function setFooterCurrency(currency) {
    setCurrency(currency);
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

// Nothing here navigates any more: picking a currency repaints the prices in
// place. The site is English-only, so there is nowhere else to send anyone.

// Initialize currency on page load
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.country-option').forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault();
            setCurrency(this.dataset.currency);
        });
    });

    setCurrency(getDefaultCurrency());
});
