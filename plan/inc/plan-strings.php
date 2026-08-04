<?php
/**
 * Plan template — copy
 *
 * The site is English-only, so plan_str() returns what it is given. It stays a
 * function so the templates do not all have to change, and so there is one
 * obvious place to hook a translation layer back in if that is ever needed.
 *
 * Usage in templates: plan_str('Default English text')
 *
 * The audience and FAQ defaults are held here as data rather than in the
 * sections that print them, so the templates and plan/inc/plan-seo.php read the
 * same array.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('plan_str')) {
    /**
     * @param string $default The English copy.
     * @return string
     */
    function plan_str($default)
    {
        return $default;
    }
}

if (!function_exists('iptv_plan_audience_defaults')) {
    /**
     * "Who this plan suits" — three cards per length.
     *
     * The only genuinely per-length copy on the site, and the reason the four
     * plans are separate pages: a 1-month page argues "try it without
     * committing", a 12-month page argues "you already know you want it".
     * Overridden per page by the plan_audience_points ACF repeater.
     *
     * @return array<int,array<int,array{title:string,text:string}>>
     */
    function iptv_plan_audience_defaults()
    {
        return array(
            1 => array(
                array(
                    'title' => 'You want to try it properly',
                    'text'  => 'A full month of the complete service — every channel, every film, every match. Long enough to judge it on your own TV, your own connection and your own evenings.',
                ),
                array(
                    'title' => 'You are not ready to commit',
                    'text'  => 'Nothing renews on its own and there is no contract to leave. When the month ends, it ends — you decide whether there is a next one.',
                ),
                array(
                    'title' => 'You only need it for a while',
                    'text'  => 'A season, a tournament, a long winter, a rented flat. Take the month you need and stop.',
                ),
            ),
            3 => array(
                array(
                    'title' => 'You have already made up your mind',
                    'text'  => 'You have tried IPTV before and you know what you want. Three months costs noticeably less per month than paying monthly.',
                ),
                array(
                    'title' => 'You are covering a season',
                    'text'  => 'One league, one winter, one stretch of long evenings — a quarter is usually the shape of it.',
                ),
                array(
                    'title' => 'You want less admin',
                    'text'  => 'One payment instead of three, and no renewal to remember in between.',
                ),
            ),
            6 => array(
                array(
                    'title' => 'You watch all year',
                    'text'  => 'Half a year of everything, at a rate that makes monthly billing look expensive.',
                ),
                array(
                    'title' => 'You want the saving without the full year',
                    'text'  => 'Most of the discount of the annual plan, at half the amount up front.',
                ),
                array(
                    'title' => 'You are done comparing',
                    'text'  => 'Set it up once, forget the billing, and go back to watching television.',
                ),
            ),
            12 => array(
                array(
                    'title' => 'You want the lowest price there is',
                    'text'  => 'The annual plan is the cheapest month of television we sell. Nothing else comes close per month.',
                ),
                array(
                    'title' => 'This is your main television',
                    'text'  => 'If the household watches most nights, a year is the plan that matches how you actually use it.',
                ),
                array(
                    'title' => 'You want to pay once and forget it',
                    'text'  => 'One payment, twelve months, no renewal notice and no auto-charge at the end of it.',
                ),
            ),
        );
    }
}

if (!function_exists('iptv_plan_faq_defaults')) {
    /**
     * Default FAQ rows. Overridden per page by the plan_faq ACF repeater.
     *
     * The first entry is 1-month only: the upsell question ("can I switch to a
     * longer plan?") makes no sense on the annual page.
     *
     * @param int $months
     * @return array<int,array{q:string,a:string}>
     */
    function iptv_plan_faq_defaults($months)
    {
        $items = array();

        if ((int) $months === 1) {
            $items[] = array(
                'q' => 'Can I switch to a longer plan later?',
                'a' => 'Yes. Plenty of people start with one month and move to 6 or 12 once they have seen the service. Nothing is locked, and the longer plans cost far less per month.',
            );
        }

        return array_merge($items, array(
            array(
                'q' => 'What do I get with the %s plan?',
                'a' => 'Everything we offer: 40,000+ live channels, 200,000+ movies and series, 4K/HD quality, the full TV guide and 24/7 support. The only thing a plan changes is how long it runs and how many screens can watch at once.',
            ),
            array(
                'q' => 'How fast is my subscription activated?',
                'a' => 'Straight after payment. Your login details are emailed within about 60 seconds, and you can be watching before the email notification fades.',
            ),
            array(
                'q' => 'Does it renew automatically?',
                'a' => 'No. There is no auto-renew and no contract — the plan simply ends, and you renew only if you want to.',
            ),
            array(
                'q' => 'How many screens do I need?',
                'a' => 'One screen streams on one device at a time. Pick the number of people who might watch different things at the same time — most households choose two.',
            ),
            array(
                'q' => 'Which devices work?',
                'a' => 'Smart TVs, Android TV, Apple TV, Fire Stick, iPhone, iPad, Android, Windows, Mac, set-top boxes, Chromecast, Roku and Kodi. No new hardware needed.',
            ),
            array(
                'q' => 'What if it does not work for me?',
                'a' => 'You are covered by our money-back guarantee, and there is a 24-hour trial with no card if you would rather test first.',
            ),
        ));
    }
}
