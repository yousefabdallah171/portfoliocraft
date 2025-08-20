<?php
/**
 * Theme Filter Hooks and Customizations
 *
 * This file contains all the filter hooks and customizations for the portfoliocraft theme.
 * It handles body classes, post type support, custom taxonomies, and various other
 * WordPress filter modifications to enhance theme functionality and user experience.
 *
 * Features included:
 * - Body class modifications for styling control
 * - Custom post type registration and support
 * - Elementor integration enhancements
 * - Archive link customization
 * - Theme builder layout management
 * - Color scheme management
 * - Search functionality enhancements
 * - Font management and customization
 * - Performance optimizations
 *
 * @package portfoliocraft-Themes
 * @since 1.0.0
 * @author portfoliocraft-Themes
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/* ==========================================================================
   Body Class Modifications
   ========================================================================== */

/**
 * Add custom classes to the body tag
 * 
 * Adds theme-specific classes to the body tag based on theme options and page settings.
 * These classes are used for styling and layout control throughout the theme.
 * 
 * The function adds classes for:
 * - Redux framework detection for enhanced styling
 * - Footer fixed state for sticky footer layouts
 * - Header type and layout variations
 * - Color scheme (normal or gradient) for dynamic theming
 * - Custom body classes from page-specific options
 * 
 * @param array $classes Array of existing body classes
 * @return array Modified array of body classes with theme-specific additions
 * @since 1.0.0
 */
function portfoliocraft_body_classes($classes) {   
    // Initialize with empty class to ensure array structure
    $classes[] = '';
    
    // Check if Redux Framework is active for enhanced theme options
    if (class_exists('ReduxFramework')) {
        $classes[] = 'pxl-redux-page';

        // Handle footer fixed state with page-level override support
        $footer_fixed = portfoliocraft()->get_theme_opt('footer_fixed');
        $p_footer_fixed = portfoliocraft()->get_page_opt('p_footer_fixed');

        // Page-level setting takes precedence over global setting
        if ($p_footer_fixed !== false && $p_footer_fixed !== 'inherit') {
            $footer_fixed = $p_footer_fixed;
        }

        // Add footer fixed class if enabled
        if (isset($footer_fixed) && $footer_fixed === 'on') {
            $classes[] = 'pxl-footer-fixed';
        }

        // Add header type class for styling variations
        $header_layout = portfoliocraft()->get_opt('header_layout');
        if (isset($header_layout) && $header_layout) {
            $post_header = get_post($header_layout);
            if ($post_header) {
                $header_type = get_post_meta($post_header->ID, 'header_type', true);
                if (isset($header_type)) {
                    $classes[] = 'bd-' . sanitize_html_class($header_type);
                }
            }
        }

        // Add color scheme class based on gradient settings
        $get_gradient_color = portfoliocraft()->get_opt('gradient_color');
        if (isset($get_gradient_color['from']) && isset($get_gradient_color['to'])) {
            if ($get_gradient_color['from'] === $get_gradient_color['to']) {
                $classes[] = 'site-color-normal';
            } else {
                $classes[] = 'site-color-gradient';
            }
        }

        // Add custom body class from page options
        $body_custom_class = portfoliocraft()->get_page_opt('body_custom_class');
        if (!empty($body_custom_class)) {
            $classes[] = sanitize_html_class($body_custom_class);
        }
    }
    
    return array_filter($classes); // Remove empty values
}
add_filter('body_class', 'portfoliocraft_body_classes');

/* ==========================================================================
   Elementor Integration and Custom Post Type Support
   ========================================================================== */

/**
 * Add custom post type support for Elementor
 * 
 * Ensures that custom post types are supported by Elementor page builder.
 * This includes portfolio, Services, footer, and custom template post types.
 * 
 * The function:
 * - Retrieves existing Elementor CPT support settings
 * - Adds required custom post types if not already supported
 * - Updates Elementor options with the modified support list
 * - Ensures compatibility with theme's custom content types
 * 
 * @since 1.0.0
 */
