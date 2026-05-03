<section class="steps">
    <div class="steps-container">
        <div class="section-header">
            <div class="section-tag">
                <?php echo esc_html(iptv_text('steps_tag', 'Easy Setup')); ?>
            </div>
            <h2 class="section-title">
                <?php echo iptv_text('steps_title', 'Start Streaming in'); ?> <span class="gradient-text">
                    <?php echo iptv_text('steps_title_span', '3 Steps'); ?>
                </span>
            </h2>
            <p class="section-subtitle">
                <?php echo esc_html(iptv_text('steps_subtitle', 'Get up and running in minutes, not hours.')); ?>
            </p>
        </div>
        <div class="steps-grid">
            <!-- Step 1 -->
            <div class="step-card animate-on-scroll">
                <div class="step-number">
                    <?php echo esc_html(iptv_text('step_1_badge', '1')); ?>
                </div>
                <h3 class="step-title">
                    <?php echo esc_html(iptv_text('step_1_title', 'Choose Your Plan')); ?>
                </h3>
                <p class="step-desc">
                    <?php echo esc_html(iptv_text('step_1_desc', 'Browse our flexible subscription packages and select the one that fits your budget and device needs.')); ?>
                </p>
            </div>
            <!-- Step 2 -->
            <div class="step-card animate-on-scroll">
                <div class="step-number">
                    <?php echo esc_html(iptv_text('step_2_badge', '2')); ?>
                </div>
                <h3 class="step-title">
                    <?php echo esc_html(iptv_text('step_2_title', 'Complete Payment')); ?>
                </h3>
                <p class="step-desc">
                    <?php echo esc_html(iptv_text('step_2_desc', 'Checkout securely using our encrypted payment gateway. We accept major cards and crypto options.')); ?>
                </p>
            </div>
            <!-- Step 3 -->
            <div class="step-card animate-on-scroll">
                <div class="step-number">
                    <?php echo esc_html(iptv_text('step_3_badge', '3')); ?>
                </div>
                <h3 class="step-title">
                    <?php echo esc_html(iptv_text('step_3_title', 'Start Watching')); ?>
                </h3>
                <p class="step-desc">
                    <?php echo esc_html(iptv_text('step_3_desc', 'Our team will configure your account and send login credentials via email. Download the app and enjoy!')); ?>
                </p>
            </div>
        </div>
        <?php
        $steps_cta_field  = function_exists('get_field') ? get_field('steps_cta', get_option('page_on_front')) : null;
        $steps_cta_url    = (!empty($steps_cta_field['url'])) ? $steps_cta_field['url'] : '#pricing';
        $steps_cta_label  = (!empty($steps_cta_field['title'])) ? $steps_cta_field['title'] : iptv_text('steps_cta', 'Get Started Now');
        $steps_cta_target = (!empty($steps_cta_field['target'])) ? ' target="' . esc_attr($steps_cta_field['target']) . '"' : '';
        ?>
        <div style="text-align:center;margin-top:var(--space-xl);">
            <a href="<?php echo esc_url($steps_cta_url); ?>" class="btn btn-primary"<?php echo $steps_cta_target; ?>>
                <?php echo esc_html($steps_cta_label); ?>
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <line x1="5" y1="12" x2="19" y2="12" />
                    <polyline points="12 5 19 12 12 19" />
                </svg>
            </a>
        </div>
    </div>
</section>