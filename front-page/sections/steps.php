<?php
/**
 * Section: Onboarding / 3 steps (Design v2)
 *
 * A single accent panel framed by a thick page-coloured border. Copy block on
 * the left, three illustrated step cards on the right.
 *
 * The mock-up panels inside each card are decorative illustration, not real UI.
 * Their icons are aria-hidden, but every string in them still goes through
 * iptv_text() so it translates like the rest of the page.
 */

$steps_cta_field  = function_exists('get_field') ? get_field('steps_cta', get_option('page_on_front')) : null;
$steps_cta_url    = (!empty($steps_cta_field['url'])) ? $steps_cta_field['url'] : '#pricing';
$steps_cta_label  = (!empty($steps_cta_field['title'])) ? $steps_cta_field['title'] : iptv_text('steps_cta', 'Unlock Instant Access Today!');
$steps_cta_target = (!empty($steps_cta_field['target'])) ? ' target="' . esc_attr($steps_cta_field['target']) . '"' : '';
?>
<section class="dv2-journey" id="how-it-works">
    <div class="dv2-journey-copy">
        <h2 class="dv2-journey-title">
            <?php echo esc_html(iptv_text('steps_title', 'Begin your journey in under 2 minutes')); ?>
        </h2>
        <div class="dv2-journey-note">
            <span class="dv2-journey-note-mark" aria-hidden="true">$</span>
            <?php echo esc_html(iptv_text('steps_subtitle', '14-Day Money-Back Guarantee')); ?>
        </div>
        <a href="<?php echo esc_url($steps_cta_url); ?>" class="dv2-btn dv2-btn-white dv2-journey-cta"<?php echo $steps_cta_target; ?>>
            <?php echo esc_html($steps_cta_label); ?>
        </a>
    </div>

    <div class="dv2-journey-cards">
        <!-- Step 1 -->
        <div class="dv2-journey-card">
            <div class="dv2-journey-head">
                <span class="dv2-journey-num">1</span>
                <h3><?php echo esc_html(iptv_text('step_1_title', 'Sign up')); ?></h3>
            </div>
            <div class="dv2-journey-visual">
                <span class="dv2-journey-bar"></span>
                <span class="dv2-journey-bar dv2-journey-bar--short"></span>
                <span class="dv2-journey-chip dv2-journey-chip--sm">
                    <?php echo esc_html(iptv_text('step_1_visual', 'Sign up')); ?>
                </span>
            </div>
            <p><?php echo esc_html(iptv_text('step_1_desc', 'Register your account and instantly unlock access')); ?></p>
        </div>

        <!-- Step 2 -->
        <div class="dv2-journey-card">
            <div class="dv2-journey-head">
                <span class="dv2-journey-num">2</span>
                <h3><?php echo esc_html(iptv_text('step_2_title', 'Download')); ?></h3>
            </div>
            <div class="dv2-journey-visual">
                <span class="dv2-journey-hint">
                    <?php echo esc_html(iptv_text('step_2_visual', 'Select your device')); ?>
                </span>
                <div class="dv2-journey-tiles">
                    <span class="dv2-journey-tile" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="6" y="3" width="12" height="18" rx="2"></rect>
                            <path d="M10 6.5h4"></path>
                        </svg>
                    </span>
                    <span class="dv2-journey-tile" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M16 3c0 1.6-1.3 3-2.8 3 -.2-1.6 1.3-3.1 2.8-3zm3.2 12.6c-.5 1.2-1.6 2.9-2.7 2.9 -.9 0-1.2-.6-2.3-.6 -1.1 0-1.4.6-2.3.6 -1.2 0-2.5-1.9-3-3.1 -.9-2.3-.4-5.2 1.4-6.2 .8-.5 1.8-.4 2.5 0 .6.3 1 .4 1.6 0 .8-.5 1.8-.5 2.6-.1 .7.3 1.2.8 1.5 1.4 -1.6 1-1.5 3.4.7 4.2z"></path>
                        </svg>
                    </span>
                    <span class="dv2-journey-tile" aria-hidden="true">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7">
                            <rect x="3" y="5" width="18" height="12" rx="2"></rect>
                            <path d="M9 20h6"></path>
                        </svg>
                    </span>
                </div>
                <span class="dv2-journey-chip">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path d="M12 4v12"></path>
                        <path d="M6 12l6 6 6-6"></path>
                    </svg>
                    <?php echo esc_html(iptv_text('step_2_visual_cta', 'Download')); ?>
                </span>
            </div>
            <p><?php echo esc_html(iptv_text('step_2_desc', 'Choose the ideal app for your devices')); ?></p>
        </div>

        <!-- Step 3 -->
        <div class="dv2-journey-card">
            <div class="dv2-journey-head">
                <span class="dv2-journey-num">3</span>
                <h3><?php echo esc_html(iptv_text('step_3_title', 'Enjoy!')); ?></h3>
            </div>
            <div class="dv2-journey-visual">
                <span class="dv2-journey-hint">
                    <?php echo esc_html(iptv_text('step_3_visual', 'Enter your credentials')); ?>
                </span>
                <div class="dv2-journey-formats">
                    <!-- Protocol names, not natural language: intentionally literal. -->
                    <span class="dv2-journey-format">M3U</span>
                    <span class="dv2-journey-format">Xtream</span>
                    <span class="dv2-journey-format">MAG</span>
                </div>
                <span class="dv2-journey-chip dv2-journey-chip--ink">
                    <svg width="11" height="11" viewBox="0 0 12 12" fill="currentColor" aria-hidden="true">
                        <path d="M2 1l8 5-8 5z"></path>
                    </svg>
                    <?php echo esc_html(iptv_text('step_3_visual_cta', 'Watch now')); ?>
                </span>
            </div>
            <p><?php echo esc_html(iptv_text('step_3_desc', 'Sign in with your credentials and enjoy streaming!')); ?></p>
        </div>
    </div>
</section>