function portfoliocraft_add_cpt_support() {
    // Get current Elementor CPT support with default fallback
    $cpt_support = get_option('elementor_cpt_support', ['page', 'post']);
    
    // Define required custom post types for theme functionality
    $required_cpts = [
        'page',              // WordPress default page type
        'post',              // WordPress default post type
        'portfolio',         // Theme portfolio content
        'Services',            // Theme Services/job listings
        'footer',            // Theme footer templates
        'pxl-template',      // Theme custom templates
        'product',           // WooCommerce products
        'elementor_library'  // Elementor template library
    ];
    
    // Check and add missing post types
    $needs_update = false;
    foreach ($required_cpts as $cpt) {
        if (!in_array($cpt, $cpt_support)) {
            $cpt_support[] = $cpt;
            $needs_update = true;
        }
    }
    
    // Update option only if changes were made
    if ($needs_update) {
        update_option('elementor_cpt_support', $cpt_support);
    }
}
add_action('after_switch_theme', 'portfoliocraft_add_cpt_support');

/* ==========================================================================
   Custom Post Type Management
   ========================================================================== */

/**
 * Filter default custom post types
 * 
 * Allows modification of the default custom post types supported by the theme.
 * This filter provides extensibility for theme customization and third-party integrations.
 * 
 * @param array $postypes Array of existing post types
 * @return array Unmodified array of post types (can be customized as needed)
 * @since 1.0.0
 */
function portfoliocraft_support_default_cpt($postypes) {
    // Return existing post types without modification
    // This can be customized to add or remove default support
    return $postypes;
}
add_filter('pxl_support_default_cpt', 'portfoliocraft_support_default_cpt');

/**
 * Add custom post types to the theme
 * 
 * Registers additional custom post types based on theme options.
 * Handles portfolio and Services post types with customizable settings.
 * 
 * The function:
 * - Checks if portfolio display is enabled in theme options
 * - Gets custom portfolio slug and name from theme settings
 * - Registers portfolio post type with full Elementor support
 * - Registers Services post type for job listings functionality
 * - Configures proper rewrite rules and capabilities
 * 
 * @param array $postypes Array of existing post types
 * @return array Modified array including new custom post types
 * @since 1.0.0
 */
