<?php
/**
 * Channel Template – Copy Helper
 *
 * The site is English-only, so tpl_str() simply returns what the template
 * passes it. It is kept as a function rather than removed so the templates do
 * not all have to change, and so there is one obvious place to hook a
 * translation layer back in if the site ever needs one again.
 *
 * Usage in templates: tpl_str('Default English text')
 */

if (!function_exists('tpl_str')) {
    /**
     * @param string $default The English copy.
     * @return string
     */
    function tpl_str(string $default): string
    {
        return $default;
    }
}
