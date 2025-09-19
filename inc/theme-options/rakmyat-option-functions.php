<?php
/**
 * Theme Helper Functions
 * 
 * This file contains utility functions for the portfoliocraft theme
 * All functions are properly secured and follow WordPress coding standards
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get Post List by Post Type
 * 
 * Retrieves a list of posts for a specific post type with proper escaping
 * Used for generating dropdown options in theme customizer
 * 
 * @param string $post_type The post type to query (default: 'post')
 * @param bool $default Whether to include an 'Inherit' option
 * @return array Array of post ID => post title pairs
 */
if (!function_exists('portfoliocraft_list_post')) {
    function portfoliocraft_list_post($post_type = 'post', $default = false) {
        $post_list = array();
        
        // Sanitize post type input
        $post_type = sanitize_key($post_type);
        
        // Query posts with proper arguments
        $posts = get_posts(array(
            'post_type' => $post_type, 
            'orderby' => 'date', 
            'order' => 'ASC', 
            'posts_per_page' => -1,
            'post_status' => 'publish'
        ));
        
        // Add inherit option if requested
        if ($default) {
            $post_list[-1] = esc_html__('Inherit', 'portfoliocraft');
        }
        
        // Build post list with proper escaping
        if (!empty($posts) && is_array($posts)) {
            foreach ($posts as $post) {
                if (isset($post->ID) && isset($post->post_title)) {
                    $post_list[absint($post->ID)] = esc_html($post->post_title);
                }
            }
        }
        
        return $post_list;
    }
}

/**
 * Get Template Options for Elementor Templates
 * 
 * Retrieves custom templates created with Elementor for use in theme options
 * Includes proper security checks and data validation
 * 
 * @param string $meta_value Template type to filter by
 * @param mixed $default Default option configuration
 * @return array Array of template options
 */
if (!function_exists('portfoliocraft_get_templates_option')) {
    function portfoliocraft_get_templates_option($meta_value = 'df', $default = false) {
        // Check user capabilities
        if (!current_user_can('edit_theme_options')) {
            return array();
        }
        
        $post_list = array();
        
        // Handle default inherit option
        if ($default && !is_array($default)) {
            $post_list[-1] = esc_html__('Inherit', 'portfoliocraft');
        }
        
        // Handle array-based default options
        if (is_array($default)) {
            $key = isset($default['key']) ? sanitize_key($default['key']) : '0';
            $value = !empty($default['value']) ? esc_html($default['value']) : esc_html__('None', 'portfoliocraft');
            $post_list[$key] = $value;
        }
        
        // Sanitize meta value
        $meta_value = sanitize_text_field($meta_value);
        
        // Query template posts with security checks
        $args = array(
            'post_type' => 'rmt-template',
            'posts_per_page' => -1,
            'orderby' => 'date',
            'order' => 'ASC',
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'template_type',
                    'value' => $meta_value,
                    'compare' => '='
                )
            )
        );

        $posts = get_posts($args);
        
        // Process posts with validation
        if (!empty($posts) && is_array($posts)) {
            foreach ($posts as $post) {
                if (!isset($post->ID) || !isset($post->post_title)) {
                    continue;
                }
                
                $template_type = get_post_meta($post->ID, 'template_type', true);
                
                // Skip default templates
                if ($template_type == 'df') {
                    continue;
                }
                
                $post_list[absint($post->ID)] = esc_html($post->post_title);
            }
        }
         
        return $post_list;
    }
}

/**
 * Get Template Slugs with Additional Data
 * 
 * Similar to portfoliocraft_get_templates_option but returns more detailed information
 * including post ID, title, and position data
 * 
 * @param string $meta_value Template type to filter by
 * @return array Array of template data with slug as key
 */
