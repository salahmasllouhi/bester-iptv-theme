<?php
/**
 * Front page — the body band
 *
 * The front page renders about 980 words of interface copy: headlines, feature
 * cards, plan labels, reviews. That is enough to sell and far too little to
 * rank — Rank Math scores content length at zero below 600 words and at 40%
 * below 1,500, and a page targeting "IPTV Anbieter" that never explains what an
 * IPTV Anbieter is has nothing for a crawler to agree with.
 *
 * This is that explanation. Same shape and same renderer as the keyword pages'
 * band (keyword/sections/keyword-content.php), so there is one prose layout on
 * the site rather than two, and the %placeholder% links resolve identically.
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_front_body')) {
    /**
     * @return array{lead:string,blocks:array}
     */
    function iptv_front_body()
    {
        return array(

            'lead' => 'Ein IPTV Anbieter liefert Fernsehen über das Internet statt über Kabel oder Satellit — dieselben Sender, dieselbe Bildqualität, nur ohne Schüssel, ohne Techniker und ohne Zweijahresvertrag. Was das im Alltag bedeutet, worauf du bei der Wahl achten solltest und wie der Einstieg abläuft, steht hier.',

            'blocks' => array(

                array(
                    'title' => 'Was ein IPTV Anbieter eigentlich macht',
                    'text'  => array(
                        'Beim klassischen Fernsehen wird ein Signal an alle gesendet und dein Receiver sucht sich den Sender heraus. Bei IPTV läuft es umgekehrt: Du forderst genau den einen Stream an, den du sehen willst, und bekommst ihn über deine Internetleitung geliefert. Deshalb braucht es keine Antenne und keine Schüssel — nur eine stabile Verbindung und ein Gerät, das ein Video abspielen kann.',
                        'Praktisch heißt das dreierlei. Erstens ist die Senderzahl nicht durch die Bandbreite eines Kabels begrenzt, sondern nur durch das, was der Anbieter einspeist — bei uns über 40.000 Sender aus 198 Ländern. Zweitens lässt sich das Live-Programm mit einer Mediathek kombinieren, weshalb neben den Sendern über 200.000 Filme und Serien auf Abruf stehen. Drittens ist der Zugang nicht an einen Ort gebunden: Dasselbe Abo läuft im Wohnzimmer, im Ferienhaus und auf dem Handy.',
                        'Wie das technisch im Detail funktioniert, beschreibt der %wiki% ausführlich und ohne Werbung.',
                    ),
                ),

                array(
                    'title' => 'Woran du einen guten IPTV Anbieter erkennst',
                    'text'  => array(
                        'Der Markt ist voll, und die Zahlen in den Anzeigen sagen wenig. Diese vier Punkte trennen einen brauchbaren Zugang von einem, den du nach zwei Wochen wieder aufgibst — ausführlicher stehen sie im %kw:iptv-anbieter-vergleich%.',
                    ),
                    'items' => array(
                        array(
                            'title' => 'Er lässt sich vorher testen',
                            'text'  => 'Ohne Test kaufst du blind. Bei uns gibt es 24 Stunden ohne Kreditkarte — genug, um einen Spieltag und einen Filmabend an deinem eigenen Anschluss zu prüfen.',
                        ),
                        array(
                            'title' => 'Er hält zur Primetime',
                            'text'  => 'Entscheidend ist nicht, ob ein Sender am Dienstagvormittag läuft, sondern ob er am Samstag um 15:30 Uhr hält. Genau dann ist die Last am höchsten — und genau dann solltest du testen.',
                        ),
                        array(
                            'title' => 'Er hat eine gefüllte Programmzeitschrift',
                            'text'  => 'Eine Senderliste ohne EPG ist halbfertig: Du siehst, dass etwas läuft, aber nicht was. Ein vollständiges EPG über mehrere Tage ist das Merkmal, an dem billige Playlists reihenweise scheitern.',
                        ),
                        array(
                            'title' => 'Er bindet dich nicht',
                            'text'  => 'Keine automatische Verlängerung, keine Kündigungsfrist, eine echte Geld-zurück-Garantie. Wer weitermachen will, verlängert bewusst — wie das geht, steht unter %kw:iptv-verlaengern%.',
                        ),
                    ),
                ),

                array(
                    'title' => 'Was in jedem Paket enthalten ist',
                    'text'  => array(
                        'Es gibt keine Zusatzpakete und keinen Aufpreis für Sport oder 4K. Der Unterschied zwischen den Laufzeiten ist ausschließlich der Preis pro Monat; die Leistung ist auf allen identisch.',
                    ),
                    'list' => array(
                        'Über 40.000 Live-Sender aus 198 Ländern, deutsche Free-TV- und Pay-TV-Sender inklusive',
                        'Mehr als 200.000 Filme und Serien auf Abruf, täglich erweitert',
                        'Sport live: %kw:iptv-bundesliga%, Champions League, Premier League, NFL, NBA und Formel 1',
                        'PPV-Events aus UFC und Boxen ohne Aufpreis',
                        '4K, Ultra HD und HD — je nachdem, was der Sender liefert',
                        'Vollständige Programmzeitschrift und sieben Tage Catch-up',
                        'Zugang wahlweise als M3U-Link oder über Xtream-Codes (%kw:iptv-m3u-kaufen%)',
                        'Support rund um die Uhr über die %contact%',
                    ),
                ),

                array(
                    'title' => 'Für wen sich der Wechsel rechnet',
                    'text'  => array(
                        'Am deutlichsten für Haushalte, die heute mehrere Abos parallel bezahlen. Netflix, Disney+, ein Sportpaket und gelegentliche PPV-Events summieren sich schnell auf über 80 € im Monat — verteilt auf vier Rechnungen, vier Apps und vier Kündigungsfristen. Ein Zugang bündelt das auf eine Oberfläche und einen Preis, beim %plan12% auf rund 5,83 € im Monat.',
                        'Ebenso deutlich für alle, die Sender außerhalb des deutschen Angebots sehen wollen: internationale Ligen mit Originalkommentar, Sender in der eigenen Muttersprache, türkische Sportkanäle (%kw:iptv-s-sport-plus%) oder englische Nachrichten. Über Kabel bedeutet das Zusatzpakete, über Satellit eine zweite Schüssel — hier ist es dieselbe Liste.',
                        'Weniger lohnt es sich, wenn du ohnehin nur die öffentlich-rechtlichen Sender schaust und nie Sport. Dafür brauchst du keinen Anbieter, und das sagen wir lieber vorher als hinterher.',
                    ),
                ),

                array(
                    'title' => 'So kommst du in zwei Minuten hinein',
                    'text'  => array(
                        'Du wählst Laufzeit und Bildschirmzahl, bezahlst über die SSL-Kasse mit Karte, PayPal oder Bitcoin und bekommst deine Zugangsdaten per E-Mail — in der Regel in unter zwei Minuten. Danach installierst du eine App auf dem Gerät, das ohnehin bei dir steht, und trägst die Daten einmal ein. Neue Hardware ist nicht nötig.',
                        'Welche App zu deinem Fernseher, Stick oder Handy passt, steht unter %kw:iptv-player%; die Einrichtung Schritt für Schritt zeigt die %guide%. Wenn du erst vergleichen willst, was ein Einstieg konkret bedeutet, führt %kw:iptv-kaufen% durch den Ablauf.',
                    ),
                ),
            ),
        );
    }
}
