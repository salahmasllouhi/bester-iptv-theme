/**
 * Sticky CTA bar
 *
 * Three jobs:
 *   1. Tick the countdown. The deadline is the same one the pricing panel
 *      quotes — same localStorage key, same window — so the bar and the
 *      "your discount is locked for" line never disagree.
 *   2. Publish the bar's height as --sticky-cta-h, which the stylesheet uses to
 *      pad the page and lift the activity ticker clear of it.
 *   3. Hide the bar while the pricing section is on screen, where it would
 *      otherwise sit on top of that section's own checkout button.
 */
(function () {
    var bar = document.getElementById('sticky-cta');
    if (!bar) return;

    // ── Countdown ────────────────────────────────────────────────────────────
    // Mirrors startCountdown() in pricing.js. Seeded on first visit and re-seeded
    // once elapsed, so it keeps running down across page views instead of
    // restarting on every load.
    (function countdown() {
        var out = document.getElementById('sticky-cta-countdown');
        if (!out) return;

        var days = parseInt(bar.dataset.offerDays, 10) || 5;
        // Localised in PHP — "d" in English, "pv" in Finnish, and so on.
        var daysSuffix = bar.dataset.daysSuffix || 'd';
        var windowMs = days * 24 * 60 * 60 * 1000;
        var key = 'iptvOfferDeadline';

        var deadline = 0;
        try {
            deadline = parseInt(localStorage.getItem(key), 10) || 0;
        } catch (e) {
            deadline = 0;
        }

        if (!deadline || deadline <= Date.now()) {
            deadline = Date.now() + windowMs;
            try { localStorage.setItem(key, String(deadline)); } catch (e) { /* private mode */ }
        }

        function pad(n) { return n < 10 ? '0' + n : String(n); }

        function tick() {
            var left = Math.max(0, deadline - Date.now());
            var d = Math.floor(left / 86400000);
            left -= d * 86400000;
            var h = Math.floor(left / 3600000);
            left -= h * 3600000;
            var m = Math.floor(left / 60000);
            var s = Math.floor((left - m * 60000) / 1000);

            // Drop the day segment on the last day — the bar is narrow, and
            // "08:14:02" reads more urgently than "0d 08:14:02".
            out.textContent = (d > 0 ? d + daysSuffix + ' ' : '') + pad(h) + ':' + pad(m) + ':' + pad(s);
        }

        tick();
        setInterval(tick, 1000);
    })();

    // ── Height → CSS custom property ─────────────────────────────────────────
    // The page pads its bottom by this much, so it has to be right at first
    // paint rather than after the first resize — otherwise the bar sits over
    // the end of the page until something happens to nudge it.
    function publishHeight() {
        document.documentElement.style.setProperty('--sticky-cta-h', bar.offsetHeight + 'px');
        publishOffset();
    }

    // Two separate numbers on purpose:
    //   --sticky-cta-h      the bar's height. Drives the page's bottom padding,
    //                       and stays put so the page never jumps.
    //   --sticky-cta-offset how much anything floating above the bar has to
    //                       clear. Drops to 0 while the bar is slid away, so
    //                       the Chaty button and the activity ticker settle
    //                       back down instead of hovering in mid-air.
    function publishOffset() {
        var clear = bar.classList.contains('is-hidden') ? 0 : bar.offsetHeight;
        document.documentElement.style.setProperty('--sticky-cta-offset', clear + 'px');
    }

    publishHeight();

    if ('ResizeObserver' in window) {
        // Catches every reflow that changes the height — web fonts swapping in,
        // a label wrapping to a second line, the desktop/mobile switch — with no
        // scroll or resize needed to trigger it.
        new ResizeObserver(publishHeight).observe(bar);
    } else {
        window.addEventListener('resize', publishHeight);
        window.addEventListener('orientationchange', publishHeight);
        if (document.fonts && document.fonts.ready) {
            document.fonts.ready.then(publishHeight);
        }
    }

    // ── Reasons to get out of the way ────────────────────────────────────────
    // Two independent triggers, so they are tracked separately rather than
    // letting whichever fires last decide.
    var reasons = { pricing: false, menu: false };

    function apply(reason, on) {
        reasons[reason] = on;
        bar.classList.toggle('is-hidden', reasons.pricing || reasons.menu);
        publishOffset();
    }

    // The pricing panel carries its own checkout button; the bar would cover it.
    var pricing = document.getElementById('pricing');
    if (pricing && 'IntersectionObserver' in window) {
        new IntersectionObserver(function (entries) {
            apply('pricing', entries[0].isIntersecting);
        }, { threshold: 0 }).observe(pricing);
    }

    // The mobile menu is a fullscreen overlay, but at a far lower z-index than
    // this bar. Watched rather than wired into toggleMobileMenu(), which is a
    // global defined in two places (header.js and inc/universal-header.php).
    var menu = document.getElementById('mobile-menu');
    if (menu && 'MutationObserver' in window) {
        new MutationObserver(function () {
            apply('menu', menu.classList.contains('active'));
        }).observe(menu, { attributes: true, attributeFilter: ['class'] });

        apply('menu', menu.classList.contains('active'));
    }
})();
