<?php
/**
 * Series Template – Copy Helper
 *
 * The site is English-only, so srs_str() simply returns what the template
 * passes it. See inc/channel-strings.php for why the function is kept.
 *
 * Usage in templates: srs_str('Default English text')
 */

if (!function_exists('srs_str')) {
    /**
     * @param string $default The English copy.
     * @return string
     */
    function srs_str(string $default): string
    {
        return $default;
    }
}