if (!function_exists('portfoliocraft_get_templates_slug')) {
    function portfoliocraft_get_templates_slug($meta_value = 'df') {
        $post_list = array();
        
        // Sanitize meta value
        $meta_value = sanitize_text_field($meta_value);
        
        // Query posts with proper validation
        $posts = get_posts(array(
            'post_type' => 'rmt-template', 
            'orderby' => 'date', 
            'order' => 'ASC', 
            'posts_per_page' => -1,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => 'template_type',
                    'value' => $meta_value,
                    'compare' => '='
                )
            )
        ));
         
        if (!empty($posts) && is_array($posts)) {
            foreach ($posts as $post) {
                if (!isset($post->ID) || !isset($post->post_title) || !isset($post->post_name)) {
                    continue;
                }
                
                $template_type = get_post_meta($post->ID, 'template_type', true);
                
                // Skip default templates
                if ($template_type == 'df') {
                    continue;
                }
                
                // Build template data array
                $value_args = array(
                    'post_id' => absint($post->ID), 
                    'title' => esc_html($post->post_title)
                );
                
                $template_position = get_post_meta($post->ID, 'template_position', true);
                $value_args['position'] = !empty($template_position) ? sanitize_text_field($template_position) : '';

                $post_list[sanitize_key($post->post_name)] = $value_args;
            }
        }
        
        return $post_list;
    }
}

/**
 * Generate Header Layout Options
 * 
 * Creates configuration array for header layout options in theme customizer
 * Includes both main header and sticky header options
 * 
 * @param array $args Configuration arguments
 * @return array Header options configuration
 */
if (!function_exists('portfoliocraft_header_opts')) {
    function portfoliocraft_header_opts($args = array()) {
        $args = wp_parse_args($args, array(
            'default' => false,
            'default_value' => ''
        ));
        
        // Sanitize default value
        $default_value = sanitize_text_field($args['default_value']);
         
        $opts = array(
            array(
                'id' => 'header_layout',
                'type' => 'select',
                'title' => esc_html__('Main Header Layout', 'portfoliocraft'),
                'desc' => sprintf(
                    esc_html__('Please create your layout before choosing. %sClick Here%s', 'portfoliocraft'),
                    '<a href="' . esc_url(admin_url('edit.php?post_type=rmt-template')) . '">',
                    '</a>'
                ),
                'options' => portfoliocraft_get_templates_option('header', $args['default']),
                'default' => $default_value
            ),
            array(
                'id' => 'header_layout_sticky',
                'type' => 'select',
                'title' => esc_html__('Sticky Header Layout', 'portfoliocraft'),
                'desc' => sprintf(
                    esc_html__('Please create your layout before choosing. %sClick Here%s', 'portfoliocraft'),
                    '<a href="' . esc_url(admin_url('edit.php?post_type=rmt-template')) . '">',
                    '</a>'
                ),
                'options' => portfoliocraft_get_templates_option('header', $args['default']),
                'default' => $default_value,
            )
        );
 
        return $opts;
    }
}

/**
 * Generate Page Title Options
 * 
 * Creates page title configuration with different modes (default, builder, disable)
 * Handles both inherit and non-inherit scenarios
 * 
 * @param array $args Configuration arguments
 * @return array Page title options configuration
 */
if (!function_exists('portfoliocraft_page_title_opts')) {
    function portfoliocraft_page_title_opts($args = array()) {
        $args = wp_parse_args($args, array(
            'default' => false,
            'default_value' => '1'
        ));
        
        // Configure options based on default setting
        if ($args['default']) {
            $pt_mode_options = array(
                '-1' => esc_html__('Inherit', 'portfoliocraft'),
                'bd' => esc_html__('Builder', 'portfoliocraft'),
                'none' => esc_html__('Disable', 'portfoliocraft')
            );
            $pt_mode_default = 'none'; // Set default to Disable in inherit mode
        } else {
            $pt_mode_options = array(
                'df' => esc_html__('Default', 'portfoliocraft'),
                'bd' => esc_html__('Builder', 'portfoliocraft'),
                'none' => esc_html__('Disable', 'portfoliocraft')
            );
            $pt_mode_default = 'none'; // Set default to Disable in normal mode
        }
        
        // Sanitize default value
        $default_value = sanitize_text_field($args['default_value']);
        
        $opts = array(
            array(
                'id' => 'pt_mode',
                'type' => 'button_set',
                'title' => esc_html__('Page Title', 'portfoliocraft'),
                'options' => $pt_mode_options, 
                'default' => $pt_mode_default
            ),
            array(
                'id' => 'ptitle_layout',
                'type' => 'select',
                'title' => esc_html__('Page Title Layout', 'portfoliocraft'),
                'desc' => sprintf(
                    esc_html__('Please create your layout before choosing. %sClick Here%s', 'portfoliocraft'),
                    '<a href="' . esc_url(admin_url('edit.php?post_type=rmt-template')) . '">',
                    '</a>'
                ),
                'options' => portfoliocraft_get_templates_option('page-title', false),
                'default' => $default_value,
                'required' => array('pt_mode', '=', 'bd')
            ),
        );

        return $opts;
    }
}



