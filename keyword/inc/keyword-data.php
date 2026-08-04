<?php
/**
 * Keyword landing pages — the copy table
 *
 * One entry per keyword. Each entry drives a page that renders the front page's
 * section stack with its own wording, plus a body band and an FAQ that exist
 * only on that page.
 *
 * Why the body band matters: eight pages built from the same sections would be
 * eight near-duplicates competing with each other and with the front page. The
 * `blocks` and `faq` below are the part that is genuinely about the keyword —
 * they are what stops these being thin copies, and they are most of the word
 * count Rank Math measures.
 *
 * `text` overrides front-page copy keys for this page only, through the
 * `iptv_text` filter. Anything not listed falls through to the front page, so
 * prices, reviews, device chips and the rest stay in one place.
 *
 * Link placeholders in copy — resolved by iptv_keyword_links():
 *   %home%  %guide%  %m3u%  %faq%  %contact%  %plan1% %plan3% %plan6% %plan12%
 *   %kw:slug%   another keyword page
 *   %wiki%      Wikipedia's IPTV article — the followed external reference
 *
 * @package Nordic_IPTV
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_keyword_definitions')) {
    /**
     * Every keyword page, keyed by slug.
     *
     * @return array<string,array>
     */
    function iptv_keyword_definitions()
    {
        static $definitions = null;

        if ($definitions !== null) {
            return $definitions;
        }

        $definitions = array(

            // ─────────────────────────────────────────────────────────────────
            'iptv-kaufen' => array(
                'keyword'   => 'IPTV kaufen',
                'title'     => 'IPTV kaufen',
                'seo_title' => 'IPTV kaufen 2026 – 40.000+ Sender in 4K ab 5,83 €',
                'seo_desc'  => 'IPTV kaufen ohne Vertrag: über 40.000 Live-Sender und 200.000+ Filme in 4K, in zwei Minuten aktiviert. Sichere Bezahlung, 14 Tage Geld-zurück-Garantie.',

                'text' => array(
                    'hero_title'        => 'IPTV kaufen – in zwei Minuten startklar.',
                    'hero_title_span'   => 'Ohne Vertrag.',
                    'hero_title_3'      => 'Ohne Wartezeit. Ohne Risiko.',
                    'hero_subtitle'     => 'Du willst IPTV kaufen, ohne dich zwölf Monate zu binden? Du bekommst über 40.000 Live-Sender, mehr als 200.000 Filme und Serien und jeden Sport in 4K — sofort freigeschaltet, auf dem Gerät, das schon bei dir steht.',
                    'hero_image_alt'    => 'IPTV kaufen – Live-Sport und Unterhaltung in 4K',
                    'vod_image_alt'     => 'Filme und Serien, wenn du IPTV kaufen möchtest',
                    'sports_image_alt'  => 'Live-Sport für alle, die IPTV kaufen',
                    'features_title'    => 'Das bekommst du, wenn du',
                    'features_title_span' => 'IPTV kaufen willst',
                    'features_subtitle' => 'Bevor du IPTV kaufen kannst, willst du wissen, was drin ist. Das hier ist in jedem Paket enthalten — ohne Zusatzkosten, ohne Kleingedrucktes.',
                    'pricing_subtitle'  => 'Ein Preis, alles inklusive. Über 40.000 Live-Sender und mehr als 200.000 Filme und Serien in 4K.',
                    'reviews_title'     => 'Kunden, die bei uns IPTV kaufen',
                    'faq_title'         => 'Fragen zum IPTV kaufen',
                ),

                'lead' => 'IPTV kaufen heißt heute nicht mehr, sich für ein Jahr zu binden und auf einen Techniker zu warten. Du wählst eine Laufzeit, bezahlst einmal und bekommst deine Zugangsdaten per E-Mail — in der Regel in weniger als zwei Minuten. Auf dieser Seite steht, was du dafür bekommst, worauf du beim Kauf achten solltest und wie die Einrichtung abläuft.',

                'blocks' => array(
                    array(
                        'title' => 'Was du bekommst, wenn du IPTV kaufen willst',
                        'text'  => array(
                            'Ein Zugang, ein Preis, kein Aufpreis für Sport oder 4K. Anders als beim klassischen Kabelanschluss gibt es keine Pakete, die du einzeln dazubuchen musst — Sport, Filme, Kinderprogramm und internationale Sender sind in jedem Abo enthalten.',
                            'Technisch bekommst du einen Zugang, der sowohl über eine M3U-Playlist als auch über Xtream-Codes funktioniert. Damit läuft er in praktisch jeder gängigen App, von IPTV Smarters über TiviMate bis VLC. Welche App zu deinem Gerät passt, steht auf der Seite %kw:iptv-player%.',
                        ),
                        'list' => array(
                            'Über 40.000 Live-Sender aus 198 Ländern, inklusive aller deutschen Free-TV- und Pay-TV-Sender',
                            'Mehr als 200.000 Filme und Serien auf Abruf, täglich erweitert',
                            'Sport live: %kw:iptv-bundesliga%, Champions League, Premier League, NFL, NBA und Formel 1',
                            'PPV-Events wie UFC und Boxen ohne Aufpreis',
                            '4K, Ultra HD und HD — je nachdem, was der Sender liefert',
                            'Vollständige Programmzeitschrift (EPG) und sieben Tage Catch-up',
                        ),
                    ),
                    array(
                        'title' => 'Worauf du achten solltest, bevor du IPTV kaufen gehst',
                        'text'  => array(
                            'Der Markt ist unübersichtlich, und die Unterschiede zwischen den Angeboten sind größer als der Preis vermuten lässt. Diese fünf Punkte trennen einen brauchbaren Zugang von einem, den du nach zwei Wochen wieder kündigst. Wer mehrere Angebote nebeneinanderlegen will, findet die Kriterien ausführlicher unter %kw:iptv-anbieter-vergleich%.',
                        ),
                        'items' => array(
                            array(
                                'title' => 'Teste, bevor du zahlst',
                                'text'  => 'Ein seriöser Zugang lässt sich vorher ausprobieren. Wir bieten einen 24-Stunden-Test ohne Kreditkarte an — damit siehst du an deinem eigenen Anschluss, ob die Streams stabil laufen, bevor Geld fließt.',
                            ),
                            array(
                                'title' => 'Achte auf die Rückgabe, nicht auf den Rabatt',
                                'text'  => 'Ein Rabatt kostet nichts, wenn der Dienst nicht läuft. Wichtiger ist, was passiert, wenn er nicht läuft: Bei uns gilt eine Geld-zurück-Garantie von 14 Tagen, ohne dass du dich rechtfertigen musst.',
                            ),
                            array(
                                'title' => 'Frag nach der Serverlast, nicht nach der Senderzahl',
                                'text'  => 'Jeder Anbieter wirbt mit einer großen Zahl. Entscheidend ist, ob der Stream am Bundesliga-Samstag um 15:30 Uhr noch läuft. Frag den Support vor dem Kauf konkret danach — die Antwort sagt dir mehr als jede Senderliste.',
                            ),
                            array(
                                'title' => 'Keine automatische Verlängerung',
                                'text'  => 'Du solltest selbst entscheiden, wann du weitermachst. Unsere Pakete verlängern sich nicht von allein; wie das Verlängern funktioniert, steht unter %kw:iptv-verlaengern%.',
                            ),
                            array(
                                'title' => 'Sichere Bezahlung',
                                'text'  => 'Bezahlt wird über eine SSL-verschlüsselte Kasse mit Karte, PayPal oder Bitcoin. Wer nur Überweisung an ein Privatkonto anbietet, ist kein Anbieter, sondern ein Risiko.',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'Welche Laufzeit lohnt sich?',
                        'text'  => array(
                            'Die Leistung ist bei allen Laufzeiten identisch — es ändert sich nur der Preis pro Monat. Wer zum ersten Mal IPTV kaufen möchte, nimmt sinnvollerweise den %plan1%: kurz genug, um nichts zu riskieren, lang genug, um den Dienst im Alltag zu beurteilen.',
                            'Wenn du weißt, dass du bleibst, ist der %plan12% die günstigste Variante — er kostet umgerechnet rund 5,83 € im Monat und damit weniger als ein einzelnes Sportpaket beim klassischen Anbieter. Dazwischen liegen der %plan3% und der %plan6%.',
                        ),
                    ),
                    array(
                        'title' => 'Nach dem Kauf: so richtest du es ein',
                        'text'  => array(
                            'Du bekommst deine Zugangsdaten per E-Mail, meist innerhalb von zwei Minuten. Danach installierst du eine App auf dem Gerät, das du ohnehin nutzt — Smart TV, Fire TV Stick, Android-Box, iPhone, Tablet oder PC — und trägst die Daten einmal ein. Neue Hardware brauchst du nicht.',
                            'Eine bebilderte Anleitung pro Gerät steht in der %guide%. Wenn du lieber mit einer Playlist arbeitest, kannst du deinen Zugang über den %m3u% in das Format bringen, das deine App erwartet. Kommst du nicht weiter, ist der Support rund um die Uhr über die %contact% erreichbar.',
                            'Was IPTV technisch überhaupt ist und warum es ohne Satellitenschüssel funktioniert, erklärt der %wiki% neutral und ohne Werbung.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Wie schnell kann ich nach dem Kauf loslegen?', 'a' => 'In der Regel in unter zwei Minuten. Die Zugangsdaten kommen automatisch per E-Mail, sobald die Zahlung bestätigt ist. Es gibt keine Wartezeit und keinen Techniktermin.'),
                    array('q' => 'Kann ich IPTV kaufen, ohne einen Vertrag abzuschließen?', 'a' => 'Ja. Du zahlst einmalig für die gewählte Laufzeit. Es gibt keine Mindestlaufzeit darüber hinaus und keine automatische Verlängerung — nach Ablauf passiert einfach nichts, bis du selbst verlängerst.'),
                    array('q' => 'Welche Zahlungsmethoden gibt es?', 'a' => 'Visa, Mastercard, PayPal und Bitcoin, alles über eine SSL-verschlüsselte Kasse. Deine Zahlungsdaten werden nicht bei uns gespeichert.'),
                    array('q' => 'Brauche ich neue Hardware?', 'a' => 'Nein. Der Zugang läuft auf Smart TVs, Fire TV Stick, Android TV, Apple TV, iPhone, iPad, Android-Handys, Windows, Mac, Set-Top-Boxen, Chromecast, Roku und Kodi. In den allermeisten Fällen reicht das Gerät, das schon bei dir steht.'),
                    array('q' => 'Was ist, wenn es bei mir nicht läuft?', 'a' => 'Dann bekommst du dein Geld zurück. Auf jedes Paket gilt eine Geld-zurück-Garantie von 14 Tagen. Vorher kannst du den Dienst 24 Stunden lang kostenlos und ohne Kreditkarte testen.'),
                    array('q' => 'Wie viele Geräte kann ich gleichzeitig nutzen?', 'a' => 'Das entscheidest du beim Kauf. Ein Zugang streamt auf einem Gerät gleichzeitig; du kannst beim Bestellen bis zu vier Bildschirme wählen, wenn mehrere Personen im Haushalt parallel schauen.'),
                    array('q' => 'Sind Sport und 4K im Preis enthalten?', 'a' => 'Ja. Es gibt keine Zusatzpakete. Sport, PPV-Events, Filme, Serien und 4K sind in jedem Abo enthalten — unabhängig von der gewählten Laufzeit.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-m3u-kaufen' => array(
                'keyword'   => 'IPTV M3U kaufen',
                'title'     => 'IPTV M3U kaufen',
                'seo_title' => 'IPTV M3U kaufen – M3U-Link mit EPG, sofort geliefert',
                'seo_desc'  => 'IPTV M3U kaufen und den Link sofort per E-Mail erhalten: 40.000+ Sender, EPG inklusive, läuft in VLC, IPTV Smarters, TiviMate und Kodi. 14 Tage Geld zurück.',

                'text' => array(
                    'hero_title'        => 'IPTV M3U kaufen – ein Link, jede App.',
                    'hero_title_span'   => 'Sofort geliefert.',
                    'hero_title_3'      => 'Mit voller Programmzeitschrift.',
                    'hero_subtitle'     => 'Du willst IPTV M3U kaufen und den Link in deiner gewohnten App nutzen? Du bekommst eine M3U-URL mit über 40.000 Sendern und passendem EPG — kompatibel mit VLC, IPTV Smarters, TiviMate, Kodi und jedem Player, der M3U versteht.',
                    'hero_image_alt'    => 'IPTV M3U kaufen – M3U-Playlist mit über 40.000 Sendern',
                    'vod_image_alt'     => 'Filme und Serien über die M3U-Playlist',
                    'sports_image_alt'  => 'Live-Sport über den gekauften M3U-Link',
                    'features_title'    => 'Was in der Playlist steckt, wenn du',
                    'features_title_span' => 'IPTV M3U kaufen willst',
                    'features_subtitle' => 'Eine M3U-Datei ist nur so gut wie das, was dahinter liegt. Das hier hängt an jedem Link, den wir ausliefern.',
                    'pricing_subtitle'  => 'Ein Zugang, wahlweise als M3U-Link oder über Xtream-Codes. Über 40.000 Live-Sender und mehr als 200.000 Filme und Serien.',
                    'reviews_title'     => 'Kunden, die bei uns IPTV M3U kaufen',
                    'faq_title'         => 'Fragen zum IPTV M3U kaufen',
                ),

                'lead' => 'Wer IPTV M3U kaufen möchte, sucht meist keine neue App, sondern einen Link für die, die er schon benutzt. Genau das bekommst du: eine M3U-URL, die sich in VLC, IPTV Smarters, TiviMate, GSE, Kodi oder Perfect Player einfügen lässt, mit vollständiger Programmzeitschrift und ohne Bindung an eine bestimmte Software.',

                'blocks' => array(
                    array(
                        'title' => 'Was eine M3U-Playlist eigentlich ist',
                        'text'  => array(
                            'Eine M3U-Datei ist im Kern eine Textliste. Sie enthält für jeden Sender einen Namen, ein Logo, eine Kategorie und die Adresse des Streams. Der Player liest diese Liste und baut daraus die Senderübersicht, die du auf dem Bildschirm siehst. Weil das Format uralt und offen ist, versteht es praktisch jede Abspielsoftware — das ist der Grund, warum so viele Leute lieber eine M3U kaufen als eine proprietäre App zu installieren.',
                            'Bei uns bekommst du keine statische Datei zum Herunterladen, sondern eine URL. Der Unterschied ist im Alltag entscheidend: Kommen Sender dazu oder ändert sich eine Streamadresse, aktualisiert sich deine Liste beim nächsten Öffnen von selbst. Bei einer heruntergeladenen Datei müsstest du sie jedes Mal neu einspielen.',
                        ),
                    ),
                    array(
                        'title' => 'M3U oder Xtream-Codes — was solltest du nehmen?',
                        'text'  => array(
                            'Jeder Zugang bei uns funktioniert in beiden Varianten. Du entscheidest, welche du benutzt, und kannst jederzeit wechseln.',
                        ),
                        'items' => array(
                            array(
                                'title' => 'M3U-Link',
                                'text'  => 'Eine einzige URL, die du einfügst. Maximale Kompatibilität — läuft auch in VLC und in älteren Playern, die nichts anderes können. Ideal, wenn du deine App nicht wechseln willst.',
                            ),
                            array(
                                'title' => 'Xtream-Codes (Server, Benutzer, Passwort)',
                                'text'  => 'Drei Felder statt einer langen URL. Moderne Apps wie TiviMate oder IPTV Smarters laden darüber Kategorien, Filme und Serien getrennt und zeigen die Programmzeitschrift sauberer an. Wenn deine App es anbietet, ist das die bequemere Wahl.',
                            ),
                        ),
                        'text_after' => array(
                            'Hast du bereits einen Link in einem Format und brauchst das andere, rechnet ihn der %m3u% um, ohne dass du etwas neu kaufen musst.',
                        ),
                    ),
                    array(
                        'title' => 'In welche Player der Link passt',
                        'text'  => array(
                            'Der M3U-Link ist nicht an ein Gerät gebunden. Diese Kombinationen sind die verbreitetsten — welche App auf welchem Gerät am angenehmsten läuft, steht ausführlich unter %kw:iptv-player%.',
                        ),
                        'list' => array(
                            'VLC Media Player auf Windows, Mac und Linux — Netzwerkstream öffnen, Link einfügen, fertig',
                            'IPTV Smarters Pro auf Android, iOS, Fire TV Stick und Smart TV',
                            'TiviMate auf Android TV und Fire TV — die komfortabelste Programmzeitschrift',
                            'GSE Smart IPTV auf iPhone und iPad',
                            'Kodi mit dem PVR-Simple-Client',
                            'Perfect Player, Smart STB und MAG-Boxen',
                        ),
                    ),
                    array(
                        'title' => 'EPG: die Programmzeitschrift gehört dazu',
                        'text'  => array(
                            'Ein M3U-Link ohne EPG ist eine Senderliste ohne Programm — du siehst, dass ein Sender läuft, aber nicht was. Zu jedem Zugang gehört deshalb eine EPG-Quelle, die deine App zusammen mit der Playlist lädt. Damit bekommst du eine echte Programmübersicht über mehrere Tage, dazu sieben Tage Catch-up, mit denen du eine verpasste Sendung nachträglich starten kannst.',
                            'Das ist auch der Punkt, an dem billige Playlists auffallen: Die Sender laufen, aber die Zeitschrift bleibt leer oder passt zeitlich nicht. Wenn du Angebote vergleichst, ist das ein guter Prüfstein — mehr dazu unter %kw:iptv-anbieter-vergleich%.',
                        ),
                    ),
                    array(
                        'title' => 'So läuft der Kauf ab',
                        'text'  => array(
                            'Du wählst eine Laufzeit, bezahlst über die SSL-Kasse und bekommst deine Zugangsdaten samt M3U-URL per E-Mail — üblicherweise in unter zwei Minuten. Danach kopierst du den Link einmal in deine App, und die Senderliste baut sich auf. Die %guide% führt dich Schritt für Schritt durch die gängigen Apps.',
                            'Wenn du erst ausprobieren willst, ob dein Player mit unserer Liste zurechtkommt: Es gibt einen 24-Stunden-Test ohne Kreditkarte. Technische Hintergründe zum Übertragungsweg beschreibt der %wiki%.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Bekomme ich eine Datei oder einen Link?', 'a' => 'Einen Link. Das ist der Unterschied zu einer heruntergeladenen M3U-Datei: Die URL bleibt gleich, der Inhalt aktualisiert sich automatisch, wenn Sender dazukommen oder sich Adressen ändern.'),
                    array('q' => 'Funktioniert der Link in VLC?', 'a' => 'Ja. In VLC gehst du auf „Medien → Netzwerkstream öffnen“, fügst die URL ein und öffnest sie. VLC zeigt allerdings kein EPG an — für die Programmzeitschrift ist eine IPTV-App wie TiviMate oder IPTV Smarters die bessere Wahl.'),
                    array('q' => 'Kann ich denselben Link auf mehreren Geräten benutzen?', 'a' => 'Der Link lässt sich auf beliebig vielen Geräten hinterlegen, aber gleichzeitig streamen kannst du nur so viele Bildschirme, wie du beim Kauf gewählt hast. Wer parallel in mehreren Zimmern schaut, nimmt zwei oder mehr Bildschirme.'),
                    array('q' => 'Ist das EPG enthalten?', 'a' => 'Ja, eine EPG-Quelle gehört zu jedem Zugang. Sie liefert die Programmübersicht für mehrere Tage sowie sieben Tage Catch-up.'),
                    array('q' => 'Was, wenn meine App lieber Xtream-Codes will?', 'a' => 'Dann nimm die. Zu jedem Zugang gehören sowohl der M3U-Link als auch Serveradresse, Benutzername und Passwort für Xtream-Codes. Du kannst jederzeit zwischen beiden wechseln.'),
                    array('q' => 'Wie lange ist der Link gültig?', 'a' => 'So lange wie deine Laufzeit. Verlängerst du rechtzeitig, bleiben Link und Zugangsdaten identisch und du musst in deiner App nichts ändern — siehe dazu die Seite zum Verlängern.'),
                    array('q' => 'Läuft die Playlist auch auf einer MAG-Box?', 'a' => 'Ja. MAG-Boxen und Smart-STB-Geräte werden unterstützt; dort trägst du das Portal statt der M3U-URL ein. Der Support hilft beim Einrichten, wenn es klemmt.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-anbieter-vergleich' => array(
                'keyword'   => 'IPTV Anbieter Vergleich',
                'title'     => 'IPTV Anbieter Vergleich',
                'seo_title' => 'IPTV Anbieter Vergleich 2026 – 9 Kriterien, die zählen',
                'seo_desc'  => 'IPTV Anbieter Vergleich mit den neun Kriterien, die im Alltag entscheiden: Senderzahl, Stabilität, EPG, 4K, Support, Test, Rückgabe und Preis pro Monat.',

                'text' => array(
                    'hero_title'        => 'IPTV Anbieter Vergleich – neun Punkte, die zählen.',
                    'hero_title_span'   => 'Ohne Marketing.',
                    'hero_title_3'      => 'Prüf uns an denselben Kriterien.',
                    'hero_subtitle'     => 'Jeder IPTV Anbieter Vergleich beginnt bei der Senderzahl und hört dort meistens auch auf. Die Punkte, die im Alltag wirklich entscheiden, sind andere — hier stehen sie, und du kannst uns an jedem einzelnen messen.',
                    'hero_image_alt'    => 'IPTV Anbieter Vergleich – Senderauswahl und Bildqualität',
                    'vod_image_alt'     => 'Filme und Serien im IPTV Anbieter Vergleich',
                    'sports_image_alt'  => 'Sportangebot im IPTV Anbieter Vergleich',
                    'features_title'    => 'Wie wir im',
                    'features_title_span' => 'IPTV Anbieter Vergleich abschneiden',
                    'features_subtitle' => 'Das sind die Punkte, an denen sich Angebote wirklich unterscheiden — und was bei uns dahintersteht.',
                    'pricing_subtitle'  => 'Was ein Zugang bei uns kostet — zum Vergleichen mit dem, was du heute zahlst.',
                    'reviews_title'     => 'Was Kunden nach dem Vergleich sagen',
                    'faq_title'         => 'Fragen zum IPTV Anbieter Vergleich',
                ),

                'lead' => 'Ein ehrlicher IPTV Anbieter Vergleich lässt sich nicht in einer Tabelle mit Häkchen führen, denn die Zahlen, mit denen geworben wird, sind die am leichtesten zu fälschenden. Diese Seite listet die neun Kriterien, die im Alltag tatsächlich den Unterschied machen — und sagt bei jedem, wie du es vor dem Kauf selbst überprüfst.',

                'blocks' => array(
                    array(
                        'title' => 'Die neun Kriterien, an denen du jeden Anbieter messen kannst',
                        'text'  => array(
                            'Nimm diese Liste mit zu jedem Angebot, das du dir ansiehst — auch zu unserem.',
                        ),
                        'items' => array(
                            array(
                                'title' => '1. Stabilität zur Primetime',
                                'text'  => 'Nicht die Senderzahl entscheidet, sondern ob der Stream am Samstag um 15:30 Uhr oder beim Champions-League-Abend hält. Genau dann ist die Serverlast am höchsten. Teste zu diesen Zeiten, nicht am Dienstagvormittag.',
                            ),
                            array(
                                'title' => '2. Ein echter Test vor dem Kauf',
                                'text'  => 'Wer keinen Test anbietet, will nicht, dass du vorher prüfst. Bei uns gibt es 24 Stunden Test ohne Kreditkarte — genug, um einen Spieltag und einen Filmabend abzudecken.',
                            ),
                            array(
                                'title' => '3. Die Rückgaberegel',
                                'text'  => 'Lies sie, bevor du zahlst. „Geld zurück“ mit einer Liste von Ausnahmen ist keine Garantie. Bei uns sind es 14 Tage ohne Begründung.',
                            ),
                            array(
                                'title' => '4. Vollständiges EPG',
                                'text'  => 'Eine Senderliste ohne Programmzeitschrift ist halbfertig. Prüfe im Test, ob das EPG für mehrere Tage gefüllt ist und zeitlich stimmt — das ist der Punkt, an dem billige Playlists reihenweise durchfallen.',
                            ),
                            array(
                                'title' => '5. Tatsächliche Bildqualität',
                                'text'  => 'Fast jeder wirbt mit 4K. Sieh dir an, wie viele Sender wirklich in 4K oder FHD laufen und nicht nur so heißen. Zoom auf einem großen Fernseher zeigt es in Sekunden.',
                            ),
                            array(
                                'title' => '6. Erreichbarer Support',
                                'text'  => 'Schreib dem Support eine konkrete technische Frage, bevor du kaufst. Wie schnell und wie brauchbar die Antwort ist, sagt dir alles über den Tag, an dem etwas nicht läuft. Unser Support ist über die %contact% rund um die Uhr erreichbar.',
                            ),
                            array(
                                'title' => '7. Zahlungswege',
                                'text'  => 'Karte, PayPal und Bitcoin über eine verschlüsselte Kasse sind Standard. Wer ausschließlich Überweisung an ein Privatkonto oder Geschenkkarten akzeptiert, ist kein Anbieter, sondern ein Risiko.',
                            ),
                            array(
                                'title' => '8. Keine automatische Verlängerung',
                                'text'  => 'Ein Zugang, der sich still verlängert, bindet dich über den Umweg der Bequemlichkeit. Bei uns läuft ein Paket einfach aus; %kw:iptv-verlaengern% ist eine bewusste Entscheidung.',
                            ),
                            array(
                                'title' => '9. Preis pro Monat, nicht Preis pro Angebot',
                                'text'  => 'Rechne jedes Angebot auf den Monat herunter, sonst vergleichst du Laufzeiten statt Preise. Bei zwölf Monaten landen wir bei rund 5,83 € im Monat.',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'IPTV gegen Kabel und Streaming-Abos gerechnet',
                        'text'  => array(
                            'Der zweite Vergleich, den die meisten anstellen, ist nicht Anbieter gegen Anbieter, sondern IPTV gegen das, was sie heute bezahlen. Ein Haushalt mit Netflix, Disney+, einem Sportpaket und gelegentlichen PPV-Events liegt schnell bei über 80 € im Monat — und hat für jedes Abo eine eigene App, eine eigene Rechnung und eine eigene Kündigungsfrist.',
                            'Ein Zugang bündelt das in einer Oberfläche und einem Preis. Der Unterschied über ein Jahr liegt im vierstelligen Bereich; die Tabelle auf der %home% rechnet es Position für Position vor.',
                        ),
                    ),
                    array(
                        'title' => 'Warnzeichen, die einen Anbieter sofort disqualifizieren',
                        'text'  => array(
                            'Manche Dinge muss man nicht abwägen. Wenn eines davon zutrifft, ist der Vergleich beendet.',
                        ),
                        'list' => array(
                            'Kein Testzugang und keine Rückgabemöglichkeit',
                            'Keine Kontaktadresse außer einem anonymen Telegram-Konto',
                            'Zahlung ausschließlich per Überweisung an eine Privatperson oder per Geschenkkarte',
                            'Lebenslange Zugänge zum Einmalpreis — Server kosten dauerhaft Geld, das rechnet sich für niemanden',
                            'Senderzahlen jenseits von 100.000, die sich beim Nachzählen als Dubletten entpuppen',
                            'Kein EPG oder ein EPG, das nur den aktuellen Tag kennt',
                        ),
                    ),
                    array(
                        'title' => 'Was du im Test konkret prüfen solltest',
                        'text'  => array(
                            'Vierundzwanzig Stunden reichen, wenn du sie gezielt nutzt. Schalte einen Sender zur Primetime ein und lass ihn zwanzig Minuten laufen — Aussetzer zeigen sich selten sofort. Wechsle mehrfach schnell hintereinander den Sender und sieh, wie lange der Bildaufbau dauert. Öffne die Programmzeitschrift und scrolle zwei Tage nach vorn. Starte einen Film aus der Mediathek und spule mittendrin. Und ruf einen Sender der %kw:iptv-bundesliga% genau zum Anpfiff auf, nicht in der Halbzeit.',
                            'Wenn das alles läuft, hast du mehr über den Anbieter gelernt als aus jeder Vergleichstabelle im Netz. Was IPTV technisch von Kabel und Satellit unterscheidet, erklärt der %wiki%.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Woran erkenne ich einen unseriösen IPTV Anbieter?', 'a' => 'An drei Dingen: kein Testzugang, keine Rückgabemöglichkeit und keine erreichbare Kontaktadresse. Kommt dazu, dass nur per Überweisung an eine Privatperson oder per Geschenkkarte gezahlt werden kann, solltest du das Angebot ohne weitere Prüfung übergehen.'),
                    array('q' => 'Sind 100.000 Sender besser als 40.000?', 'a' => 'Fast nie. Sehr hohe Zahlen entstehen meist durch Dubletten — derselbe Sender in mehreren Qualitätsstufen und Sprachen, jeweils einzeln gezählt. Entscheidend ist, ob die Sender dabei sind, die du tatsächlich schaust, und ob sie stabil laufen.'),
                    array('q' => 'Wie vergleiche ich Preise fair?', 'a' => 'Rechne jedes Angebot auf den Preis pro Monat herunter und zähle dazu, was du bei anderen Anbietern extra bezahlen müsstest — Sportpaket, 4K-Aufschlag, PPV-Events. Erst dann sind zwei Angebote wirklich vergleichbar.'),
                    array('q' => 'Was sagt die Senderzahl über die Qualität aus?', 'a' => 'Wenig. Aussagekräftiger sind Bildqualität, Stabilität zur Primetime und ein vollständiges EPG. Diese drei Punkte kannst du im Testzugang innerhalb eines Abends selbst überprüfen.'),
                    array('q' => 'Lohnt sich der Wechsel von Kabel zu IPTV?', 'a' => 'Rechnerisch fast immer: Ein Haushalt mit mehreren Streaming-Abos und einem Sportpaket zahlt schnell über 80 € im Monat. Ob es sich für dich lohnt, hängt davon ab, ob die Sender dabei sind, die du wirklich schaust — genau dafür gibt es den kostenlosen Test.'),
                    array('q' => 'Kann ich mehrere Anbieter gleichzeitig testen?', 'a' => 'Ja, und das ist sogar die beste Methode. Lege zwei Testzugänge parallel auf dasselbe Gerät und schalte am selben Abend zwischen ihnen hin und her. Unterschiede in Bildaufbau und Stabilität siehst du dann sofort.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-bundesliga' => array(
                'keyword'   => 'IPTV Bundesliga',
                'title'     => 'IPTV Bundesliga',
                'seo_title' => 'IPTV Bundesliga live – alle 306 Spiele, Konferenz & 4K',
                'seo_desc'  => 'IPTV Bundesliga live schauen: alle Spiele inklusive Konferenz, 2. Bundesliga und DFB-Pokal in HD und 4K. Sofort aktiviert, ohne Vertrag, ab 5,83 € im Monat.',
                'wiki'      => array('https://de.wikipedia.org/wiki/Fu%C3%9Fball-Bundesliga', 'Wikipedia-Artikel zur Bundesliga'),

                'text' => array(
                    'hero_title'        => 'IPTV Bundesliga – jeder Spieltag, jede Konferenz.',
                    'hero_title_span'   => 'Ein Zugang.',
                    'hero_title_3'      => 'Kein zweites Sportpaket.',
                    'hero_subtitle'     => 'Mit IPTV Bundesliga schauen heißt: alle Partien von Freitagabend bis Sonntagnachmittag, dazu Konferenz, 2. Bundesliga und DFB-Pokal — in HD und 4K, ohne zusätzliches Sportabo.',
                    'hero_image_alt'    => 'IPTV Bundesliga live in 4K auf dem Fernseher',
                    'vod_image_alt'     => 'Filme und Serien neben der IPTV Bundesliga',
                    'sports_image_alt'  => 'IPTV Bundesliga und weitere Ligen live',
                    'features_title'    => 'Was zur',
                    'features_title_span' => 'IPTV Bundesliga dazugehört',
                    'features_subtitle' => 'Nicht nur die 90 Minuten: Konferenz, Wiederholungen, Analysen und jede andere große Liga sind im selben Zugang.',
                    'pricing_subtitle'  => 'Ein Preis für die komplette Saison und alles andere dazu — über 40.000 Sender und 200.000+ Filme.',
                    'reviews_title'     => 'Was Bundesliga-Zuschauer sagen',
                    'faq_title'         => 'Fragen zur IPTV Bundesliga',
                ),

                'lead' => 'Eine Bundesliga-Saison hat 306 Spiele, verteilt auf Freitagabend, Samstagnachmittag, Samstagabend, Sonntagmittag und Sonntagnachmittag — und beim klassischen Anbieter auf mehrere Abos. Mit IPTV Bundesliga zu schauen bedeutet, dass alle Anstoßzeiten in derselben Senderliste liegen, samt Konferenz, 2. Liga und Pokal.',

                'blocks' => array(
                    array(
                        'title' => 'Alle Anstoßzeiten in einer Senderliste',
                        'text'  => array(
                            'Der deutsche Spielplan ist über das Wochenende verteilt, und wer alles sehen will, jongliert normalerweise mit zwei Diensten und zwei Rechnungen. In der Senderliste liegen die Übertragungen aller Anstoßzeiten nebeneinander — du wechselst zwischen Einzelspiel und Konferenz wie zwischen zwei Fernsehsendern, nicht wie zwischen zwei Apps.',
                            'Dazu kommt die Programmzeitschrift: Der EPG zeigt dir den kompletten Spieltag im Voraus, sodass du am Samstagmorgen siehst, was wann läuft, statt danach zu suchen.',
                        ),
                        'list' => array(
                            'Freitag 20:30, Samstag 15:30 und 18:30, Sonntag 15:30 und 17:30',
                            'Die Konferenz am Samstagnachmittag',
                            '2. Bundesliga inklusive Freitag- und Sonntagsspielen',
                            'DFB-Pokal von der ersten Runde bis zum Finale',
                            'Supercup und Relegation',
                            'Zusammenfassungen, Analysesendungen und Wiederholungen',
                        ),
                    ),
                    array(
                        'title' => 'Nicht nur die Bundesliga',
                        'text'  => array(
                            'Wer den Samstagnachmittag schaut, schaut selten nur den Samstagnachmittag. Im selben Zugang liegen die Champions League und Europa League, die Premier League, La Liga, Serie A und Ligue 1 — ein Überblick über das gesamte Fußballangebot steht unter %kw:iptv-fussball%.',
                            'Über den Fußball hinaus sind NFL, NBA, MLB, Formel 1, Handball, Tennis und Motorsport dabei, dazu PPV-Events aus UFC und Boxen, die sonst einzeln abgerechnet werden. Wer türkische Sportsender sucht, findet unter %kw:iptv-s-sport-plus% die Details.',
                        ),
                    ),
                    array(
                        'title' => 'Bildqualität und Verzögerung',
                        'text'  => array(
                            'Bei Fußball zählen zwei Dinge, die bei Filmen niemanden interessieren: wie scharf die Spielernamen auf dem Trikot sind und wie viele Sekunden hinter dem Livegeschehen du liegst. Das erste löst 4K beziehungsweise saubere FHD-Streams mit hoher Bitrate — auf einem großen Fernseher ist der Unterschied bei Kameraschwenks sofort sichtbar.',
                            'Das zweite ist eine Frage der Serverqualität. Jeder Streamingweg hat eine Verzögerung von einigen Sekunden gegenüber dem Stadion; entscheidend ist, dass sie stabil bleibt und der Stream beim Torjubel nicht neu puffert. Unsere Anti-Buffer-Stabilisierung ist genau für diese Momente gebaut — und das ist auch der Test, den du im 24-Stunden-Zugang machen solltest: Schalte um 15:30 Uhr ein, nicht am Dienstagvormittag.',
                            'Empfohlen sind 15–25 Mbit/s. Am Fernseher ist ein Netzwerkkabel dem WLAN vorzuziehen, wenn der Router weit entfernt steht.',
                        ),
                    ),
                    array(
                        'title' => 'Was du dafür brauchst',
                        'text'  => array(
                            'Kein neues Gerät. Ein Fire TV Stick, ein Smart TV, eine Android-Box oder ein Apple TV reichen; die App installierst du einmal und trägst die Zugangsdaten ein. Welche App auf welchem Gerät die beste Programmzeitschrift liefert, steht unter %kw:iptv-player%, die Einrichtung Schritt für Schritt in der %guide%.',
                            'Wenn du lieber mit einer Playlist arbeitest, funktioniert der Zugang auch als M3U-Link — siehe %kw:iptv-m3u-kaufen%. Wie du zum Saisonende weitermachst, ohne deine Zugangsdaten zu ändern, steht unter %kw:iptv-verlaengern%.',
                            'Hintergründe zur Liga selbst, Spielplan und Historie liefert der %wiki%.',
                        ),
                    ),
                    array(
                        'title' => 'Was der Spieltag sonst noch kostet',
                        'text'  => array(
                            'Rechne einmal zusammen, was ein Bundesliga-Wochenende beim klassischen Anbieter kostet: ein Abo für die Samstagsspiele, ein zweites für Freitag und Sonntag, ein drittes, wenn auch die Champions League dazukommen soll. Bei drei Abos sind 60 bis 80 € im Monat schnell erreicht, und für PPV-Events zahlst du weiterhin einzeln.',
                            'Ein Zugang bei uns deckt dieselben Anstoßzeiten ab und kostet beim %plan12% rund 5,83 € im Monat — inklusive der internationalen Ligen, der 200.000 Filme und Serien und der PPV-Events. Wer nur die Rückrunde überbrücken will, nimmt den %plan6%; für ein einzelnes Turnier reicht der %plan1%.',
                            'Der ehrliche Zusatz dazu: Rechne nicht nur den Preis, sondern auch den Aufwand. Ein Zugang bedeutet eine App, eine Fernbedienung und eine Programmzeitschrift statt drei Oberflächen, zwischen denen du zum Anpfiff hin- und herspringst.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Sind wirklich alle Bundesliga-Spiele dabei?', 'a' => 'Ja, alle Anstoßzeiten von Freitagabend bis Sonntagnachmittag, dazu die Samstagskonferenz. Ergänzend liegen 2. Bundesliga, DFB-Pokal, Supercup und Relegation in derselben Senderliste.'),
                    array('q' => 'Gibt es die Konferenz?', 'a' => 'Ja. Die Konferenzschaltung am Samstagnachmittag ist enthalten, und du kannst jederzeit zwischen Konferenz und Einzelspiel wechseln — technisch ist das nur ein Senderwechsel.'),
                    array('q' => 'In welcher Qualität wird übertragen?', 'a' => 'In HD, FHD und je nach Sender in 4K. Für Fußball ist die Bitrate wichtiger als das Label auf dem Sender; deshalb solltest du im Testzugang selbst prüfen, wie scharf ein Kameraschwenk auf deinem Fernseher aussieht.'),
                    array('q' => 'Wie groß ist die Verzögerung gegenüber dem Stadion?', 'a' => 'Wie bei jedem Streamingweg liegen einige Sekunden zwischen Stadion und Bildschirm. Wichtiger als die absolute Zahl ist, dass sie stabil bleibt — genau dafür ist die Stream-Stabilisierung gebaut.'),
                    array('q' => 'Welche Internetgeschwindigkeit brauche ich?', 'a' => '15–25 Mbit/s reichen für flüssiges HD und 4K. Wenn der Fernseher weit vom Router entfernt steht, ist ein Netzwerkkabel oder ein Powerline-Adapter dem WLAN vorzuziehen.'),
                    array('q' => 'Brauche ich zusätzlich ein Sportpaket?', 'a' => 'Nein. Sport ist in jedem Abo enthalten, unabhängig von der Laufzeit. Es gibt keinen Aufpreis für Bundesliga, Champions League oder PPV-Events.'),
                    array('q' => 'Kann ich verpasste Spiele nachschauen?', 'a' => 'Ja, über die Catch-up-Funktion kannst du bis zu sieben Tage zurückspulen und ein Spiel oder eine Zusammenfassung nachträglich starten.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-fussball' => array(
                'keyword'   => 'IPTV Fußball',
                'title'     => 'IPTV Fußball',
                'seo_title' => 'IPTV Fußball live – Bundesliga, CL, Premier League in 4K',
                'seo_desc'  => 'IPTV Fußball live: Bundesliga, Champions League, Premier League, La Liga, Serie A und Nationalmannschaft in einem Zugang. Ohne Vertrag, sofort freigeschaltet.',

                'text' => array(
                    'hero_title'        => 'IPTV Fußball – jede große Liga, ein Zugang.',
                    'hero_title_span'   => 'Kein Abo-Puzzle.',
                    'hero_title_3'      => 'Von der Bundesliga bis zur WM.',
                    'hero_subtitle'     => 'IPTV Fußball heißt: Bundesliga, Champions League, Premier League, La Liga, Serie A, Ligue 1 und die Nationalmannschaft in einer Senderliste — statt vier Abos mit vier Rechnungen.',
                    'hero_image_alt'    => 'IPTV Fußball live in 4K auf dem großen Bildschirm',
                    'vod_image_alt'     => 'Filme und Serien neben dem IPTV Fußball',
                    'sports_image_alt'  => 'IPTV Fußball – Ligen und Turniere live',
                    'features_title'    => 'Was',
                    'features_title_span' => 'IPTV Fußball abdeckt',
                    'features_subtitle' => 'Von der Kreisliga der großen Ligen bis zum Finale: Diese Wettbewerbe liegen im selben Zugang.',
                    'pricing_subtitle'  => 'Eine Rechnung für alle Ligen — und dazu über 200.000 Filme und Serien.',
                    'reviews_title'     => 'Was Fußballfans über uns sagen',
                    'faq_title'         => 'Fragen zu IPTV Fußball',
                ),

                'lead' => 'Wer in Deutschland Fußball vollständig sehen will, braucht normalerweise drei bis vier Abos: eines für die Bundesliga, eines für die Champions League, eines für die Premier League und ein weiteres für alles andere. IPTV Fußball löst das anders — alle Wettbewerbe liegen in einer Senderliste, mit einer Programmzeitschrift und einer Rechnung.',

                'blocks' => array(
                    array(
                        'title' => 'Welche Wettbewerbe drin sind',
                        'text'  => array(
                            'Die Senderliste ist nicht auf Deutschland beschränkt. Weil Sender aus 198 Ländern enthalten sind, findest du zu den großen Spielen fast immer mehrere Übertragungen — die deutsche, die englische und oft die des Heimatlandes der beteiligten Mannschaft.',
                        ),
                        'list' => array(
                            'Bundesliga und 2. Bundesliga inklusive Konferenz — Details unter %kw:iptv-bundesliga%',
                            'DFB-Pokal, Supercup und Relegation',
                            'Champions League, Europa League und Conference League',
                            'Premier League, FA Cup und EFL Cup',
                            'La Liga, Copa del Rey, Serie A, Coppa Italia und Ligue 1',
                            'Eredivisie, Primeira Liga, Süper Lig und die Schweizer Super League',
                            'Nationalmannschaft: WM, EM, Nations League und Freundschaftsspiele',
                            'Frauenfußball, U21 und die großen Jugendturniere',
                        ),
                    ),
                    array(
                        'title' => 'Mehrere Kommentare zur selben Partie',
                        'text'  => array(
                            'Das ist der Unterschied, den man erst nach ein paar Wochen zu schätzen weiß: Bei einem Spiel wie Real gegen Bayern hast du nicht eine Übertragung, sondern mehrere. Du kannst den deutschen Kommentar nehmen, den spanischen oder den englischen — je nachdem, worauf du Lust hast oder welche Analyse dich interessiert.',
                            'Für Zuschauer mit türkischem Hintergrund gilt dasselbe für die Süper Lig und die Sender, die internationale Ligen auf Türkisch übertragen; die stehen unter %kw:iptv-s-sport-plus%.',
                        ),
                    ),
                    array(
                        'title' => 'Was du zum Anpfiff brauchst',
                        'text'  => array(
                            'Fußball ist der Härtetest für jeden Streamingdienst, weil alle gleichzeitig einschalten. Drei Dinge entscheiden, ob es funktioniert.',
                        ),
                        'items' => array(
                            array(
                                'title' => 'Eine stabile Leitung',
                                'text'  => '15–25 Mbit/s reichen für FHD und 4K. Wichtiger als die reine Bandbreite ist die Stabilität: Ein Netzwerkkabel zum Fernseher ist einem schwachen WLAN-Signal immer vorzuziehen.',
                            ),
                            array(
                                'title' => 'Eine App, die schnell umschaltet',
                                'text'  => 'Beim Sport wechselst du oft zwischen Konferenz und Einzelspiel. Apps wie TiviMate bauen das Bild spürbar schneller auf als generische Player — welche auf deinem Gerät läuft, steht unter %kw:iptv-player%.',
                            ),
                            array(
                                'title' => 'Server, die den Andrang aushalten',
                                'text'  => 'Das ist der Punkt, den du nicht selbst beeinflussen kannst — und deshalb der, den du vor dem Kauf prüfen solltest. Nimm den 24-Stunden-Test und schalte zu einer Zeit ein, zu der halb Deutschland zuschaut.',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'Was es kostet — und was du heute zahlst',
                        'text'  => array(
                            'Ein Haushalt, der Bundesliga, Champions League und Premier League sehen will, kommt mit den üblichen Abos schnell auf 60 bis 80 € im Monat, PPV-Events noch nicht eingerechnet. Ein Zugang bei uns liegt beim %plan12% bei rund 5,83 € im Monat und deckt dieselben Wettbewerbe ab, dazu über 200.000 Filme und Serien.',
                            'Wer nur eine Saisonhälfte überbrücken will, nimmt den %plan6%; für ein einzelnes Turnier reicht der %plan1%. Wie du Angebote fair gegeneinander rechnest, steht unter %kw:iptv-anbieter-vergleich%. Wie die Übertragung technisch abläuft, beschreibt der %wiki%.',
                        ),
                    ),
                    array(
                        'title' => 'Der Fußballkalender über das ganze Jahr',
                        'text'  => array(
                            'Fußball hat keine Saisonpause mehr, sondern nur noch Verschiebungen. Von August bis Mai laufen die nationalen Ligen und die europäischen Wettbewerbe parallel; im Sommer übernehmen die Turniere der Nationalmannschaften, dazu Vorbereitungsspiele und in geraden Jahren EM oder WM. Wer das vollständig sehen will, braucht einen Zugang, der nicht nach Wettbewerb abgerechnet wird.',
                            'Praktisch heißt das: Im Herbst schaust du Bundesliga und Champions League, im Winter kommen Pokalrunden und die Winterpause der einen bei laufender Saison der anderen Ligen dazu, im Frühjahr die entscheidenden Wochen, und im Sommer das Turnier. Ein Jahreszugang deckt diesen ganzen Bogen ab, ohne dass du zwischendurch etwas dazubuchst oder kündigst.',
                            'Wenn du erst am Ende der Saison einsteigst, ist der kurze Zugang die vernünftigere Wahl — verlängern kannst du jederzeit, ohne deine Zugangsdaten zu ändern (%kw:iptv-verlaengern%).',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Sind Champions League und Bundesliga zusammen enthalten?', 'a' => 'Ja, beide liegen im selben Zugang, ohne Aufpreis und ohne zweites Abo. Dasselbe gilt für Europa League, Premier League, La Liga, Serie A und Ligue 1.'),
                    array('q' => 'Kann ich zwischen deutschem und englischem Kommentar wählen?', 'a' => 'Bei den großen Partien meistens ja. Weil Sender aus 198 Ländern enthalten sind, gibt es zu einem Spitzenspiel häufig mehrere Übertragungen in verschiedenen Sprachen.'),
                    array('q' => 'Läuft das auch bei WM und EM?', 'a' => 'Ja. Turniere der Nationalmannschaften sind enthalten, inklusive Nations League und Freundschaftsspielen. Bei Turnieren stehen zusätzlich die Übertragungen der beteiligten Länder zur Verfügung.'),
                    array('q' => 'Wie viele Geräte kann ich gleichzeitig nutzen?', 'a' => 'So viele Bildschirme, wie du beim Kauf gewählt hast — von einem bis vier. Praktisch, wenn im Wohnzimmer die Konferenz und im Nebenzimmer ein Einzelspiel läuft.'),
                    array('q' => 'Was passiert bei Überlastung zur Anstoßzeit?', 'a' => 'Genau dafür ist die Stream-Stabilisierung da, die die Bitrate dynamisch anpasst statt neu zu puffern. Ob es bei dir funktioniert, prüfst du am besten im kostenlosen 24-Stunden-Test zu einer echten Anstoßzeit.'),
                    array('q' => 'Kann ich Spiele nachträglich sehen?', 'a' => 'Ja, über Catch-up bis zu sieben Tage rückwirkend. Zusammenfassungen und Analysesendungen liegen zusätzlich in der Mediathek.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-s-sport-plus' => array(
                'keyword'   => 'IPTV S Sport Plus',
                'title'     => 'IPTV S Sport Plus',
                'seo_title' => 'IPTV S Sport Plus – türkische Sportsender live in HD',
                'seo_desc'  => 'IPTV S Sport Plus und weitere türkische Sportsender live in HD: Süper Lig, internationale Ligen, Basketball und Motorsport. Sofort aktiviert, ohne Vertrag.',

                'text' => array(
                    'hero_title'        => 'IPTV S Sport Plus – türkischer Sport ohne Umwege.',
                    'hero_title_span'   => 'Live und in HD.',
                    'hero_title_3'      => 'Mit deutschen Sendern dazu.',
                    'hero_subtitle'     => 'IPTV S Sport Plus bedeutet: türkische Sportsender live in HD, dazu die Süper Lig, internationale Ligen und der komplette deutsche Sender-Katalog — in einem Zugang, ohne Vertrag.',
                    'hero_image_alt'    => 'IPTV S Sport Plus – türkische Sportsender live',
                    'vod_image_alt'     => 'Türkische Filme und Serien neben IPTV S Sport Plus',
                    'sports_image_alt'  => 'IPTV S Sport Plus und weitere Sportsender',
                    'features_title'    => 'Was neben',
                    'features_title_span' => 'IPTV S Sport Plus dabei ist',
                    'features_subtitle' => 'Türkische Sender allein reichen selten. Das hier liegt im selben Zugang.',
                    'pricing_subtitle'  => 'Türkische und deutsche Sender in einem Paket — über 40.000 Kanäle aus 198 Ländern.',
                    'reviews_title'     => 'Was Zuschauer türkischer Sender sagen',
                    'faq_title'         => 'Fragen zu IPTV S Sport Plus',
                ),

                'lead' => 'Wer in Deutschland türkischen Sport verfolgt, kennt das Problem: Die Sender sind über der Türkei frei zu empfangen, hier aber nur über Umwege oder gar nicht. IPTV S Sport Plus löst das über das Internet — die türkischen Sportsender liegen in derselben Senderliste wie die deutschen, mit Programmzeitschrift und ohne Satellitenschüssel.',

                'blocks' => array(
                    array(
                        'title' => 'Was auf den türkischen Sportsendern läuft',
                        'text'  => array(
                            'S Sport und S Sport Plus gehören zu den Sendern, über die in der Türkei ein großer Teil des internationalen Sports übertragen wird. Je nach aktueller Rechtelage stehen dort europäische Ligen, Basketball, Motorsport, Tennis und Kampfsport im Programm — teilweise Wettbewerbe, die in Deutschland auf ganz andere Anbieter verteilt sind.',
                            'Dazu kommen die übrigen türkischen Sportsender sowie die großen Free-TV-Sender aus der Türkei, sodass du Sport, Nachrichten und Unterhaltung nicht auf verschiedene Dienste aufteilen musst.',
                        ),
                        'list' => array(
                            'S Sport und S Sport Plus in HD, soweit verfügbar',
                            'Süper Lig und türkischer Pokal',
                            'Internationale Fußballligen mit türkischem Kommentar',
                            'Basketball: türkische Liga und europäische Wettbewerbe',
                            'Motorsport, Tennis und Kampfsport',
                            'Türkische Free-TV-Sender für Nachrichten, Serien und Unterhaltung',
                        ),
                    ),
                    array(
                        'title' => 'Türkisch und deutsch in einer Senderliste',
                        'text'  => array(
                            'Das ist der eigentliche Vorteil gegenüber einem reinen Türkei-Paket: Du musst dich nicht entscheiden. In derselben Liste liegen die deutschen Free-TV- und Pay-TV-Sender, die %kw:iptv-bundesliga% und die europäischen Wettbewerbe — ein Überblick steht unter %kw:iptv-fussball%.',
                            'Für Haushalte, in denen unterschiedliche Sprachen geschaut werden, ist das der praktische Punkt: Ein Zugang, zwei oder mehr Bildschirme, jeder schaut in seiner Sprache. Untertitel gibt es in vielen Sprachen dazu.',
                        ),
                    ),
                    array(
                        'title' => 'Warum es ohne Schüssel funktioniert',
                        'text'  => array(
                            'Türksat-Empfang bedeutet eine zweite Satellitenschüssel, eine Ausrichtung auf 42° Ost und in Mietwohnungen oft eine Diskussion mit dem Vermieter. Über das Internet entfällt das komplett: Du brauchst nur deine bestehende Leitung und ein Gerät, das schon bei dir steht — Smart TV, Fire TV Stick, Android-Box oder Handy.',
                            'Empfohlen sind 15–25 Mbit/s für flüssiges HD. Welche App auf welchem Gerät am besten läuft, steht unter %kw:iptv-player%; die Einrichtung Schritt für Schritt beschreibt die %guide%. Wie die Übertragung technisch funktioniert, erklärt der %wiki%.',
                        ),
                    ),
                    array(
                        'title' => 'Verfügbarkeit ehrlich betrachtet',
                        'text'  => array(
                            'Senderlisten sind nichts Statisches. Übertragungsrechte wechseln, Sender werden umbenannt, Wettbewerbe wandern zu anderen Anbietern — das gilt für türkische Sender genauso wie für deutsche. Wer dir eine bestimmte Liga für die nächsten Jahre garantiert, verspricht etwas, das niemand halten kann.',
                            'Deshalb der ehrliche Rat: Nimm den kostenlosen 24-Stunden-Test und sieh nach, ob die Sender drin sind, auf die es dir ankommt — heute, nicht laut Werbetext. Bleibt etwas unklar, frag vorher über die %contact% nach. Kriterien für die Prüfung stehen unter %kw:iptv-anbieter-vergleich%.',
                        ),
                    ),
                    array(
                        'title' => 'Einrichtung für den türkischen Sender-Katalog',
                        'text'  => array(
                            'Technisch unterscheidet sich nichts von einem rein deutschen Zugang: Du bekommst nach dem Kauf Zugangsdaten per E-Mail, installierst eine App und trägst sie einmal ein. Der Unterschied liegt nur darin, wie du dir die Senderliste danach zurechtlegst.',
                            'Weil der Katalog Sender aus 198 Ländern enthält, ist die ungeordnete Liste lang. Lohnenswert ist deshalb, gleich am Anfang zwei Dinge zu tun: die türkischen Sportsender und die deutschen Sender, die du wirklich schaust, als Favoriten zu markieren, und in der App die Kategorien nach Ländern zu sortieren. Danach bist du mit zwei Klicks bei dem, was du sehen willst — egal in welcher Sprache.',
                            'Apps wie TiviMate und IPTV Smarters können beides und merken sich die Sortierung dauerhaft; welche auf deinem Gerät am besten läuft, steht unter %kw:iptv-player%. Wer lieber mit einer Playlist arbeitet, findet den Weg unter %kw:iptv-m3u-kaufen%.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Ist S Sport Plus wirklich enthalten?', 'a' => 'Die türkischen Sportsender gehören zum Katalog, die genaue Zusammensetzung hängt aber wie überall von der aktuellen Rechtelage ab. Prüfe es im kostenlosen 24-Stunden-Test oder frag vorher den Support — das ist verlässlicher als jede Senderliste in einer Werbeanzeige.'),
                    array('q' => 'Brauche ich eine Türksat-Schüssel?', 'a' => 'Nein. Die Übertragung läuft komplett über deine Internetleitung. Eine zweite Satellitenschüssel, eine Ausrichtung auf 42° Ost und die Erlaubnis des Vermieters entfallen damit.'),
                    array('q' => 'Bekomme ich türkische und deutsche Sender zusammen?', 'a' => 'Ja, beide liegen in derselben Senderliste. Du kannst zwischen türkischen und deutschen Übertragungen wechseln wie zwischen zwei Fernsehsendern.'),
                    array('q' => 'Läuft die Süper Lig live?', 'a' => 'Die türkische Liga gehört zum Sportangebot, einschließlich der Übertragungen mit türkischem Kommentar. Die Verfügbarkeit einzelner Partien richtet sich nach den geltenden Rechten.'),
                    array('q' => 'In welcher Qualität laufen die türkischen Sender?', 'a' => 'In HD, soweit der Sender es liefert. Bei Sport ist die Bitrate wichtiger als das Label — im Testzugang siehst du auf deinem eigenen Fernseher, wie es aussieht.'),
                    array('q' => 'Können mehrere Personen gleichzeitig schauen?', 'a' => 'Ja, wenn du beim Kauf mehrere Bildschirme wählst. Das ist der übliche Fall in Haushalten, in denen parallel auf Türkisch und auf Deutsch geschaut wird.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-player' => array(
                'keyword'   => 'IPTV Player',
                'title'     => 'IPTV Player',
                'seo_title' => 'IPTV Player 2026 – die besten Apps für TV, Stick & Handy',
                'seo_desc'  => 'Welcher IPTV Player passt zu deinem Gerät? TiviMate, IPTV Smarters, VLC, Kodi und GSE im Überblick — mit Einrichtung in wenigen Minuten und Zugang ab 5,83 €.',

                'text' => array(
                    'hero_title'        => 'IPTV Player – die App, die zu deinem Gerät passt.',
                    'hero_title_span'   => 'Einmal eingerichtet.',
                    'hero_title_3'      => 'Dann nie wieder anfassen.',
                    'hero_subtitle'     => 'Der beste IPTV Player ist der, den dein Gerät flüssig abspielt. Hier steht, welche App auf Smart TV, Fire TV Stick, Android, iPhone und PC am besten läuft — und wie du unseren Zugang in zwei Minuten einträgst.',
                    'hero_image_alt'    => 'IPTV Player mit Senderübersicht auf dem Fernseher',
                    'vod_image_alt'     => 'Filme und Serien im IPTV Player',
                    'sports_image_alt'  => 'Live-Sport im IPTV Player',
                    'features_title'    => 'Was dein',
                    'features_title_span' => 'IPTV Player anzeigt',
                    'features_subtitle' => 'Die App liefert die Oberfläche — der Inhalt kommt aus deinem Zugang. Das steckt darin.',
                    'pricing_subtitle'  => 'Ein Zugang für jeden Player: M3U oder Xtream-Codes, über 40.000 Sender.',
                    'reviews_title'     => 'Was Nutzer über Einrichtung und Bedienung sagen',
                    'faq_title'         => 'Fragen zum IPTV Player',
                ),

                'lead' => 'Ein IPTV Player ist die App, die aus deinen Zugangsdaten eine Senderübersicht macht. Der Zugang selbst ist bei allen gleich — die Unterschiede liegen in der Programmzeitschrift, im Tempo beim Umschalten und darin, wie gut sich das Ganze mit einer Fernbedienung bedienen lässt. Diese Seite ordnet die gängigen Apps nach Gerät.',

                'blocks' => array(
                    array(
                        'title' => 'Welcher Player zu welchem Gerät passt',
                        'text'  => array(
                            'Es gibt nicht den einen besten Player, sondern den, der auf deiner Hardware am flüssigsten läuft. Diese Zuordnung deckt die allermeisten Fälle ab.',
                        ),
                        'items' => array(
                            array(
                                'title' => 'TiviMate — Android TV und Fire TV Stick',
                                'text'  => 'Die komfortabelste Programmzeitschrift und das schnellste Umschalten. Für alle gebaut, die mit der Fernbedienung durch Sender blättern statt zu tippen. Erste Wahl, wenn du viel Live-TV und Sport schaust.',
                            ),
                            array(
                                'title' => 'IPTV Smarters Pro — fast überall',
                                'text'  => 'Läuft auf Android, iOS, Fire TV, Windows und vielen Smart TVs. Der beste Kompromiss, wenn du auf mehreren unterschiedlichen Geräten dieselbe Oberfläche willst. Unterstützt M3U und Xtream-Codes.',
                            ),
                            array(
                                'title' => 'GSE Smart IPTV — iPhone und iPad',
                                'text'  => 'Auf iOS die verbreitetste Lösung. Beherrscht M3U-Links und EPG und spielt sauber auf Apple TV weiter, wenn du per AirPlay auf den Fernseher gehst.',
                            ),
                            array(
                                'title' => 'VLC — Windows, Mac und Linux',
                                'text'  => 'Kein echter IPTV Player, aber der schnellste Test: „Medien → Netzwerkstream öffnen“, Link einfügen, läuft. Ideal zum Prüfen, ob ein Zugang funktioniert. Für den Alltag fehlt ihm die Programmzeitschrift.',
                            ),
                            array(
                                'title' => 'Kodi — für alle, die ohnehin Kodi nutzen',
                                'text'  => 'Über den PVR-IPTV-Simple-Client bindest du Playlist und EPG ein. Aufwendiger einzurichten als die anderen, dafür in einer Oberfläche mit deiner übrigen Mediathek.',
                            ),
                            array(
                                'title' => 'Smart STB und MAG — Set-Top-Boxen',
                                'text'  => 'Hier trägst du statt einer Playlist ein Portal ein. Verbreitet bei älteren Boxen; der Support hilft beim Hinterlegen der MAC-Adresse.',
                            ),
                        ),
                    ),
                    array(
                        'title' => 'M3U oder Xtream-Codes im Player eintragen',
                        'text'  => array(
                            'Fast jeder Player bietet beim ersten Start beide Wege an. Xtream-Codes bedeutet drei Felder — Serveradresse, Benutzername, Passwort — und liefert Live-TV, Filme und Serien sauber getrennt. Ein M3U-Link ist eine einzige URL und funktioniert dafür auch in Apps, die nichts anderes können.',
                            'Beides gehört zu jedem Zugang bei uns, du kannst also jederzeit wechseln. Wenn dein Player ein bestimmtes Format erwartet, rechnet der %m3u% es dir um. Wer direkt mit einer Playlist starten will, findet die Details unter %kw:iptv-m3u-kaufen%.',
                        ),
                    ),
                    array(
                        'title' => 'Woran es liegt, wenn der Player ruckelt',
                        'text'  => array(
                            'Wenn das Bild stockt, ist selten der Player schuld. Diese vier Ursachen decken die meisten Fälle ab, und alle vier kannst du selbst prüfen.',
                        ),
                        'list' => array(
                            'WLAN statt Kabel: Ein Fire TV Stick hinter dem Fernseher hat oft schlechteren Empfang, als das Balkensymbol vermuten lässt. Netzwerkkabel oder Powerline schaffen sofort Klarheit.',
                            'Zu wenig Arbeitsspeicher: Ältere Sticks und Smart TVs kommen mit schweren Oberflächen nicht zurecht. Ein schlanker Player hilft mehr als jede Einstellung.',
                            'Falscher Decoder: Viele Apps lassen dich zwischen Hardware- und Software-Decoding wählen. Wenn es ruckelt, probiere die jeweils andere Einstellung.',
                            'Zu viele parallele Streams: Mehr gleichzeitige Bildschirme, als beim Kauf gewählt, führen zu Abbrüchen statt zu einer Fehlermeldung.',
                        ),
                    ),
                    array(
                        'title' => 'Einrichtung in zwei Minuten',
                        'text'  => array(
                            'Nach dem Kauf bekommst du deine Zugangsdaten per E-Mail. Du installierst die passende App, wählst M3U oder Xtream-Codes, trägst die Daten einmal ein und wartest kurz, bis die Senderliste und das EPG geladen sind. Danach musst du nichts mehr anfassen — auch nicht, wenn Sender dazukommen.',
                            'Für jedes gängige Gerät liegt eine bebilderte Anleitung in der %guide%. Wenn etwas klemmt, ist der Support rund um die Uhr über die %contact% erreichbar. Noch keinen Zugang? Der Einstieg steht unter %kw:iptv-kaufen%. Was hinter dem Übertragungsweg steckt, beschreibt der %wiki%.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Welcher IPTV Player ist der beste?', 'a' => 'Für Live-TV und Sport auf Android TV oder Fire TV ist TiviMate die komfortabelste Wahl. Wer dieselbe Oberfläche auf mehreren Systemen will, nimmt IPTV Smarters Pro. Auf iPhone und iPad ist GSE Smart IPTV verbreitet, am PC reicht VLC zum schnellen Prüfen.'),
                    array('q' => 'Ist der Player im Preis enthalten?', 'a' => 'Der Zugang ist unabhängig von der App. Die meisten Player sind kostenlos, einige bieten eine kostenpflichtige Premium-Version mit zusätzlichen Funktionen. Du kannst jeden davon mit unseren Zugangsdaten verwenden.'),
                    array('q' => 'Kann ich den Player wechseln?', 'a' => 'Jederzeit. Deine Zugangsdaten bleiben gleich; du trägst sie einfach in der neuen App ein. Es gibt keine Bindung an eine bestimmte Software.'),
                    array('q' => 'Warum ruckelt das Bild in meiner App?', 'a' => 'Meistens liegt es am WLAN, an einem zu schwachen Gerät oder an der Decoder-Einstellung. Probiere zuerst ein Netzwerkkabel und wechsle in den App-Einstellungen zwischen Hardware- und Software-Decoding.'),
                    array('q' => 'Brauche ich für jedes Gerät einen eigenen Zugang?', 'a' => 'Nein. Du kannst deine Daten auf beliebig vielen Geräten hinterlegen. Begrenzt ist nur, wie viele Bildschirme gleichzeitig streamen — das legst du beim Kauf fest.'),
                    array('q' => 'Funktioniert das auch ohne App, direkt im Browser?', 'a' => 'Für einen schnellen Test ja, über VLC oder einen Webplayer. Für den Alltag ist eine richtige IPTV-App klar besser, weil nur sie Programmzeitschrift, Favoriten und Catch-up sauber darstellt.'),
                ),
            ),

            // ─────────────────────────────────────────────────────────────────
            'iptv-verlaengern' => array(
                'keyword'   => 'IPTV verlängern',
                'title'     => 'IPTV verlängern',
                'seo_title' => 'IPTV verlängern – gleiche Zugangsdaten, in 2 Minuten',
                'seo_desc'  => 'IPTV verlängern ohne neue Einrichtung: gleiche Zugangsdaten, keine automatische Verlängerung, längere Laufzeiten ab 5,83 € im Monat. In zwei Minuten erledigt.',

                'text' => array(
                    'hero_title'        => 'IPTV verlängern – ohne alles neu einzurichten.',
                    'hero_title_span'   => 'Gleiche Zugangsdaten.',
                    'hero_title_3'      => 'Zwei Minuten, dann läuft es weiter.',
                    'hero_subtitle'     => 'Beim IPTV verlängern bleiben Benutzername, Passwort und M3U-Link identisch. Du zahlst für die neue Laufzeit, und dein Zugang läuft weiter — ohne dass du in deiner App irgendetwas ändern musst.',
                    'hero_image_alt'    => 'IPTV verlängern – Zugang läuft nahtlos weiter',
                    'vod_image_alt'     => 'Filme und Serien nach dem IPTV verlängern',
                    'sports_image_alt'  => 'Sport weiterschauen nach dem IPTV verlängern',
                    'features_title'    => 'Was du behältst, wenn du',
                    'features_title_span' => 'IPTV verlängern willst',
                    'features_subtitle' => 'Verlängern heißt weitermachen, nicht neu anfangen. Das bleibt unverändert.',
                    'pricing_subtitle'  => 'Längere Laufzeit, niedrigerer Monatspreis — die Leistung bleibt bei allen Paketen dieselbe.',
                    'reviews_title'     => 'Was Kunden nach der Verlängerung sagen',
                    'faq_title'         => 'Fragen zum IPTV verlängern',
                ),

                'lead' => 'IPTV verlängern ist bei uns bewusst eine eigene Entscheidung: Es gibt keine automatische Verlängerung, kein stilles Abbuchen und keine Kündigungsfrist. Wenn deine Laufzeit endet, passiert einfach nichts — bis du selbst verlängerst. Diese Seite erklärt, wie das geht, wann der beste Zeitpunkt ist und was dabei erhalten bleibt.',

                'blocks' => array(
                    array(
                        'title' => 'So verlängerst du in zwei Minuten',
                        'text'  => array(
                            'Der Ablauf ist derselbe wie beim ersten Kauf, nur schneller — du wählst Laufzeit und Bildschirmzahl, bezahlst über die SSL-Kasse und gibst dabei den Benutzernamen an, den du bereits hast. Wichtig ist genau dieser Schritt: Nur so wird die Zeit auf deinen bestehenden Zugang gebucht statt auf einen neuen.',
                            'Wenn du deinen Benutzernamen nicht mehr findest, steht er in der Bestätigungsmail vom ersten Kauf oder in deinem Konto. Im Zweifel schreib kurz über die %contact% — der Support ordnet die Verlängerung dann zu.',
                        ),
                        'list' => array(
                            'Laufzeit wählen: 1, 3, 6 oder 12 Monate',
                            'Bildschirmzahl bestätigen oder ändern',
                            'Bestehenden Benutzernamen angeben',
                            'Sicher bezahlen — Karte, PayPal oder Bitcoin',
                            'Bestätigung abwarten, meist unter zwei Minuten',
                        ),
                    ),
                    array(
                        'title' => 'Was gleich bleibt — und warum das wichtig ist',
                        'text'  => array(
                            'Der eigentliche Vorteil des Verlängerns gegenüber einem Neukauf ist, dass du an deinen Geräten nichts anfassen musst. Benutzername, Passwort, M3U-Link und Xtream-Zugangsdaten bleiben identisch. Deine App merkt vom Vorgang nichts.',
                            'Damit bleiben auch die Dinge erhalten, die du dir über Monate eingerichtet hast: Favoritenlisten, sortierte Sendergruppen und die Anordnung in deiner Programmzeitschrift. Wer stattdessen einen neuen Zugang kauft, richtet das auf jedem Gerät wieder neu ein — das ist der eine Fehler, der beim Verlängern Zeit kostet.',
                        ),
                    ),
                    array(
                        'title' => 'Der richtige Zeitpunkt',
                        'text'  => array(
                            'Verlängere ein bis zwei Tage vor Ablauf, nicht danach. Buchst du rechtzeitig, wird die neue Laufzeit an die alte angehängt und es entsteht keine Lücke — du verlierst also nichts, wenn du früh dran bist.',
                            'Läuft der Zugang dagegen erst aus und du verlängerst Tage später, bleibt der Zugang in der Zwischenzeit inaktiv. Wieder aktivieren lässt er sich in aller Regel weiterhin unter denselben Zugangsdaten; nach längerer Pause kann es allerdings passieren, dass der Benutzername neu vergeben werden muss. Rechtzeitig zu verlängern erspart dir diese Diskussion.',
                            'Wer über einen Spieltag hinweg nicht unterbrochen werden will, verlängert unter der Woche — Details zum Sportbetrieb stehen unter %kw:iptv-bundesliga%.',
                        ),
                    ),
                    array(
                        'title' => 'Welche Laufzeit beim Verlängern sinnvoll ist',
                        'text'  => array(
                            'Beim ersten Kauf ist eine kurze Laufzeit vernünftig, weil du den Dienst noch nicht kennst. Beim Verlängern kennst du ihn — und dann ist die lange Laufzeit fast immer die bessere Rechnung. Die Leistung ist bei allen Paketen identisch, nur der Preis pro Monat sinkt: Der %plan12% liegt bei rund 5,83 € im Monat, der %plan1% bei einem Vielfachen davon.',
                            'Dazwischen liegen der %plan3% und der %plan6%, die sich anbieten, wenn du bis zum Saisonende oder über einen bestimmten Zeitraum planen willst. Wie sich das gegen andere Angebote rechnet, steht unter %kw:iptv-anbieter-vergleich%; wer erst einsteigt, findet den Ablauf unter %kw:iptv-kaufen%.',
                        ),
                    ),
                    array(
                        'title' => 'Wenn nach dem Verlängern etwas nicht läuft',
                        'text'  => array(
                            'In den meisten Fällen liegt es daran, dass die App die neue Laufzeit noch nicht abgefragt hat. Schließe sie vollständig und starte sie neu, oder lade in den Einstellungen die Playlist einmal manuell nach — danach steht das neue Ablaufdatum drin.',
                            'Bleibt es dabei, prüfe, ob du beim Bezahlen tatsächlich den bestehenden Benutzernamen angegeben hast; andernfalls wurde ein zweiter Zugang angelegt, den der Support in wenigen Minuten zusammenführt. Anleitungen pro App stehen in der %guide% und unter %kw:iptv-player%. Was IPTV technisch ausmacht, erklärt der %wiki%.',
                        ),
                    ),
                ),

                'faq' => array(
                    array('q' => 'Bleiben meine Zugangsdaten beim Verlängern gleich?', 'a' => 'Ja. Benutzername, Passwort, M3U-Link und Xtream-Daten bleiben identisch, solange du beim Bezahlen deinen bestehenden Benutzernamen angibst. In deiner App musst du nichts ändern.'),
                    array('q' => 'Verlängert sich mein Paket automatisch?', 'a' => 'Nein. Es gibt keine automatische Verlängerung und keine wiederkehrende Abbuchung. Nach Ablauf passiert nichts, bis du selbst verlängerst.'),
                    array('q' => 'Wann sollte ich verlängern?', 'a' => 'Ein bis zwei Tage vor Ablauf. Die neue Laufzeit wird an die bestehende angehängt, du verlierst also keine Zeit — und vermeidest eine Lücke mitten in der Woche oder am Spieltag.'),
                    array('q' => 'Was passiert, wenn mein Zugang schon abgelaufen ist?', 'a' => 'In aller Regel lässt er sich unter denselben Zugangsdaten wieder aktivieren. Nach längerer Pause kann es vorkommen, dass der Benutzername neu vergeben werden muss — dann meldet sich der Support und richtet es ein.'),
                    array('q' => 'Kann ich beim Verlängern die Laufzeit oder Bildschirmzahl ändern?', 'a' => 'Ja, beides. Du kannst von einem Monat auf zwölf wechseln oder Bildschirme dazunehmen, ohne einen neuen Zugang anzulegen.'),
                    array('q' => 'Bleiben meine Favoriten erhalten?', 'a' => 'Ja. Weil sich die Zugangsdaten nicht ändern, behalten deine Apps Favoritenlisten und Sendersortierung. Genau das geht verloren, wenn man statt zu verlängern einen neuen Zugang kauft.'),
                    array('q' => 'Nach dem Verlängern läuft nichts — was tun?', 'a' => 'Schließe die App vollständig und starte sie neu oder lade die Playlist in den Einstellungen manuell nach. Danach steht das neue Ablaufdatum drin. Hilft das nicht, meldet sich der Support in wenigen Minuten.'),
                ),
            ),
        );

        return $definitions;
    }
}

if (!function_exists('iptv_keyword_slugs')) {
    /**
     * Slugs in the order they should appear in the footer.
     *
     * @return string[]
     */
    function iptv_keyword_slugs()
    {
        return array_keys(iptv_keyword_definitions());
    }
}

if (!function_exists('iptv_keyword_definition')) {
    /**
     * One page's definition.
     *
     * @param string $slug
     * @return array|null
     */
    function iptv_keyword_definition($slug)
    {
        $definitions = iptv_keyword_definitions();

        return isset($definitions[$slug]) ? $definitions[$slug] : null;
    }
}
