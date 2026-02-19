/**
 * Activity Ticker - Social Proof Notifications
 * Generates random activities from names and plans
 */
(function () {
    // Names by country
    const names = {
        "Sweden": ["Erik", "Linnea", "Oscar", "Astrid", "Viktor", "Alma", "Hugo", "Wilma", "Axel", "Ella", "Lucas", "Ebba", "Liam", "Saga", "Noah", "Freja", "Oliver", "Selma", "William", "Alice"],
        "Norway": ["Magnus", "Ingrid", "Sander", "Nora", "Henrik", "Thea", "Emil", "Maja", "Odin", "Leah", "Jakob", "Emma", "Filip", "Sofia", "Aksel", "Olivia", "Mathias", "Amalie", "Tobias", "Ida"],
        "Denmark": ["Mikkel", "Freja", "Rasmus", "Sofie", "Frederik", "Ida", "Noah", "Clara", "Oliver", "Alma", "Victor", "Ella", "Malthe", "Karla", "August", "Josefine", "Oscar", "Anna", "Carl", "Laura"],
        "Finland": ["Elias", "Aino", "Onni", "Venla", "Leo", "Sofia", "Eetu", "Emma", "Väinö", "Olivia", "Oliver", "Aada", "Noel", "Helmi", "Eino", "Isla", "Leevi", "Aurora", "Niilo", "Ella"],
        "Iceland": ["Aron", "Saga", "Gunnar", "Embla", "Bjarni", "Freyja", "Sigurður", "Guðrún", "Jón", "Anna", "Ólafur", "Kristín", "Einar", "María", "Ragnar", "Eva"],
        "USA": ["Jake", "Emily", "Mike", "Sarah", "Chris", "Jessica", "David", "Ashley", "Ryan", "Amanda", "Brandon", "Megan", "Tyler", "Lauren", "Kevin", "Brittany", "Justin", "Stephanie", "Andrew", "Nicole", "Matt", "Jennifer", "Josh", "Rachel", "Brian", "Michelle", "Eric", "Amber", "Jason", "Samantha"]
    };

    // Subscription plans (matching pricing)
    const plans = [
        "1 Month 1 Device",
        "1 Month 2 Devices",
        "1 Month 3 Devices",
        "1 Month 4 Devices",
        "3 Month 1 Device",
        "3 Month 2 Devices",
        "3 Month 3 Devices",
        "3 Month 4 Devices",
        "6 Month 1 Device",
        "6 Month 2 Devices",
        "6 Month 3 Devices",
        "6 Month 4 Devices",
        "12 Month 1 Device",
        "12 Month 2 Devices",
        "12 Month 3 Devices",
        "12 Month 4 Devices"
    ];

    // Country weights (higher = more frequent)
    const countryWeights = {
        "Sweden": 20,
        "Norway": 20,
        "Denmark": 15,
        "Finland": 15,
        "Iceland": 5,
        "USA": 25
    };

    // Randomizer functions
    function getRandomItem(array) {
        return array[Math.floor(Math.random() * array.length)];
    }

    function getWeightedCountry() {
        const countries = Object.keys(countryWeights);
        const weights = Object.values(countryWeights);
        const totalWeight = weights.reduce((a, b) => a + b, 0);

        let random = Math.random() * totalWeight;
        for (let i = 0; i < countries.length; i++) {
            random -= weights[i];
            if (random <= 0) return countries[i];
        }
        return countries[0];
    }

    function getRandomTime() {
        // 50% chance for minutes, 50% chance for hours
        if (Math.random() < 0.5) {
            const mins = Math.floor(Math.random() * 41) + 15; // 15-55 minutes
            return `${mins} min ago`;
        } else {
            const hours = Math.floor(Math.random() * 3) + 1; // 1-3 hours
            return `${hours}h ago`;
        }
    }

    function generateActivity() {
        const country = getWeightedCountry();
        const name = getRandomItem(names[country]);
        const plan = getRandomItem(plans);

        return {
            name: `${name} from ${country}`,
            action: `Got ${plan}`,
            time: getRandomTime()
        };
    }

    // Settings
    const SHOW_DURATION = 5000;
    const INTERVAL = 8000;
    const INITIAL_DELAY = 3000;

    // State
    let tickerInterval;
    let isTickerClosed = false;

    function showActivity() {
        if (isTickerClosed) return;

        const ticker = document.getElementById('activityTicker');
        const tickerName = document.getElementById('tickerName');
        const tickerAction = document.getElementById('tickerAction');

        if (!ticker || !tickerName || !tickerAction) return;

        // Generate a fresh random activity each time
        const activity = generateActivity();

        tickerName.textContent = activity.name;
        tickerAction.textContent = `${activity.action} • ${activity.time}`;

        ticker.classList.remove('hide');
        ticker.classList.add('show');

        setTimeout(() => {
            ticker.classList.remove('show');
            ticker.classList.add('hide');
        }, SHOW_DURATION);
    }

    // Global function for close button
    window.closeTicker = function () {
        isTickerClosed = true;
        const ticker = document.getElementById('activityTicker');
        if (ticker) {
            ticker.classList.remove('show');
            ticker.classList.add('hide');
        }
        clearInterval(tickerInterval);
    };

    function startTicker() {
        setTimeout(() => {
            showActivity();
            tickerInterval = setInterval(showActivity, INTERVAL);
        }, INITIAL_DELAY);
    }

    // Start when DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startTicker);
    } else {
        startTicker();
    }
})();
