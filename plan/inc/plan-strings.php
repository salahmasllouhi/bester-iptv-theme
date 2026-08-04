<?php
/**
 * Plan template — copy
 *
 * The site is German. plan_str() takes the English written into the templates
 * and returns the German the visitor reads; anything not in the table falls
 * through unchanged, so a new string shows up in English rather than blank and
 * is obvious the first time anyone looks at the page.
 *
 * Keeping the English as the key is deliberate. The alternative — translating
 * the templates in place — would have meant editing eleven section files and
 * would have left plan/inc/plan-seo.php building its Rank Math digest from a
 * different set of words than the sections print.
 *
 * Usage in templates: plan_str('The English copy')
 *
 * The audience and FAQ defaults are held here as data rather than in the
 * sections that print them, so the templates and plan/inc/plan-seo.php read the
 * same array. Those are written in German directly — they are paragraphs, not
 * labels, and a lookup table of paragraphs reads worse than the paragraphs.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_plan_string_table')) {
    /**
     * English source => German.
     *
     * %s is the plan length as iptv_plan_label() gives it — "1 Monat",
     * "12 Monate" — so the German has to read correctly with a noun phrase
     * substituted in, not a number.
     *
     * @return array<string,string>
     */
    function iptv_plan_string_table()
    {
        return array(
            // Hero and closing band.
            'The whole service, one month at a time. No contract, no auto-renew — stop whenever you like.'
                => 'Der komplette Dienst, Monat für Monat. Ohne Vertrag, ohne automatische Verlängerung — du hörst auf, wann du willst.',
            'The whole service for %s. One payment, no contract, no auto-renew.'
                => 'Der komplette Dienst für %s. Eine Zahlung, ohne Vertrag, ohne automatische Verlängerung.',
            'From %s. Activated in about a minute, watchable on the TV you already own.'
                => 'Ab %s. In rund einer Minute aktiviert, auf dem Fernseher, der schon bei dir steht.',
            'Activated in about a minute, watchable on the TV you already own.'
                => 'In rund einer Minute aktiviert, auf dem Fernseher, der schon bei dir steht.',
            'Start your %s plan today'
                => 'Jetzt mit %s starten',
            'Watching in 60 seconds'
                => 'In 60 Sekunden startklar',
            'No contract, no auto-renew'
                => 'Ohne Vertrag, ohne automatische Verlängerung',
            '24/7 support'
                => 'Support rund um die Uhr',

            // Prices.
            '%s — choose your screens'
                => '%s — wähle deine Bildschirme',
            'One screen streams on one device at a time. Everything else is identical on every plan.'
                => 'Ein Bildschirm streamt auf einem Gerät gleichzeitig. Alles andere ist bei jeder Laufzeit identisch.',
            'One device watching at a time'
                => 'Ein Gerät gleichzeitig',
            '%d devices watching at the same time'
                => '%d Geräte gleichzeitig',
            '%s / month'
                => '%s / Monat',
            'Per month'
                => 'Pro Monat',
            'From'
                => 'ab',
            'You save'
                => 'Du sparst',
            'Save %d%%'
                => '%d %% sparen',
            'Best value'
                => 'Bestes Angebot',
            'See prices'
                => 'Preise ansehen',
            'See pricing'
                => 'Preise ansehen',

            // Compare table.
            'How the four plans compare'
                => 'Die vier Laufzeiten im Vergleich',
            'Prices shown for %s. Longer plans cost less per month — the service is the same on all of them.'
                => 'Preise für %s. Längere Laufzeiten kosten pro Monat weniger — die Leistung ist bei allen dieselbe.',
            'Plan'
                => 'Laufzeit',
            'You are here'
                => 'Du bist hier',
            'See %s'
                => '%s ansehen',
            'for %s'
                => 'für %s',

            // Audience band.
            'Who the %s plan suits'
                => 'Für wen sich %s lohnt',

            // Titles and schema.
            '%s IPTV Subscription'
                => 'IPTV Abo %s',
            'IPTV Subscription'
                => 'IPTV Abo',
            '%s IPTV Anbieter subscription: 40,000+ live channels, 200,000+ movies and series in 4K and HD, on 1 to 4 screens. No contract and no auto-renew.'
                => 'IPTV Abo für %s: über 40.000 Live-Sender, mehr als 200.000 Filme und Serien in 4K und HD, auf 1 bis 4 Bildschirmen. Ohne Vertrag und ohne automatische Verlängerung.',

            // ACF field labels — admin-facing, but there is no reason to leave
            // them in a second language from the rest of the editor.
            'Hero image'
                => 'Titelbild',
        );
    }
}