function portfoliocraft_add_post_type($postypes) {
    // Get portfolio settings from theme options
    $portfolio_display = portfoliocraft()->get_theme_opt('portfolio_display', 'on');
    $portfolio_slug = portfoliocraft()->get_theme_opt('portfolio_slug', 'portfolio');
    $portfolio_name = portfoliocraft()->get_theme_opt('portfolio_name', 'Portfolio');
    
    // Determine portfolio status based on theme option
    $portfolio_status = ($portfolio_display === 'on');

    // Register Portfolio post type
    $postypes['portfolio'] = array(
        'status'     => $portfolio_status,
        'item_name'  => $portfolio_name,
        'items_name' => $portfolio_name,
        'args'       => array(
            'supports'           => array(
                'title',           // Post title support
                'editor',          // Content editor support
                'thumbnail',       // Featured image support
                'elementor'        // Elementor page builder support
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'rewrite'           => array(
                'slug'       => sanitize_title($portfolio_slug),
            ),
            'capability_type'   => 'post',
            'has_archive'       => true,
            'hierarchical'      => false,
            'menu_position'     => null,
            'menu_icon'         => 'dashicons-portfolio',
            'show_in_rest'      => true, // Enable Gutenberg support
        ),
        'labels'     => array()
    );
  
    // Get Services settings from theme options
    $Services_display = portfoliocraft()->get_theme_opt('Services_display', 'on');
    $Services_slug = portfoliocraft()->get_theme_opt('Services_slug', 'Services');
    $Services_name = portfoliocraft()->get_theme_opt('Services_name', 'Services');
    
    // Determine Services status based on theme option
    $Services_status = ($Services_display === 'on');

    // Register Services post type
    $postypes['services'] = array(
        'status'     => $Services_status,
        'item_name'  => $Services_name,
        'items_name' => $Services_name,
        'args'       => array(
            'supports'           => array(
                'title',           // Job title support
                'editor',          // Job description support
                'thumbnail',       // Job image support
                'elementor'        // Elementor page builder support
            ),
            'public'             => true,
            'publicly_queryable' => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'query_var'         => true,
            'rewrite'           => array(
                'slug'       => sanitize_title($Services_slug),
            ),
            'capability_type'   => 'post',
            'has_archive'       => true,
            'hierarchical'      => false,
            'menu_position'     => null,
            'menu_icon'         => 'dashicons-businessman',
            'show_in_rest'      => true, // Enable Gutenberg support
        ),
        'labels'     => array()
    );
  
    return $postypes;
}
add_filter('pxl_extra_post_types', 'portfoliocraft_add_post_type');

/* ==========================================================================
   Custom Taxonomy Management
   ========================================================================== */

/**
 * Add custom taxonomies to the theme
 * 
 * Registers custom taxonomies for portfolio and Services post types.
 * These taxonomies provide categorization and filtering capabilities.
 * 
 * The function registers:
 * - Portfolio Categories taxonomy for portfolio content organization
 * - Services Categories taxonomy for job listing categorization
 * 
 * Both taxonomies support:
 * - Hierarchical structure (like categories)
 * - Admin column display for easy management
 * - Custom rewrite rules for SEO-friendly URLs
 * - Query variable support for filtering
 * 
 * @param array $taxonomies Array of existing taxonomies
 * @return array Modified array including new custom taxonomies
 * @since 1.0.0
 */
function portfoliocraft_add_tax($taxonomies) {
    // Register Portfolio Categories taxonomy
    $taxonomies['portfolio-category'] = array(
        'status'     => true,
        'post_type'  => array('portfolio'),
        'taxonomy'   => 'Portfolio Categories',
        'taxonomies' => 'Portfolio Categories',
        'args'       => array(
            'hierarchical'      => true,  // Allow parent-child relationships
            'show_ui'           => true,  // Show in admin interface
            'show_admin_column' => true,  // Show in post list columns
            'query_var'         => true,  // Allow query by taxonomy
            'show_in_rest'      => true,  // Enable REST API support
            'rewrite'           => array(
                'slug'       => 'portfolio-category'
            ),
        ),
        'labels'     => array()
    );
    
    // Register Services Categories taxonomy
    $taxonomies['services-category'] = array(
        'status'     => true,
        'post_type'  => array('services'),
        'taxonomy'   => 'Services Categories',
        'taxonomies' => 'Services Categories',
        'args'       => array(
            'hierarchical'      => true,  // Allow parent-child relationships
            'show_ui'           => true,  // Show in admin interface
            'show_admin_column' => true,  // Show in post list columns
            'query_var'         => true,  // Allow query by taxonomy
            'show_in_rest'      => true,  // Enable REST API support
            'rewrite'           => array(
                'slug'       => 'services-category'
            ),
        ),
        'labels'     => array()
    );
    
    return $taxonomies;
}
add_filter('pxl_extra_taxonomies', 'portfoliocraft_add_tax');

/* ==========================================================================
   Archive Link Customization
   ========================================================================== */

/**
 * Customize archive links for custom post types
 * 
 * Modifies the archive links for portfolio and team post types
 * based on theme options. This allows administrators to set custom
 * pages as archive pages instead of using default WordPress archives.
 * 
 * The function:
 * - Checks if custom archive links are set in theme options
 * - Modifies the archive link to point to the custom page if configured
 * - Maintains default behavior if no custom link is set
 * - Supports both portfolio and team post types
 * 
 * @param string $link The default archive link
 * @param string $post_type The post type being queried
 * @return string Modified archive link or original if no custom link set
 * @since 1.0.0
 */
function portfoliocraft_get_post_type_archive_link($link, $post_type) {
    // Handle portfolio archive link customization
    if ($post_type === 'portfolio') {
        $port_archive_link = portfoliocraft()->get_theme_opt('archive_portfolio_link', '');
        if (!empty($port_archive_link)) { 
            $custom_link = get_permalink($port_archive_link);
            if ($custom_link) {
                $link = $custom_link;
            }
        }
    }

    // Handle Services archive link customization
    if ($post_type === 'services') {
        $Services_archive_link = portfoliocraft()->get_theme_opt('archive_Services_link', '');
        if (!empty($Services_archive_link)) { 
            $custom_link = get_permalink($Services_archive_link);
            if ($custom_link) {
                $link = $custom_link;
            }
        }
    }

    // Handle team archive link customization
    if ($post_type === 'team') {
        $team_archive_link = portfoliocraft()->get_theme_opt('archive_team_link', '');
        if (!empty($team_archive_link)) { 
            $custom_link = get_permalink($team_archive_link);
            if ($custom_link) {
                $link = $custom_link;
            }
        }
    }

    return $link;
}
add_filter('post_type_archive_link', 'portfoliocraft_get_post_type_archive_link', 10, 2);

/* ==========================================================================
   Theme Builder Configuration
   ========================================================================== */

/**
 * Filter theme builder post types
 * 
 * Allows modification of post types that can use the theme builder.
 * Default supported types include header, footer, and mega-menu templates.
 * This filter provides extensibility for adding custom builder post types.
 * 
 * @param array $postypes Array of theme builder post types
 * @return array Unmodified array (can be customized to add/remove types)
 * @since 1.0.0
 */
function portfoliocraft_theme_builder_post_type($postypes) {
    // Default supported types: header, footer, mega-menu
    // Can be modified to add custom template types
    return $postypes;
}
add_filter('pxl_theme_builder_post_types', 'portfoliocraft_theme_builder_post_type');

/**
 * Filter theme builder layout IDs
 * 
 * Collects and returns all layout IDs used by the theme builder.
 * This includes headers, footers, page titles, and other template parts.
 * The function ensures all active layouts are properly registered for
 * theme builder functionality and template management.
 * 
 * The function collects IDs from:
 * - Header layouts (normal and sticky)
 * - Footer layouts
 * - Page title layouts
 * - Product bottom content
 * - Slider templates
 * - Tab templates
 * - Mega menu templates
 * - Page popup templates
 * 
 * @param array $layout_ids Array of existing layout IDs
 * @return array Complete array of all theme builder layout IDs
 * @since 1.0.0
 */
function portfoliocraft_theme_builder_layout_id($layout_ids) {
    // Get layout IDs from theme options
    $header_layout = (int)portfoliocraft()->get_opt('header_layout');
    $header_sticky_layout = (int)portfoliocraft()->get_opt('header_sticky_layout');
    $footer_layout = (int)portfoliocraft()->get_opt('footer_layout');
    $ptitle_layout = (int)portfoliocraft()->get_opt('ptitle_layout');
    $product_bottom_content = (int)portfoliocraft()->get_opt('product_bottom_content');
    
    // Add valid layout IDs to the array
    if ($header_layout > 0) {
        $layout_ids[] = $header_layout;
    }
    if ($header_sticky_layout > 0) {
        $layout_ids[] = $header_sticky_layout;
    }
    if ($footer_layout > 0) {
        $layout_ids[] = $footer_layout;
    }
    if ($ptitle_layout > 0) {
        $layout_ids[] = $ptitle_layout;
    }
    if ($product_bottom_content > 0) {
        $layout_ids[] = $product_bottom_content;
    }

    // Add slider template IDs
    if (function_exists('portfoliocraft_get_templates_option')) {
        $slider_template = portfoliocraft_get_templates_option('slider');
        if (is_array($slider_template) && count($slider_template) > 0) {
            foreach ($slider_template as $key => $value) {
                if (is_numeric($key) && $key > 0) {
                    $layout_ids[] = (int)$key;
                }
            }
        }

        // Add tab template IDs
        $tab_template = portfoliocraft_get_templates_option('tab');
        if (is_array($tab_template) && count($tab_template) > 0) {
            foreach ($tab_template as $key => $value) {
                if (is_numeric($key) && $key > 0) {
                    $layout_ids[] = (int)$key;
                }
            }
        }
    }
    
    // Add mega menu builder IDs
    if (function_exists('portfoliocraft_get_mega_menu_builder_id')) {
        $mega_menu_id = portfoliocraft_get_mega_menu_builder_id();
        if (!empty($mega_menu_id) && is_array($mega_menu_id)) {
            $layout_ids = array_merge($layout_ids, $mega_menu_id);
        }
    }

    // Add page popup builder IDs
    if (function_exists('portfoliocraft_get_page_popup_builder_id')) {
        $page_popup_id = portfoliocraft_get_page_popup_builder_id();
        if (!empty($page_popup_id) && is_array($page_popup_id)) {
            $layout_ids = array_merge($layout_ids, $page_popup_id);
        }
    }

    // Remove duplicates and return
    return array_unique(array_filter($layout_ids));
}
add_filter('pxl_theme_builder_layout_ids', 'portfoliocraft_theme_builder_layout_id');

/**
 * Configure widget source builder data
 * 
 * Defines the mapping between widget controls and their source templates.
 * This configuration is used by the theme builder to properly link
 * widget controls with their corresponding template sources.
 * 
 * @param array $wg_datas Array of existing widget data configurations
 * @return array Modified array with tabs and slides configurations
 * @since 1.0.0
 */
function portfoliocraft_wg_get_source_builder($wg_datas) {
    // Configure tabs widget source mapping
    $wg_datas['tabs'] = [
        'control_name' => 'tabs', 
        'source_name' => 'content_template'
    ];
    
    // Configure slides widget source mapping
    $wg_datas['slides'] = [
        'control_name' => 'slides', 
        'source_name' => 'slide_template'
    ];
    
    return $wg_datas;
}
add_filter('pxl_wg_get_source_id_builder', 'portfoliocraft_wg_get_source_builder');

/* ==========================================================================
   Elementor Editor Enhancements
   ========================================================================== */

/**
 * Add custom styles to Elementor editor preview
 * 
 * Injects theme color variables into the Elementor editor preview
 * to ensure consistent styling between frontend and backend editing.
 * 
 * @since 1.0.0
 */
function portfoliocraft_add_editor_preview_style() {
    wp_add_inline_style('editor-preview', portfoliocraft_editor_preview_inline_styles());
}
add_action('elementor/preview/enqueue_styles', 'portfoliocraft_add_editor_preview_style');

/**
 * Generate inline styles for Elementor editor preview
 * 
 * Creates CSS custom properties for theme colors that can be used
 * in the Elementor editor preview for consistent color representation.
 * 
 * @return string CSS styles with theme color variables
 * @since 1.0.0
 */
function portfoliocraft_editor_preview_inline_styles() {
    $theme_colors = portfoliocraft_configs('theme_colors');
    
    if (!is_array($theme_colors)) {
        return '';
    }
    
    ob_start();
    echo '.elementor-edit-area-active {';
    foreach ($theme_colors as $color => $value) {
        if (isset($value['value'])) {
            printf(
                '--%1$s-color: %2$s;', 
                str_replace('#', '', sanitize_html_class($color)), 
                esc_attr($value['value'])
            );
        }
    }
    echo '}';
    
    return ob_get_clean();
}

/* ==========================================================================
   Archive and Content Modifications
   ========================================================================== */

/**
 * Remove label prefixes from archive titles
 * 
 * Removes WordPress default prefixes like "Category:", "Tag:", etc.
 * from archive page titles for cleaner, more professional appearance.
 * 
 * Handles:
 * - Category archives (removes "Category:")
 * - Tag archives (removes "Tag:")
 * - Author archives (removes "Author:")
 * - Post type archives (removes post type prefix)
 * - Taxonomy archives (removes taxonomy prefix)
 * - Home page titles
 * 
 * @param string $title The original archive title with prefix
 * @return string Clean title without prefix
 * @since 1.0.0
 */
function portfoliocraft_archive_title_remove_label($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    } elseif (is_tag()) {
        $title = single_tag_title('', false);
    } elseif (is_author()) {
        $title = get_the_author();
    } elseif (is_post_type_archive()) {
        $title = post_type_archive_title('', false);
    } elseif (is_tax()) {
        $title = single_term_title('', false);
    } elseif (is_home()) {
        $title = single_post_title('', false);
    }

    return $title;
}
add_filter('get_the_archive_title', 'portfoliocraft_archive_title_remove_label');

