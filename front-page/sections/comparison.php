<?php
// Get content settings
$all_content = get_option('iptv_content', array());
$lang = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : 'en';
$content = isset($all_content[$lang]) ? $all_content[$lang] : (isset($all_content['en']) ? $all_content['en'] : array());

// Helper function safely get field
function get_comp_field($key, $content, $default = '')
{
    return isset($content[$key]) && !empty($content[$key]) ? $content[$key] : $default;
}

// Get Left Column Content
$comp_badge = get_comp_field('comp_badge', $content, '⚡ Save Thousands Yearly');
$comp_title_main = get_comp_field('comp_title_main', $content, 'Stop Overpaying for Cable.');
$comp_title_sub = get_comp_field('comp_title_sub', $content, 'Switch to Premium IPTV.');
$comp_desc = get_comp_field('comp_desc', $content, 'Why waste money? Get instant access to 33,000+ live channels and 150,000+ movies & shows in stunning 4K. No contracts, no hidden fees—just pure entertainment.');
$comp_cta_text = get_comp_field('comp_cta_text', $content, 'Start Watching Now →');
$comp_cta_link = get_comp_field('comp_cta_link', $content, '#pricing');

// Get Table Header Content
$col1 = get_comp_field('comp_col_1', $content, 'Traditional Cable');
$col2 = get_comp_field('comp_col_2', $content, 'Nordic IPTV');

// Define Rows (Label, Val1, Val2)
$rows = array(
    array(
        'label' => get_comp_field('comp_row_1_label', $content, 'Live Channels'),
        'val1' => get_comp_field('comp_row_1_val_1', $content, '200+ avg'),
        'val2' => get_comp_field('comp_row_1_val_2', $content, '33,000+'),
        'highlight' => true
    ),
    array(
        'label' => get_comp_field('comp_row_2_label', $content, 'VOD Content'),
        'val1' => get_comp_field('comp_row_2_val_1', $content, 'Limited'),
        'val2' => get_comp_field('comp_row_2_val_2', $content, '150,000+ titles'),
        'highlight' => true
    ),
    array(
        'label' => get_comp_field('comp_row_3_label', $content, 'Annual Cost'),
        'val1' => get_comp_field('comp_row_3_val_1', $content, '$1,200+'),
        'val2' => get_comp_field('comp_price', $content, '$69.99'), // Currency Aware Field
        'highlight' => true,
        'is_price' => true
    ),
    array(
        'label' => get_comp_field('comp_row_4_label', $content, 'Sports Packages'),
        'val1' => get_comp_field('comp_row_4_val_1', $content, '$$ Extra Fees'),
        'val2' => get_comp_field('comp_row_4_val_2', $content, 'Included Free'),
        'highlight' => true
    ),
    array(
        'label' => get_comp_field('comp_row_5_label', $content, 'PPV Events'),
        'val1' => get_comp_field('comp_row_5_val_1', $content, '$70+/event'),
        'val2' => get_comp_field('comp_row_5_val_2', $content, 'Included Free'),
        'highlight' => true
    ),
    array(
        'label' => get_comp_field('comp_row_7_label', $content, 'Contracts'),
        'val1' => get_comp_field('comp_row_7_val_1', $content, 'Required'),
        'val2' => get_comp_field('comp_row_7_val_2', $content, 'Never'),
        'highlight' => true
    ),
    array(
        'label' => get_comp_field('comp_row_8_label', $content, 'Multi-Device'),
        'val1' => get_comp_field('comp_row_8_val_1', $content, 'Extra fees'),
        'val2' => get_comp_field('comp_row_8_val_2', $content, 'Included'),
        'highlight' => true
    ),
);
?>

