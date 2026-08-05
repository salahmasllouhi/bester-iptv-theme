<?php
/**
 * Keyword landing pages — the copy table
 *
 * One entry per keyword. Each entry drives a page that renders the front page's
 * section stack with its own wording, plus a body band and an FAQ that exist
 * only on that page.
 *
 * `text` overrides front-page copy keys for this page only, through the
 * `iptv_text` filter. Anything not listed falls through to the front page, so
 * prices, reviews, device chips and the rest stay in one place. That is what
 * makes eight pages out of one section stack: the keyword lives in the
 * headline, the section subtitles, the image alt text and the FAQ.
 *
 * These entries also carried a `lead` and a `blocks` array — 900-odd words of
 * body copy per page, rendered between the onboarding panel and the device
 * chips. That band was removed: these are landing pages, not articles. Both the
 * copy and its renderer are in the git history if it is ever wanted as blog
 * posts, which is where an article belongs.
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
                'seo_title' => 'IPTV kaufen 2026 – sofort aktiv, 40.000+ Sender in 4K',
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
                    'showcase_subtitle' => 'Wer IPTV kaufen will, bekommt Live-TV, Filme und Serien über das Internet — ohne Schüssel, ohne Vertrag, ohne teure Hardware.',
                    'vod_subtitle' => 'Die Mediathek ist inklusive, wenn du IPTV kaufen möchtest: alle Genres und Sprachen, auf Abruf, täglich erweitert.',
                    'sports_desc' => 'Alle Ligen und Turniere live — für viele der eigentliche Grund, IPTV kaufen zu wollen.',
                    'cta_bar_label' => 'IPTV KAUFEN UND BIS ZU 90 % GEGENÜBER KLASSISCHEN ANBIETERN SPAREN',
                    'steps_subtitle' => 'IPTV kaufen, Zugangsdaten erhalten, loslegen — in zwei Minuten erledigt.',
                    'devices_subtitle' => 'Wer IPTV kaufen will, braucht keine neue Hardware: Smart TV, Android, iOS, Fire Stick, MAG und viele mehr.',
                    'reviews_subtitle' => 'Tausende in Deutschland, die IPTV kaufen statt den Kabelvertrag zu verlängern.',
                    'faq_subtitle' => 'Die häufigsten Fragen von allen, die IPTV kaufen möchten.',
                    'contact_subtitle' => 'Noch unsicher, ob du IPTV kaufen möchtest? Schreib uns — der Support antwortet meist in wenigen Minuten.',
                    'features_cta_note' => 'IPTV kaufen · Ohne Vertrag · Sofort aktiviert · 14 Tage Geld-zurück',
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
                'seo_title' => 'IPTV M3U kaufen 2026 – Link mit EPG, sofort geliefert',
                'seo_desc'  => 'IPTV M3U kaufen und den Link sofort per E-Mail erhalten: 40.000+ Sender, EPG inklusive, läuft in VLC, IPTV Smarters, TiviMate und Kodi. 14 Tage Geld zurück.',

                'text' => array(
                    'hero_title'        => 'IPTV M3U kaufen – ein Link, jede App.',
                    'hero_title_span'   => 'Sofort geliefert.',
                    'hero_title_3'      => 'Mit voller Programmzeitschrift.',
                    'hero_subtitle'     => 'Du willst IPTV M3U kaufen und den Link in deiner gewohnten App nutzen? Du bekommst eine M3U-URL mit über 40.000 Sendern und passendem EPG — kompatibel mit VLC, IPTV Smarters, TiviMate, Kodi und jedem Player, der M3U versteht.',
                    'hero_image_alt'    => 'IPTV M3U kaufen – M3U-Playlist mit über 40.000 Sendern',
                    'features_title'    => 'Was in der Playlist steckt, wenn du',
                    'features_title_span' => 'IPTV M3U kaufen willst',
                    'features_subtitle' => 'Eine M3U-Datei ist nur so gut wie das, was dahinter liegt. Das hier hängt an jedem Link, den wir ausliefern.',
                    'pricing_subtitle'  => 'Ein Zugang, wahlweise als M3U-Link oder über Xtream-Codes. Über 40.000 Live-Sender und mehr als 200.000 Filme und Serien.',
                    'reviews_title'     => 'Kunden, die bei uns IPTV M3U kaufen',
                    'faq_title'         => 'Fragen zum IPTV M3U kaufen',
                    'vod_image_alt' => 'Filme und Serien über die Playlist, wenn Sie IPTV M3U kaufen',
                    'sports_image_alt' => 'Live-Sport über den Link, den Sie beim IPTV M3U kaufen erhalten',
                    'showcase_subtitle' => 'Wer IPTV M3U kaufen will, bekommt Live-TV, Filme und Serien über einen einzigen Link — ohne Schüssel und ohne Vertrag.',
                    'vod_subtitle' => 'Filme und Serien hängen an derselben Playlist, wenn du IPTV M3U kaufen möchtest — auf Abruf, in vielen Sprachen.',
                    'sports_desc' => 'Sport liegt in derselben Liste: ein Grund mehr, IPTV M3U kaufen zu wollen.',
                    'cta_bar_label' => 'IPTV M3U KAUFEN UND BIS ZU 90 % GEGENÜBER KLASSISCHEN ANBIETERN SPAREN',
                    'steps_subtitle' => 'IPTV M3U kaufen, Link einfügen, schauen — zwei Minuten reichen.',
                    'devices_subtitle' => 'Der Link läuft überall: wer IPTV M3U kaufen will, braucht keine bestimmte App und keine neue Hardware.',
                    'reviews_subtitle' => 'Was Kunden sagen, die bei uns IPTV M3U kaufen statt eine Datei herunterzuladen.',
                    'faq_subtitle' => 'Die häufigsten Fragen von allen, die IPTV M3U kaufen möchten.',
                    'contact_subtitle' => 'Unsicher, ob dein Player passt, bevor du IPTV M3U kaufen willst? Frag uns vorher.',
                    'features_cta_note' => 'IPTV M3U kaufen · Sofort geliefert · EPG inklusive · 14 Tage Geld-zurück',
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
                'seo_title' => 'IPTV Anbieter Vergleich: 9 Kriterien für den besten Dienst',
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
                    'showcase_subtitle' => 'Was im IPTV Anbieter Vergleich zählt: Live-TV, Filme und Serien über das Internet, ohne Schüssel und ohne Vertrag.',
                    'vod_subtitle' => 'Die Mediathek ist im IPTV Anbieter Vergleich der zweite Punkt nach den Sendern — alle Genres, auf Abruf, täglich erweitert.',
                    'sports_desc' => 'Sport entscheidet den IPTV Anbieter Vergleich häufiger als jede Senderzahl.',
                    'cta_bar_label' => 'IPTV ANBIETER VERGLEICH: BIS ZU 90 % GÜNSTIGER ALS KLASSISCHE ANBIETER',
                    'steps_subtitle' => 'Nach dem IPTV Anbieter Vergleich bist du in zwei Minuten startklar.',
                    'devices_subtitle' => 'Geräteunterstützung gehört in jeden IPTV Anbieter Vergleich: Smart TV, Android, iOS, Fire Stick, MAG und mehr.',
                    'reviews_subtitle' => 'Was Kunden sagen, die nach einem IPTV Anbieter Vergleich zu uns gewechselt sind.',
                    'faq_subtitle' => 'Die häufigsten Fragen zum IPTV Anbieter Vergleich.',
                    'contact_subtitle' => 'Fragen, die dein IPTV Anbieter Vergleich offen lässt? Schreib uns, wir antworten rund um die Uhr.',
                    'features_cta_note' => 'IPTV Anbieter Vergleich · Ohne Vertrag · 24 h testen · 14 Tage Geld-zurück',
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
                'seo_title' => 'IPTV Bundesliga live – alle 306 Spiele, einfach in 4K',
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
                    'showcase_subtitle' => 'Neben der IPTV Bundesliga liegen Live-TV, Filme und Serien in derselben Liste — ohne Schüssel, ohne Vertrag.',
                    'vod_subtitle' => 'Wenn kein Spiel läuft: Filme und Serien gehören zur IPTV Bundesliga dazu, auf Abruf und täglich erweitert.',
                    'sports_desc' => 'IPTV Bundesliga, Champions League und jede andere große Liga — live und auf Abruf.',
                    'cta_bar_label' => 'IPTV BUNDESLIGA: BIS ZU 90 % GÜNSTIGER ALS EIN KLASSISCHES SPORTPAKET',
                    'steps_subtitle' => 'Die IPTV Bundesliga einzurichten dauert zwei Minuten — rechtzeitig vor dem Anpfiff.',
                    'devices_subtitle' => 'Die IPTV Bundesliga läuft auf dem Gerät, das schon bei dir steht: Smart TV, Fire Stick, Android, iOS und mehr.',
                    'reviews_subtitle' => 'Was Zuschauer über die IPTV Bundesliga am Samstagnachmittag sagen.',
                    'faq_subtitle' => 'Die häufigsten Fragen zur IPTV Bundesliga.',
                    'contact_subtitle' => 'Frage zur IPTV Bundesliga offen? Der Support ist rund um die Uhr erreichbar.',
                    'features_cta_note' => 'IPTV Bundesliga · Ohne zweites Sportpaket · Sofort aktiviert · 14 Tage Geld-zurück',
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
                'seo_title' => 'IPTV Fußball live 2026 – alle Top-Ligen, sofort in 4K',
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
                    'showcase_subtitle' => 'Neben IPTV Fußball liegen Live-TV, Filme und Serien in derselben Liste — ohne Schüssel und ohne Vertrag.',
                    'vod_subtitle' => 'Zwischen zwei Spieltagen: Filme und Serien gehören zu IPTV Fußball dazu, auf Abruf und täglich erweitert.',
                    'sports_desc' => 'IPTV Fußball plus NFL, NBA, Formel 1 und Handball — live und auf Abruf.',
                    'cta_bar_label' => 'IPTV FUSSBALL: BIS ZU 90 % GÜNSTIGER ALS DREI EINZELNE SPORTABOS',
                    'steps_subtitle' => 'IPTV Fußball einzurichten dauert zwei Minuten — rechtzeitig vor dem Anpfiff.',
                    'devices_subtitle' => 'IPTV Fußball läuft auf dem Gerät, das schon bei dir steht: Smart TV, Fire Stick, Android, iOS und mehr.',
                    'reviews_subtitle' => 'Was Zuschauer über IPTV Fußball an einem Champions-League-Abend sagen.',
                    'faq_subtitle' => 'Die häufigsten Fragen zu IPTV Fußball.',
                    'contact_subtitle' => 'Frage zu IPTV Fußball offen? Der Support ist rund um die Uhr erreichbar.',
                    'features_cta_note' => 'IPTV Fußball · Alle Ligen in einem Abo · Sofort aktiviert · 14 Tage Geld-zurück',
                ),

                'faq' => array(
                    array('q' => 'Sind Champions League und Bundesliga zusammen enthalten?', 'a' => 'Ja, bei IPTV Fußball liegen beide im selben Zugang, ohne Aufpreis und ohne zweites Abo. Dasselbe gilt für Europa League, Premier League, La Liga, Serie A und Ligue 1.'),
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
                'seo_title' => 'IPTV S Sport Plus 2026 – türkischer Sport, sofort in HD',
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
                    'showcase_subtitle' => 'Neben IPTV S Sport Plus liegen deutsche Sender, Filme und Serien in derselben Liste — ohne zweite Schüssel.',
                    'vod_subtitle' => 'Türkische und internationale Filme und Serien gehören zu IPTV S Sport Plus dazu, auf Abruf und täglich erweitert.',
                    'sports_desc' => 'IPTV S Sport Plus, Süper Lig und die europäischen Ligen — live und auf Abruf.',
                    'cta_bar_label' => 'IPTV S SPORT PLUS: BIS ZU 90 % GÜNSTIGER ALS SATELLIT UND SPORTPAKET',
                    'steps_subtitle' => 'IPTV S Sport Plus einzurichten dauert zwei Minuten, ganz ohne Türksat-Schüssel.',
                    'devices_subtitle' => 'IPTV S Sport Plus läuft auf dem Gerät, das schon bei dir steht: Smart TV, Fire Stick, Android, iOS und mehr.',
                    'reviews_subtitle' => 'Was Zuschauer sagen, die IPTV S Sport Plus über das Internet statt über Satellit schauen.',
                    'faq_subtitle' => 'Die häufigsten Fragen zu IPTV S Sport Plus.',
                    'contact_subtitle' => 'Unsicher, ob IPTV S Sport Plus die Sender hat, die du suchst? Frag vorher nach.',
                    'features_cta_note' => 'IPTV S Sport Plus · Türkisch und deutsch · Sofort aktiviert · 14 Tage Geld-zurück',
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
                'seo_title' => 'IPTV Player 2026 – die 6 besten Apps, einfach erklärt',
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
                    'showcase_subtitle' => 'Was dein IPTV Player anzeigt: Live-TV, Filme und Serien über das Internet, ohne Schüssel und ohne Vertrag.',
                    'vod_subtitle' => 'Ein guter IPTV Player trennt Live-TV, Filme und Serien sauber — hier sind über 200.000 Titel auf Abruf.',
                    'sports_desc' => 'Beim Sport zeigt sich, welcher IPTV Player schnell genug umschaltet.',
                    'cta_bar_label' => 'EIN IPTV PLAYER, EIN ZUGANG: BIS ZU 90 % GEGENÜBER KLASSISCHEN ANBIETERN SPAREN',
                    'steps_subtitle' => 'IPTV Player installieren, Zugangsdaten eintragen, schauen — zwei Minuten.',
                    'devices_subtitle' => 'Für jedes Gerät gibt es einen passenden IPTV Player: Smart TV, Fire Stick, Android, iOS, MAG und mehr.',
                    'reviews_subtitle' => 'Was Nutzer über Einrichtung und Bedienung im IPTV Player sagen.',
                    'faq_subtitle' => 'Die häufigsten Fragen zum IPTV Player.',
                    'contact_subtitle' => 'Dein IPTV Player macht Probleme? Der Support ist rund um die Uhr erreichbar.',
                    'features_cta_note' => 'Jeder IPTV Player · M3U und Xtream · Sofort aktiviert · 14 Tage Geld-zurück',
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
                'seo_title' => 'IPTV verlängern – schnell, sicher, in 2 Minuten erledigt',
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
                    'showcase_subtitle' => 'Was du behältst, wenn du IPTV verlängern willst: über 40.000 Sender, Filme und Serien, ohne neue Einrichtung.',
                    'vod_subtitle' => 'Die Mediathek läuft weiter, sobald du IPTV verlängern lässt — alle Genres, auf Abruf, täglich erweitert.',
                    'sports_desc' => 'Damit kein Spieltag ausfällt, solltest du rechtzeitig IPTV verlängern.',
                    'cta_bar_label' => 'IPTV VERLÄNGERN UND WEITER BIS ZU 90 % GEGENÜBER KLASSISCHEN ANBIETERN SPAREN',
                    'steps_subtitle' => 'IPTV verlängern dauert zwei Minuten und ändert an deinen Geräten nichts.',
                    'devices_subtitle' => 'Nach dem IPTV verlängern läuft alles weiter wie bisher: Smart TV, Fire Stick, Android, iOS und mehr.',
                    'reviews_subtitle' => 'Was Kunden sagen, die bei uns IPTV verlängern statt neu zu kaufen.',
                    'faq_subtitle' => 'Die häufigsten Fragen zum IPTV verlängern.',
                    'contact_subtitle' => 'Benutzername verlegt, bevor du IPTV verlängern kannst? Wir finden ihn für dich.',
                    'features_cta_note' => 'IPTV verlängern · Gleiche Zugangsdaten · In 2 Minuten · Ohne Auto-Verlängerung',
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