/**
 * Customize comment reply link text
 * 
 * Replaces the default "Reply" text in comment reply links with
 * a translatable version for better internationalization support.
 * 
 * @param string $link The comment reply link HTML
 * @return string Modified link with translatable reply text
 * @since 1.0.0
 */
function portfoliocraft_comment_reply_text($link) {
    $link = str_replace('Reply', esc_html__('Reply', 'portfoliocraft'), $link);
    return $link;
}
add_filter('comment_reply_link', 'portfoliocraft_comment_reply_text');

/**
 * Move comment field to bottom of comment form
 * 
 * Reorders comment form fields to place the comment textarea
 * at the bottom, after name and email fields for better UX.
 * 
 * @param array $fields Array of comment form fields
 * @return array Reordered fields with comment field at bottom
 * @since 1.0.0
 */
function portfoliocraft_comment_field_to_bottom($fields) {
    if (isset($fields['comment'])) {
        $comment_field = $fields['comment'];
        unset($fields['comment']);
        $fields['comment'] = $comment_field;
    }
    return $fields;
}
add_filter('comment_form_fields', 'portfoliocraft_comment_field_to_bottom');

/* ==========================================================================
   Feature Control Filters
   ========================================================================== */

/**
 * Control page popup feature availability
 * 
 * Determines whether the page popup feature is enabled for the theme.
 * Currently disabled by default for performance and UX considerations.
 * 
 * @return bool False to disable page popup feature
 * @since 1.0.0
 */
