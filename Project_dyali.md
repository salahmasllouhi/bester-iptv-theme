# Project Dyali – Memory File

> **Last Updated:** 2026-01-25

---

## Overview

**My IPTV Theme** — A modern WordPress theme for selling IPTV streaming subscriptions with WooCommerce integration and multisite support.

**Primary Domain:** nordictv.io

---

## Tech Stack

| Layer | Technology |
|-------|------------|
| CMS | WordPress Multisite |
| E-commerce | WooCommerce |
| Frontend | PHP + Vanilla JS + CSS |
| Fonts | Inter (Google Fonts) |

---

## Architecture

```
my-iptv-theme/
├── front-page/
│   ├── css/           # Modular CSS (16 files)
│   ├── js/            # JavaScript (5 files)
│   ├── sections/      # Landing page sections
│   │   ├── hero.php, header.php, footer.php
│   │   ├── pricing.php, features.php, brands.php
│   │   ├── comparison.php, reviews.php, sports.php
│   │   ├── steps.php, contact.php, dark-cta.php, unlock.php
│   └── partials/
│       └── checkout/  # Checkout partials (thank-you.php)
├── inc/               # PHP includes
│   ├── geo-redirect.php       # Geo-based redirection
│   ├── network-cloner.php     # Multisite cloning
│   ├── currency-settings.php  # Multi-currency pricing
│   ├── content-settings.php   # Front page content settings
│   ├── openai-translator.php  # AI translation service
│   ├── admin-bulk-editor.php  # Bulk product management
│   ├── product-setup.php      # WooCommerce product setup
│   ├── user-guide-shortcode.php
│   ├── universal-header.php
│   └── seo-manager.php        # Disabled (using Rank Math Pro)
├── template-store-checkout.php
├── template-store-shop.php
├── template-store-cart.php
├── front-page.php
├── functions.php
└── style.css
```

---

## Environment & Deployment

### Constants
- `IPTV_MAIN_SITE_URL` — Main site URL for cross-site cart (nordictv.io)

### Environment Variables (names only)
- OpenAI API key (stored in WordPress options)

---

## Features Implemented

### Landing Page Sections
- [x] Hero section
- [x] Features showcase
- [x] Pricing table
- [x] Brand logos
- [x] Comparison table
- [x] Customer reviews
- [x] Sports section
- [x] Steps/How-it-works
- [x] Contact form
- [x] Dark CTA section

### WooCommerce Customizations
- [x] Simplified checkout (email + phone only required)
- [x] Cart disabled — redirect to checkout
- [x] Shop/category pages redirect to home
- [x] "Buy Now" instead of "Add to Cart"
- [x] Cross-site cart (subsites redirect to main site checkout)
- [x] Hide trial products from related products

### Multisite Features
- [x] Geo-redirect system
- [x] Network cloner utility
- [x] Multi-currency pricing

### Admin Features
- [x] Bulk product editor
- [x] Content settings with OpenAI translation
- [x] User guide shortcode

---

## Bugs & Fixes

| Date | Bug | Resolution |
|------|-----|------------|
| — | — | — |

---

## Decisions Log

| Date | Decision | Rationale |
|------|----------|-----------|
| — | SEO Manager disabled | Using Rank Math Pro instead |
| — | Cart page disabled | Direct checkout flow |
| 2026-01-25 | Non-English/Swedish languages disabled | Focus on EN/SE first, reactivate others one-by-one |

---

## User Preferences

- **Code Style:** Decomposition — keep scripts short, modular files
- **Security:** Follow strict security guidelines (see user rules)

---

## Commands & Scripts

```bash
# No specific commands documented yet
```

---

## TODO / Roadmap

- [ ] Items to be added as work progresses

---

## Language Reactivation Guide

> **Disabled on:** 2026-01-25  
> **Active languages:** English (en), Swedish (se)  
> **Disabled languages:** Norwegian (no), Danish (dk), Finnish (fi), Icelandic (is)

### Files Modified

| File | What Was Changed |
|------|-----------------|
| `inc/geo-redirect.php` | Commented out NO, DK, FI, IS in `$redirect_map` |
| `front-page/sections/header.php` | Disabled currency options (EUR, NOK, DKK, ISK) in dropdown and mobile menu |
| `front-page/sections/footer.php` | Disabled currency options in footer dropdown |
| `inc/universal-header.php` | Same as header.php (for WooCommerce pages) |
| `inc/openai-translator.php` | Commented out NO, DK, FI, IS in `$language_map` |
| `inc/content-settings.php` | Commented out NO, DK, FI, IS in `$languages` array |

### Comment Marker

All disabled code uses this pattern:
```
// LANG-DISABLED: [code] - See Project_dyali.md "Language Reactivation Guide" to revert
```

### Quick Reactivation Steps

To re-enable a language (e.g., Norwegian):

1. **Search all files** for `LANG-DISABLED: no`
2. **Uncomment** those lines (remove `//` or `<!-- -->`)
3. **Test** geo-redirect and UI selectors
4. **Update** this section to move `no` to active languages

### Current Geo-Redirect Behavior

| User Location | Redirect |
|---------------|----------|
| Sweden (SE) | → `/se/` (Swedish) |
| All other countries | Stay on main site (English) |

---

## Change Log

| Date | Change |
|------|--------|
| 2026-01-25 | Created Project_dyali.md as project memory file |
| 2026-01-25 | Disabled non-English/Swedish languages (NO, DK, FI, IS) |
