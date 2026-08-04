<?php
/**
 * Front page — default copy that is not a single string
 *
 * iptv_text() covers everything that is one field. The accordion and the review
 * wall are lists, and their fallbacks used to be written inline in
 * front-page/sections/faq.php and reviews.php — which meant the Rank Math
 * digest in inc/front-page-seo.php could not see them, and would have had to
 * keep its own copy of both.
 *
 * Same reason plan/inc/plan-strings.php exists: one array, so a section and the
 * analysis of that section cannot drift apart.
 *
 * These are fallbacks. A populated ACF repeater on the front page wins.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_front_faq_defaults')) {
    /**
     * @return array<int,array{q:string,a:string}>
     */
    function iptv_front_faq_defaults()
    {
        return array(
            array('q' => 'Was ist IPTV?',                              'a' => 'IPTV liefert TV-Sender und Inhalte auf Abruf über das Internet statt über Satellit oder Kabel. Du verbindest eine passende App mit deinen Zugangsdaten und kannst sofort loslegen.'),
            array('q' => 'Welche Geräte werden unterstützt?',          'a' => 'Smart TVs, Android TV, Apple TV, Fire Stick, iOS, Android, Windows, Mac, Set-Top-Boxen, Chromecast, Roku und Kodi — du brauchst keine neue Hardware.'),
            array('q' => 'Wie schnell muss meine Internetverbindung sein?', 'a' => 'Wir empfehlen mindestens 15–25 Mbit/s für flüssiges HD- und 4K-Streaming. Bei langsameren Verbindungen passt sich die Bitrate automatisch an.'),
            array('q' => 'Kann ich jederzeit kündigen?',               'a' => 'Ja. Es gibt keine Verträge und keine automatische Verlängerung — du kündigst, wann du möchtest, ohne Gebühren.'),
            array('q' => 'Wie viele Geräte kann ich gleichzeitig nutzen?', 'a' => 'Je nach Paket können 1 bis 4 Geräte gleichzeitig streamen.'),
            array('q' => 'Gibt es einen kostenlosen Test?',            'a' => 'Ja, es gibt einen 24-Stunden-Test, mit dem du den Dienst unverbindlich ausprobieren kannst. Auf jedes bezahlte Paket gibt es zusätzlich 30 Tage Geld-zurück-Garantie.'),
            array('q' => 'Welche Zahlungsmethoden werden akzeptiert?', 'a' => 'Visa, Mastercard, PayPal und Bitcoin — alles über eine sichere SSL-Kasse.'),
            array('q' => 'Welche Länder und Sprachen sind verfügbar?', 'a' => 'Die Inhalte umfassen 198 Länder, mit Untertiteln in vielen Sprachen und vollständiger Programmzeitschrift.'),
            array('q' => 'Wie erreiche ich den Support?',              'a' => 'Unser Support ist rund um die Uhr für dich da: <a href="mailto:support@bester-iptv-anbieter.com">support@bester-iptv-anbieter.com</a>.'),
        );
    }
}

if (!function_exists('iptv_front_review_defaults')) {
    /**
     * @return array<int,array{title:string,when:string,author:string,text:string}>
     */
    function iptv_front_review_defaults()
    {
        return array(
            array('title' => 'Crystal clear on every device', 'when' => 'Dec 2024', 'author' => 'Marcus L. · Stockholm, SE', 'text' => 'Crystal clear picture on all my devices. No buffering, no freezing — just pure streaming. Switched from cable 6 months ago and never looked back.'),
            array('title' => 'The sports coverage is insane', 'when' => 'Jan 2025', 'author' => 'Anna K. · Oslo, NO', 'text' => 'Every Premier League game, Champions League, NBA — all in HD. Setup took 5 minutes. Incredible service.'),
            array('title' => 'The quality blew me away', 'when' => 'Nov 2024', 'author' => 'Thomas B. · Copenhagen, DK', 'text' => 'I was skeptical at first but the quality blew me away. 40,000+ channels and they all work perfectly. Customer support replied within the hour.'),
            array('title' => 'Works on everything at once', 'when' => 'Feb 2025', 'author' => 'Erika V. · Helsinki, FI', 'text' => 'Finally a service that actually works on my Fire Stick AND smart TV at the same time. The 4-device plan is worth every penny.'),
            array('title' => 'Zero downtime, ever', 'when' => 'Jan 2025', 'author' => 'Jonas H. · Gothenburg, SE', 'text' => 'Been with IPTV Anbieter for over a year now. Zero downtime, constant channel list updates. This is how streaming should be done.'),
            array('title' => 'Great value for the price', 'when' => '3 days ago', 'author' => 'Sofia N. · Bergen, NO', 'text' => 'Great value for the price. Support answered my questions within minutes on WhatsApp — no waiting around.'),
            array('title' => 'Replaced four subscriptions', 'when' => '1 week ago', 'author' => 'Henrik D. · Malmö, SE', 'text' => 'I cancelled cable and three streaming apps. One bill, more content, and 4K on everything. Should have done it years ago.'),
        );
    }
}

if (!function_exists('iptv_front_reviews')) {
    /**
     * The review wall as the page renders it: the ACF repeater when it has rows,
     * the defaults above otherwise. Rows without both text and author are
     * skipped, exactly as the section does.
     *
     * @return array<int,array{title:string,when:string,author:string,text:string}>
     */
    function iptv_front_reviews()
    {
        $reviews = array();

        $rows = function_exists('get_field')
            ? get_field('reviews_list', get_option('page_on_front'))
            : null;

        if (is_array($rows)) {
            foreach ($rows as $row) {
                $text   = isset($row['review_text']) ? $row['review_text'] : '';
                $author = isset($row['review_author']) ? $row['review_author'] : '';

                if ($text && $author) {
                    $reviews[] = array(
                        'text'   => $text,
                        'author' => $author,
                        'title'  => isset($row['review_title']) ? $row['review_title'] : '',
                        'when'   => isset($row['review_when']) ? $row['review_when'] : '',
                    );
                }
            }
        }

        return $reviews ? $reviews : iptv_front_review_defaults();
    }
}

if (!function_exists('iptv_front_faq')) {
    /**
     * The accordion as the page renders it, keyword pages included.
     *
     * @return array<int,array{q:string,a:string}>
     */
    function iptv_front_faq()
    {
        $slug = function_exists('iptv_keyword_context') ? iptv_keyword_context() : '';

        if ($slug) {
            $definition = iptv_keyword_definition($slug);
            $items      = array();

            foreach ((array) (isset($definition['faq']) ? $definition['faq'] : array()) as $row) {
                if (!empty($row['q'])) {
                    $items[] = array('q' => $row['q'], 'a' => isset($row['a']) ? $row['a'] : '');
                }
            }

            if ($items) {
                return $items;
            }
        }

        $items = array();

        $rows = function_exists('get_field')
            ? get_field('faq_list', get_option('page_on_front'))
            : null;

        foreach ((array) $rows as $row) {
            if (!empty($row['question'])) {
                $items[] = array(
                    'q' => $row['question'],
                    'a' => isset($row['answer']) ? $row['answer'] : '',
                );
            }
        }

        return $items ? $items : iptv_front_faq_defaults();
    }
}