function portfoliocraft_enable_pagepopup() {
    return false;
}
add_filter('pxl_enable_pagepopup', 'portfoliocraft_enable_pagepopup');

/**
 * Control mega menu feature availability
 * 
 * Mega menu feature has been completely disabled and removed.
 * 
 * @return bool False to disable mega menu feature
 * @since 1.0.0
 */
function portfoliocraft_enable_megamenu() {
    return false;
}
add_filter('pxl_enable_megamenu', 'portfoliocraft_enable_megamenu');

/**
 * Control one-page navigation feature availability
 * 
 * Determines whether the one-page navigation feature is enabled.
 * Enabled by default for single-page website functionality.
 * 
 * @return bool True to enable one-page navigation
 * @since 1.0.0
 */
function portfoliocraft_enable_onepage() {
    return true;
}
add_filter('pxl_enable_onepage', 'portfoliocraft_enable_onepage');

/**
 * Control Font Awesome Pro support
 * 
 * Determines whether Font Awesome Pro icons are supported.
 * Disabled by default to use free version and reduce dependencies.
 * 
 * @return bool False to disable Font Awesome Pro support
 * @since 1.0.0
 */
function portfoliocraft_support_awesome_pro() {
    return false;
}
add_filter('pxl_support_awesome_pro', 'portfoliocraft_support_awesome_pro');

