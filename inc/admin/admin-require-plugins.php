<?php
/**
 * Rakmyat Required Plugins Registration
 * 
 * This file handles the registration of required and recommended plugins
 * for the portfoliocraft theme using the TGM Plugin Activation (TGMPA) library.
 * Provides automated plugin installation and activation functionality.
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Include TGM Plugin Activation Library
 * 
 * Loads the TGMPA class for handling plugin installation and activation
 * This library provides the interface and functionality for managing plugins
 */
get_template_part('inc/admin/libs/tgmpa/class-tgm-plugin-activation');

/**
 * Register Required Plugins
 * 
 * Hooks into TGMPA to register all required and recommended plugins
 * Defines plugin configurations, sources, and installation settings
 */
add_action('tgmpa_register', 'Rakmyat_register_required_plugins');

/**
 * Register Required and Recommended Plugins
 * 
 * Main function that defines all plugins needed for the theme
 * Includes both free WordPress.org plugins and premium/custom plugins
 * Configures TGMPA settings for automated installation process
 * 
 * @return void
 */
function Rakmyat_register_required_plugins() {
    
    // Include demo configuration if available
    $demo_config_file = locate_template('inc/admin/demo-data/demo-config.php');
    if ($demo_config_file) {
        get_template_part('inc/admin/demo-data/demo-config');
    }
    
    // Define default path for locally stored plugins
    $default_path = get_template_directory() . '/inc/admin/plugins/';
    
    // Define path for plugin logos/images
    $images = get_template_directory_uri() . '/assets/img/plugins';
    
    /**
     * Plugin Definitions Array
     * 
     * Each plugin entry contains:
     * - name: Display name for the plugin
     * - slug: WordPress.org plugin slug or custom identifier
     * - source: URL or local path for premium/custom plugins
     * - required: Whether plugin is required (true) or recommended (false)
     * - logo: Path to plugin logo image
     * - description: Brief description of plugin functionality
     */
    $plugins = array(
        
        /**
         * Elementor Page Builder
         * 
         * Essential page builder plugin for creating custom layouts
         * Required for theme's Elementor-based templates and widgets
         */
        array(
            'name'        => esc_html__('Elementor', 'portfoliocraft'),
            'slug'        => 'elementor',
            'required'    => true,
            'logo'        => $images . '/elementor.png',
            'description' => esc_html__('Introducing a WordPress website builder, with no limits of design. A website builder that delivers high-end page designs and advanced capabilities', 'portfoliocraft'),
        ),

        /**
         * Rakmyat Core Plugin
         * 
         * Custom theme plugin containing essential functionality
         * Includes custom post types, widgets, and theme-specific features
         * Hosted externally and updated through theme updates
         */
        array(
            'name'        => esc_html__('Rakmyat Core', 'portfoliocraft'),
            'slug'        => 'rakmyat-core',
            'source'      => 'https://assets.Rakmyat.com/plugins/rakmyat-core.zip',
            'required'    => true,
            'is_callable' => 'Pxltheme_Core',
            'logo'        => $images . '/Rakmyat-core.png',
            'description' => esc_html__('Main process and Powerful Elements Plugin, exclusively for Rakmyat WordPress Theme.', 'portfoliocraft'),
        ),

        /**
         * WooCommerce E-commerce Plugin
         * 
         * Essential for e-commerce functionality
         * Required if theme includes shop features and product layouts
         */
        array(
            'name'        => esc_html__('WooCommerce', 'portfoliocraft'),
            'slug'        => 'woocommerce',
            'required'    => true,
            'logo'        => $images . '/woo.png',
            'description' => esc_html__('WooCommerce is the world\'s most popular open-source eCommerce solution.', 'portfoliocraft'),
        ),

        /**
         * Contact Form 7
         * 
         * Popular contact form plugin for creating custom forms
         * Required for theme's contact form templates and functionality
         */
        array(
            'name'        => esc_html__('Contact Form 7', 'portfoliocraft'),
            'slug'        => 'contact-form-7',
            'required'    => true,
            'logo'        => $images . '/contact-f7.png',
            'description' => esc_html__('Contact Form 7 can manage multiple contact forms, you can customize the form and the mail contents flexibly with simple markup', 'portfoliocraft'),
        ),

    );

    /**
     * TGMPA Configuration Array
     * 
     * Defines how TGMPA should behave and what features to enable
     * Controls the plugin installation interface and automation
     */
    $config = array(
        
        /**
         * Default Plugin Path
         * 
         * Local directory where premium/custom plugins are stored
         * Used for plugins that aren't available on WordPress.org
         */
        'default_path' => $default_path,
        
        /**
         * Menu Slug
         * 
         * WordPress admin menu slug for the plugin installation page
         * Creates a dedicated page for managing theme plugins
         */
        'menu' => 'tgmpa-install-plugins',
        
        /**
         * Automatic Activation
         * 
         * Whether to automatically activate plugins after installation
         * Set to false to allow users to control activation manually
         */
        'is_automatic' => false,
        
        /**
         * Admin Notices
         * 
         * Whether to show admin notices about required plugins
         * Helps remind users to install essential plugins
         */
        'has_notices' => true,
        
        /**
         * Dismissible Notices
         * 
         * Whether users can dismiss the plugin installation notices
         * Allows users to hide notices if they choose not to install
         */
        'dismissable' => true,
        
        /**
         * Dismiss Message
         * 
         * Custom message shown when notices are not dismissible
         * Empty string uses default TGMPA message
         */
        'dismiss_msg' => '',
        
        /**
         * Custom Message
         * 
         * Additional message displayed above the plugins table
         * Can be used to provide installation instructions
         */
        'message' => '',
        
        /**
         * Plugin Table Strings
         * 
         * Custom text strings for the plugin installation interface
         * Allows localization and customization of TGMPA messages
         */
        'strings' => array(
            'page_title'                      => esc_html__('Install Required Plugins', 'portfoliocraft'),
            'menu_title'                      => esc_html__('Install Plugins', 'portfoliocraft'),
            'installing'                      => esc_html__('Installing Plugin: %s', 'portfoliocraft'),
            'updating'                        => esc_html__('Updating Plugin: %s', 'portfoliocraft'),
            'oops'                           => esc_html__('Something went wrong with the plugin API.', 'portfoliocraft'),
            'notice_can_install_required'     => _n_noop(
                'This theme requires the following plugin: %1$s.',
                'This theme requires the following plugins: %1$s.',
                'portfoliocraft'
            ),
            'notice_can_install_recommended'  => _n_noop(
                'This theme recommends the following plugin: %1$s.',
                'This theme recommends the following plugins: %1$s.',
                'portfoliocraft'
            ),
            'notice_ask_to_update'           => _n_noop(
                'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
                'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
                'portfoliocraft'
            ),
            'notice_ask_to_update_maybe'     => _n_noop(
                'There is an update available for: %1$s.',
                'There are updates available for the following plugins: %1$s.',
                'portfoliocraft'
            ),
            'notice_can_activate_required'    => _n_noop(
                'The following required plugin is currently inactive: %1$s.',
                'The following required plugins are currently inactive: %1$s.',
                'portfoliocraft'
            ),
            'notice_can_activate_recommended' => _n_noop(
                'The following recommended plugin is currently inactive: %1$s.',
                'The following recommended plugins are currently inactive: %1$s.',
                'portfoliocraft'
            ),
            'install_link'                    => _n_noop(
                'Begin installing plugin',
                'Begin installing plugins',
                'portfoliocraft'
            ),
            'update_link'                     => _n_noop(
                'Begin updating plugin',
                'Begin updating plugins',
                'portfoliocraft'
            ),
            'activate_link'                   => _n_noop(
                'Begin activating plugin',
                'Begin activating plugins',
                'portfoliocraft'
            ),
            'return'                         => esc_html__('Return to Required Plugins Installer', 'portfoliocraft'),
            'plugin_activated'               => esc_html__('Plugin activated successfully.', 'portfoliocraft'),
            'activated_successfully'         => esc_html__('The following plugin was activated successfully:', 'portfoliocraft'),
            'plugin_already_active'          => esc_html__('No action taken. Plugin %1$s was already active.', 'portfoliocraft'),
            'plugin_needs_higher_version'    => esc_html__('Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'portfoliocraft'),
            'complete'                       => esc_html__('All plugins installed and activated successfully. %1$s', 'portfoliocraft'),
            'dismiss'                        => esc_html__('Dismiss this notice', 'portfoliocraft'),
            'notice_cannot_install_activate' => esc_html__('There are one or more required or recommended plugins to install, update or activate.', 'portfoliocraft'),
            'contact_admin'                  => esc_html__('Please contact the administrator of this site for help.', 'portfoliocraft'),
        ),
    );

    /**
     * Register Plugins with TGMPA
     * 
     * Passes the plugin array and configuration to TGMPA
     * This creates the plugin installation interface and functionality
     */
    tgmpa($plugins, $config);
}
