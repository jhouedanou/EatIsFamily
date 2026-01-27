<?php
/**
 * EatIsFamily Theme Customizer
 * 
 * @package EatIsFamily
 * @version 4.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Customizer Settings
 */
function eatisfamily_customize_register($wp_customize) {
    
    // =========================================================================
    // SECTION: Brand Identity
    // =========================================================================
    $wp_customize->add_section('eatisfamily_brand', array(
        'title'    => __('Brand Identity', 'eatisfamily'),
        'priority' => 20,
    ));
    
    // Site Logo
    $wp_customize->add_setting('eatisfamily_site_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_site_logo', array(
        'label'       => __('Site Logo', 'eatisfamily'),
        'description' => __('Upload your site logo (SVG or PNG recommended)', 'eatisfamily'),
        'section'     => 'eatisfamily_brand',
        'mime_type'   => 'image',
    )));
    
    // Site Logo Dark Version
    $wp_customize->add_setting('eatisfamily_site_logo_dark', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_site_logo_dark', array(
        'label'       => __('Site Logo (Dark Version)', 'eatisfamily'),
        'description' => __('Logo for dark backgrounds', 'eatisfamily'),
        'section'     => 'eatisfamily_brand',
        'mime_type'   => 'image',
    )));
    
    // Favicon
    $wp_customize->add_setting('eatisfamily_favicon', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_favicon', array(
        'label'       => __('Favicon', 'eatisfamily'),
        'description' => __('32x32 or 64x64 PNG/ICO', 'eatisfamily'),
        'section'     => 'eatisfamily_brand',
        'mime_type'   => 'image',
    )));
    
    // Brand Name
    $wp_customize->add_setting('eatisfamily_brand_name', array(
        'default'           => 'Eat is',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_brand_name', array(
        'label'       => __('Brand Name (Part 1)', 'eatisfamily'),
        'description' => __('First part of the brand name', 'eatisfamily'),
        'section'     => 'eatisfamily_brand',
        'type'        => 'text',
    ));
    
    // Brand Name Highlight
    $wp_customize->add_setting('eatisfamily_brand_highlight', array(
        'default'           => 'Family',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_brand_highlight', array(
        'label'       => __('Brand Name (Part 2 - Highlighted)', 'eatisfamily'),
        'description' => __('Second part shown in accent color', 'eatisfamily'),
        'section'     => 'eatisfamily_brand',
        'type'        => 'text',
    ));
    
    // =========================================================================
    // SECTION: Header Configuration
    // =========================================================================
    $wp_customize->add_section('eatisfamily_header', array(
        'title'    => __('Header Configuration', 'eatisfamily'),
        'priority' => 25,
    ));
    
    // Header Background Image
    $wp_customize->add_setting('eatisfamily_header_bg', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_header_bg', array(
        'label'       => __('Header Background Image', 'eatisfamily'),
        'section'     => 'eatisfamily_header',
        'mime_type'   => 'image',
    )));
    
    // Sticky Header
    $wp_customize->add_setting('eatisfamily_sticky_header', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_sticky_header', array(
        'label'   => __('Enable Sticky Header', 'eatisfamily'),
        'section' => 'eatisfamily_header',
        'type'    => 'checkbox',
    ));
    
    // CTA Button Text (Desktop)
    $wp_customize->add_setting('eatisfamily_header_cta_text', array(
        'default'           => 'Get in touch',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_header_cta_text', array(
        'label'   => __('CTA Button Text (Desktop)', 'eatisfamily'),
        'section' => 'eatisfamily_header',
        'type'    => 'text',
    ));
    
    // CTA Button Text (Mobile)
    $wp_customize->add_setting('eatisfamily_header_cta_text_mobile', array(
        'default'           => 'Contact',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_header_cta_text_mobile', array(
        'label'   => __('CTA Button Text (Mobile)', 'eatisfamily'),
        'section' => 'eatisfamily_header',
        'type'    => 'text',
    ));
    
    // CTA Link
    $wp_customize->add_setting('eatisfamily_header_cta_link', array(
        'default'           => '/contact',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_header_cta_link', array(
        'label'   => __('CTA Button Link', 'eatisfamily'),
        'section' => 'eatisfamily_header',
        'type'    => 'text',
    ));
    
    // =========================================================================
    // SECTION: Navigation Menu
    // =========================================================================
    $wp_customize->add_section('eatisfamily_navigation', array(
        'title'    => __('Navigation Menu', 'eatisfamily'),
        'priority' => 26,
    ));
    
    // Navigation Links (stored as JSON)
    for ($i = 1; $i <= 6; $i++) {
        // Link Text
        $wp_customize->add_setting("eatisfamily_nav_link_{$i}_text", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));
        
        $wp_customize->add_control("eatisfamily_nav_link_{$i}_text", array(
            'label'   => sprintf(__('Nav Link %d - Text', 'eatisfamily'), $i),
            'section' => 'eatisfamily_navigation',
            'type'    => 'text',
        ));
        
        // Link URL
        $wp_customize->add_setting("eatisfamily_nav_link_{$i}_url", array(
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));
        
        $wp_customize->add_control("eatisfamily_nav_link_{$i}_url", array(
            'label'   => sprintf(__('Nav Link %d - URL', 'eatisfamily'), $i),
            'section' => 'eatisfamily_navigation',
            'type'    => 'text',
        ));
    }
    
    // =========================================================================
    // SECTION: Map Configuration
    // =========================================================================
    $wp_customize->add_section('eatisfamily_map', array(
        'title'    => __('Map Configuration', 'eatisfamily'),
        'priority' => 30,
    ));
    
    // MapTiler API Key
    $wp_customize->add_setting('eatisfamily_maptiler_key', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_maptiler_key', array(
        'label'       => __('MapTiler API Key', 'eatisfamily'),
        'description' => __('Your MapTiler API key for the interactive map', 'eatisfamily'),
        'section'     => 'eatisfamily_map',
        'type'        => 'text',
    ));
    
    // MapTiler Style URL
    $wp_customize->add_setting('eatisfamily_maptiler_style', array(
        'default'           => 'https://api.maptiler.com/maps/streets/style.json',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_maptiler_style', array(
        'label'       => __('MapTiler Style URL', 'eatisfamily'),
        'description' => __('Custom map style URL from MapTiler', 'eatisfamily'),
        'section'     => 'eatisfamily_map',
        'type'        => 'url',
    ));
    
    // Default Map Center - Latitude
    $wp_customize->add_setting('eatisfamily_map_center_lat', array(
        'default'           => '48.5',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_map_center_lat', array(
        'label'   => __('Default Map Center - Latitude', 'eatisfamily'),
        'section' => 'eatisfamily_map',
        'type'    => 'text',
    ));
    
    // Default Map Center - Longitude
    $wp_customize->add_setting('eatisfamily_map_center_lng', array(
        'default'           => '2.0',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_map_center_lng', array(
        'label'   => __('Default Map Center - Longitude', 'eatisfamily'),
        'section' => 'eatisfamily_map',
        'type'    => 'text',
    ));
    
    // Default Map Zoom
    $wp_customize->add_setting('eatisfamily_map_zoom', array(
        'default'           => '5',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_map_zoom', array(
        'label'   => __('Default Map Zoom Level', 'eatisfamily'),
        'section' => 'eatisfamily_map',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 1,
            'max'  => 20,
            'step' => 1,
        ),
    ));
    
    // =========================================================================
    // SECTION: Venue Markers
    // =========================================================================
    $wp_customize->add_section('eatisfamily_markers', array(
        'title'    => __('Venue Markers', 'eatisfamily'),
        'priority' => 31,
    ));
    
    // Marker Type
    $wp_customize->add_setting('eatisfamily_marker_type', array(
        'default'           => 'custom',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_marker_type', array(
        'label'   => __('Marker Type', 'eatisfamily'),
        'section' => 'eatisfamily_markers',
        'type'    => 'select',
        'choices' => array(
            'default' => __('Default Pin', 'eatisfamily'),
            'custom'  => __('Custom Image', 'eatisfamily'),
            'circle'  => __('Circle', 'eatisfamily'),
        ),
    ));
    
    // Stadium Marker
    $wp_customize->add_setting('eatisfamily_marker_stadium', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_marker_stadium', array(
        'label'       => __('Stadium Marker Image', 'eatisfamily'),
        'description' => __('Custom marker for stadiums (recommended: 40x40 PNG)', 'eatisfamily'),
        'section'     => 'eatisfamily_markers',
        'mime_type'   => 'image',
    )));
    
    // Arena Marker
    $wp_customize->add_setting('eatisfamily_marker_arena', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_marker_arena', array(
        'label'       => __('Arena Marker Image', 'eatisfamily'),
        'description' => __('Custom marker for arenas', 'eatisfamily'),
        'section'     => 'eatisfamily_markers',
        'mime_type'   => 'image',
    )));
    
    // Festival Marker
    $wp_customize->add_setting('eatisfamily_marker_festival', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_marker_festival', array(
        'label'       => __('Festival Marker Image', 'eatisfamily'),
        'description' => __('Custom marker for festivals', 'eatisfamily'),
        'section'     => 'eatisfamily_markers',
        'mime_type'   => 'image',
    )));
    
    // Default Marker
    $wp_customize->add_setting('eatisfamily_marker_default', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_marker_default', array(
        'label'       => __('Default Marker Image', 'eatisfamily'),
        'description' => __('Fallback marker for other venue types', 'eatisfamily'),
        'section'     => 'eatisfamily_markers',
        'mime_type'   => 'image',
    )));

    // =========================================================================
    // SECTION: UI Icons & Buttons
    // =========================================================================
    $wp_customize->add_section('eatisfamily_icons', array(
        'title'       => __('UI Icons & Buttons', 'eatisfamily'),
        'description' => __('Upload custom icons and button images used throughout the site', 'eatisfamily'),
        'priority'    => 32,
    ));

    // --- Job Icons ---
    $wp_customize->add_setting('eatisfamily_icon_briefcase', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_briefcase', array(
        'label'       => __('Job Icon - Briefcase', 'eatisfamily'),
        'description' => __('Icon for job type/department field', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_moneybag', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_moneybag', array(
        'label'       => __('Job Icon - Moneybag', 'eatisfamily'),
        'description' => __('Icon for salary field', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_apply_for_this', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_apply_for_this', array(
        'label'       => __('Job Icon - Apply For This', 'eatisfamily'),
        'description' => __('Decorative icon near apply section', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_share_job', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_share_job', array(
        'label'       => __('Job Icon - Share This Job', 'eatisfamily'),
        'description' => __('Share job icon/graphic', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    // --- CTA Buttons ---
    $wp_customize->add_setting('eatisfamily_btn_apply_position', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_apply_position', array(
        'label'       => __('Button - Apply for Position', 'eatisfamily'),
        'description' => __('Apply button on job detail page', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_go_back_jobs', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_go_back_jobs', array(
        'label'       => __('Button - Go Back to Jobs', 'eatisfamily'),
        'description' => __('Back button on job detail page', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_apply', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_apply', array(
        'label'       => __('Button - Apply (Careers Page)', 'eatisfamily'),
        'description' => __('Apply button on careers listing', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_view', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_view', array(
        'label'       => __('Button - View Details', 'eatisfamily'),
        'description' => __('View button on careers listing', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_discover_apply', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_discover_apply', array(
        'label'       => __('Button - Discover and Apply', 'eatisfamily'),
        'description' => __('Large CTA button on careers page', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_explore', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_explore', array(
        'label'       => __('Button - Explore', 'eatisfamily'),
        'description' => __('Explore button on homepage hero', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_learn_more', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_learn_more', array(
        'label'       => __('Button - Learn More About Us', 'eatisfamily'),
        'description' => __('Learn more button on homepage', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_get_in_touch', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_get_in_touch', array(
        'label'       => __('Button - Get in Touch', 'eatisfamily'),
        'description' => __('Get in touch button in header', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_read_more', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_read_more', array(
        'label'       => __('Button - Read More', 'eatisfamily'),
        'description' => __('Read more button on blog posts', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_search_form', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_search_form', array(
        'label'       => __('Button - Search Form', 'eatisfamily'),
        'description' => __('Search button icon in job search form', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_btn_join_now', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_btn_join_now', array(
        'label'       => __('Button - Join Now', 'eatisfamily'),
        'description' => __('Join now button in explore section', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    // --- UI Icons ---
    $wp_customize->add_setting('eatisfamily_icon_chevron_down', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_chevron_down', array(
        'label'       => __('Icon - Chevron Down', 'eatisfamily'),
        'description' => __('Dropdown indicator icon', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_map', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_map', array(
        'label'       => __('Icon - Map/Location', 'eatisfamily'),
        'description' => __('Map pin icon in venue info', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_calendar', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_calendar', array(
        'label'       => __('Icon - Calendar', 'eatisfamily'),
        'description' => __('Calendar/date icon', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_info', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_info', array(
        'label'       => __('Icon - Info/Overview', 'eatisfamily'),
        'description' => __('Overview tab icon', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_shop', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_shop', array(
        'label'       => __('Icon - Shop', 'eatisfamily'),
        'description' => __('Shops tab icon', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    $wp_customize->add_setting('eatisfamily_icon_food', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_icon_food', array(
        'label'       => __('Icon - Food/Menu', 'eatisfamily'),
        'description' => __('Menus tab icon', 'eatisfamily'),
        'section'     => 'eatisfamily_icons',
        'mime_type'   => 'image',
    )));

    // =========================================================================
    // SECTION: SEO & Meta
    // =========================================================================
    $wp_customize->add_section('eatisfamily_seo', array(
        'title'    => __('SEO & Meta Tags', 'eatisfamily'),
        'priority' => 35,
    ));
    
    // Default Meta Title
    $wp_customize->add_setting('eatisfamily_seo_title', array(
        'default'           => 'Eat Is Family - Event Catering & Food Experiences',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_seo_title', array(
        'label'   => __('Default Meta Title', 'eatisfamily'),
        'section' => 'eatisfamily_seo',
        'type'    => 'text',
    ));
    
    // Title Template
    $wp_customize->add_setting('eatisfamily_seo_title_template', array(
        'default'           => '%s | Eat Is Family',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_seo_title_template', array(
        'label'       => __('Title Template', 'eatisfamily'),
        'description' => __('Use %s for page title', 'eatisfamily'),
        'section'     => 'eatisfamily_seo',
        'type'        => 'text',
    ));
    
    // Default Meta Description
    $wp_customize->add_setting('eatisfamily_seo_description', array(
        'default'           => 'Eat Is Family delivers exceptional catering for stadiums, arenas, and festivals across France.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_seo_description', array(
        'label'   => __('Default Meta Description', 'eatisfamily'),
        'section' => 'eatisfamily_seo',
        'type'    => 'textarea',
    ));
    
    // Keywords
    $wp_customize->add_setting('eatisfamily_seo_keywords', array(
        'default'           => 'event catering, stadium catering, food service, VIP hospitality',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_seo_keywords', array(
        'label'   => __('Keywords (comma-separated)', 'eatisfamily'),
        'section' => 'eatisfamily_seo',
        'type'    => 'text',
    ));
    
    // OG Image
    $wp_customize->add_setting('eatisfamily_seo_og_image', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_seo_og_image', array(
        'label'       => __('Default OG Image', 'eatisfamily'),
        'description' => __('Default social sharing image (1200x630 recommended)', 'eatisfamily'),
        'section'     => 'eatisfamily_seo',
        'mime_type'   => 'image',
    )));
    
    // Twitter Handle
    $wp_customize->add_setting('eatisfamily_seo_twitter', array(
        'default'           => '@eatisfamily',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_seo_twitter', array(
        'label'   => __('Twitter Handle', 'eatisfamily'),
        'section' => 'eatisfamily_seo',
        'type'    => 'text',
    ));
    
    // Canonical Base URL
    $wp_customize->add_setting('eatisfamily_seo_canonical', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_seo_canonical', array(
        'label'       => __('Canonical Base URL', 'eatisfamily'),
        'description' => __('e.g., https://eatisfamily.fr', 'eatisfamily'),
        'section'     => 'eatisfamily_seo',
        'type'        => 'url',
    ));
    
    // =========================================================================
    // SECTION: Social Media
    // =========================================================================
    $wp_customize->add_section('eatisfamily_social', array(
        'title'    => __('Social Media Links', 'eatisfamily'),
        'priority' => 40,
    ));
    
    $social_networks = array(
        'facebook'  => 'Facebook URL',
        'instagram' => 'Instagram URL',
        'twitter'   => 'Twitter/X URL',
        'linkedin'  => 'LinkedIn URL',
        'youtube'   => 'YouTube URL',
        'tiktok'    => 'TikTok URL',
    );
    
    foreach ($social_networks as $network => $label) {
        $wp_customize->add_setting("eatisfamily_social_{$network}", array(
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
            'transport'         => 'refresh',
        ));
        
        $wp_customize->add_control("eatisfamily_social_{$network}", array(
            'label'   => __($label, 'eatisfamily'),
            'section' => 'eatisfamily_social',
            'type'    => 'url',
        ));
    }
    
    // =========================================================================
    // SECTION: Contact Information
    // =========================================================================
    $wp_customize->add_section('eatisfamily_contact', array(
        'title'    => __('Contact Information', 'eatisfamily'),
        'priority' => 45,
    ));
    
    // Email
    $wp_customize->add_setting('eatisfamily_contact_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_contact_email', array(
        'label'   => __('Contact Email', 'eatisfamily'),
        'section' => 'eatisfamily_contact',
        'type'    => 'email',
    ));
    
    // Phone
    $wp_customize->add_setting('eatisfamily_contact_phone', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_contact_phone', array(
        'label'   => __('Contact Phone', 'eatisfamily'),
        'section' => 'eatisfamily_contact',
        'type'    => 'tel',
    ));
    
    // Address
    $wp_customize->add_setting('eatisfamily_contact_address', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_contact_address', array(
        'label'   => __('Address', 'eatisfamily'),
        'section' => 'eatisfamily_contact',
        'type'    => 'textarea',
    ));
    
    // Business Hours
    $wp_customize->add_setting('eatisfamily_contact_hours', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_contact_hours', array(
        'label'   => __('Business Hours', 'eatisfamily'),
        'section' => 'eatisfamily_contact',
        'type'    => 'text',
    ));
    
    // Legal Email (for privacy/legal pages)
    $wp_customize->add_setting('eatisfamily_legal_email', array(
        'default'           => '',
        'sanitize_callback' => 'sanitize_email',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_legal_email', array(
        'label'       => __('Legal/Privacy Email', 'eatisfamily'),
        'description' => __('Email for legal inquiries', 'eatisfamily'),
        'section'     => 'eatisfamily_contact',
        'type'        => 'email',
    ));
    
    // =========================================================================
    // SECTION: Footer Configuration
    // =========================================================================
    $wp_customize->add_section('eatisfamily_footer', array(
        'title'    => __('Footer Configuration', 'eatisfamily'),
        'priority' => 50,
    ));
    
    // Footer Logo
    $wp_customize->add_setting('eatisfamily_footer_logo', array(
        'default'           => '',
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, 'eatisfamily_footer_logo', array(
        'label'   => __('Footer Logo', 'eatisfamily'),
        'section' => 'eatisfamily_footer',
        'mime_type' => 'image',
    )));
    
    // Footer Tagline
    $wp_customize->add_setting('eatisfamily_footer_tagline', array(
        'default'           => 'Creating unforgettable culinary experiences that bring people together.',
        'sanitize_callback' => 'sanitize_textarea_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_footer_tagline', array(
        'label'   => __('Footer Tagline', 'eatisfamily'),
        'section' => 'eatisfamily_footer',
        'type'    => 'textarea',
    ));
    
    // Copyright Text
    $wp_customize->add_setting('eatisfamily_footer_copyright', array(
        'default'           => '© {year} Eat is Family. All Rights Reserved.',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_footer_copyright', array(
        'label'       => __('Copyright Text', 'eatisfamily'),
        'description' => __('Use {year} for dynamic year', 'eatisfamily'),
        'section'     => 'eatisfamily_footer',
        'type'        => 'text',
    ));
    
    // Show Back to Top
    $wp_customize->add_setting('eatisfamily_footer_back_to_top', array(
        'default'           => true,
        'sanitize_callback' => 'wp_validate_boolean',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_footer_back_to_top', array(
        'label'   => __('Show "Back to Top" Button', 'eatisfamily'),
        'section' => 'eatisfamily_footer',
        'type'    => 'checkbox',
    ));
    
    // Back to Top Text
    $wp_customize->add_setting('eatisfamily_footer_back_to_top_text', array(
        'default'           => 'Back to top',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_footer_back_to_top_text', array(
        'label'   => __('"Back to Top" Text', 'eatisfamily'),
        'section' => 'eatisfamily_footer',
        'type'    => 'text',
    ));
    
    // Company Links Title
    $wp_customize->add_setting('eatisfamily_footer_company_title', array(
        'default'           => 'Company',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_footer_company_title', array(
        'label'   => __('Company Links Section Title', 'eatisfamily'),
        'section' => 'eatisfamily_footer',
        'type'    => 'text',
    ));
    
    // Policy Links Title
    $wp_customize->add_setting('eatisfamily_footer_policy_title', array(
        'default'           => 'Policy Info',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_footer_policy_title', array(
        'label'   => __('Policy Links Section Title', 'eatisfamily'),
        'section' => 'eatisfamily_footer',
        'type'    => 'text',
    ));
    
    // =========================================================================
    // SECTION: Global Configuration
    // =========================================================================
    $wp_customize->add_section('eatisfamily_global', array(
        'title'    => __('Global Configuration', 'eatisfamily'),
        'priority' => 55,
    ));
    
    // Jobs per Page
    $wp_customize->add_setting('eatisfamily_jobs_per_page', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_jobs_per_page', array(
        'label'   => __('Jobs Per Page', 'eatisfamily'),
        'section' => 'eatisfamily_global',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 24,
            'step' => 3,
        ),
    ));
    
    // Events per Page
    $wp_customize->add_setting('eatisfamily_events_per_page', array(
        'default'           => 9,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_events_per_page', array(
        'label'   => __('Events Per Page', 'eatisfamily'),
        'section' => 'eatisfamily_global',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 24,
            'step' => 3,
        ),
    ));
    
    // Blog Posts per Page
    $wp_customize->add_setting('eatisfamily_blog_per_page', array(
        'default'           => 6,
        'sanitize_callback' => 'absint',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_blog_per_page', array(
        'label'   => __('Blog Posts Per Page', 'eatisfamily'),
        'section' => 'eatisfamily_global',
        'type'    => 'number',
        'input_attrs' => array(
            'min'  => 3,
            'max'  => 24,
            'step' => 3,
        ),
    ));
    
    // Language/Locale
    $wp_customize->add_setting('eatisfamily_locale', array(
        'default'           => 'fr',
        'sanitize_callback' => 'sanitize_text_field',
        'transport'         => 'refresh',
    ));
    
    $wp_customize->add_control('eatisfamily_locale', array(
        'label'   => __('Site Language', 'eatisfamily'),
        'section' => 'eatisfamily_global',
        'type'    => 'select',
        'choices' => array(
            'fr' => __('French', 'eatisfamily'),
            'en' => __('English', 'eatisfamily'),
        ),
    ));
    
    // =========================================================================
    // SECTION: UI Text Strings
    // =========================================================================
    $wp_customize->add_section('eatisfamily_strings', array(
        'title'       => __('UI Text Strings', 'eatisfamily'),
        'description' => __('Customize text labels and messages throughout the site', 'eatisfamily'),
        'priority'    => 60,
    ));
    
    $string_defaults = array(
        'loading'           => 'Loading...',
        'no_results'        => 'No results found.',
        'error_generic'     => 'An error occurred. Please try again.',
        'submit_button'     => 'Submit',
        'send_message'      => 'Send Message',
        'sending'           => 'Sending...',
        'message_sent'      => 'Message Sent!',
        'thank_you'         => 'Thank you for reaching out. We will get back to you soon.',
        'apply_now'         => 'Apply Now',
        'view_details'      => 'View Details',
        'read_more'         => 'Read More',
        'back_to_jobs'      => 'Browse All Jobs',
        'job_not_found'     => 'Job Not Found',
        'all_job_titles'    => 'All job titles',
        'all_sites'         => 'All sites',
        'search_placeholder' => 'Search...',
    );
    
    foreach ($string_defaults as $key => $default) {
        $wp_customize->add_setting("eatisfamily_string_{$key}", array(
            'default'           => $default,
            'sanitize_callback' => 'sanitize_text_field',
            'transport'         => 'refresh',
        ));
        
        $wp_customize->add_control("eatisfamily_string_{$key}", array(
            'label'   => ucfirst(str_replace('_', ' ', $key)),
            'section' => 'eatisfamily_strings',
            'type'    => 'text',
        ));
    }
    
    // =========================================================================
    // SECTION: Background Images
    // =========================================================================
    $wp_customize->add_section('eatisfamily_backgrounds', array(
        'title'    => __('Background Images', 'eatisfamily'),
        'priority' => 65,
    ));
    
    $backgrounds = array(
        'hero_default'    => __('Default Hero Background', 'eatisfamily'),
        'about_hero'      => __('About Page Hero Background', 'eatisfamily'),
        'contact_hero'    => __('Contact Page Hero Background', 'eatisfamily'),
        'careers_hero'    => __('Careers Page Hero Background', 'eatisfamily'),
        'events_hero'     => __('Events Page Hero Background', 'eatisfamily'),
        'blog_hero'       => __('Blog Page Hero Background', 'eatisfamily'),
        'decoration_1'    => __('Decoration Image 1', 'eatisfamily'),
        'decoration_2'    => __('Decoration Image 2', 'eatisfamily'),
        'vector_bg'       => __('Vector Background', 'eatisfamily'),
    );
    
    foreach ($backgrounds as $key => $label) {
        $wp_customize->add_setting("eatisfamily_bg_{$key}", array(
            'default'           => '',
            'sanitize_callback' => 'absint',
            'transport'         => 'refresh',
        ));
        
        $wp_customize->add_control(new WP_Customize_Media_Control($wp_customize, "eatisfamily_bg_{$key}", array(
            'label'    => $label,
            'section'  => 'eatisfamily_backgrounds',
            'mime_type' => 'image',
        )));
    }
}
add_action('customize_register', 'eatisfamily_customize_register');

/**
 * Get all Customizer settings as an array for the API
 */
function eatisfamily_get_customizer_settings() {
    $settings = array();
    
    // Brand Identity
    $settings['brand'] = array(
        'logo'           => eatisfamily_get_image_url('eatisfamily_site_logo'),
        'logo_dark'      => eatisfamily_get_image_url('eatisfamily_site_logo_dark'),
        'favicon'        => eatisfamily_get_image_url('eatisfamily_favicon'),
        'name'           => get_theme_mod('eatisfamily_brand_name', 'Eat is'),
        'highlight'      => get_theme_mod('eatisfamily_brand_highlight', 'Family'),
    );
    
    // Header Configuration
    $settings['header'] = array(
        'background_image' => eatisfamily_get_image_url('eatisfamily_header_bg'),
        'sticky'           => get_theme_mod('eatisfamily_sticky_header', true),
        'cta_text'         => get_theme_mod('eatisfamily_header_cta_text', 'Get in touch'),
        'cta_text_mobile'  => get_theme_mod('eatisfamily_header_cta_text_mobile', 'Contact'),
        'cta_link'         => get_theme_mod('eatisfamily_header_cta_link', '/contact'),
    );
    
    // Navigation
    $nav_links = array();
    for ($i = 1; $i <= 6; $i++) {
        $text = get_theme_mod("eatisfamily_nav_link_{$i}_text", '');
        $url = get_theme_mod("eatisfamily_nav_link_{$i}_url", '');
        if (!empty($text) && !empty($url)) {
            $nav_links[] = array(
                'text' => $text,
                'to'   => $url,
            );
        }
    }
    $settings['navigation'] = array(
        'links' => $nav_links,
    );
    
    // Map Configuration
    $settings['map'] = array(
        'maptiler_key'   => get_theme_mod('eatisfamily_maptiler_key', ''),
        'maptiler_style' => get_theme_mod('eatisfamily_maptiler_style', 'https://api.maptiler.com/maps/streets/style.json'),
        'center'         => array(
            floatval(get_theme_mod('eatisfamily_map_center_lng', '2.0')),
            floatval(get_theme_mod('eatisfamily_map_center_lat', '48.5')),
        ),
        'zoom'           => intval(get_theme_mod('eatisfamily_map_zoom', 5)),
    );
    
    // Venue Markers
    $settings['markers'] = array(
        'type'     => get_theme_mod('eatisfamily_marker_type', 'custom'),
        'stadium'  => eatisfamily_get_image_url('eatisfamily_marker_stadium'),
        'arena'    => eatisfamily_get_image_url('eatisfamily_marker_arena'),
        'festival' => eatisfamily_get_image_url('eatisfamily_marker_festival'),
        'default'  => eatisfamily_get_image_url('eatisfamily_marker_default'),
    );

    // UI Icons & Buttons
    $settings['icons'] = array(
        // Job Icons
        'icon_briefcase'      => eatisfamily_get_image_url('eatisfamily_icon_briefcase'),
        'icon_moneybag'       => eatisfamily_get_image_url('eatisfamily_icon_moneybag'),
        'icon_apply_for_this' => eatisfamily_get_image_url('eatisfamily_icon_apply_for_this'),
        'icon_share_job'      => eatisfamily_get_image_url('eatisfamily_icon_share_job'),
        // CTA Buttons
        'btn_apply_position'  => eatisfamily_get_image_url('eatisfamily_btn_apply_position'),
        'btn_go_back_jobs'    => eatisfamily_get_image_url('eatisfamily_btn_go_back_jobs'),
        'btn_apply'           => eatisfamily_get_image_url('eatisfamily_btn_apply'),
        'btn_view'            => eatisfamily_get_image_url('eatisfamily_btn_view'),
        'btn_discover_apply'  => eatisfamily_get_image_url('eatisfamily_btn_discover_apply'),
        'btn_explore'         => eatisfamily_get_image_url('eatisfamily_btn_explore'),
        'btn_learn_more'      => eatisfamily_get_image_url('eatisfamily_btn_learn_more'),
        'btn_get_in_touch'    => eatisfamily_get_image_url('eatisfamily_btn_get_in_touch'),
        'btn_read_more'       => eatisfamily_get_image_url('eatisfamily_btn_read_more'),
        'btn_search_form'     => eatisfamily_get_image_url('eatisfamily_btn_search_form'),
        'btn_join_now'        => eatisfamily_get_image_url('eatisfamily_btn_join_now'),
        // UI Icons
        'icon_chevron_down'   => eatisfamily_get_image_url('eatisfamily_icon_chevron_down'),
        'icon_map'            => eatisfamily_get_image_url('eatisfamily_icon_map'),
        'icon_calendar'       => eatisfamily_get_image_url('eatisfamily_icon_calendar'),
        'icon_info'           => eatisfamily_get_image_url('eatisfamily_icon_info'),
        'icon_shop'           => eatisfamily_get_image_url('eatisfamily_icon_shop'),
        'icon_food'           => eatisfamily_get_image_url('eatisfamily_icon_food'),
    );

    // SEO
    $settings['seo'] = array(
        'default_title'       => get_theme_mod('eatisfamily_seo_title', 'Eat Is Family'),
        'title_template'      => get_theme_mod('eatisfamily_seo_title_template', '%s | Eat Is Family'),
        'default_description' => get_theme_mod('eatisfamily_seo_description', ''),
        'keywords'            => get_theme_mod('eatisfamily_seo_keywords', ''),
        'og_image'            => eatisfamily_get_image_url('eatisfamily_seo_og_image'),
        'twitter_site'        => get_theme_mod('eatisfamily_seo_twitter', ''),
        'canonical_base'      => get_theme_mod('eatisfamily_seo_canonical', ''),
    );
    
    // Social Media
    $settings['social'] = array(
        'facebook'  => get_theme_mod('eatisfamily_social_facebook', ''),
        'instagram' => get_theme_mod('eatisfamily_social_instagram', ''),
        'twitter'   => get_theme_mod('eatisfamily_social_twitter', ''),
        'linkedin'  => get_theme_mod('eatisfamily_social_linkedin', ''),
        'youtube'   => get_theme_mod('eatisfamily_social_youtube', ''),
        'tiktok'    => get_theme_mod('eatisfamily_social_tiktok', ''),
    );
    
    // Contact
    $settings['contact'] = array(
        'email'       => get_theme_mod('eatisfamily_contact_email', ''),
        'phone'       => get_theme_mod('eatisfamily_contact_phone', ''),
        'address'     => get_theme_mod('eatisfamily_contact_address', ''),
        'hours'       => get_theme_mod('eatisfamily_contact_hours', ''),
        'legal_email' => get_theme_mod('eatisfamily_legal_email', ''),
    );
    
    // Footer
    $settings['footer'] = array(
        'logo'             => eatisfamily_get_image_url('eatisfamily_footer_logo'),
        'tagline'          => get_theme_mod('eatisfamily_footer_tagline', ''),
        'copyright'        => get_theme_mod('eatisfamily_footer_copyright', '© {year} Eat is Family.'),
        'show_back_to_top' => get_theme_mod('eatisfamily_footer_back_to_top', true),
        'back_to_top_text' => get_theme_mod('eatisfamily_footer_back_to_top_text', 'Back to top'),
        'company_title'    => get_theme_mod('eatisfamily_footer_company_title', 'Company'),
        'policy_title'     => get_theme_mod('eatisfamily_footer_policy_title', 'Policy Info'),
    );
    
    // Global Config
    $settings['config'] = array(
        'jobs_per_page'   => intval(get_theme_mod('eatisfamily_jobs_per_page', 6)),
        'events_per_page' => intval(get_theme_mod('eatisfamily_events_per_page', 9)),
        'blog_per_page'   => intval(get_theme_mod('eatisfamily_blog_per_page', 6)),
        'locale'          => get_theme_mod('eatisfamily_locale', 'fr'),
    );
    
    // UI Strings
    $string_keys = array(
        'loading', 'no_results', 'error_generic', 'submit_button',
        'send_message', 'sending', 'message_sent', 'thank_you',
        'apply_now', 'view_details', 'read_more', 'back_to_jobs',
        'job_not_found', 'all_job_titles', 'all_sites', 'search_placeholder'
    );
    
    $settings['strings'] = array();
    foreach ($string_keys as $key) {
        $settings['strings'][$key] = get_theme_mod("eatisfamily_string_{$key}", '');
    }
    
    // Background Images
    $bg_keys = array(
        'hero_default', 'about_hero', 'contact_hero', 'careers_hero',
        'events_hero', 'blog_hero', 'decoration_1', 'decoration_2', 'vector_bg'
    );
    
    $settings['backgrounds'] = array();
    foreach ($bg_keys as $key) {
        $settings['backgrounds'][$key] = eatisfamily_get_image_url("eatisfamily_bg_{$key}");
    }
    
    return $settings;
}

/**
 * Helper function to get image URL from media ID
 */
function eatisfamily_get_image_url($setting_name) {
    $media_id = get_theme_mod($setting_name, '');
    if (!empty($media_id)) {
        $image_url = wp_get_attachment_url($media_id);
        return $image_url ? $image_url : '';
    }
    return '';
}