/* ==========================================================================
   Icon Management
   ========================================================================== */

/**
 * Add custom icons to icon picker field
 * 
 * Extends the available icons in the theme's icon picker field.
 * Custom icons can be added to the $custom_icons array as needed.
 * 
 * @param array $icons Array of existing icons
 * @return array Modified array with custom icons added
 * @since 1.0.0
 */
function portfoliocraft_add_icons_to_pxl_iconpicker_field($icons) {
    // Custom icons array - add custom icon sets here
    $custom_icons = []; // Using only GPL-compatible icons
    
    // Merge custom icons with existing icons
    $icons = array_merge($custom_icons, $icons);
    
    return $icons;
}
add_filter('redux_pxl_iconpicker_field/get_icons', 'portfoliocraft_add_icons_to_pxl_iconpicker_field');

/**
 * Add custom icons to mega menu
 * 
 * Extends the available icons in the mega menu builder.
 * Allows for consistent icon usage across theme components.
 * 
 * @param array $icons Array of existing mega menu icons
 * @return array Modified array with custom icons added
 * @since 1.0.0
 */
function portfoliocraft_add_icons_to_megamenu($icons) {
    // Custom icons array - add custom icon sets here
    $custom_icons = []; // Using only GPL-compatible icons
    
    // Merge custom icons with existing icons
    $icons = array_merge($custom_icons, $icons);
    
    return $icons;
}
add_filter("pxl_mega_menu/get_icons", "portfoliocraft_add_icons_to_megamenu");

