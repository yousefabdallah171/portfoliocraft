<?php
/**
 * Demo Configuration File
 * 
 * File: /inc/demo-import/demo-config.php
 * 
 * Configure your remote demo server here
 * The system will automatically read demos from demos.json file
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Remote Demo Configuration
 * 
 * Complete configuration for both JSON-based and manual demo setup
 */
function get_remote_demo_config() {
    return array(
        
        // STEP 1: Set your demo server base URL
        'base_url' => 'https://portfoliocraft.rakmyat.com/demo-content',
        
        // STEP 2: Redux option name (will auto-detect if not specified)
        'redux_option_name' => 'rmt_theme_options',
        
        // STEP 3: Manual demo configuration (used if demos.json is not available)
        // The system will first try to load demos.json from your server
        // If that fails, it will use the demos configured below
        'demos' => array(
            
            'portfolio' => array(
                'name' => 'Portfolio Demo',
                'description' => 'Creative portfolio showcase for designers and photographers',
                'preview_url' => 'https://portfoliocraft.rakmyat.com/',
                'category' => 'portfolio',
                'tags' => array('creative', 'portfolio', 'gallery'),
            ),
            
            'sass' => array(
                'name' => 'Sass Demo', 
                'description' => 'Modern Sass-based design with advanced styling',
                'preview_url' => 'https://sass-demo.portfoliocraft.rakmyat.com',
                'category' => 'business',
                'tags' => array('modern', 'sass', 'advanced'),
            ),
            
            // Add more demos here...
            // 'business' => array(
            //     'name' => 'Business Pro',
            //     'description' => 'Professional business website',
            //     'preview_url' => 'https://business.portfoliocraft.rakmyat.com',
            //     'category' => 'business',
            // ),
            
        ),
        
        // STEP 4: Advanced settings
        'settings' => array(
            'timeout' => 30,                    // Connection timeout in seconds
            'enable_cache' => true,              // Cache demo list
            'cache_duration' => 3600,            // Cache for 1 hour
            'test_connection' => true,           // Test if files exist before showing demo
            'show_categories' => true,           // Group demos by category
            'enable_preview' => true,            // Enable preview links
            'enable_elementor_import' => true,   // Import Elementor site settings
            'enable_manifest_import' => true,    // Import manifest.json data
        ),
    );
}

/**
 * Get demo server URL for current theme
 * 
 * This function builds the complete URL to your theme's demo folder
 * Format: https://your-server.com/demos/theme-name/
 */
function get_demo_server_url() {
    $config = get_remote_demo_config();
    $theme_slug = get_option('stylesheet');
    
    return rtrim($config['base_url'], '/') . '/' . $theme_slug . '/';
}

/**
 * Test if demo server is accessible
 * 
 * Use this to check if your server is working
 */
function test_demo_server_connection() {
    $config = get_remote_demo_config();
    $base_url = $config['base_url'];
    
    $response = wp_remote_head($base_url, array(
        'timeout' => $config['settings']['timeout'] ?? 10,
        'sslverify' => false,
    ));
    
    return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
}