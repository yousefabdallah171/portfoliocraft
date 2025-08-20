<?php
/**
 * Redux Framework Theme Options Configuration
 *
 * This file configures the Redux Framework for the portfoliocraft theme options panel.
 * It sets up all theme customization options including colors, typography, header,
 * footer, blog settings, portfolio, and WooCommerce configurations.
 *
 * Features included:
 * - Global color scheme management
 * - Typography settings for all heading levels
 * - Header and mobile header configurations
 * - Footer settings and back-to-top functionality
 * - Blog archive and single post options
 * - Portfolio custom post type settings
 * - WooCommerce shop and product configurations
 * - Page title and breadcrumb customizations
 * - Cookie policy and GDPR compliance
 * - Site loader and animation controls
 *
 * @package portfoliocraft-Themes
 * @since 1.0.0
 * @author portfoliocraft-Themes
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Check if Redux Framework is available
if (!class_exists('ReduxFramework')) {
    return;
}

// Remove Redux admin notices if plugin version is active
if (class_exists('ReduxFrameworkPlugin')) {
    remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
}

// Get theme option name and version
$opt_name = portfoliocraft()->get_option_name();
$version = portfoliocraft()->get_version();

/* ==========================================================================
   Redux Framework Configuration Arguments
   ========================================================================== */

/**
 * Redux Framework main configuration array
 * 
 * Defines all the settings for the Redux options panel including:
 * - Panel appearance and behavior
 * - Menu positioning and permissions
 * - Google Fonts integration
 * - Development and customizer settings
 * - Import/export functionality
 * - Hint system configuration
 */
$args = array(
    // Basic Configuration
    'opt_name'             => $opt_name,
    'display_name'         => esc_html__('portfoliocraft Theme Options', 'portfoliocraft'),
    'display_version'      => $version,
    'menu_type'            => 'submenu',
    'allow_sub_menu'       => true,
    'menu_title'           => esc_html__('Theme Options', 'portfoliocraft'),
    'page_title'           => esc_html__('portfoliocraft Theme Options', 'portfoliocraft'),
    
    // Google Fonts Configuration
    'google_api_key'       => '', // Add your Google Fonts API key here if needed
    'google_update_weekly' => false,
    'async_typography'     => false,
    
    // Admin Interface Settings
    'admin_bar'            => false,
    'admin_bar_icon'       => 'dashicons-admin-generic',
    'admin_bar_priority'   => 50,
    'global_variable'      => '',
    
    // Development and Debug Settings
    'dev_mode'             => false, // Set to false in production
    'update_notice'        => true,
    'customizer'           => true,
    'show_options_object'  => false,
    
    // Menu and Page Settings
    'page_priority'        => 80,
    'page_parent'          => 'pxlart',
    'page_permissions'     => 'manage_options',
    'menu_icon'            => '',
    'last_tab'             => '',
    'page_icon'            => 'icon-themes',
    'page_slug'            => 'pxlart-theme-options',
    
    // Data Management
    'save_defaults'        => true,
    'default_show'         => false,
    'default_mark'         => '',
    'show_import_export'   => true,
    
    // Performance Settings
    'transient_time'       => 60 * MINUTE_IN_SECONDS,
    'output'               => true,
    'output_tag'           => true,
    'database'             => '',
    'use_cdn'              => true,
    
    // Help and Hints Configuration
    'hints'                => array(
        'icon'          => 'el el-question-sign',
        'icon_position' => 'right',
        'icon_color'    => 'lightgray',
        'icon_size'     => 'normal',
        'tip_style'     => array(
            'color'   => '#333333',
            'shadow'  => true,
            'rounded' => false,
            'style'   => '',
        ),
        'tip_position'  => array(
            'my' => 'top left',
            'at' => 'bottom right',
        ),
        'tip_effect'    => array(
            'show' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'mouseover',
            ),
            'hide' => array(
                'effect'   => 'slide',
                'duration' => '500',
                'event'    => 'click mouseleave',
            ),
        ),
    ),
);

// Initialize Redux with configuration
Redux::SetArgs($opt_name, $args);

/* ==========================================================================
   Global Colors Section
   ========================================================================== */

/**
 * Global Colors Configuration
 * 
 * Sets up the main color scheme for the theme including:
 * - Primary brand color for main elements
 * - Secondary color for accents and highlights
 * - Third color for additional design elements
 * - Link colors with hover and active states
 * - Gradient colors for modern design effects
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Global Colors', 'portfoliocraft'),
    'icon'   => 'el el-filter',
    'desc'   => esc_html__('Configure the main color scheme for your website. These colors will be used throughout the theme.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id'          => 'primary_color',
            'type'        => 'color',
            'title'       => esc_html__('Primary Color', 'portfoliocraft'),
            'subtitle'    => esc_html__('Main brand color used for buttons, links, and key elements.', 'portfoliocraft'),
            'transparent' => false,
            'default'     => '#667eea',
            'validate'    => 'color',
        ),
        array(
            'id'          => 'secondary_color',
            'type'        => 'color',
            'title'       => esc_html__('Secondary Color', 'portfoliocraft'),
            'subtitle'    => esc_html__('Secondary brand color for accents and highlights.', 'portfoliocraft'),
            'transparent' => false,
            'default'     => '#764ba2',
            'validate'    => 'color',
        ),
        array(
            'id'          => 'third_color',
            'type'        => 'color',
            'title'       => esc_html__('Third Color', 'portfoliocraft'),
            'subtitle'    => esc_html__('Additional color for design variety and special elements.', 'portfoliocraft'),
            'transparent' => false,
            'default'     => '#2c3e50',
            'validate'    => 'color',
        ),
        array(
            'id'      => 'link_color',
            'type'    => 'link_color',
            'title'   => esc_html__('Link Colors', 'portfoliocraft'),
            'subtitle' => esc_html__('Configure colors for different link states.', 'portfoliocraft'),
            'default' => array(
                'regular' => '#667eea',
                'hover'   => '#764ba2',
                'active'  => '#2c3e50'
            ),
            'output'  => array('a'),
        ),
        array(
            'id'          => 'gradient_color',
            'type'        => 'color_gradient',
            'title'       => esc_html__('Gradient Colors', 'portfoliocraft'),
            'subtitle'    => esc_html__('Create beautiful gradient effects for backgrounds and elements.', 'portfoliocraft'),
            'transparent' => false,
            'default'  => array(
                'from' => '#667eea',
                'to'   => '#764ba2', 
            ),
        ),
    )
));

/* ==========================================================================
   Typography Section
   ========================================================================== */

