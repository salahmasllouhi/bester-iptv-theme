/**
 * Checkout Page JavaScript
 * Decomposed from template-store-checkout.php
 */

// Submit checkout form via AJAX (WooCommerce way)
function submitCheckout() {
    // Get selected payment method from radio buttons
    const selectedPayment = document.querySelector('.payment-option input[type="radio"]:checked');
    if (selectedPayment) {
        document.getElementById('hidden_payment_method').value = selectedPayment.value;
    }

    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);

    // Show loading state
    const btn = document.querySelector('.place-order-btn');
    btn.disabled = true;
    btn.textContent = 'Processing...';

    // Get the AJAX URL from PHP (set in the template)
    const ajaxUrl = typeof checkoutParams !== 'undefined' ? checkoutParams.ajaxUrl : '/wp-admin/admin-ajax.php';

    // Submit via AJAX to WooCommerce
    fetch(ajaxUrl, {
        method: 'POST',
        body: new URLSearchParams(formData).toString() + '&action=woocommerce_checkout',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.result === 'success') {
                // Redirect to thank you page
                window.location.href = data.redirect;
            } else if (data.result === 'failure') {
                // Show error messages
                btn.disabled = false;
                btn.textContent = 'Complete Purchase';
                const msg = data.messages ? data.messages.replace(/<[^>]*>/g, '').trim() : 'An error occurred. Please try again.';
                alert(msg);
            } else {
                // Unknown response, show error
                btn.disabled = false;
                btn.textContent = 'Complete Purchase';
                console.log('Checkout response:', data);
                alert('Unexpected response. Please try again.');
            }
        })
        .catch(error => {
            console.error('Checkout error:', error);
            btn.disabled = false;
            btn.textContent = 'Complete Purchase';
            alert('An error occurred. Please try again.');
        });
}

// Initialize payment method on page load
document.addEventListener('DOMContentLoaded', function () {
    const firstPayment = document.querySelector('.payment-option input[type="radio"]');
    if (firstPayment) {
        const hiddenField = document.getElementById('hidden_payment_method');
        if (hiddenField) {
            hiddenField.value = firstPayment.value;
        }
    }
});

// Apply coupon function
function applyCoupon() {
    const code = document.getElementById('coupon_code');
    if (!code) return;

    const couponCode = code.value.trim();
    if (couponCode && typeof wc_checkout_params !== 'undefined') {
        jQuery.post(wc_checkout_params.ajax_url, {
            action: 'apply_coupon',
            coupon_code: couponCode,
            security: wc_checkout_params.apply_coupon_nonce
        }, function () {
            location.reload();
        });
    }
}

// Handle remove item - stay on checkout page
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.cart-item-remove').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const url = this.getAttribute('href');
            // Add return_to parameter to stay on checkout
            const checkoutUrl = window.location.href.split('?')[0];
            window.location.href = url + '&_wp_http_referer=' + encodeURIComponent(checkoutUrl);
        });
    });
});
