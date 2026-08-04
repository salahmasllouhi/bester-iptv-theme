<?php
/**
 * Sport Template – Copy Helper
 *
 * The site is English-only, so spl_str() simply returns what the template
 * passes it. See inc/channel-strings.php for why the function is kept.
 *
 * Usage in templates: spl_str('Default English text')
 */

if (!function_exists('spl_str')) {
    /**
     * @param string $default The English copy.
     * @return string
     */
    function spl_str(string $default): string
    {
        return $default;
    }
}
