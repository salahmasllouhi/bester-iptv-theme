<section class="cta">
    <div class="cta-box">
    <div class="cta-content">
        <h2>
            <?php echo esc_html(iptv_text('cta_title', 'Ready to Start Streaming?')); ?>
        </h2>
        <p>
            <?php echo esc_html(iptv_text('cta_subtitle', 'Join thousands of satisfied customers who switched to premium IPTV streaming.')); ?>
        </p>
        <?php
        $cta_btn_field  = function_exists('get_field') ? get_field('cta_btn_text', get_option('page_on_front')) : null;
        $cta_btn_url    = (!empty($cta_btn_field['url'])) ? $cta_btn_field['url'] : '#pricing';
        $cta_btn_label  = (!empty($cta_btn_field['title'])) ? $cta_btn_field['title'] : iptv_text('cta_btn_text', 'Start Streaming Now');
        $cta_btn_target = (!empty($cta_btn_field['target'])) ? ' target="' . esc_attr($cta_btn_field['target']) . '"' : '';
        ?>
        <a href="<?php echo esc_url($cta_btn_url); ?>" class="btn btn-primary"<?php echo $cta_btn_target; ?>>
            <?php echo esc_html($cta_btn_label); ?>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <line x1="5" y1="12" x2="19" y2="12" />
                <polyline points="12 5 19 12 12 19" />
            </svg>
        </a>
        <div class="cta-features">
            <div class="cta-feature">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z" />
                </svg>
                <?php echo esc_html(iptv_text('cta_f1', '256-bit SSL Encryption')); ?>
            </div>
            <div class="cta-feature">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polyline points="20 6 9 17 4 12" />
                </svg>
                <?php echo esc_html(iptv_text('cta_f2', 'Instant Activation')); ?>
            </div>
            <div class="cta-feature">
                <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
                <?php echo esc_html(iptv_text('cta_f3', '24/7 Customer Support')); ?>
            </div>
        </div>
    </div>
    </div>
</section>