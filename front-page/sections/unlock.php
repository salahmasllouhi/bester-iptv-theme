<section class="devices">
    <div class="section-header">
        <div class="section-tag"><?php echo esc_html(iptv_text('devices_tag', 'Compatibility')); ?></div>
        <h2 class="section-title"><?php echo iptv_text('devices_title', 'Watch On'); ?> <span
                class="gradient-text"><?php echo iptv_text('devices_title_span', 'Any Device'); ?></span></h2>
        <p class="section-subtitle">
            <?php echo esc_html(iptv_text('devices_subtitle', 'Works flawlessly on Smart TV, Android, iOS, Firestick, MAG, and more.')); ?>
        </p>
    </div>
    <!-- Devices Marquee -->
    <div class="devices-marquee">
        <div class="devices-track">
            <?php
            // Get device list from settings (comma separated)
            $devices_str = iptv_text('device_list', 'Apple TV, Firestick, Smart TV, Roku, Android, iPhone/iPad, Browser, Shield, MAG Box, Chromecast, Windows, MacOS');
            $devices = array_map('trim', explode(',', $devices_str));

            // duplicate for infinite scroll
            for ($i = 0; $i < 2; $i++) {
                foreach ($devices as $device) {
                    if (!empty($device)) {
                        echo '<div class="device-text-item">' . esc_html($device) . '</div>';
                    }
                }
            }
            ?>
        </div>
    </div>
</section>

<style>
    /* Devices Marquee Styles */
    .devices {
        overflow: hidden;
        padding: var(--space-xl) 0;
    }

    .devices-marquee {
        position: relative;
        width: 100vw;
        margin-left: 50%;
        transform: translateX(-50%);
        /* Mask for fade effect on edges */
        mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
    }

    .devices-track {
        display: flex;
        gap: var(--space-xl);
        width: max-content;
        animation: scrollDevices 30s linear infinite;
        padding: var(--space-md) 0;
    }

    @keyframes scrollDevices {
        0% {
            transform: translateX(0);
        }

        100% {
            transform: translateX(-50%);
        }
    }

    .device-text-item {
        font-size: 1.5rem;
        font-weight: 700;
        /* Bold names */
        color: var(--text-secondary);
        /* or primary? User said 'use bold names', let's make them prominent */
        opacity: 0.6;
        white-space: nowrap;
        transition: all 0.3s ease;
    }

    .device-text-item:hover {
        color: var(--color-teal);
        opacity: 1;
        transform: scale(1.1);
    }

    @media (max-width: 768px) {
        .device-text-item {
            font-size: 1.2rem;
        }
    }
</style>