/**
 * Typography Configuration
 * 
 * Configures all font settings for the theme including:
 * - Primary, secondary, and third font families
 * - Heading font with color and weight options
 * - Individual heading levels (H1-H6) with full typography controls
 * - Google Fonts integration for web font loading
 * - Responsive font sizing and line height
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Typography', 'portfoliocraft'),
    'icon'   => 'el-icon-text-width',
    'desc'   => esc_html__('Configure fonts and typography settings for your website.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id'          => 'primary_font',
            'type'        => 'typography',
            'title'       => esc_html__('Primary Font Family', 'portfoliocraft'),
            'subtitle'    => esc_html__('Main font used throughout the website for body text.', 'portfoliocraft'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => false,
            'line-height' => false,
            'font-size'   => false,
            'color'       => false,
            'font-style'  => false,
            'font-weight' => false,
            'text-align'  => false,
            'default'     => array(
                'font-family' => 'Inter',
                'google'      => true,
            ),
        ),
        array(
            'id'          => 'secondary_font',
            'type'        => 'typography',
            'title'       => esc_html__('Secondary Font Family', 'portfoliocraft'),
            'subtitle'    => esc_html__('Alternative font for special elements and accents.', 'portfoliocraft'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => false,
            'line-height' => false,
            'font-size'   => false,
            'color'       => false,
            'font-style'  => false,
            'font-weight' => false,
            'text-align'  => false,
            'default'     => array(
                'font-family' => 'Montserrat',
                'google'      => true,
            ),
        ),
        array(
            'id'          => 'third_font',
            'type'        => 'typography',
            'title'       => esc_html__('Third Font Family', 'portfoliocraft'),
            'subtitle'    => esc_html__('Additional font option for design variety.', 'portfoliocraft'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => false,
            'line-height' => false,
            'font-size'   => false,
            'color'       => false,
            'font-style'  => false,
            'font-weight' => false,
            'text-align'  => false,
            'default'     => array(
                'font-family' => 'Playfair Display',
                'google'      => true,
            ),
        ),
        array(
            'id'          => 'heading_font',
            'type'        => 'typography',
            'title'       => esc_html__('Heading Font Settings', 'portfoliocraft'),
            'subtitle'    => esc_html__('Global settings for all heading elements (H1-H6).', 'portfoliocraft'),
            'google'      => true,
            'font-backup' => true,
            'all_styles'  => false,
            'line-height' => false,
            'font-size'   => false,
            'font-style'  => false,
            'font-weight' => true,
            'text-align'  => false,
            'color'       => true,
            'default'     => array(
                'font-weight' => '700',
                'color'       => '#2c3e50',
            ),
        ),
        
        // Individual Heading Level Configurations
        array(
            'id'          => 'font_heading_h1',
            'type'        => 'typography',
            'title'       => esc_html__('Heading H1 Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for main page titles and H1 elements.', 'portfoliocraft'),
            'google'      => false,
            'font-backup' => false,
            'all_styles'  => true,
            'text-align'  => false,
            'line-height' => true,
            'font-size'   => true,
            'font-style'  => false,
            'output'      => array('h1', '.h1'),
            'units'       => 'px',
            'font-family' => false,
            'color'       => false,
            'default'     => array(
                'font-size'   => '48px',
                'line-height' => '56px',
                'font-weight' => '700',
            ),
        ),
        array(
            'id'          => 'font_heading_h2',
            'type'        => 'typography',
            'title'       => esc_html__('Heading H2 Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for section titles and H2 elements.', 'portfoliocraft'),
            'google'      => false,
            'font-backup' => false,
            'all_styles'  => true,
            'text-align'  => false,
            'line-height' => true,
            'font-size'   => true,
            'font-style'  => false,
            'output'      => array('h2', '.h2'),
            'units'       => 'px',
            'font-family' => false,
            'color'       => false,
            'default'     => array(
                'font-size'   => '36px',
                'line-height' => '44px',
                'font-weight' => '600',
            ),
        ),
        array(
            'id'          => 'font_heading_h3',
            'type'        => 'typography',
            'title'       => esc_html__('Heading H3 Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for subsection titles and H3 elements.', 'portfoliocraft'),
            'google'      => false,
            'font-backup' => false,
            'all_styles'  => true,
            'text-align'  => false,
            'line-height' => true,
            'font-size'   => true,
            'font-style'  => false,
            'output'      => array('h3', '.h3'),
            'units'       => 'px',
            'font-family' => false,
            'color'       => false,
            'default'     => array(
                'font-size'   => '28px',
                'line-height' => '36px',
                'font-weight' => '600',
            ),
        ),
        array(
            'id'          => 'font_heading_h4',
            'type'        => 'typography',
            'title'       => esc_html__('Heading H4 Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for widget titles and H4 elements.', 'portfoliocraft'),
            'google'      => false,
            'font-backup' => false,
            'all_styles'  => true,
            'text-align'  => false,
            'line-height' => true,
            'font-size'   => true,
            'font-style'  => false,
            'output'      => array('h4', '.h4'),
            'units'       => 'px',            
            'font-family' => false,
            'color'       => false,
            'default'     => array(
                'font-size'   => '24px',
                'line-height' => '32px',
                'font-weight' => '600',
            ),
        ),
        array(
            'id'          => 'font_heading_h5',
            'type'        => 'typography',
            'title'       => esc_html__('Heading H5 Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for small headings and H5 elements.', 'portfoliocraft'),
            'google'      => false,
            'font-backup' => false,
            'all_styles'  => true,
            'text-align'  => false,
            'line-height' => true,
            'font-size'   => true,
            'font-style'  => false,
            'output'      => array('h5', '.h5'),
            'units'       => 'px',
            'font-family' => false,
            'color'       => false,
            'default'     => array(
                'font-size'   => '20px',
                'line-height' => '28px',
                'font-weight' => '600',
            ),
        ),
        array(
            'id'          => 'font_heading_h6',
            'type'        => 'typography',
            'title'       => esc_html__('Heading H6 Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for smallest headings and H6 elements.', 'portfoliocraft'),
            'google'      => false,
            'font-backup' => false,
            'all_styles'  => true,
            'text-align'  => false,
            'line-height' => true,
            'font-size'   => true,
            'font-style'  => false,
            'output'      => array('h6', '.h6'),
            'units'       => 'px',
            'font-family' => false,
            'color'       => false,
            'default'     => array(
                'font-size'   => '18px',
                'line-height' => '26px',
                'font-weight' => '600',
            ),
        ),
    )
));

/* ==========================================================================
   General Settings Section
   ========================================================================== */