/* ==========================================================================
   Performance and Optimization
   ========================================================================== */

/**
 * Disable WordPress lazy loading
 * 
 * Disables WordPress native lazy loading to prevent conflicts
 * with theme's custom lazy loading implementation or third-party solutions.
 * 
 * @return bool False to disable WordPress lazy loading
 * @since 1.0.0
 */
add_filter('wp_lazy_loading_enabled', '__return_false');

/* ==========================================================================
   Export and Import Settings
   ========================================================================== */

/**
 * Add custom options to export settings
 * 
 * Includes additional WordPress options in the theme export functionality.
 * This ensures important plugin settings are preserved during theme demos.
 * 
 * @param array $wp_options Array of WordPress options to export
 * @return array Modified array with additional options
 * @since 1.0.0
 */
function portfoliocraft_export_wp_settings($wp_options) {
    // Add MailChimp default form ID to export
    $wp_options[] = 'mc4wp_default_form_id';
    
    return $wp_options;
}
add_filter('pxl_export_wp_settings', 'portfoliocraft_export_wp_settings');

/* ==========================================================================
   Theme Information and Support
   ========================================================================== */

/**
 * Add theme server information
 * 
 * Provides essential URLs and contact information for theme support,
 * documentation, demos, and API endpoints. This information is used
 * throughout the theme admin interface for user assistance.
 * 
 * @param array $infos Array of existing server information
 * @return array Complete server information array
 * @since 1.0.0
 */
function portfoliocraft_add_server_info($infos) {
    $infos = [
        'api_url'       => 'https://api.portfoliocraft-themes.net/',
        'docs_url'      => 'https://docs.rakmyat.com/',
        'plugin_url'    => 'https://api.portfoliocraft-themes.net/plugins/',
        'demo_url'      => 'https://rakmyat.com/#templates',
        'support_url'   => 'support@rakmyat.com',
        'help_url'      => 'https://doc.portfoliocraft-themes.net/portfoliocraft',
        'email_support' => 'portfoliocraft-themesagency@gmail.com',
        'video_url'     => '#'
    ];
    
    return $infos;
}
add_filter('pxl_server_info', 'portfoliocraft_add_server_info');

/* ==========================================================================
   Search Functionality Enhancement
   ========================================================================== */

/**
 * Include custom post types in search results
 * 
 * Extends WordPress search to include custom post types alongside
 * default posts. This provides comprehensive search functionality
 * across all theme content types.
 * 
 * Included post types:
 * - post (default WordPress posts)
 * - portfolio (theme portfolio items)
 * - service (service pages)
 * - product (WooCommerce products)
 * 
 * @param WP_Query $query The WordPress query object
 * @since 1.0.0
 */
