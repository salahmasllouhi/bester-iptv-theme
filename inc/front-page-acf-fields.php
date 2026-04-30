<?php
/**
 * ACF field group registration for the front page.
 * Covers: hero dual CTAs + Member Dashboard section.
 */
if (!function_exists('acf_add_local_field_group')) {
    return;
}

acf_add_local_field_group(array(
    'key'      => 'group_front_page_custom',
    'title'    => 'Front Page — Custom Fields',
    'fields'   => array(

        // ── Hero: Primary CTA ──────────────────────────────────────────────
        array(
            'key'           => 'field_hero_primary_cta_label',
            'label'         => 'Hero Primary CTA Label',
            'name'          => 'hero_primary_cta_label',
            'type'          => 'text',
            'default_value' => 'Get Started',
        ),
        array(
            'key'           => 'field_hero_primary_cta_url',
            'label'         => 'Hero Primary CTA URL',
            'name'          => 'hero_primary_cta_url',
            'type'          => 'url',
            'default_value' => '#plans',
        ),

        // ── Hero: Secondary CTA ────────────────────────────────────────────
        array(
            'key'           => 'field_hero_secondary_cta_label',
            'label'         => 'Hero Secondary CTA Label',
            'name'          => 'hero_secondary_cta_label',
            'type'          => 'text',
            'default_value' => 'My Dashboard',
        ),
        array(
            'key'           => 'field_hero_secondary_cta_url',
            'label'         => 'Hero Secondary CTA URL',
            'name'          => 'hero_secondary_cta_url',
            'type'          => 'url',
            'default_value' => 'https://panel.nordictv.io/login',
        ),

        // ── Member Dashboard section ───────────────────────────────────────
        array(
            'key'           => 'field_dashboard_badge',
            'label'         => 'Dashboard Badge',
            'name'          => 'dashboard_badge',
            'type'          => 'text',
            'default_value' => 'Member Area',
        ),
        array(
            'key'           => 'field_dashboard_title',
            'label'         => 'Dashboard Title',
            'name'          => 'dashboard_title',
            'type'          => 'text',
            'default_value' => 'Your Personal Streaming Dashboard',
        ),
        array(
            'key'           => 'field_dashboard_subtitle',
            'label'         => 'Dashboard Subtitle',
            'name'          => 'dashboard_subtitle',
            'type'          => 'textarea',
            'default_value' => 'Once you subscribe, manage everything in one place.',
            'rows'          => 3,
        ),
        array(
            'key'           => 'field_dashboard_cta_label',
            'label'         => 'Dashboard CTA Label',
            'name'          => 'dashboard_cta_label',
            'type'          => 'text',
            'default_value' => 'Access Your Dashboard →',
        ),
        array(
            'key'           => 'field_dashboard_cta_url',
            'label'         => 'Dashboard CTA URL',
            'name'          => 'dashboard_cta_url',
            'type'          => 'url',
            'default_value' => 'https://panel.nordictv.io/login',
        ),

        // ── Dashboard Features (repeater) ──────────────────────────────────
        array(
            'key'        => 'field_dashboard_features',
            'label'      => 'Dashboard Features',
            'name'       => 'dashboard_features',
            'type'       => 'repeater',
            'min'        => 0,
            'max'        => 0,
            'layout'     => 'block',
            'button_label' => 'Add Feature',
            'sub_fields' => array(
                array(
                    'key'   => 'field_dashboard_feature_icon',
                    'label' => 'Icon (emoji or image)',
                    'name'  => 'icon',
                    'type'  => 'text',
                ),
                array(
                    'key'   => 'field_dashboard_feature_title',
                    'label' => 'Title',
                    'name'  => 'title',
                    'type'  => 'text',
                ),
                array(
                    'key'    => 'field_dashboard_feature_description',
                    'label'  => 'Description',
                    'name'   => 'description',
                    'type'   => 'textarea',
                    'rows'   => 3,
                ),
            ),
        ),
    ),

    'location' => array(
        array(
            array(
                'param'    => 'page_type',
                'operator' => '==',
                'value'    => 'front_page',
            ),
        ),
    ),

    'menu_order'            => 0,
    'position'              => 'normal',
    'style'                 => 'default',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'active'                => true,
));