/**
 * General Theme Settings
 * 
 * Controls various site-wide features and behaviors including:
 * - Site loading animation and styles
 * - Mouse movement animations and custom cursor
 * - Smooth scrolling functionality
 * - Cookie policy compliance and GDPR settings
 * - Performance and user experience enhancements
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('General Settings', 'portfoliocraft'),
    'icon'   => 'el el-wrench',
    'desc'   => esc_html__('Configure general theme settings and site-wide functionality.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id'       => 'site_loader',
            'type'     => 'button_set',
            'title'    => esc_html__('Site Loading Animation', 'portfoliocraft'),
            'subtitle' => esc_html__('Show loading animation while the site loads.', 'portfoliocraft'),
            'options'  => array(
                'on'  => esc_html__('Enable', 'portfoliocraft'),
                'off' => esc_html__('Disable', 'portfoliocraft'),
            ),
            'default'  => 'off',
        ),
        array(
            'id'       => 'site_loader_style',
            'type'     => 'select',
            'title'    => esc_html__('Loading Animation Style', 'portfoliocraft'),
            'subtitle' => esc_html__('Choose the style of loading animation.', 'portfoliocraft'),
            'options'  => array(
                'loader-default' => esc_html__('Default Spinner', 'portfoliocraft'),
                'loader-style1'  => esc_html__('Modern Style', 'portfoliocraft'),
            ),
            'default'  => 'loader-style1',
            'required' => array('site_loader', 'equals', 'on'),
        ),
        array(
            'id'       => 'mouse_move_animation',
            'type'     => 'button_set',
            'title'    => esc_html__('Mouse Movement Animation', 'portfoliocraft'),
            'subtitle' => esc_html__('Enable custom cursor and mouse movement effects.', 'portfoliocraft'),
            'options'  => array(
                'on'  => esc_html__('Enable', 'portfoliocraft'),
                'off' => esc_html__('Disable', 'portfoliocraft'),
            ),
            'default'  => 'off',
        ),
        array(
            'id'       => 'smooth_scroll',
            'type'     => 'button_set',
            'title'    => esc_html__('Smooth Scrolling', 'portfoliocraft'),
            'subtitle' => esc_html__('Enable smooth scrolling animation for better user experience.', 'portfoliocraft'),
            'options'  => array(
                'on'  => esc_html__('Enable', 'portfoliocraft'),
                'off' => esc_html__('Disable', 'portfoliocraft'),
            ),
            'default'  => 'off',
        ),
        
        // Cookie Policy Section
        array(
            'id'       => 'cookie_policy_section',
            'type'     => 'section',
            'title'    => esc_html__('Cookie Policy & GDPR Compliance', 'portfoliocraft'),
            'subtitle' => esc_html__('Configure cookie consent notice for GDPR compliance.', 'portfoliocraft'),
            'indent'   => true,
        ),
        array(
            'id'       => 'cookie_policy',
            'type'     => 'button_set',
            'title'    => esc_html__('Cookie Policy Notice', 'portfoliocraft'),
            'subtitle' => esc_html__('Display cookie consent notice to comply with GDPR regulations.', 'portfoliocraft'),
            'options'  => array(
                'show' => esc_html__('Show', 'portfoliocraft'),
                'hide' => esc_html__('Hide', 'portfoliocraft'),
            ),
            'default'  => 'hide',
        ),
        array(
            'id'       => 'cookie_policy_description',
            'type'     => 'textarea',
            'title'    => esc_html__('Cookie Notice Text', 'portfoliocraft'),
            'subtitle' => esc_html__('Enter the text to display in the cookie notice.', 'portfoliocraft'),
            'default'  => esc_html__('This website uses cookies to ensure you get the best experience on our website.', 'portfoliocraft'),
            'required' => array('cookie_policy', 'equals', 'show'),
        ),
        array(
            'id'          => 'cookie_policy_description_typo',
            'type'        => 'typography',
            'title'       => esc_html__('Cookie Notice Typography', 'portfoliocraft'),
            'subtitle'    => esc_html__('Typography settings for the cookie notice text.', 'portfoliocraft'),
            'google'      => true,
            'font-backup' => false,
            'all_styles'  => true,
            'line-height' => true,
            'font-size'   => true,
            'text-align'  => false,
            'color'       => false,
            'output'      => array('.pxl-cookie-policy .pxl-item--description'),
            'units'       => 'px',
            'required'    => array('cookie_policy', 'equals', 'show'),
        ),
        array(
            'id'       => 'cookie_policy_btntext',
            'type'     => 'text',
            'title'    => esc_html__('Cookie Button Text', 'portfoliocraft'),
            'subtitle' => esc_html__('Text for the "Learn More" button in cookie notice.', 'portfoliocraft'),
            'default'  => esc_html__('Learn More', 'portfoliocraft'),
            'required' => array('cookie_policy', 'equals', 'show'),
        ),
        array(
            'id'       => 'cookie_policy_link',
            'type'     => 'select',
            'title'    => esc_html__('Cookie Policy Page', 'portfoliocraft'),
            'subtitle' => esc_html__('Select the page that contains your cookie policy.', 'portfoliocraft'),
            'data'     => 'page',
            'args'     => array(
                'post_type'      => 'page',
                'posts_per_page' => -1,
                'orderby'        => 'title',
                'order'          => 'ASC',
            ),
            'required' => array('cookie_policy', 'equals', 'show'),
        ),
    )
));

/* ==========================================================================
   Header Section
   ========================================================================== */

/**
 * Header Configuration
 * 
 * Configures header layout, styling, and behavior including:
 * - Header layout selection from Elementor templates
 * - Sticky header functionality and scroll behavior
 * - Logo settings for different header states
 * - Navigation menu configurations
 * - Mobile header responsive settings
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Header Settings', 'portfoliocraft'),
    'icon'   => 'el el-indent-left',
    'desc'   => esc_html__('Configure header layout, logo, navigation, and mobile settings.', 'portfoliocraft'),
    'fields' => array_merge(
        portfoliocraft_header_opts(), // Include header options from theme functions
        array(
            array(
                'id'       => 'sticky_scroll',
                'type'     => 'button_set',
                'title'    => esc_html__('Sticky Header Behavior', 'portfoliocraft'),
                'subtitle' => esc_html__('Choose when the sticky header should appear.', 'portfoliocraft'),
                'options'  => array(
                    'scroll-up'   => esc_html__('Show on Scroll Up', 'portfoliocraft'),
                    'scroll-down' => esc_html__('Show on Scroll Down', 'portfoliocraft'),
                ),
                'default'  => 'scroll-down',
            ),
        )
    )
));

/**
 * Mobile Header Subsection
 * 
 * Dedicated settings for mobile header behavior including:
 * - Mobile header visibility controls
 * - Mobile-specific logo settings (dark and light versions)
 * - Logo sizing for mobile devices
 * - Mobile search functionality
 * - Mobile navigation customizations
 */
Redux::setSection($opt_name, array(
    'title'      => esc_html__('Mobile Header', 'portfoliocraft'),
    'icon'       => 'el el-circle-arrow-right',
    'subsection' => true,
    'desc'       => esc_html__('Configure mobile-specific header settings and responsive behavior.', 'portfoliocraft'),
    'fields'     => array_merge(
        portfoliocraft_header_mobile_opts(), // Include mobile header options
        array(
            array(
                'id'       => 'mobile_display',
                'type'     => 'button_set',
                'title'    => esc_html__('Mobile Header Display', 'portfoliocraft'),
                'subtitle' => esc_html__('Control mobile header visibility.', 'portfoliocraft'),
                'options'  => array(
                    'show' => esc_html__('Show', 'portfoliocraft'),
                    'hide' => esc_html__('Hide', 'portfoliocraft'),
                ),
                'default'  => 'show'
            ),
            array(
                'id'       => 'logo_m',
                'type'     => 'media',
                'title'    => esc_html__('Mobile Logo (Dark)', 'portfoliocraft'),
                'subtitle' => esc_html__('Logo displayed in mobile menu sidebar (dark version).', 'portfoliocraft'),
                'default'  => array(
                    'url' => get_template_directory_uri() . '/assets/img/logo.png'
                ),
                'url'      => false,
                'required' => array('mobile_display', 'equals', 'show'),
                'desc'     => sprintf(
                    esc_html__('You can also set page-specific logos in Page Options. %sView Instructions%s', 'portfoliocraft'),
                    '<a class="pxl-admin-popup" href="' . esc_url(get_template_directory_uri()) . '/inc/theme-options/instruct/logo_m_page.png">',
                    '</a>'
                ),
            ),
            array(
                'id'       => 'logo_light_m',
                'type'     => 'media',
                'title'    => esc_html__('Mobile Logo (Light)', 'portfoliocraft'),
                'subtitle' => esc_html__('Logo displayed in mobile menu sidebar (light version).', 'portfoliocraft'),
                'default'  => array(
                    'url' => get_template_directory_uri() . '/assets/img/logo.png'
                ),
                'url'      => false,
                'required' => array('mobile_display', 'equals', 'show'),
            ),
            array(
                'id'       => 'logo_height',
                'type'     => 'dimensions',
                'title'    => esc_html__('Mobile Logo Height', 'portfoliocraft'),
                'subtitle' => esc_html__('Set the height for mobile logos.', 'portfoliocraft'),
                'width'    => false,
                'unit'     => 'px',
                'output'   => array(
                    '#pxl-header-default .pxl-header-branding img',
                    '#pxl-header-default #pxl-header-mobile .pxl-header-branding img',
                    '#pxl-header-elementor #pxl-header-mobile .pxl-header-branding img',
                    '.pxl-logo-mobile img'
                ),
                'required' => array('mobile_display', 'equals', 'show'),
                'default'  => array('height' => '40px'),
            ),
            array(
                'id'       => 'search_mobile',
                'type'     => 'switch',
                'title'    => esc_html__('Mobile Search Form', 'portfoliocraft'),
                'subtitle' => esc_html__('Display search form in mobile header.', 'portfoliocraft'),
                'default'  => true,
                'required' => array('mobile_display', 'equals', 'show'),
            ),
            array(
                'id'       => 'search_placeholder_mobile',
                'type'     => 'text',
                'title'    => esc_html__('Mobile Search Placeholder', 'portfoliocraft'),
                'subtitle' => esc_html__('Placeholder text for mobile search input.', 'portfoliocraft'),
                'default'  => esc_html__('Search...', 'portfoliocraft'),
                'required' => array('search_mobile', 'equals', true),
            )
        )
    )
));