/**             'page_title' => array(
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
                
 * Generate Post Title Options
 * 
 * Creates post title configuration for specific post types
 * Allows switching between default, builder, and disabled modes
 * 
 * @param string $post_type The post type to create options for
 * @return array Post title options configuration
 */
if (!function_exists('portfoliocraft_post_title_opts')) {
    function portfoliocraft_post_title_opts($post_type = 'post') {
        // Sanitize post type
        $post_type = sanitize_key($post_type);
        
        $opts = array(
            array(
                'id' => $post_type . '_title_mode',
                'type' => 'button_set',
                'title' => esc_html__('Post Title Mode', 'portfoliocraft'),
                'options' => array(
                    '' => esc_html__('Default', 'portfoliocraft'),
                    'builder' => esc_html__('Builder', 'portfoliocraft'),
                    'disable' => esc_html__('Disable', 'portfoliocraft')
                ), 
                'default' => 'disable' 
                
            ),
            array(
                'id' => $post_type . '_title_layout',
                'type' => 'select',
                'title' => esc_html__('Post Title Layout', 'portfoliocraft'),
                'desc' => sprintf(
                    esc_html__('Please create your layout before choosing. %sClick Here%s', 'portfoliocraft'),
                    '<a href="' . esc_url(admin_url('edit.php?post_type=rmt-template')) . '">',
                    '</a>'
                ),
                'options' => portfoliocraft_get_templates_option('post-title', false),
                'default' => 'default',
                'required' => array($post_type . '_title_mode', '=', 'builder')
            ),
        );
 
        return $opts;
    }
}

/**
 * Generate Footer Layout Options
 * 
 * Creates footer layout configuration for theme customizer
 * 
 * @param array $args Configuration arguments
 * @return array Footer options configuration
 */
if (!function_exists('portfoliocraft_footer_opts')) {
    function portfoliocraft_footer_opts($args = array()) {
        $args = wp_parse_args($args, array(
            'default' => false,
            'default_value' => ''
        ));
        
        // Sanitize default value
        $default_value = sanitize_text_field($args['default_value']);
         
        $opts = array(
            array(
                'id' => 'footer_layout',
                'type' => 'select',
                'title' => esc_html__('Footer Layout', 'portfoliocraft'),
                'desc' => sprintf(
                    esc_html__('Please create your layout before choosing. %sClick Here%s', 'portfoliocraft'),
                    '<a href="' . esc_url(admin_url('edit.php?post_type=rmt-template')) . '">',
                    '</a>'
                ),
                'options' => portfoliocraft_get_templates_option('footer', $args['default']),
                'default' => $default_value,
            ),
        );
 
        return $opts;
    }
}

/**
 * Generate Sidebar Options Configuration
 * 
 * Creates sidebar position options (left, right, disable)
 * Supports both page-level and global configurations
 * 
 * @param array $args Configuration arguments including prefix and options
 * @return array Sidebar options configuration
 */
