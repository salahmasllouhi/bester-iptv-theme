<?php
require_once('wp-load.php');

$sku = '1_month';
$base_product_id = wc_get_product_id_by_sku($sku);
echo "Base Product ID ($sku): " . $base_product_id . "\n";

if (function_exists('pll_get_post_translations')) {
    $translations = pll_get_post_translations($base_product_id);
    echo "Translations: \n";
    print_r($translations);
} else {
    echo "Polylang function not found.\n";
}
