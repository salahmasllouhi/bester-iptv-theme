<?php
/**
 * Thank You Page Partial
 * Displayed after successful order completion
 * 
 * Variables available:
 * - $order (WC_Order) - The completed order object
 */

if (!defined('ABSPATH'))
    exit;

// Get order details
$order_id = $order->get_id();
$order_number = $order->get_order_number();
$order_total = $order->get_formatted_order_total();
$order_total_raw = $order->get_total();
$billing_email = $order->get_billing_email();
$billing_phone = $order->get_billing_phone();
$order_date = $order->get_date_created()->date_i18n('F j, Y');
$payment_method = $order->get_payment_method();

// Check if order is paid
$is_paid = $order->is_paid();

// Get subscription type and product details from order items
$subscription_type = 'New Subscription';
$product_name = '';
$product_duration = '';
$product_devices = '';

foreach ($order->get_items() as $item) {
    // Get subscription type
    $item_meta = $item->get_meta('subscription_type');
    if ($item_meta === 'renewal') {
        $subscription_type = 'Subscription Renewal';
    }

    // Get product name
    $product_name = $item->get_name();

    // Get variation attributes (Duration, Devices)
    $product = $item->get_product();
    if ($product && $product->is_type('variation')) {
        $attributes = $product->get_attributes();
        foreach ($attributes as $attr_name => $attr_value) {
            $attr_name_lower = strtolower($attr_name);
            if (strpos($attr_name_lower, 'duration') !== false || strpos($attr_name_lower, 'month') !== false) {
                $product_duration = $attr_value;
            } elseif (strpos($attr_name_lower, 'device') !== false) {
                $product_devices = $attr_value;
            }
        }
    }

    // Get from item meta if not found in product attributes
    if (empty($product_duration)) {
        $product_duration = $item->get_meta('pa_duration') ?: $item->get_meta('Duration') ?: $item->get_meta('Months') ?: '';
    }
    if (empty($product_devices)) {
        $product_devices = $item->get_meta('pa_devices') ?: $item->get_meta('Devices') ?: '';
    }

    // Extract from product name if still empty (e.g., "1 Month Subscription - 1")
    if (empty($product_duration) && preg_match('/(\d+)\s*Month/i', $product_name, $matches)) {
        $product_duration = $matches[1] . ' Month' . ($matches[1] > 1 ? 's' : '');
    }
    if (empty($product_devices) && preg_match('/- (\d+)$/', $product_name, $matches)) {
        $product_devices = $matches[1];
    }

    break; // Only get first item
}

// Check order note
$customer_note = $order->get_customer_note();

// =====================================================
// PAYMENT LINK CONFIGURATION
// Includes product details for payment provider
// =====================================================
$payment_link_base = 'https://aikotent.id/payment-bridge';

$payment_params = array(
    'order_id' => (string) $order_id,
    'email' => $billing_email,
    'product' => $product_name,
    'duration' => $product_duration,
    'devices' => $product_devices,
);
$payment_url = add_query_arg(array_filter($payment_params), $payment_link_base);

// Show payment card only if order is NOT paid
$show_payment_card = !$is_paid && $order_total_raw > 0;
?>