if (!function_exists('plan_str')) {
    /**
     * @param string $default The English copy written into the template.
     * @return string
     */
    function plan_str($default)
    {
        static $table = null;

        if ($table === null) {
            $table = iptv_plan_string_table();
        }

        return isset($table[$default]) ? $table[$default] : $default;
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
                    'title' => 'Du willst es richtig ausprobieren',
                    'text'  => 'Ein voller Monat mit dem kompletten Angebot — jeder Sender, jeder Film, jedes Spiel. Lang genug, um es auf deinem eigenen Fernseher, an deiner eigenen Leitung und an deinen eigenen Abenden zu beurteilen.',
                ),
                array(
                    'title' => 'Du willst dich noch nicht festlegen',
                    'text'  => 'Nichts verlängert sich von allein, und es gibt keinen Vertrag, aus dem du herauskommen müsstest. Wenn der Monat endet, endet er — ob es einen nächsten gibt, entscheidest du.',
                ),
                array(
                    'title' => 'Du brauchst es nur für eine Weile',
                    'text'  => 'Eine Saison, ein Turnier, ein langer Winter, eine Zwischenmiete. Nimm den Monat, den du brauchst, und hör danach auf.',
                ),
            ),
            3 => array(
                array(
                    'title' => 'Du weißt schon, was du willst',
                    'text'  => 'Du hast IPTV vorher ausprobiert und kennst den Dienst. Drei Monate kosten pro Monat spürbar weniger, als monatlich zu zahlen.',
                ),
                array(
                    'title' => 'Du überbrückst eine Saison',
                    'text'  => 'Eine Liga, ein Winter, eine Reihe langer Abende — ein Quartal hat meistens genau diese Form.',
                ),
                array(
                    'title' => 'Du willst weniger Verwaltung',
                    'text'  => 'Eine Zahlung statt drei, und dazwischen keine Verlängerung, an die du denken müsstest.',
                ),
            ),
            6 => array(
                array(
                    'title' => 'Du schaust das ganze Jahr',
                    'text'  => 'Ein halbes Jahr mit allem, zu einem Preis, neben dem monatliche Abrechnung teuer aussieht.',
                ),
                array(
                    'title' => 'Du willst sparen, ohne gleich ein Jahr zu buchen',
                    'text'  => 'Fast der ganze Rabatt des Jahrespakets, bei der Hälfte des Betrags im Voraus.',
                ),
                array(
                    'title' => 'Du hast genug verglichen',
                    'text'  => 'Einmal einrichten, die Abrechnung vergessen und einfach wieder fernsehen.',
                ),
            ),
            12 => array(
                array(
                    'title' => 'Du willst den niedrigsten Preis',
                    'text'  => 'Das Jahrespaket ist der günstigste Fernsehmonat, den wir verkaufen. Pro Monat kommt nichts anderes in die Nähe.',
                ),
                array(
                    'title' => 'Das ist dein Hauptfernsehen',
                    'text'  => 'Wenn im Haushalt an den meisten Abenden geschaut wird, passt ein Jahr zu dem, wie ihr den Dienst tatsächlich nutzt.',
                ),
                array(
                    'title' => 'Du willst einmal zahlen und es vergessen',
                    'text'  => 'Eine Zahlung, zwölf Monate, keine Verlängerungsmitteilung und am Ende keine automatische Abbuchung.',
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
                'q' => 'Kann ich später auf eine längere Laufzeit wechseln?',
                'a' => 'Ja. Viele starten mit einem Monat und wechseln auf 6 oder 12, sobald sie den Dienst kennen. Nichts ist festgelegt, und die längeren Laufzeiten kosten pro Monat deutlich weniger.',
            );
        }

        return array_merge($items, array(
            array(
                'q' => 'Was bekomme ich mit der Laufzeit %s?',
                'a' => 'Alles, was wir anbieten: über 40.000 Live-Sender, mehr als 200.000 Filme und Serien, 4K- und HD-Qualität, die vollständige Programmzeitschrift und Support rund um die Uhr. Die Laufzeit ändert nur, wie lange dein Zugang läuft und wie viele Bildschirme gleichzeitig schauen können.',
            ),
            array(
                'q' => 'Wie schnell wird mein Zugang freigeschaltet?',
                'a' => 'Direkt nach der Zahlung. Deine Zugangsdaten kommen in etwa 60 Sekunden per E-Mail, und du kannst schauen, bevor die Benachrichtigung wieder verschwunden ist.',
            ),
            array(
                'q' => 'Verlängert sich das automatisch?',
                'a' => 'Nein. Es gibt keine automatische Verlängerung und keinen Vertrag — die Laufzeit endet einfach, und du verlängerst nur, wenn du möchtest.',
            ),
            array(
                'q' => 'Wie viele Bildschirme brauche ich?',
                'a' => 'Ein Bildschirm streamt auf einem Gerät gleichzeitig. Nimm die Zahl der Personen, die zur selben Zeit etwas Unterschiedliches schauen könnten — die meisten Haushalte wählen zwei.',
            ),
            array(
                'q' => 'Welche Geräte funktionieren?',
                'a' => 'Smart TVs, Android TV, Apple TV, Fire Stick, iPhone, iPad, Android, Windows, Mac, Set-Top-Boxen, Chromecast, Roku und Kodi. Neue Hardware brauchst du nicht.',
            ),
            array(
                'q' => 'Was ist, wenn es bei mir nicht funktioniert?',
                'a' => 'Dann greift unsere Geld-zurück-Garantie. Und wenn du lieber vorher testen willst, gibt es einen 24-Stunden-Test ganz ohne Karte.',
            ),
        ));
    }
}
