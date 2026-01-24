<!-- Sports Section -->
<section class="ultimate-experience">
    <div class="container">
        <div class="sports-grid" id="sportsCarousel">
            <?php
            // Sports content (same across all languages - no translation needed)
            $sports = array(
                array('name' => 'NFL', 'subtitle' => 'American Football', 'features' => array('Sunday Ticket', 'RedZone Channel', 'Super Bowl 4K')),
                array('name' => 'NBA', 'subtitle' => 'Basketball', 'features' => array('All Regular Season', 'Playoffs & Finals', 'March Madness')),
                array('name' => 'MLB', 'subtitle' => 'Baseball', 'features' => array('Full Season', 'World Series', 'All-Star Game')),
                array('name' => 'Soccer', 'subtitle' => 'MLS & International', 'features' => array('Premier League', 'Champions League', 'World Cup')),
                array('name' => 'NHL', 'subtitle' => 'Ice Hockey', 'features' => array('Regular Season', 'Stanley Cup', 'Winter Classic')),
                array('name' => 'Tennis', 'subtitle' => 'ATP & WTA', 'features' => array('Grand Slams', 'US Open', 'Wimbledon')),
                array('name' => 'Golf', 'subtitle' => 'PGA Tour', 'features' => array('Masters', 'PGA Championship', 'US Open')),
                array('name' => 'Motorsports', 'subtitle' => 'NASCAR & F1', 'features' => array('NASCAR Cup', 'Formula 1', 'Indy 500')),
                array('name' => 'Combat Sports', 'subtitle' => 'Boxing & MMA', 'features' => array('UFC PPV', 'Boxing Title Fights', 'Bellator')),
                array('name' => 'Track & Field', 'subtitle' => 'World Athletics', 'features' => array('World Championships', 'Diamond League', 'Olympic Games')),
            );

            $live_text = 'LIVE NOW';

            foreach ($sports as $sport):
                ?>
                <div class="sport-card">
                    <div class="sport-header">
                        <div class="sport-icon">
                            <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="sport-name">
                            <h3>
                                <?php echo esc_html($sport['name']); ?>
                            </h3>
                            <span>
                                <?php echo esc_html($sport['subtitle']); ?>
                            </span>
                        </div>
                    </div>
                    <ul class="sport-features">
                        <?php foreach ($sport['features'] as $feature): ?>
                            <li>
                                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                <?php echo esc_html($feature); ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="live-badge">
                        <div class="live-dot"></div><?php echo esc_html($live_text); ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>