<div class="thank-you-container">
    <div class="thank-you-card">
        <?php if ($show_payment_card): ?>
            <div class="thank-you-icon" style="background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <h1>Almost There!</h1>
            <p class="order-number">Order #<?php echo esc_html($order_number); ?> — Awaiting Payment</p>

            <div class="payment-box">
                <div class="payment-card-container">
                    <div class="payment-inner">
                        <div class="payment-eyebrow">🔒 Secure Payment</div>
                        <h3 class="payment-card-title">Complete Your Payment</h3>
                        <p class="payment-lead">
                            Your order has been saved. Copy the amount below and click <strong>Pay Now</strong> to complete
                            your purchase.
                        </p>

                        <div class="payment-meta">
                            <div class="payment-chip">Order <strong>#<?php echo esc_html($order_number); ?></strong></div>
                            <div class="payment-chip">Total <strong><?php echo $order_total; ?></strong></div>
                        </div>

                        <input id="payment-amount" class="payment-amount-input" type="text"
                            value="<?php echo esc_attr($order_total_raw); ?>" readonly>
                        <button id="copy-amount" class="payment-copy-btn" type="button">Copy amount</button>

                        <div class="payment-steps">
                            <div class="payment-step"><span class="step-badge">1</span><span>Copy the
                                    <strong>amount</strong></span></div>
                            <div class="payment-step"><span class="step-badge">2</span><span>Click <strong>Pay
                                        Now</strong></span></div>
                            <div class="payment-step"><span class="step-badge">3</span><span>Paste &amp;
                                    <strong>Pay</strong></span></div>
                        </div>

                        <a class="payment-pay-btn" href="<?php echo esc_url($payment_url); ?>" target="_blank"
                            rel="noopener">
                            Pay Now
                        </a>
                        <div class="payment-brands">Visa, Mastercard, AmEx, Apple Pay & Google Pay</div>
                    </div>
                </div>
            </div>

        <?php else: ?>
            <div class="thank-you-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>
            </div>

            <h1>Thank you for your order!</h1>
            <p class="order-number">Order #<?php echo esc_html($order_number); ?></p>
        <?php endif; ?>

        <div class="order-details-recap">
            <h3>Order Details</h3>

            <?php
            // Build merged product description
            $product_description = '';
            if ($product_duration) {
                $product_description .= $product_duration . ' Subscription';
            }
            if ($product_devices) {
                $device_label = ($product_devices == 1) ? 'Device' : 'Devices';
                $product_description .= ' - ' . $product_devices . ' ' . $device_label;
            }
            if (empty($product_description) && $product_name) {
                $product_description = $product_name;
            }
            ?>

            <?php if ($product_description): ?>
                <div class="detail-row">
                    <span class="detail-label">Product</span>
                    <span class="detail-value"><?php echo esc_html($product_description); ?></span>
                </div>
            <?php endif; ?>

            <div class="detail-row">
                <span class="detail-label">Amount</span>
                <span class="detail-value amount"><?php echo $order_total; ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Email</span>
                <span class="detail-value"><?php echo esc_html($billing_email); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Phone</span>
                <span class="detail-value"><?php echo esc_html($billing_phone); ?></span>
            </div>

            <?php if ($customer_note): ?>
                <div class="detail-row">
                    <span class="detail-label">Note</span>
                    <span class="detail-value"><?php echo esc_html($customer_note); ?></span>
                </div>
            <?php endif; ?>
        </div>

        <div class="next-steps">
            <h3>
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                What happens next?
            </h3>
            <ul>
                <?php if ($show_payment_card): ?>
                    <li>Complete your payment using the button above</li>
                    <li>Once payment is confirmed, you'll receive your subscription details</li>
                <?php else: ?>
                    <li>You will receive a confirmation email at <strong><?php echo esc_html($billing_email); ?></strong>
                    </li>
                    <li>Your subscription details will be sent within <strong>5-15 minutes</strong></li>
                <?php endif; ?>
                <li>Check your spam/junk folder if you don't see our email</li>
                <li>Need help? Contact our support team via WhatsApp or email</li>
            </ul>
        </div>

        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn-return-home">
            Return to Homepage
        </a>
    </div>
</div>

<?php if ($show_payment_card): ?>
    <script>
        (function () {
            const amount = document.getElementById('payment-amount');
            const copyBtn = document.getElementById('copy-amount');

            function copyAmount() {
                if (!amount) return;
                amount.select();
                try {
                    document.execCommand('copy');
                    if (copyBtn) {
                        copyBtn.textContent = 'Copied!';
                        copyBtn.classList.add('copied');
                        setTimeout(() => {
                            copyBtn.textContent = 'Copy amount';
                            copyBtn.classList.remove('copied');
                        }, 1500);
                    }
                } catch (e) { }
            }

            if (copyBtn) copyBtn.addEventListener('click', copyAmount);
            // Auto-copy after page load
            if (amount) setTimeout(copyAmount, 200);
        })();
    </script>
<?php endif; ?>