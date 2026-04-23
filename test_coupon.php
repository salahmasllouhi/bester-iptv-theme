<?php
require 'wp-load.php';
$url = WC_AJAX::get_endpoint( 'apply_coupon' );
echo "Ajax URL: " . $url . "\n";