/* ==========================================================================
   Footer Section
   ========================================================================== */

/**
 * Footer Configuration
 * 
 * Controls footer layout, content, and functionality including:
 * - Footer template selection from Elementor
 * - Back to top button functionality
 * - Footer fixed positioning
 * - Footer widget areas and content
 * - Copyright and social media settings
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Footer Settings', 'portfoliocraft'),
    'icon'   => 'el el-website',
    'desc'   => esc_html__('Configure footer layout, back to top button, and footer behavior.', 'portfoliocraft'),
    'fields' => array_merge(
        portfoliocraft_footer_opts(), // Include footer options from theme functions
        array(
            array(
                'id'       => 'footer_fixed',
                'type'     => 'button_set',
                'title'    => esc_html__('Footer Fixed Position', 'portfoliocraft'),
                'subtitle' => esc_html__('Make footer stick to the bottom of the viewport.', 'portfoliocraft'),
                'options'  => array(
                    'on'  => esc_html__('Enable', 'portfoliocraft'),
                    'off' => esc_html__('Disable', 'portfoliocraft'),
                ),
                'default'  => 'off',
            ),
        ) 
    )
));

/* ==========================================================================
   Page Title Section
   ========================================================================== */


/**
 * 404 Error Page Configuration
 * 
 * Settings for the 404 error page including:
 * - Custom 404 page selection
 * - Error page content and styling
 * - Redirect options and suggestions
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Page Title', 'portfoliocraft'),
    'icon'   => 'el-icon-map-marker',
    'fields' => array_merge(
        portfoliocraft_page_title_opts(),
    )
));


/* ==========================================================================
   Blog Section
   ========================================================================== */

/**
 * Blog Configuration Parent Section
 * 
 * Main section for all blog-related settings including
 * archive pages, single posts, and search results.
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Blog Settings', 'portfoliocraft'),
    'icon'  => 'el el-edit',
    'desc'  => esc_html__('Configure blog archive, single post, and search settings.', 'portfoliocraft'),
    'fields' => array()
));

/**
 * Blog Archive Subsection
 * 
 * Settings for blog archive pages including:
 * - Sidebar configuration and layout
 * - Post display options and meta information
 * - Pagination and post excerpts
 * - Archive page customizations
 */
Redux::setSection($opt_name, array(
    'title'      => esc_html__('Blog Archive', 'portfoliocraft'),
    'icon'       => 'el-icon-pencil',
    'subsection' => true,
    'desc'       => esc_html__('Configure blog archive page layout and post display options.', 'portfoliocraft'),
    'fields'     => array(
        array(
            'id'     => 'blog_general_h',
            'title'  => esc_html__('General Settings', 'portfoliocraft'),
            'type'   => 'section',
            'indent' => true,
        ),
        portfoliocraft_sidebar_options(), // Include sidebar options
        array(
            'id'     => 'blog_display_h',
            'title'  => esc_html__('Display Options', 'portfoliocraft'),
            'type'   => 'section',
            'indent' => true,
        ),
        array(
            'id'       => 'archive_date',
            'title'    => esc_html__('Show Post Date', 'portfoliocraft'),
            'subtitle' => esc_html__('Display the publication date for each blog post.', 'portfoliocraft'),
            'type'     => 'switch',
            'default'  => true,
        ),
        array(
            'id'       => 'archive_author',
            'title'    => esc_html__('Show Post Author', 'portfoliocraft'),
            'subtitle' => esc_html__('Display the author name for each blog post.', 'portfoliocraft'),
            'type'     => 'switch',
            'default'  => true,
        ),
        array(
            'id'       => 'archive_excerpt',
            'title'    => esc_html__('Show Post Excerpt', 'portfoliocraft'),
            'subtitle' => esc_html__('Display post excerpt on archive pages.', 'portfoliocraft'),
            'type'     => 'switch',
            'default'  => true,
        ),
    )
));

/**
 * Single Post Subsection
 * 
 * Comprehensive settings for individual blog posts including:
 * - Sidebar configuration for single posts
 * - Post title and breadcrumb settings
 * - Featured image sizing and display
 * - Post meta information (date, author, categories, tags)
 * - Social sharing functionality
 * - Post navigation between posts
 * - Comment system configuration
 */
