<?php
$front_page_id   = get_option('page_on_front');
$badge           = get_field('dashboard_badge', $front_page_id)    ?: 'Member Area';
$title           = get_field('dashboard_title', $front_page_id)    ?: 'Your Personal Streaming Dashboard';
$subtitle        = get_field('dashboard_subtitle', $front_page_id) ?: 'Once you subscribe, manage everything in one place.';
$cta_label       = get_field('dashboard_cta_label', $front_page_id) ?: 'Access Your Dashboard →';
$cta_url         = get_field('dashboard_cta_url', $front_page_id)   ?: 'https://panel.nordictv.io/login';
$features        = get_field('dashboard_features', $front_page_id);

if (!$features) {
    $features = array(
        array('icon' => '⚡', 'title' => 'Instant Activation',  'description' => 'Your account goes live the moment your order is placed. No waiting, no delays.'),
        array('icon' => '🔑', 'title' => 'Your Credentials',    'description' => 'Access your M3U link, Xtream username & password anytime. One click to copy.'),
        array('icon' => '🔄', 'title' => 'Easy Renewals',       'description' => 'Renew or upgrade your plan directly from your dashboard in a few clicks.'),
        array('icon' => '💬', 'title' => '24/7 Support',        'description' => 'Reach our team via Live Chat, WhatsApp, Telegram, or Email — inside your dashboard.'),
    );
}
?>
<section class="member-dashboard">
    <div class="dashboard-container">
        <div class="section-header">
            <div class="section-tag"><?php echo esc_html($badge); ?></div>
            <h2 class="section-title"><?php echo esc_html($title); ?></h2>
            <p class="section-subtitle"><?php echo esc_html($subtitle); ?></p>
        </div>

        <?php if (!empty($features)) : ?>
        <div class="dashboard-grid">
            <?php foreach ($features as $feature) :
                $icon  = !empty($feature['icon']['url']) ? '<img src="' . esc_url($feature['icon']['url']) . '" alt="" class="dashboard-feature-img">' : esc_html($feature['icon']);
            ?>
            <div class="dashboard-card animate-on-scroll">
                <div class="dashboard-card-icon"><?php echo $icon; ?></div>
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
