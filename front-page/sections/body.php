<?php
/**
 * Section: Front page body band
 *
 * A wrapper, not a layout. The prose layout lives in
 * keyword/sections/keyword-content.php and is shared with the eight keyword
 * landing pages — one set of styles, one set of link placeholders, one place to
 * change how long-form copy looks on this site.
 *
 * The copy is in front-page/inc/front-page-body.php.
 *
 * 'home' is not a keyword page, and that is deliberate: iptv_keyword_links()
 * treats an unknown slug as "links to all eight keyword pages, self-link to
 * none", which is exactly right here.
 */

$kw      = iptv_front_body();
$kw_slug = 'home';

include get_template_directory() . '/keyword/sections/keyword-content.php';
