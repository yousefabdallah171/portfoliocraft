<?php
/**
 * Page Options Metabox Registration
 * 
 * This file registers metabox options for all public post types in the theme
 * Provides comprehensive page-level customization options including header, footer,
 * page title, content, colors, and extra settings
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Page Options Metaboxes
 * 
 * Hooks into the pxl_post_metabox_register action to register metabox options
 * for all public post types. Creates comprehensive page-level options including
 * header settings, page title configuration, content options, footer settings,
 * color customization, and extra features.
 * 
 * @param object $metabox The metabox registration object
 * @return void
 */
add_action('pxl_post_metabox_register', 'portfoliocraft_page_options_register');
function portfoliocraft_page_options_register($metabox) {
    
    // Security check: Ensure metabox object exists
    if (!is_object($metabox) || !method_exists($metabox, 'add_meta_data')) {
        return;
    }
    
    // Get all registered public post types
    $post_types = get_post_types(array('public' => true), 'names');
    
    // Remove unwanted post types from metabox registration
    $excluded_types = array('attachment', 'elementor_library', 'rmt-template');
    foreach ($excluded_types as $excluded_type) {
        if (isset($post_types[$excluded_type])) {
            unset($post_types[$excluded_type]);
        }
    }
    
    // Add WooCommerce product post type if WooCommerce is active
    if (class_exists('WooCommerce') && !isset($post_types['product'])) {
        $post_types['product'] = 'product';
    }
    
    // Initialize panels array for metabox configuration
    $panels = array();
    
    // Create metabox panels for each post type
    foreach ($post_types as $post_type) {
        // Sanitize post type for use in option names
        $sanitized_post_type = sanitize_key($post_type);
        
        // Build panel configuration for current post type
        $panels[$sanitized_post_type] = array(
            'opt_name'            => 'pxl_' . $sanitized_post_type . '_options',
            'display_name'        => sprintf(esc_html__('%s Options', 'portfoliocraft'), ucfirst($post_type)),
            'show_options_object' => false,
            'context'            => 'advanced',
            'priority'           => 'default',
            'sections'           => array(
                
                /**
                 * Header Section
                 * Contains header layout, mobile header, display options, and styling
                 */
                'header' => array(
                    'title'  => esc_html__('Header', 'portfoliocraft'),
                    'icon'   => 'el-icon-website',
                    'fields' => array_merge(
                        // Main header and mobile header layout options
                        portfoliocraft_header_opts(array(
                            'default'         => true,
                            'default_value'   => '-1'
                        )),
                        // Header display and mobile styling options
                        array(
                            array(
                                'id'       => 'header_display',
                                'type'     => 'button_set',
                                'title'    => esc_html__('Header Display', 'portfoliocraft'),
                                'desc'     => esc_html__('Choose whether to show or hide the header on this page', 'portfoliocraft'),
                                'options'  => array(
                                    'show' => esc_html__('Show', 'portfoliocraft'),
                                    'hide' => esc_html__('Hide', 'portfoliocraft'),
                                ),
                                'default'  => 'show',
                            ),
                            array(
                                'id'       => 'page_mobile_style',
                                'type'     => 'button_set',
                                'title'    => esc_html__('Mobile Style', 'portfoliocraft'),
                                'desc'     => esc_html__('Select mobile header style for this page', 'portfoliocraft'),
                                'options'  => array(
                                    'inherit' => esc_html__('Inherit', 'portfoliocraft'),
                                    'light'   => esc_html__('Light', 'portfoliocraft'),
                                    'dark'    => esc_html__('Dark', 'portfoliocraft'),
                                ),
                                'default'  => 'inherit',
                            ),
                            array(
                                'id'       => 'logo_m',
                                'type'     => 'media',
                                'title'    => esc_html__('Mobile Logo Dark', 'portfoliocraft'),
                                'desc'     => esc_html__('Upload mobile logo for dark theme', 'portfoliocraft'),
                                'default'  => '',
                                'url'      => false,
                            ),
                            array(
                                'id'       => 'logo_light_m',
                                'type'     => 'media',
                                'title'    => esc_html__('Mobile Logo Light', 'portfoliocraft'),
                                'desc'     => esc_html__('Upload mobile logo for light theme', 'portfoliocraft'),
                                'default'  => '',
                                'url'      => false,
                            ),
                            array(
                                'id'       => 'p_menu',
                                'type'     => 'select',
                                'title'    => esc_html__('Menu', 'portfoliocraft'),
                                'desc'     => esc_html__('Select custom menu for this page. The custom menu will apply to the entire layout when you use Case Nav Menu widget in Elementor and Menu on header layout in Mobile.', 'portfoliocraft'),
                                'options'  => portfoliocraft_get_nav_menu_slug(),
                                'default'  => '',
                            ),
                        ),
                        // Sticky header and spacing options
                        array(
                            array(
                                'id'       => 'sticky_scroll',
                                'type'     => 'button_set',
                                'title'    => esc_html__('Sticky Scroll', 'portfoliocraft'),
                                'desc'     => esc_html__('Configure sticky header scroll behavior', 'portfoliocraft'),
                                'options'  => array(
                                    '-1'          => esc_html__('Inherit', 'portfoliocraft'),
                                    'scroll-up'   => esc_html__('Scroll Up', 'portfoliocraft'),
                                    'scroll-down' => esc_html__('Scroll Down', 'portfoliocraft'),
                                ),
                                'default'  => '-1',
                            ),
                            array(
                                'id'       => 'header_margin',
                                'type'     => 'spacing',
                                'mode'     => 'margin',
                                'title'    => esc_html__('Header Margin', 'portfoliocraft'),
                                'desc'     => esc_html__('Set custom margin for header element', 'portfoliocraft'),
                                'width'    => false,
                                'unit'     => 'px',
                                'output'   => array('#rmt-header-elementor .rmt-header-elementor-main'),
                            ),
                        )
                    )
                ),
                
                /**
                 * Page Title Section
                 * Contains page title mode, layout, and custom title options
                 */
                'page_title' => array(
                    'title'  => esc_html__('Page Title', 'portfoliocraft'),
                    'icon'   => 'el el-indent-left',
                    'fields' => array_merge(
                        // Page title configuration options
                        portfoliocraft_page_title_opts(array(
                            'default'         => true,
                            'default_value'   => '-1'
                        )),
                        array(
                            array(
                                'id'    => 'page_title_custom',
                                'type'  => 'text',
                                'title' => esc_html__('Custom Page Title', 'portfoliocraft'),
                                'desc'  => esc_html__('Enter custom title to override the default page title', 'portfoliocraft'),
                                'default' => '',
                            ),
                        ),
                    ),
                ),
                
                /**
                 * Content Section
                 * Contains sidebar options and content spacing settings
                 */
                'content' => array(
                    'title'  => esc_html__('Content', 'portfoliocraft'),
                    'icon'   => 'el-icon-pencil',
                    'fields' => array(
                        // Sidebar configuration
                        portfoliocraft_sidebar_options(array(
                            'prefix'  => $sanitized_post_type, 
                            'default' => 'disable'
                        )),
                        // Content spacing options
                        array(
                            'id'             => 'content_spacing',
                            'type'           => 'spacing',
                            'title'          => esc_html__('Content Spacing Top/Bottom', 'portfoliocraft'),
                            'desc'           => esc_html__('Set custom padding for main content area', 'portfoliocraft'),
                            'output'         => array('#rmt-wrapper #rmt-main'),
                            'right'          => false,
                            'left'           => false,
                            'mode'           => 'padding',
                            'units'          => array('px'),
                            'units_extended' => 'false',
                            'default'        => array(
                                'padding-top'    => '',
                                'padding-bottom' => '',
                                'units'          => 'px',
                            )
                        ), 
                    )
                ),
                
                /**
                 * Footer Section
                 * Contains footer layout, display, and fixed footer options
                 */
                'footer' => array(
                    'title'  => esc_html__('Footer', 'portfoliocraft'),
                    'icon'   => 'el el-website',
                    'fields' => array_merge(
                        // Footer layout options
                        portfoliocraft_footer_opts(array(
                            'default'         => true,
                            'default_value'   => '-1'
                        )),
                        array(
                            array(
                                'id'       => 'footer_display',
                                'type'     => 'button_set',
                                'title'    => esc_html__('Footer Display', 'portfoliocraft'),
                                'desc'     => esc_html__('Choose whether to show or hide the footer on this page', 'portfoliocraft'),
                                'options'  => array(
                                    'show' => esc_html__('Show', 'portfoliocraft'),
                                    'hide' => esc_html__('Hide', 'portfoliocraft'),
                                ),
                                'default'  => 'show',
                            ),
                            array(
                                'id'       => 'p_footer_fixed',
                                'type'     => 'button_set',
                                'title'    => esc_html__('Footer Fixed', 'portfoliocraft'),
                                'desc'     => esc_html__('Enable or disable fixed footer positioning', 'portfoliocraft'),
                                'options'  => array(
                                    'inherit' => esc_html__('Inherit', 'portfoliocraft'),
                                    'on'      => esc_html__('On', 'portfoliocraft'),
                                    'off'     => esc_html__('Off', 'portfoliocraft'),
                                ),
                                'default'  => 'inherit',
                            ),
                        )
                    )
                ),
                
                /**
                 * Colors Section
                 * Contains color customization options for the page
                 */
                'colors' => array(
                    'title'  => esc_html__('Colors', 'portfoliocraft'),
                    'icon'   => 'el el-website',
                    'fields' => array(
                        array(
                            'id'          => 'page_body_color',
                            'type'        => 'color',
                            'title'       => esc_html__('Body Background Color', 'portfoliocraft'),
                            'desc'        => esc_html__('Set custom background color for this page', 'portfoliocraft'),
                            'default'     => '',
                            'transparent' => false,
                            'output'      => array(
                                'background-color' => 'body',
                            )
                        ),
                        array(
                            'id'          => 'primary_color',
                            'type'        => 'color',
                            'title'       => esc_html__('Primary Color', 'portfoliocraft'),
                            'desc'        => esc_html__('Override theme primary color for this page', 'portfoliocraft'),
                            'transparent' => false,
                            'default'     => ''
                        ),
                        array(
                            'id'          => 'gradient_color',
                            'type'        => 'color_gradient',
                            'title'       => esc_html__('Gradient Color', 'portfoliocraft'),
                            'desc'        => esc_html__('Set custom gradient colors for this page', 'portfoliocraft'),
                            'transparent' => false,
                            'default'     => array(
                                'from' => '',
                                'to'   => '', 
                            ),
                        ),
                    )
                ),
                
                /**
                 * Extra Section
                 * Contains additional customization options
                 */
                'extra' => array(
                    'title'  => esc_html__('Extra', 'portfoliocraft'),
                    'icon'   => 'el el-website',
                    'fields' => array(
                        array(
                            'id'    => 'body_custom_class',
                            'type'  => 'text',
                            'title' => esc_html__('Body Custom Class', 'portfoliocraft'),
                            'desc'  => esc_html__('Add custom CSS classes to the body element for this page', 'portfoliocraft'),
                            'default' => '',
                        ),
                    )
                )
            )
        );
    }
    
    // Register all panels with the metabox system
    if (!empty($panels)) {
        $metabox->add_meta_data($panels);
    }
}