function portfoliocraft_custom_post_types_in_search_results($query) {
    // Only modify main search queries on frontend
    if ($query->is_main_query() && $query->is_search() && !is_admin()) {
        $query->set('post_type', array('post', 'portfolio', 'service', 'product'));
    }
}
add_action('pre_get_posts', 'portfoliocraft_custom_post_types_in_search_results');

/* ==========================================================================
   Font Management and Customization
   ========================================================================== */

/**
 * Add custom font group to Elementor
 * 
 * Creates a custom font group in Elementor's font selector
 * for theme-specific fonts, providing better organization.
 * 
 * @param array $font_groups Array of existing font groups
 * @return array Modified array with custom font group added
 * @since 1.0.0
 */
function portfoliocraft_update_elementor_font_groups_control($font_groups) {
    $pxlfonts_group = array('pxlfonts' => esc_html__('portfoliocraft Fonts', 'portfoliocraft'));
    return array_merge($pxlfonts_group, $font_groups);
}
add_filter('elementor/fonts/groups', 'portfoliocraft_update_elementor_font_groups_control');

/**
 * Add custom fonts to Elementor
 * 
 * Registers theme-specific fonts with Elementor for use in
 * the page builder. Fonts are organized under the custom font group.
 * 
 * @param array $additional_fonts Array of existing additional fonts
 * @return array Modified array with custom fonts added
 * @since 1.0.0
 */
function portfoliocraft_update_elementor_font_control($additional_fonts) {
    // Add custom theme fonts to Elementor
    $additional_fonts['Julietta-Messie'] = 'pxlfonts';
    
    return $additional_fonts;
}
add_filter('elementor/fonts/additional_fonts', 'portfoliocraft_update_elementor_font_control');

/**
 * Add custom fonts to Redux typography field
 * 
 * Extends Redux Framework typography fields with theme-specific fonts.
 * This ensures consistent font availability across theme options.
 * 
 * @param array $fonts Array of existing fonts
 * @return array Modified array with custom fonts added
 * @since 1.0.0
 */
function portfoliocraft_add_redux_option_typo_customfont($fonts) {
    $fonts = [
        'Theme Custom Fonts' => [
            // Add custom fonts here as needed
        ]
    ];
    
    return $fonts;
}
add_filter('redux/' . portfoliocraft()->get_option_name() . '/field/typography/custom_fonts', 'portfoliocraft_add_redux_option_typo_customfont', 10, 1);

/* ==========================================================================
   Elementor Pro Enhancements
   ========================================================================== */

/**
 * Fix Elementor popup location settings
 * 
 * Modifies Elementor Pro popup location settings to enable
 * content editing within the popup builder interface.
 * This improves the user experience when creating popups.
 * 
 * @param object $that The Elementor locations manager instance
 * @since 1.0.0
 */
function portfoliocraft_fix_elementor_popup_location($that) {
    $loc = $that->get_location('popup');
    
    // Check if popup location needs content editing enabled
    if (!$loc['edit_in_content']) {
        $args = [
            'label'           => $loc['label'],
            'multiple'        => $loc['multiple'],
            'public'          => $loc['public'],
            'edit_in_content' => true, // Enable content editing
            'hook'            => $loc['hook'],
        ];
        
        // Re-register popup location with updated settings
        $that->register_location('popup', $args);
    }
}
add_action('elementor/theme/register_locations', 'portfoliocraft_fix_elementor_popup_location', 9999999);

/* ==========================================================================
   Development Mode Configuration
   ========================================================================== */

/**
 * Development mode constants
 * 
 * These constants control development features and debugging.
 * Should be set to false in production environments.
 */

// Enable general development mode features
if (!defined('DEV_MODE')) {
    define('DEV_MODE', true);
}

// Enable theme development mode for elements
if (!defined('THEME_DEV_MODE_ELEMENTS')) {
    define('THEME_DEV_MODE_ELEMENTS', true);
}

