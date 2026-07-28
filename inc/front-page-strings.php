<?php
/**
 * Front Page – Polylang String Registration
 *
 * Only the printf-format strings live here. Every other front page string is an
 * ACF field on the front page itself (field group `group_homepage_fields`), so it
 * is translated per language in the page editor rather than in this table.
 *
 * These four are the exception on purpose: they carry `%` placeholders that
 * sprintf() consumes in front-page/sections/pricing.php. Dropping a placeholder
 * turns the pricing panel into a PHP warning or a wrong number, so they are kept
 * out of reach of the page editor and translated in
 * Languages → Translations instead.
 *
 * Usage in templates: iptv_text('key', 'Default English text')
 */

add_action('init', function () {
    if (!function_exists('pll_register_string')) {
        return;
    }

    $group = 'Front Page';

    // ── Pricing panel format strings ─────────────────────────────────────────
    // Keep the placeholders. Translate only the words around them.

    // %d = discount percent. Note the doubled %% renders a literal "%".
    pll_register_string('save_percent_format', 'Save %d%%', $group);

    // %1$s = money saved, %2$d = discount percent.
    pll_register_string('total_save_format', 'Save %1$s (%2$d%%)', $group);

    // %s = price per month.
    pll_register_string('total_meta_format', 'one-time · %s/mo', $group);

    // %d = discount percent.
    pll_register_string('total_lock_format', 'Your %d%% discount is locked for', $group);
});