if (!function_exists('portfoliocraft_sidebar_options')) {
    function portfoliocraft_sidebar_options($args = array()) {
        $args = wp_parse_args($args, array(
            'prefix' => 'blog',
            'page_option' => false,
            'default' => 'right'
        ));
        
        // Sanitize and build prefix
        $prefix = !empty($args['prefix']) ? sanitize_key($args['prefix']) . '_' : '';
        
        // Configure options based on page_option setting
        if ($args['page_option']) {
            $options = array(
                'inherit' => esc_html__('Inherit', 'portfoliocraft'),
                'left' => esc_html__('Left', 'portfoliocraft'),
                'right' => esc_html__('Right', 'portfoliocraft'),
                'disable' => esc_html__('Disable', 'portfoliocraft'),
            );
        } else {
            $options = array(
                'left' => esc_html__('Left', 'portfoliocraft'),
                'right' => esc_html__('Right', 'portfoliocraft'),
                'disable' => esc_html__('Disable', 'portfoliocraft'),
            ); 
        }
        
        // Validate default value
        $default_value = in_array($args['default'], array_keys($options)) ? $args['default'] : 'right';
        
        $opts = array(
            'id' => $prefix . 'sidebar',
            'type' => 'button_set',
            'title' => esc_html__('Sidebar', 'portfoliocraft'),
            'options' => $options,
            'default' => $default_value,
        );
        
        return $opts;
    }
}

/**
 * Get Navigation Menu Slugs
 * 
 * Retrieves all registered navigation menus with proper escaping
 * Used for menu selection in theme options
 * 
 * @return array Array of menu slug => menu name pairs
 */
function portfoliocraft_get_nav_menu_slug() {
    $menus = array(
        '-1' => esc_html__('Inherit', 'portfoliocraft')
    );

    $obj_menus = wp_get_nav_menus();

    if (!empty($obj_menus) && is_array($obj_menus)) {
        foreach ($obj_menus as $obj_menu) {
            if (isset($obj_menu->slug) && isset($obj_menu->name)) {
                $menus[esc_attr($obj_menu->slug)] = esc_html($obj_menu->name);
            }
        }
    }
    
    return $menus;
}

/**
 * Get Menu Options for Theme Customizer
 * 
 * Alternative method to get navigation menus using get_terms
 * Provides backward compatibility and additional flexibility
 * 
 * @return array Array of menu options with proper escaping
 */
function portfoliocraft_get_menu_options() {
    $menus = get_terms('nav_menu', array('hide_empty' => false));
    $pxl_menus = array();
    
    if (is_array($menus) && !empty($menus)) {
        $pxl_menus = array(
            '' => esc_html__('Default', 'portfoliocraft')
        );
        
        foreach ($menus as $value) {
            if (is_object($value) && isset($value->name, $value->slug)) {
                $pxl_menus[esc_attr($value->slug)] = esc_html($value->name);
            }
        }
    }
    
    return $pxl_menus;
}

/**
 * Get Pages with Elementor Support
 * 
 * Retrieves pages that have Elementor edit mode enabled
 * Used for 404 page selection and other page-based options
 * 
 * @return array Complete field configuration for page selection
 */
function portfoliocraft_get_pages() {
    $args = array(
        'post_type' => 'page', 
        'meta_key' => '_elementor_edit_mode',
        'meta_compare' => 'EXISTS',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    );
    
    $query = new WP_Query($args);
    $options = array(
        '' => esc_html__('Default', 'portfoliocraft'),
    );
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $page_id = get_the_ID();
            $page_title = get_the_title();
            
            // Validate data before adding to options
            if ($page_id && $page_title) {
                $options[absint($page_id)] = esc_html($page_title);
            }
        }
        wp_reset_postdata();
    }
    
    return array(
        'id' => '404_page',
        'type' => 'select',
        'title' => esc_html__('404 Page', 'portfoliocraft'),
        'desc' => sprintf(
            esc_html__('Please create your layout before choosing. %sClick Here%s', 'portfoliocraft'),
            '<a href="' . esc_url(admin_url('edit.php?post_type=page')) . '">',
            '</a>'
        ),
        'options' => $options,
        'default' => '',
    );
}
