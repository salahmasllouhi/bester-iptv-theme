<?php
$front_page_id = get_option('page_on_front');
$badge         = 'Member Area';
$title         = 'Your Personal Streaming Dashboard';
$subtitle      = 'Once you subscribe, manage everything in one place.';
$cta_label     = 'Access Your Dashboard →';
$cta_url       = 'https://panel.nordictv.io/login';
$features      = array();

if (function_exists('get_field')) {
    $badge     = get_field('dashboard_badge', $front_page_id)     ?: $badge;
    $title     = get_field('dashboard_title', $front_page_id)     ?: $title;
    $subtitle  = get_field('dashboard_subtitle', $front_page_id)  ?: $subtitle;
    $cta_label = get_field('dashboard_cta_label', $front_page_id) ?: $cta_label;
    $cta_url   = get_field('dashboard_cta_url', $front_page_id)   ?: $cta_url;
    $features  = get_field('dashboard_features', $front_page_id)  ?: array();
}

// Hardcoded SVG icons — echoed directly (not via kses) since they are fully controlled strings
$svg_activation  = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>';
$svg_credentials = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12.65 10A6 6 0 1 0 17 14.35V13h2v-2h1v-2h-4.35A6 6 0 0 0 12.65 10zM7 16a2 2 0 1 1 0-4 2 2 0 0 1 0 4z"/></svg>';
$svg_renewal     = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M12 4V1L8 5l4 4V6c3.31 0 6 2.69 6 6 0 1.01-.25 1.97-.7 2.8l1.46 1.46A7.93 7.93 0 0 0 20 12c0-4.42-3.58-8-8-8zm0 14c-3.31 0-6-2.69-6-6 0-1.01.25-1.97.7-2.8L5.24 7.74A7.93 7.93 0 0 0 4 12c0 4.42 3.58 8 8 8v3l4-4-4-4v3z"/></svg>';
$svg_support     = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M20 2H4c-1.1 0-2 .9-2 2v18l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>';

if (!$features) {
    $features = array(
        array('svg' => $svg_activation,  'title' => 'Instant Activation',  'description' => 'Your account goes live the moment your order is placed. No waiting, no delays.'),
        array('svg' => $svg_credentials, 'title' => 'Your Credentials',    'description' => 'Access your M3U link, Xtream username & password anytime. One click to copy.'),
        array('svg' => $svg_renewal,     'title' => 'Easy Renewals',       'description' => 'Renew or upgrade your plan directly from your dashboard in a few clicks.'),
        array('svg' => $svg_support,     'title' => '24/7 Support',        'description' => 'Reach our team via Live Chat, WhatsApp, Telegram, or Email — inside your dashboard.'),
    );
}
?>
<section class="member-dashboard">
    <div class="dashboard-inner">
        <div class="section-header">
            <div class="section-tag"><?php echo esc_html($badge); ?></div>
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
        </div>

        <?php if (!empty($features)) : ?>
        <div class="dashboard-grid">
            <?php foreach ($features as $feature) : ?>
            <div class="dashboard-card animate-on-scroll">
                <div class="dashboard-card-icon">
                    <?php if (!empty($feature['icon']['url'])) : ?>
                        <img src="<?php echo esc_url($feature['icon']['url']); ?>" alt="" class="dashboard-feature-img">
                    <?php elseif (!empty($feature['svg'])) : ?>
                        <?php echo $feature['svg']; // trusted hardcoded SVG ?>
                    <?php elseif (!empty($feature['icon'])) : ?>
                        <?php echo $feature['icon']; // ACF text field — echoed as-is ?>
                    <?php endif; ?>
                </div>
                <div class="dashboard-card-body">
                    <h3 class="dashboard-card-title"><?php echo esc_html($feature['title']); ?></h3>
                    <p class="dashboard-card-desc"><?php echo esc_html($feature['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <div class="dashboard-cta">
            <a href="<?php echo esc_url($cta_url); ?>" class="btn btn-primary">
                <?php echo esc_html($cta_label); ?>
            </a>
        </div>
    </div>
</section>
