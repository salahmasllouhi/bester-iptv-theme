<?php
/**
 * Brand wordmark
 *
 * The logo is drawn rather than loaded: images/logo/*.png still carry the old
 * NordicTV mark, and an SVG wordmark keeps the header honest until a real logo
 * is commissioned. It also scales to any DPI and picks up the brand tokens, so
 * a palette change moves the logo with it.
 *
 * To go back to an image logo, replace the iptv_brand_logo() call in
 * front-page/sections/header.php, offer-header.php and footer.php with an
 * <img> tag — nothing else depends on this.
 *
 * @package IPTV_Anbieter
 */

if (!defined('ABSPATH')) {
    exit;
}

if (!function_exists('iptv_brand_name')) {
    /**
     * The brand name, in one place so templates and schema agree.
     *
     * @return string
     */
    function iptv_brand_name()
    {
        return apply_filters('iptv_brand_name', 'IPTV Anbieter');
    }
}

if (!function_exists('iptv_brand_logo')) {
    /**
     * Print the wordmark.
     *
     * Two tones: "IPTV" in gold, "ANBIETER" in the surrounding text colour, so
     * the same markup works on a light header and a dark footer without a
     * second asset. currentColor does that work — the caller sets the colour.
     *
     * Rendered as text in a <span>, not an <svg>, so it stays selectable,
     * searchable and translatable, and costs no extra request.
     *
     * @param array $args {
     *     @type string $class Extra class on the wrapper.
     *     @type bool   $link  Wrap in a link to the home page. Default true.
     * }
     */
    function iptv_brand_logo($args = array())
    {
        $args = array_merge(array('class' => '', 'link' => true), $args);

        $classes = trim('brand-logo ' . $args['class']);

        $mark = sprintf(
            '<span class="%s" role="img" aria-label="%s">'
                . '<span class="brand-logo__mark">IPTV</span>'
                . '<span class="brand-logo__word">Anbieter</span>'
                . '</span>',
            esc_attr($classes),
            esc_attr(iptv_brand_name())
        );

        if (!$args['link']) {
            echo $mark; // phpcs:ignore WordPress.Security.EscapeOutput -- built above
            return;
        }

        printf(
            '<a href="%s" class="logo brand-logo-link">%s</a>',
            esc_url(home_url('/')),
            $mark // phpcs:ignore WordPress.Security.EscapeOutput -- built above
        );
    }
}
