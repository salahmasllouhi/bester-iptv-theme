<?php
/**
 * Section: Journey / 3 steps (Design v2)
 * Purple gradient panel with three illustrated step cards.
 */

$steps_cta_field  = function_exists('get_field') ? get_field('steps_cta', get_option('page_on_front')) : null;
$steps_cta_url    = (!empty($steps_cta_field['url'])) ? $steps_cta_field['url'] : '#pricing';
$steps_cta_label  = (!empty($steps_cta_field['title'])) ? $steps_cta_field['title'] : iptv_text('steps_cta', 'Start Watching Now');
$steps_cta_target = (!empty($steps_cta_field['target'])) ? ' target="' . esc_attr($steps_cta_field['target']) . '"' : '';
?>
<section class="dv2-journey">
    <div>
        <h2 class="dv2-journey-title">
            <?php echo esc_html(iptv_text('steps_title', 'Up and running in 3 minutes')); ?>
        </h2>
        <div class="dv2-journey-note">
            <span class="dv2-journey-note-mark" aria-hidden="true">$</span>
            <?php echo esc_html(iptv_text('steps_subtitle', 'No technician. No hardware. No waiting.')); ?>
        </div>
        <a href="<?php echo esc_url($steps_cta_url); ?>" class="dv2-btn dv2-btn-white"<?php echo $steps_cta_target; ?>>
            <?php echo esc_html($steps_cta_label); ?>
        </a>
    </div>

    <div class="dv2-journey-cards">
        <!-- Step 1 -->
        <div class="dv2-journey-card">
            <div class="dv2-journey-visual" aria-hidden="true">
                <div class="dv2-journey-bar"></div>
                <div class="dv2-journey-bar"></div>
                <div class="dv2-journey-chip"><?php echo esc_html(iptv_text('step_1_visual', 'Choose plan')); ?></div>
            </div>
            <span class="dv2-journey-num">1</span>
            <div>
                <h3><?php echo esc_html(iptv_text('step_1_title', 'Choose Your Plan')); ?></h3>
                <p><?php echo esc_html(iptv_text('step_1_desc', 'Pick the number of devices and duration that suits you. Prices start from $8/month.')); ?></p>
            </div>
        </div>

        <!-- Step 2 -->
        <div class="dv2-journey-card">
            <div class="dv2-journey-visual" style="align-items:center;" aria-hidden="true">
                <div style="color:var(--dv2-purple);font-size:12px;font-weight:700;text-align:center;">
                    <?php echo esc_html(iptv_text('step_2_visual', 'Check your inbox')); ?>
                </div>
                <div style="display:flex;gap:8px;justify-content:center;">
                    <span style="width:26px;height:26px;border:1px solid #e4dcf5;border-radius:6px;"></span>
                    <span style="width:26px;height:26px;border:1px solid #e4dcf5;border-radius:6px;"></span>
                    <span style="width:26px;height:26px;border:1px solid #e4dcf5;border-radius:6px;"></span>
                </div>
                <div class="dv2-journey-chip" style="align-self:center;padding:4px 22px;">↓</div>
            </div>
            <span class="dv2-journey-num">2</span>
            <div>
                <h3><?php echo esc_html(iptv_text('step_2_title', 'Get Your Credentials')); ?></h3>
                <p><?php echo esc_html(iptv_text('step_2_desc', 'Receive your login details instantly by email after payment — no waiting.')); ?></p>
            </div>
        </div>

        <!-- Step 3 -->
        <div class="dv2-journey-card">
            <div class="dv2-journey-visual"
                style="border:1px solid #efe9fb;border-radius:8px;align-items:center;justify-content:center;position:relative;"
                aria-hidden="true">
                <span style="position:absolute;top:8px;left:10px;font-size:11px;color:var(--dv2-ink);font-weight:700;">
                    <?php echo esc_html(iptv_text('step_3_visual', 'Live')); ?> <span style="color:#e5484d;">•</span>
                </span>
                <span
                    style="width:34px;height:34px;border-radius:999px;border:1.5px solid var(--dv2-purple);display:inline-flex;align-items:center;justify-content:center;color:var(--dv2-purple);">▶</span>
            </div>
            <span class="dv2-journey-num">3</span>
            <div>
                <h3><?php echo esc_html(iptv_text('step_3_title', 'Start Watching')); ?></h3>
                <p><?php echo esc_html(iptv_text('step_3_desc', 'Open your preferred app, enter your credentials, and enjoy 35,000+ channels immediately.')); ?></p>
            </div>
        </div>
    </div>
</section>
