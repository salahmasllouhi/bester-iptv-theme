<!-- Header Section -->
<?php
$nav_home = trailingslashit(home_url('/'));

// The guide's real slug. home_url('/user-guide/') was a 404.
$nav_guide = function_exists('iptv_page_url')
    ? iptv_page_url('iptv-guide-setup-apps-devices-tips', $nav_home)
    : $nav_home;
?>
<header class="site-header" id="site-header">
    <div class="container nav-container">
        <?php iptv_brand_logo(); ?>
        <?php if (has_nav_menu('primary')): ?>
            <?php wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => 'nav',
                'container_class' => 'nav-links',
                'menu_class' => '',
                'fallback_cb' => false,
            )); ?>
        <?php else: ?>
            <nav class="nav-links">
                <a href="<?php echo esc_url($nav_home); ?>"><?php echo esc_html(iptv_text('nav_link_home', 'Home')); ?></a>
                <a href="<?php echo esc_url($nav_home . '#features'); ?>"><?php echo esc_html(iptv_text('nav_link_features', 'Features')); ?></a>
                <a href="<?php echo esc_url($nav_home . '#pricing'); ?>"><?php echo esc_html(iptv_text('nav_link_pricing', 'Pricing')); ?></a>
                <!-- Blog lives in the footer only. -->
                <a href="<?php echo esc_url($nav_guide); ?>"><?php echo esc_html(iptv_text('nav_link_guide', 'User Guide')); ?></a>
                <a href="<?php echo esc_url($nav_home . '#contact'); ?>"><?php echo esc_html(iptv_text('nav_link_contact', 'Contact')); ?></a>
                <a href="https://panel.nordictv.io/login"><?php echo esc_html(iptv_text('nav_link_account', 'My Account')); ?></a>
            </nav>
        <?php endif; ?>
        <div class="nav-right">
            <a href="<?php echo esc_url($nav_home . '#pricing'); ?>" class="nav-btn nav-btn-primary"><?php echo esc_html(iptv_text('nav_cta_label', 'Get Access Now')); ?></a>
        </div>
        <button class="mobile-menu-toggle" onclick="toggleMobileMenu()">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobile-menu">
    <button class="mobile-menu-close" onclick="toggleMobileMenu()">&times;</button>

    <?php if (has_nav_menu('primary')): ?>
        <?php wp_nav_menu(array(
            'theme_location' => 'primary',
            'container' => false,
            'menu_class' => '',
            'fallback_cb' => false,
        )); ?>
    <?php else: ?>
        <a href="<?php echo esc_url($nav_home); ?>"><?php echo esc_html(iptv_text('nav_link_home', 'Home')); ?></a>
        <a href="<?php echo esc_url($nav_home . '#features'); ?>"><?php echo esc_html(iptv_text('nav_link_features', 'Features')); ?></a>
        <a href="<?php echo esc_url($nav_home . '#pricing'); ?>"><?php echo esc_html(iptv_text('nav_link_pricing', 'Pricing')); ?></a>
        <!-- Blog lives in the footer only. -->
        <a href="<?php echo esc_url($nav_guide); ?>"><?php echo esc_html(iptv_text('nav_link_guide', 'User Guide')); ?></a>
        <a href="<?php echo esc_url($nav_home . '#contact'); ?>"><?php echo esc_html(iptv_text('nav_link_contact', 'Contact')); ?></a>
        <a href="https://panel.nordictv.io/login"><?php echo esc_html(iptv_text('nav_link_account', 'My Account')); ?></a>
    <?php endif; ?>

    <a href="<?php echo esc_url($nav_home . '#pricing'); ?>" class="nav-btn nav-btn-primary" style="margin-top:1rem;"
        onclick="toggleMobileMenu()"><?php echo esc_html(iptv_text('nav_cta_label', 'Get Access Now')); ?></a>
</div>