Redux::setSection($opt_name, array(
    'title'      => esc_html__('Single Post', 'portfoliocraft'),
    'icon'       => 'el el-icon-pencil',
    'subsection' => true,
    'desc'       => esc_html__('Configure single blog post layout and display options.', 'portfoliocraft'),
    'fields'     => array_merge(
        array(
            array(
                'id'     => 'sg_post_general_h',
                'title'  => esc_html__('General Settings', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            portfoliocraft_sidebar_options(['prefix' => 'post']),
            array(
                'id'     => 'sg_post_title_h',
                'title'  => esc_html__('Post Title Configuration', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
        ),
        portfoliocraft_post_title_opts(), // Include post title options
        array(
            array(
                'id'     => 'sg_breadcrumb_post_title_h',
                'title'  => esc_html__('Breadcrumb Settings', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            array(
                'id'       => 'sg_post_breadcrumb',
                'type'     => 'button_set',
                'title'    => esc_html__('Breadcrumb Title Source', 'portfoliocraft'),
                'subtitle' => esc_html__('Choose the source for breadcrumb title.', 'portfoliocraft'),
                'options'  => array(
                    'default' => esc_html__('Use Post Title', 'portfoliocraft'),
                    'custom'  => esc_html__('Use Custom Title', 'portfoliocraft'),
                ),
                'default'  => 'default',
            ),            
            array(
                'id'       => 'custom_sg_post_breadcrumb',
                'type'     => 'text',
                'title'    => esc_html__('Custom Breadcrumb Title', 'portfoliocraft'),
                'subtitle' => esc_html__('Enter custom title for post breadcrumbs.', 'portfoliocraft'),
                'default'  => esc_html__('Blog Details', 'portfoliocraft'),
                'required' => array('sg_post_breadcrumb', 'equals', 'custom'),
            ),
            array(
                'id'     => 'sg_post_display_h',
                'title'  => esc_html__('Display Options', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            array(
                'id'       => 'sg_featured_img_size',
                'type'     => 'text',
                'title'    => esc_html__('Featured Image Size', 'portfoliocraft'),
                'subtitle' => esc_html__('Enter image size (e.g., "thumbnail", "medium", "large", "full" or custom dimensions like "370x300").', 'portfoliocraft'),
                'default'  => 'large',
            ),
            array(
                'id'       => 'post_date',
                'title'    => esc_html__('Show Post Date', 'portfoliocraft'),
                'subtitle' => esc_html__('Display the publication date for blog posts.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true
            ),
            array(
                'id'       => 'post_author',
                'title'    => esc_html__('Show Post Author', 'portfoliocraft'),
                'subtitle' => esc_html__('Display the author information for blog posts.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true
            ),
            array(
                'id'       => 'post_comment',
                'title'    => esc_html__('Show Comment Count', 'portfoliocraft'),
                'subtitle' => esc_html__('Display the comment count for blog posts.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true
            ),
            array(
                'id'       => 'post_category',
                'title'    => esc_html__('Show Post Categories', 'portfoliocraft'),
                'subtitle' => esc_html__('Display the categories for blog posts.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true
            ),
            array(
                'id'       => 'post_tag',
                'title'    => esc_html__('Show Post Tags', 'portfoliocraft'),
                'subtitle' => esc_html__('Display the tags for blog posts.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true
            ),
            array(
                'id'       => 'post_navigation',
                'title'    => esc_html__('Post Navigation', 'portfoliocraft'),
                'subtitle' => esc_html__('Display previous/next post navigation.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true,
            ),
            
            // Social Sharing Section
            array(
                'title'  => esc_html__('Social Sharing', 'portfoliocraft'),
                'type'   => 'section',
                'id'     => 'social_section_h',
                'indent' => true,
            ),
            array(
                'id'       => 'post_social_share',
                'title'    => esc_html__('Enable Social Sharing', 'portfoliocraft'),
                'subtitle' => esc_html__('Display social media sharing buttons for blog posts.', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true,
            ),
            array(
                'id'       => 'social_facebook',
                'title'    => esc_html__('Facebook Sharing', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true,
                'indent'   => true,
                'required' => array('post_social_share', 'equals', '1'),
            ),
            array(
                'id'       => 'social_twitter',
                'title'    => esc_html__('Twitter Sharing', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true,
                'indent'   => true,
                'required' => array('post_social_share', 'equals', '1'),
            ),
            array(
                'id'       => 'social_pinterest',
                'title'    => esc_html__('Pinterest Sharing', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true,
                'indent'   => true,
                'required' => array('post_social_share', 'equals', '1'),
            ),
            array(
                'id'       => 'social_linkedin',
                'title'    => esc_html__('LinkedIn Sharing', 'portfoliocraft'),
                'type'     => 'switch',
                'default'  => true,
                'indent'   => true,
                'required' => array('post_social_share', 'equals', '1'),
            ),
        ),
    ),
));

/**
 * Search Results Subsection
 * 
 * Configuration for search result pages including:
 * - Search page layout and sidebar settings
 * - Search result display options
 * - Custom search page titles and breadcrumbs
 * - Search form customizations
 */
Redux::setSection($opt_name, array(
    'title'      => esc_html__('Search Results', 'portfoliocraft'),
    'icon'       => 'el el-icon-search',
    'subsection' => true,
    'desc'       => esc_html__('Configure search results page layout and display options.', 'portfoliocraft'),
    'fields'     => array_merge(
        array(
            array(
                'id'     => 'sg_search_general_h',
                'title'  => esc_html__('General Settings', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            portfoliocraft_sidebar_options(['prefix' => 'search']),
            array(
                'id'     => 'sg_search_title_h',
                'title'  => esc_html__('Search Title Configuration', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
        ),
        portfoliocraft_post_title_opts('search'),
        array(
            array(
                'id'     => 'sg_breadcrumb_search_title_h',
                'title'  => esc_html__('Breadcrumb Settings', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            array(
                'id'       => 'sg_search_breadcrumb',
                'type'     => 'button_set',
                'title'    => esc_html__('Search Title Source', 'portfoliocraft'),
                'subtitle' => esc_html__('Choose the source for search page title.', 'portfoliocraft'),
                'options'  => array(
                    'default' => esc_html__('Use Search Query', 'portfoliocraft'),
                    'custom'  => esc_html__('Use Custom Title', 'portfoliocraft'),
                ),
                'default'  => 'default',
            ),       
            array(
                'id'       => 'custom_sg_search_breadcrumb',
                'type'     => 'text',
                'title'    => esc_html__('Custom Search Title', 'portfoliocraft'),
                'subtitle' => esc_html__('Enter custom title for search results page.', 'portfoliocraft'),
                'default'  => esc_html__('Search Results', 'portfoliocraft'),
                'required' => array('sg_search_breadcrumb', 'equals', 'custom'),
            ),
        ),
    ),
));

/* ==========================================================================
   Portfolio Section
   ========================================================================== */

/**
 * Portfolio Configuration
 * 
 * Settings for portfolio custom post type including:
 * - Portfolio enable/disable functionality
 * - Custom slug and naming options
 * - Archive page configuration
 * - Single portfolio post settings
 * - Portfolio title and breadcrumb options
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Portfolio', 'portfoliocraft'),
    'icon'   => 'el el-briefcase',
    'desc'   => esc_html__('Configure portfolio post type and display settings.', 'portfoliocraft'),
    'fields' => array_merge(
        array(
            array(
                'title'  => esc_html__('General Settings', 'portfoliocraft'),
                'type'   => 'section',
                'id'     => 'portfolio_general_h',
                'indent' => true,
            ),
            array(
                'id'       => 'portfolio_display',
                'type'     => 'button_set',
                'title'    => esc_html__('Portfolio Post Type', 'portfoliocraft'),
                'subtitle' => esc_html__('Enable or disable the portfolio custom post type.', 'portfoliocraft'),
                'options'  => array(
                    'on'  => esc_html__('Enable', 'portfoliocraft'),
                    'off' => esc_html__('Disable', 'portfoliocraft'),
                ),
                'default'  => 'on',
            ),
            array(
                'id'           => 'portfolio_slug',
                'type'         => 'text',
                'title'        => esc_html__('Portfolio URL Slug', 'portfoliocraft'),
                'subtitle'     => esc_html__('Custom URL slug for portfolio posts (e.g., "portfolio", "work", "projects").', 'portfoliocraft'),
                'default'      => 'portfolio',
                'required'     => array('portfolio_display', 'equals', 'on'),
                'force_output' => true
            ),
            array(
                'id'           => 'portfolio_name',
                'type'         => 'text',
                'title'        => esc_html__('Portfolio Display Name', 'portfoliocraft'),
                'subtitle'     => esc_html__('Display name for portfolio in admin and frontend.', 'portfoliocraft'),
                'default'      => 'Portfolio',
                'required'     => array('portfolio_display', 'equals', 'on'),
                'force_output' => true
            ),
            array(
                'title'  => esc_html__('Portfolio Title Settings', 'portfoliocraft'),
                'type'   => 'section',
                'id'     => 'portfolio_title_h',
                'indent' => true,
            ),
        ),
        portfoliocraft_post_title_opts('portfolio'),
        array(
            array(
                'id'     => 'sg_breadcrumb_portfolio_title_h',
                'title'  => esc_html__('Breadcrumb Settings', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            array(
                'id'       => 'sg_portfolio_breadcrumb',
                'type'     => 'button_set',
                'title'    => esc_html__('Portfolio Title Source', 'portfoliocraft'),
                'subtitle' => esc_html__('Choose the source for portfolio breadcrumb title.', 'portfoliocraft'),
                'options'  => array(
                    'default' => esc_html__('Use Portfolio Title', 'portfoliocraft'),
                    'custom'  => esc_html__('Use Custom Title', 'portfoliocraft'),
                ),
                'default'  => 'default',
            ),            
            array(
                'id'       => 'custom_sg_portfolio_breadcrumb',
                'type'     => 'text',
                'title'    => esc_html__('Custom Portfolio Title', 'portfoliocraft'),
                'subtitle' => esc_html__('Enter custom title for portfolio breadcrumbs.', 'portfoliocraft'),
                'default'  => esc_html__('Portfolio Details', 'portfoliocraft'),
                'required' => array('sg_portfolio_breadcrumb', 'equals', 'custom'),
            ),
        )
    ),
));

/* ==========================================================================
   Services Section
   ========================================================================== */

/**
 * Services Configuration
 * 
 * Settings for Services custom post type including:
 * - Services enable/disable functionality
 * - Custom slug and naming options
 * - Archive page configuration
 * - Single Services post settings
 * - Services title and breadcrumb options
 */
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Services', 'portfoliocraft'),
    'icon'   => 'el el-user',
    'desc'   => esc_html__('Configure Services post type and display settings.', 'portfoliocraft'),
    'fields' => array_merge(
        array(
            array(
                'title'  => esc_html__('General Settings', 'portfoliocraft'),
                'type'   => 'section',
                'id'     => 'Services_general_h',
                'indent' => true,
            ),
            array(
                'id'       => 'Services_display',
                'type'     => 'button_set',
                'title'    => esc_html__('Services Post Type', 'portfoliocraft'),
                'subtitle' => esc_html__('Enable or disable the Services custom post type.', 'portfoliocraft'),
                'options'  => array(
                    'on'  => esc_html__('Enable', 'portfoliocraft'),
                    'off' => esc_html__('Disable', 'portfoliocraft'),
                ),
                'default'  => 'on',
            ),
            array(
                'id'           => 'Services_slug',
                'type'         => 'text',
                'title'        => esc_html__('Services URL Slug', 'portfoliocraft'),
                'subtitle'     => esc_html__('Custom URL slug for Services posts (e.g., "Services", "jobs", "opportunities").', 'portfoliocraft'),
                'default'      => 'Services',
                'required'     => array('Services_display', 'equals', 'on'),
                'force_output' => true
            ),
            array(
                'id'           => 'Services_name',
                'type'         => 'text',
                'title'        => esc_html__('Services Display Name', 'portfoliocraft'),
                'subtitle'     => esc_html__('Display name for Services in admin and frontend.', 'portfoliocraft'),
                'default'      => 'Services',
                'required'     => array('Services_display', 'equals', 'on'),
                'force_output' => true
            ),
            array(
                'title'  => esc_html__('Services Title Settings', 'portfoliocraft'),
                'type'   => 'section',
                'id'     => 'Services_title_h',
                'indent' => true,
            ),
        ),
        portfoliocraft_post_title_opts('Services'),
        array(
            array(
                'id'     => 'sg_breadcrumb_Services_title_h',
                'title'  => esc_html__('Breadcrumb Settings', 'portfoliocraft'),
                'type'   => 'section',
                'indent' => true,
            ),
            array(
                'id'       => 'sg_Services_breadcrumb',
                'type'     => 'button_set',
                'title'    => esc_html__('Services Title Source', 'portfoliocraft'),
                'subtitle' => esc_html__('Choose the source for Services breadcrumb title.', 'portfoliocraft'),
                'options'  => array(
                    'default' => esc_html__('Use Services Title', 'portfoliocraft'),
                    'custom'  => esc_html__('Use Custom Title', 'portfoliocraft'),
                ),
                'default'  => 'default',
            ),            
            array(
                'id'       => 'custom_sg_Services_breadcrumb',
                'type'     => 'text',
                'title'    => esc_html__('Custom Services Title', 'portfoliocraft'),
                'subtitle' => esc_html__('Enter custom title for Services breadcrumbs.', 'portfoliocraft'),
                'default'  => esc_html__('Services Details', 'portfoliocraft'),
                'required' => array('sg_Services_breadcrumb', 'equals', 'custom'),
            ),
        )
    ),
));

/* ==========================================================================
   WooCommerce Shop Section
   ========================================================================== */

// Only add WooCommerce options if WooCommerce is active
if (class_exists('WooCommerce')) {
    
    /**
     * WooCommerce Shop Parent Section
     * 
     * Main section for all WooCommerce-related settings.
     */
    Redux::setSection($opt_name, array(
        'title' => esc_html__('WooCommerce Shop', 'portfoliocraft'),
        'icon'  => 'el el-shopping-cart',
        'desc'  => esc_html__('Configure WooCommerce shop settings, product display, and e-commerce functionality.', 'portfoliocraft'),
    ));

    /**
     * Product Archive Subsection
     * 
     * Settings for WooCommerce shop and category pages including:
     * - Sidebar configuration for shop pages
     * - Product image sizing and display
     * - Products per row and pagination settings
     * - Shop layout and grid options
     */
    Redux::setSection($opt_name, array(
        'title'      => esc_html__('Product Archive', 'portfoliocraft'),
        'icon'       => 'el-icon-shopping-cart-sign',
        'subsection' => true,
        'desc'       => esc_html__('Configure shop page layout and product display options.', 'portfoliocraft'),
        'fields'     => array(
            portfoliocraft_sidebar_options(['prefix' => 'shop']),
            array(
                'id'       => 'shop_featured_img_size',
                'type'     => 'text',
                'title'    => esc_html__('Product Image Size', 'portfoliocraft'),
                'subtitle' => esc_html__('Enter image size for shop products (e.g., "thumbnail", "medium", "large", "full" or custom dimensions like "370x300").', 'portfoliocraft'),
                'default'  => 'medium',
            ),
            array(
                'title'         => esc_html__('Products Per Row', 'portfoliocraft'),
                'id'            => 'number_of_products_per_row',
                'type'          => 'slider',
                'subtitle'      => esc_html__('Number of products to display per row on shop pages.', 'portfoliocraft'),
                'default'       => 3,
                'min'           => 2,
                'step'          => 1,
                'max'           => 5,
                'display_value' => 'text',
            ),
            array(
                'title'         => esc_html__('Products Per Page', 'portfoliocraft'),
                'id'            => 'product_pages_show_at_most',
                'type'          => 'slider',
                'subtitle'      => esc_html__('Maximum number of products to display per page.', 'portfoliocraft'),
                'default'       => 12,
                'min'           => 6,
                'step'          => 3,
                'max'           => 50,
                'display_value' => 'text'
            ),
        ),
    ));

    /**
     * Single Product Subsection
     * 
     * Comprehensive settings for individual product pages including:
     * - Product display options and layout
     * - Product image sizing and gallery settings
     * - Product information and meta display
     * - Social sharing for products
     * - Related products configuration
     */
    Redux::setSection($opt_name, array(
        'title'      => esc_html__('Single Product', 'portfoliocraft'),
        'icon'       => 'el-icon-shopping-cart',
        'subsection' => true,
        'desc'       => esc_html__('Configure single product page layout and display options.', 'portfoliocraft'),
        'fields'     => array_merge(
            array(
                array(
                    'title'  => esc_html__('General Settings', 'portfoliocraft'),
                    'type'   => 'section',
                    'id'     => 'product_general_h',
                    'indent' => true,
                ),
                array(
                    'id'       => 'product_display',
                    'type'     => 'button_set',
                    'title'    => esc_html__('Single Product Display', 'portfoliocraft'),
                    'subtitle' => esc_html__('Enable or disable single product page enhancements.', 'portfoliocraft'),
                    'options'  => array(
                        'on'  => esc_html__('Enable', 'portfoliocraft'),
                        'off' => esc_html__('Disable', 'portfoliocraft'),
                    ),
                    'default'  => 'on',
                ),
                array(
                    'id'           => 'product_slug',
                    'type'         => 'text',
                    'title'        => esc_html__('Product URL Slug', 'portfoliocraft'),
                    'subtitle'     => esc_html__('Custom URL slug for product posts.', 'portfoliocraft'),
                    'default'      => 'product',
                    'required'     => array('product_display', 'equals', 'on'),
                    'force_output' => true
                ),
                array(
                    'id'           => 'product_name',
                    'type'         => 'text',
                    'title'        => esc_html__('Product Display Name', 'portfoliocraft'),
                    'subtitle'     => esc_html__('Display name for products in admin and frontend.', 'portfoliocraft'),
                    'default'      => 'Product',
                    'required'     => array('product_display', 'equals', 'on'),
                    'force_output' => true
                ),
                array(
                    'id'           => 'archive_product_link',
                    'type'         => 'select',
                    'title'        => esc_html__('Custom Shop Page', 'portfoliocraft'),
                    'subtitle'     => esc_html__('Select a custom page to use as shop archive.', 'portfoliocraft'),
                    'data'         => 'page',
                    'args'         => array(
                        'post_type'      => 'page',
                        'posts_per_page' => -1,
                        'orderby'        => 'title',
                        'order'          => 'ASC',
                    ),
                    'required'     => array('product_display', 'equals', 'on'),
                    'force_output' => true
                ),
                array(
                    'title'  => esc_html__('Product Title Settings', 'portfoliocraft'),
                    'type'   => 'section',
                    'id'     => 'product_title_h',
                    'indent' => true,
                ),
            ),
            portfoliocraft_post_title_opts('product'),
            array(
                array(
                    'id'     => 'sg_breadcrumb_product_title_h',
                    'title'  => esc_html__('Breadcrumb Settings', 'portfoliocraft'),
                    'type'   => 'section',
                    'indent' => true,
                ),
                       array(
                    'id'      => 'custom_sg_product_breadcrumb',
                    'type'    => 'text',
                    'title'   => esc_html__('Custom Post Title', 'portfoliocraft'),
                    'default' => esc_html__('Product Details', 'portfoliocraft'),
                    'required' => array( 0 => 'sg_product_breadcrumb', 1 => 'equals', 2 => 'custom' ),
                ),
            ),
            array(
                array(
                    'id'       => 'single_img_size',
                    'type'     => 'dimensions',
                    'title'    => esc_html__('Image Size', 'portfoliocraft'),
                    'unit'     => 'px',
                ),
                array(
                    'id'       => 'product_social_share',
                    'type'     => 'switch',
                    'title'    => esc_html__('Social Share', 'portfoliocraft'),
                    'default'  => false
                ),
            )
        )
    ));
}

// Add Back to Top Button Section
Redux::setSection($opt_name, array(
    'title'  => esc_html__('Back to Top', 'portfoliocraft'),
    'icon'   => 'el el-arrow-up',
    'desc'   => esc_html__('Configure the Back to Top button appearance and behavior.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id' => 'back_to_top',
            'type' => 'switch',
            'title' => esc_html__('Enable Back to Top Button', 'portfoliocraft'),
            'subtitle' => esc_html__('Show a back to top button on all pages.', 'portfoliocraft'),
            'default' => false,
        ),
        array(
            'id' => 'back_to_top_bg',
            'type' => 'color',
            'title' => esc_html__('Button Background', 'portfoliocraft'),
            'default' => '#222222',
            'required' => array('back_to_top', '=', true),
        ),
        array(
            'id' => 'back_to_top_color',
            'type' => 'color',
            'title' => esc_html__('Button Text Color', 'portfoliocraft'),
            'default' => '#ffffff',
            'required' => array('back_to_top', '=', true),
        ),
        array(
            'id' => 'back_to_top_bg_hover',
            'type' => 'color',
            'title' => esc_html__('Button Background (Hover)', 'portfoliocraft'),
            'default' => '#444444',
            'required' => array('back_to_top', '=', true),
        ),
        array(
            'id' => 'back_to_top_color_hover',
            'type' => 'color',
            'title' => esc_html__('Button Text Color (Hover)', 'portfoliocraft'),
            'default' => '#ffffff',
            'required' => array('back_to_top', '=', true),
        ),
        array(
            'id' => 'back_to_top_size',
            'type' => 'button_set',
            'title' => esc_html__('Button Size', 'portfoliocraft'),
            'options' => array(
                'small' => esc_html__('Small', 'portfoliocraft'),
                'medium' => esc_html__('Medium', 'portfoliocraft'),
                'large' => esc_html__('Large', 'portfoliocraft'),
                'custom' => esc_html__('Custom', 'portfoliocraft'),
            ),
            'default' => 'medium',
            'required' => array('back_to_top', '=', true),
        ),
        array(
            'id' => 'back_to_top_size_custom',
            'type' => 'dimensions',
            'title' => esc_html__('Custom Size (px)', 'portfoliocraft'),
            'units' => array('px'),
            'width' => true,
            'height' => true,
            'default' => array('width' => 48, 'height' => 48),
            'required' => array('back_to_top_size', '=', 'custom'),
        ),
        array(
            'id' => 'back_to_top_position',
            'type' => 'button_set',
            'title' => esc_html__('Button Position', 'portfoliocraft'),
            'options' => array(
                'right' => esc_html__('Right', 'portfoliocraft'),
                'left' => esc_html__('Left', 'portfoliocraft'),
            ),
            'default' => 'right',
            'required' => array('back_to_top', '=', true),
        ),
    )
));

/* ==========================================================================
   Cache Management Section
   ========================================================================== */

/**
 * Cache Management Section
 * 
 * Provides controls for the Rakmyat Cache Management system including:
 * - Enable/disable cache system
 * - Auto-refresh after cache clear
 * - Cache duration settings
 * - Manual cache clear buttons
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Cache Management', 'portfoliocraft'),
    'icon' => 'el el-refresh',
    'desc' => esc_html__('Configure cache settings for improved website performance. The cache system helps speed up your site by storing frequently accessed data.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id' => 'cache_section_start',
            'type' => 'section',
            'title' => esc_html__('Cache System Settings', 'portfoliocraft'),
            'subtitle' => esc_html__('Control how the cache system operates on your website.', 'portfoliocraft'),
            'indent' => true,
        ),
        array(
            'id' => 'cache_enabled',
            'type' => 'switch',
            'title' => esc_html__('Enable Cache System', 'portfoliocraft'),
            'subtitle' => esc_html__('Turn on/off the entire cache management system.', 'portfoliocraft'),
            'desc' => esc_html__('When enabled, the cache system will store demo imports, Elementor data, and other frequently accessed content to improve performance.', 'portfoliocraft'),
            'default' => true,
        ),
        array(
            'id' => 'cache_show_admin_bar',
            'type' => 'switch',
            'title' => esc_html__('Show Admin Bar Button', 'portfoliocraft'),
            'subtitle' => esc_html__('Display cache clear button in WordPress admin bar.', 'portfoliocraft'),
            'desc' => esc_html__('Shows a cache management button in the top admin bar for quick access to cache clearing functions.', 'portfoliocraft'),
            'default' => true,
            'required' => array('cache_enabled', '=', true),
        ),
        array(
            'id' => 'cache_auto_refresh',
            'type' => 'switch',
            'title' => esc_html__('Auto-Refresh After Clear', 'portfoliocraft'),
            'subtitle' => esc_html__('Automatically refresh the page after clearing cache.', 'portfoliocraft'),
            'desc' => esc_html__('When enabled, the page will automatically reload after cache is cleared to show updated content immediately.', 'portfoliocraft'),
            'default' => true,
            'required' => array('cache_enabled', '=', true),
        ),
        array(
            'id' => 'cache_duration',
            'type' => 'button_set',
            'title' => esc_html__('Cache Duration', 'portfoliocraft'),
            'subtitle' => esc_html__('How long should cache be stored before automatic refresh.', 'portfoliocraft'),
            'desc' => esc_html__('Shorter durations mean more frequent updates but may impact performance. Longer durations improve performance but may delay content updates.', 'portfoliocraft'),
            'options' => array(
                '1800' => esc_html__('30 Minutes', 'portfoliocraft'),
                '3600' => esc_html__('1 Hour', 'portfoliocraft'),
                '21600' => esc_html__('6 Hours', 'portfoliocraft'),
                '86400' => esc_html__('24 Hours', 'portfoliocraft'),
            ),
            'default' => '3600',
            'required' => array('cache_enabled', '=', true),
        ),
        array(
            'id' => 'cache_auto_clear_on_update',
            'type' => 'switch',
            'title' => esc_html__('Auto-Clear on Updates', 'portfoliocraft'),
            'subtitle' => esc_html__('Automatically clear cache when themes or plugins are updated.', 'portfoliocraft'),
            'desc' => esc_html__('Ensures that cache doesn\'t interfere with updates by automatically clearing it when changes are made.', 'portfoliocraft'),
            'default' => true,
            'required' => array('cache_enabled', '=', true),
        ),
        array(
            'id' => 'cache_clear_on_theme_switch',
            'type' => 'switch',
            'title' => esc_html__('Clear on Theme Switch', 'portfoliocraft'),
            'subtitle' => esc_html__('Automatically clear cache when switching themes.', 'portfoliocraft'),
            'desc' => esc_html__('Prevents cached data from previous themes interfering with the new active theme.', 'portfoliocraft'),
            'default' => true,
            'required' => array('cache_enabled', '=', true),
        ),
    )
));

/* ==========================================================================
   SEO Management Settings
   ========================================================================== */

/**
 * SEO Management Section
 * 
 * Provides controls for SEO functionality including:
 * - Enable/disable SEO metabox for all post types
 * - Control meta description fields
 * - SEO feature management
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('SEO Management', 'portfoliocraft'),
    'icon' => 'el el-search',
    'desc' => esc_html__('Configure SEO settings and features for your website. Control meta descriptions, SEO fields, and search engine optimization options.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id' => 'seo_section_start',
            'type' => 'section',
            'title' => esc_html__('SEO System Settings', 'portfoliocraft'),
            'subtitle' => esc_html__('Control how SEO features operate on your website.', 'portfoliocraft'),
            'indent' => true,
        ),
        array(
            'id' => 'seo_metabox_enable',
            'type' => 'switch',
            'title' => esc_html__('Enable SEO Metabox', 'portfoliocraft'),
            'subtitle' => esc_html__('Show SEO metabox on all post types including posts, pages, portfolio, services, and products.', 'portfoliocraft'),
            'desc' => esc_html__('When enabled, SEO fields (meta title, description, keywords) will appear on all content types. Disable if you are using another SEO plugin like Yoast or RankMath.', 'portfoliocraft'),
            'default' => true,
        ),
        array(
            'id' => 'seo_supported_post_types',
            'type' => 'select',
            'title' => esc_html__('Supported Post Types', 'portfoliocraft'),
            'subtitle' => esc_html__('Choose which post types should have SEO metabox.', 'portfoliocraft'),
            'desc' => esc_html__('Select post types where you want SEO fields to appear. Leave empty to auto-detect all public post types.', 'portfoliocraft'),
            'options' => array(
                'auto' => esc_html__('Auto-detect all public post types', 'portfoliocraft'),
                'post' => esc_html__('Posts only', 'portfoliocraft'),
                'page' => esc_html__('Pages only', 'portfoliocraft'),
                'custom' => esc_html__('Posts, Pages + Custom Post Types', 'portfoliocraft'),
            ),
            'default' => 'auto',
            'required' => array('seo_metabox_enable', '=', true),
        ),
        array(
            'id' => 'seo_meta_description_required',
            'type' => 'switch',
            'title' => esc_html__('Require Meta Description', 'portfoliocraft'),
            'subtitle' => esc_html__('Make meta description field required when editing content.', 'portfoliocraft'),
            'desc' => esc_html__('When enabled, content editors will be encouraged to add meta descriptions for better SEO.', 'portfoliocraft'),
            'default' => false,
            'required' => array('seo_metabox_enable', '=', true),
        ),
        array(
            'id' => 'seo_open_graph_enable',
            'type' => 'switch',
            'title' => esc_html__('Enable Open Graph Tags', 'portfoliocraft'),
            'subtitle' => esc_html__('Output Open Graph meta tags for social media sharing.', 'portfoliocraft'),
            'desc' => esc_html__('Generates og:title, og:description, and other meta tags for better social media integration.', 'portfoliocraft'),
            'default' => true,
            'required' => array('seo_metabox_enable', '=', true),
        ),
        array(
            'id' => 'seo_twitter_cards_enable',
            'type' => 'switch',
            'title' => esc_html__('Enable Twitter Cards', 'portfoliocraft'),
            'subtitle' => esc_html__('Output Twitter Card meta tags for Twitter sharing.', 'portfoliocraft'),
            'desc' => esc_html__('Generates twitter:title, twitter:description, and other meta tags for better Twitter integration.', 'portfoliocraft'),
            'default' => true,
            'required' => array('seo_metabox_enable', '=', true),
        ),
        array(
            'id' => 'seo_section_end',
            'type' => 'section',
            'indent' => false,
        ),
        array(
            'id' => 'seo_info',
            'type' => 'info',
            'title' => esc_html__('SEO Plugin Compatibility', 'portfoliocraft'),
            'desc' => esc_html__('<strong>Important:</strong> If you are using dedicated SEO plugins like Yoast SEO, RankMath, or All in One SEO, you should disable the built-in SEO metabox to avoid conflicts. This built-in SEO system is designed for users who prefer a simple, lightweight solution without additional plugins.', 'portfoliocraft'),
        ),
    )
));

/* ==========================================================================
   Content Management Settings
   ========================================================================== */

/**
 * Content Management Section
 * 
 * Provides controls for content-related features including:
 * - Post views counter
 * - Content display options
 * - Performance settings for content
 */
Redux::setSection($opt_name, array(
    'title' => esc_html__('Content Management', 'portfoliocraft'),
    'icon' => 'el el-file-edit',
    'desc' => esc_html__('Configure content management features including view counters, display options, and content-related functionality.', 'portfoliocraft'),
    'fields' => array(
        array(
            'id' => 'content_section_start',
            'type' => 'section',
            'title' => esc_html__('Post Views Counter', 'portfoliocraft'),
            'subtitle' => esc_html__('Track and display view counts for your content.', 'portfoliocraft'),
            'indent' => true,
        ),
        array(
            'id' => 'post_views_enable',
            'type' => 'switch',
            'title' => esc_html__('Enable Post Views Counter', 'portfoliocraft'),
            'subtitle' => esc_html__('Track view counts for posts, pages, portfolio, and services.', 'portfoliocraft'),
            'desc' => esc_html__('When enabled, the system will track how many times each post/page has been viewed. View counts appear in admin columns and can be displayed on frontend.', 'portfoliocraft'),
            'default' => true,
        ),
        array(
            'id' => 'post_views_exclude_logged_in',
            'type' => 'switch',
            'title' => esc_html__('Exclude Logged-in Users', 'portfoliocraft'),
            'subtitle' => esc_html__('Don\'t count views from logged-in users including admins.', 'portfoliocraft'),
            'desc' => esc_html__('When enabled, view counts will only track anonymous visitors, excluding views from logged-in users.', 'portfoliocraft'),
            'default' => true,
            'required' => array('post_views_enable', '=', true),
        ),
        array(
            'id' => 'post_views_supported_types',
            'type' => 'checkbox',
            'title' => esc_html__('Supported Post Types', 'portfoliocraft'),
            'subtitle' => esc_html__('Choose which post types should have view tracking.', 'portfoliocraft'),
            'desc' => esc_html__('Select the post types where you want to track and display view counts.', 'portfoliocraft'),
            'options' => array(
                'post' => esc_html__('Posts', 'portfoliocraft'),
                'page' => esc_html__('Pages', 'portfoliocraft'),
                'portfolio' => esc_html__('Portfolio', 'portfoliocraft'),
                'services' => esc_html__('Services', 'portfoliocraft'),
                'product' => esc_html__('Products (WooCommerce)', 'portfoliocraft'),
            ),
            'default' => array(
                'post' => '1',
                'page' => '1',
                'portfolio' => '1',
                'services' => '1'
            ),
            'required' => array('post_views_enable', '=', true),
        ),
        array(
            'id' => 'content_section_end',
            'type' => 'section',
            'indent' => false,
        ),
        array(
            'id' => 'post_views_info',
            'type' => 'info',
            'title' => esc_html__('View Counter Information', 'portfoliocraft'),
            'desc' => esc_html__('<strong>Performance:</strong> The view counter is highly optimized and uses direct database updates to minimize performance impact. Views are tracked only for anonymous visitors by default.<br><br><strong>Admin Display:</strong> View counts appear as a column in the post/page admin lists. You can show/hide this column using the "Screen Options" tab at the top of admin pages.<br><br><strong>Frontend Display:</strong> Use <code>PXL_Post_Views::display_post_views()</code> in your theme templates to show view counts on the frontend.', 'portfoliocraft'),
        ),
    )
));