<?php
// my-iptv-theme/test-attrs.php
require_once('../../../wp-load.php');

$sku = '1_month';
$base_product_id = wc_get_product_id_by_sku($sku);

echo "Base Product ID ($sku): " . $base_product_id . "<br>";

if (function_exists('pll_get_post_translations')) {
    $translations = pll_get_post_translations($base_product_id);
    echo "Translations: <pre>";
    print_r($translations);
    echo "</pre>";

    foreach ($translations as $lang => $pid) {
        echo "<h3>Language: $lang (ID: $pid)</h3>";
        $product = wc_get_product($pid);
        if ($product && $product->is_type('variable')) {
            echo "<h4>Product Attributes:</h4><pre>";
            print_r($product->get_attributes());
            echo "</pre>";

            echo "<h4>Variations:</h4>";
            $children = $product->get_children();
            if (empty($children)) {
                echo "No variations found.<br>";
            } else {
                foreach ($children as $cid) {
                    $variation = wc_get_product($cid);
                    if ($variation) {
                        echo "Variation ID: $cid<br>";
                        echo "Attributes: <pre>";
                        print_r($variation->get_attributes());
                        echo "</pre>";
                    }
                }
            }
        } else {
            echo "Not a variable product or not found.<br>";
        }
    }
} else {
    echo "Polylang function not found.<br>";
}
