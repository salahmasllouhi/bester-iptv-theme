// Currency Selector JavaScript
function toggleCountryDropdown() {
    const dropdown = document.getElementById('countryDropdown');
    dropdown.classList.toggle('active');
}

// Footer dropdown toggle
function toggleFooterDropdown() {
    const dropdown = document.getElementById('footerCountryDropdown');
    if (dropdown) dropdown.classList.toggle('active');
}

// Redirect to region subsite
function redirectToRegion(currency) {
    const countryUrls = {
        usd: '/',
        eur: '/fi/',
        sek: '/se/',
        nok: '/no/',
        dkk: '/dk/',
        isk: '/is/'
    };

    const targetPath = countryUrls[currency];
    if (targetPath) {
        const baseUrl = window.location.origin;
        window.location.href = baseUrl + targetPath;
    }
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

// Currency data
const currencyData = {
    usd: { symbol: '$', flag: '🇺🇸', code: 'USD', position: 'before' },
    eur: { symbol: '€', flag: '🇫🇮', code: 'EUR', position: 'before' },
    sek: { symbol: 'kr', flag: '🇸🇪', code: 'SEK', position: 'after' },
    nok: { symbol: 'kr', flag: '🇳🇴', code: 'NOK', position: 'after' },
    dkk: { symbol: 'kr', flag: '🇩🇰', code: 'DKK', position: 'after' },
    isk: { symbol: 'kr', flag: '🇮🇸', code: 'ISK', position: 'after' }
};

// URL mappings for each currency/country
const countryUrls = {
    usd: '/',
    eur: '/fi/',
    sek: '/se/',
    nok: '/no/',
    dkk: '/dk/',
    isk: '/is/'
};

// Get default currency from URL path or localStorage
function getDefaultCurrency() {
    const pathname = window.location.pathname;
    if (pathname.startsWith('/se')) return 'sek';
    if (pathname.startsWith('/no')) return 'nok';
    if (pathname.startsWith('/dk')) return 'dkk';
    if (pathname.startsWith('/fi')) return 'eur';
    if (pathname.startsWith('/is')) return 'isk';

    const saved = localStorage.getItem('iptv_currency');
    if (saved && currencyData[saved]) return saved;

    return 'usd';
}

// Detect current currency from URL
function getCurrentCurrencyFromUrl() {
    const currentPath = window.location.pathname;
    if (currentPath.startsWith('/se')) return 'sek';
    if (currentPath.startsWith('/no')) return 'nok';
    if (currentPath.startsWith('/dk')) return 'dkk';
    if (currentPath.startsWith('/fi')) return 'eur';
    if (currentPath.startsWith('/is')) return 'isk';
    return 'usd';
}

// Update UI and prices for selected currency
function setCurrency(currency) {
    const data = currencyData[currency];
    if (!data) return;

    // Update header dropdown
    const headerFlag = document.getElementById('selectedFlag');
    const headerCode = document.getElementById('selectedCode');
    if (headerFlag) headerFlag.textContent = data.flag;
    if (headerCode) headerCode.textContent = data.code;

    // Update footer dropdown
    const footerFlag = document.getElementById('footerSelectedFlag');
    const footerCode = document.getElementById('footerSelectedCode');
    if (footerFlag) footerFlag.textContent = data.flag;
    if (footerCode) footerCode.textContent = data.code;

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

// Footer currency setter (syncs with header)
function setFooterCurrency(currency) {
    setCurrency(currency);
}

// Update all prices based on selected device count and currency
function updateAllPrices() {
    if (!window.iptvPrices) return;

    const currency = window.currentCurrency || 'usd';
    const data = currencyData[currency];

    const selectedDevice = document.querySelector('.select-card.selected[data-devices]');
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
}

// Initialize currency on page load
document.addEventListener('DOMContentLoaded', function () {
    // Set up country option click handlers with redirect
    document.querySelectorAll('.country-option').forEach(option => {
        option.addEventListener('click', function () {
            const currency = this.dataset.currency;
            const targetPath = countryUrls[currency];
            const currentCurrency = getCurrentCurrencyFromUrl();

            if (currency !== currentCurrency) {
                const baseUrl = window.location.origin;
                window.location.href = baseUrl + targetPath;
            } else {
                setCurrency(currency);
            }
        });
    });

    // Set default currency based on current URL
    const defaultCurrency = getDefaultCurrency();
    setCurrency(defaultCurrency);
});
