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
        sek: '/sv/',
        nok: '/no/',
        dkk: '/dk/',
        isk: '/is/'
    };

    const targetPath = countryUrls[currency];
    if (targetPath) {
        // Set noredirect cookie to prevent PHP geo-redirect from overriding (30 days)
        document.cookie = 'noredirect=1;path=/;max-age=' + (30 * 24 * 60 * 60);
        localStorage.setItem('iptv_manual_switch', 'true');

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
    sek: '/sv/',
    nok: '/no/',
    dkk: '/dk/',
    isk: '/is/'
};

// Get default currency from URL path or localStorage
// Get default currency from URL path
function getDefaultCurrency() {
    return getCurrentCurrencyFromUrl();
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
    const targetPath = countryUrls[currency];
    const currentCurrency = getCurrentCurrencyFromUrl();

    if (currency !== currentCurrency && targetPath) {
        const baseUrl = window.location.origin;
        window.location.href = baseUrl + targetPath;
    } else {
        setCurrency(currency);
    }
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

// Check if we should auto-redirect based on browser language
function checkForSmartRedirect() {
    // If user has manually switched before, respect their choice (DO NOT REDIRECT)
    if (localStorage.getItem('iptv_manual_switch')) {
        return;
    }

    const currentPath = window.location.pathname;
    // Only redirect if we are on the main domain (root) or a non-specific path
    // Avoid circular redirects if already on correct subsite
    if (currentPath.startsWith('/se') || currentPath.startsWith('/no') || currentPath.startsWith('/dk') || currentPath.startsWith('/fi') || currentPath.startsWith('/is')) {
        return;
    }

    const lang = navigator.language || navigator.userLanguage;
    if (!lang) return;

    const primaryLang = lang.split('-')[0].toLowerCase();

    // Redirect Logic
    if (primaryLang === 'sv') {
        window.location.href = window.location.origin + '/sv/';
    }
}

// Initialize currency on page load
document.addEventListener('DOMContentLoaded', function () {

    // Check for smart redirect FIRST
    checkForSmartRedirect();

    // Set up country option click handlers with redirect
    document.querySelectorAll('.country-option').forEach(option => {
        option.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default link behavior to ensure storage save

            // User manually clicked, so save this preference to prevent auto-redirect
            localStorage.setItem('iptv_manual_switch', 'true');

            // Also set the noredirect cookie to prevent PHP geo-redirect (30 days)
            document.cookie = 'noredirect=1;path=/;max-age=' + (30 * 24 * 60 * 60);

            const currency = this.dataset.currency;
            const targetPath = countryUrls[currency];
            const currentCurrency = htmlCurrentCurrency(); // Helper to safely get current

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

function htmlCurrentCurrency() {
    return getCurrentCurrencyFromUrl();
}