<style>
    /* Comparison Section Styles */
    .comparison-section {
        padding: 100px 0;
        /* Soft gradient background */
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        position: relative;
        overflow: hidden;
    }

    /* Split Layout Container */
    .comparison-container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 60px;
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Left Column: Content */
    .comp-content {
        flex: 1;
        max-width: 500px;
    }

    .comp-badge {
        display: inline-block;
        background: #FFC107;
        /* Gold/Yellow */
        color: #1e293b;
        font-weight: 700;
        font-size: 0.9rem;
        padding: 8px 16px;
        border-radius: 50px;
        margin-bottom: 24px;
        box-shadow: 0 4px 12px rgba(255, 193, 7, 0.3);
    }

    .comp-content h2 {
        font-size: 2.8rem;
        line-height: 1.1;
        font-weight: 800;
        color: #0f172a;
        margin-bottom: 24px;
        font-family: 'Inter', sans-serif;
    }

    .comp-highlight {
        color: #8b5cf6;
        /* Purple highlight */
    }

    .comp-content p {
        font-size: 1.125rem;
        line-height: 1.6;
        color: #475569;
        margin-bottom: 40px;
    }

    .comp-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #8b5cf6;
        /* Purple button */
        color: #fff;
        font-weight: 600;
        padding: 16px 32px;
        border-radius: 50px;
        text-decoration: none;
        transition: all 0.3s ease;
        box-shadow: 0 10px 20px rgba(139, 92, 246, 0.3);
    }

    .comp-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px rgba(139, 92, 246, 0.4);
        background: #7c3aed;
    }

    /* Right Column: Table */
    .comp-table-wrapper {
        flex: 1.2;
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
    }

    .comp-new-table {
        width: 100%;
        border-collapse: collapse;
    }

    .comp-new-table th {
        padding: 24px;
        text-align: left;
        font-weight: 600;
    }

    /* Header Styling */
    .comp-header-row th {
        background: #8b5cf6;
        /* Purple Header */
        color: #fff;
        font-size: 1rem;
        text-transform: capitalize;
    }

    .comp-header-row th:first-child {
        border-top-left-radius: 20px;
        /* Internal radius fix if needed */
    }

    .comp-header-feature {
        width: 30%;
    }

    .comp-header-competitor {
        width: 35%;
        background: #7c3aed !important;
        /* Slightly darker purple */
    }

    .comp-header-us {
        width: 35%;
        font-weight: 700 !important;
    }

    /* Body Styling */
    .comp-new-table td {
        padding: 20px 24px;
        border-bottom: 1px solid #f1f5f9;
        color: #334155;
        font-size: 0.95rem;
        font-weight: 500;
    }

    .comp-new-table tr:last-child td {
        border-bottom: none;
    }

    /* Right Column Highlight (Our Service) */
    .comp-new-table td:nth-child(3) {
        color: #8b5cf6;
        /* Purple text */
        font-weight: 700;
        background: #fdfbff;
        /* Very subtle purple tint */
    }

    /* Check Icons */
    .comp-check-icon {
        display: inline-block;
        width: 18px;
        height: 18px;
        margin-left: 6px;
        color: #8b5cf6;
        vertical-align: text-bottom;
    }

    /* Responsive */
    @media (max-width: 1024px) {
        .comparison-container {
            flex-direction: column;
            text-align: center;
            gap: 40px;
        }

        .comp-content {
            max-width: 100%;
        }

        .comp-content h2 {
            font-size: 2.5rem;
        }
    }

    @media (max-width: 640px) {

        .comp-new-table th,
        .comp-new-table td {
            padding: 15px;
            font-size: 0.85rem;
        }
    }
</style>

<section class="comparison-section">
    <div class="comparison-container">
        <!-- Left Column: Text Content -->
        <div class="comp-content">
            <span class="comp-badge"><?php echo esc_html($comp_badge); ?></span>
            <h2>
                <?php echo esc_html($comp_title_main); ?><br>
                <span class="comp-highlight"><?php echo esc_html($comp_title_sub); ?></span>
            </h2>
            <p><?php echo esc_html($comp_desc); ?></p>
            <a href="<?php echo esc_attr($comp_cta_link); ?>" class="comp-btn">
                <?php echo esc_html($comp_cta_text); ?>
            </a>
        </div>

        <!-- Right Column: Comparison Table -->
        <div class="comp-table-wrapper">
            <table class="comp-new-table">
                <thead>
                    <tr class="comp-header-row">
                        <th class="comp-header-feature">Feature</th>
                        <th class="comp-header-competitor"><?php echo esc_html($col1); ?></th>
                        <th class="comp-header-us"><?php echo esc_html($col2); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?php echo esc_html($row['label']); ?></td>
                            <td><?php echo esc_html($row['val1']); ?></td>
                            <td>
                                <?php if (isset($row['is_price']) && $row['is_price']): ?>
                                    <span id="comp-annual-price"><?php echo esc_html($row['val2']); ?></span>
                                <?php else: ?>
                                    <?php echo esc_html($row['val2']); ?>
                                <?php endif; ?>

                                <?php if (stripos($row['val2'], 'Included') !== false || stripos($row['val2'], 'Never') !== false || stripos($row['val2'], 'titles') !== false || $row['val2'] === '33,000+' || isset($row['is_price'])): ?>
                                    <svg class="comp-check-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"
                                        stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                                    </svg>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</section>