<?php
/**
 * Theme Demo Controller
 * 
 * Controls all demo imports from the theme side while using Rakmyat Core OCDI engine
 * 
 * Features:
 * - Local demo management from theme/inc/demo-import/demo-content/
 * - Remote demo configuration via demos.json
 * - Complete file support (content.xml, theme-options.json, site-settings.json, manifest.json)
 * - Easy demo addition/removal from theme
 */

if (!defined('ABSPATH')) {
    exit;
}

class Theme_Demo_Controller {
    
    private static $instance = null;
    private $demo_path;
    private $remote_config;
    
    /**
     * Singleton instance
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Constructor
     */
    private function __construct() {
        $this->demo_path = get_template_directory() . '/inc/demo-import/demo-content/';
        $this->remote_config = $this->load_remote_config();
        
        $this->init();
    }
    
    /**
     * Initialize (PERFORMANCE OPTIMIZED)
     */
    private function init() {
        // Register the theme demo function immediately (always needed for plugin)
        $this->register_theme_demo_function();
        
        // Delay conditional loading until admin_init when get_current_screen() is available
        add_action('admin_init', array($this, 'conditional_admin_init'), 1);
        
        // Add demo management actions (always register for AJAX)
        add_action('wp_ajax_refresh_demo_cache', array($this, 'refresh_demo_cache'));
        add_action('wp_ajax_preload_demo_cache', array($this, 'preload_demo_cache'));
    }
    
    /**
     * Conditional admin initialization (SAFE SCREEN DETECTION)
     */
    public function conditional_admin_init() {
        // Only proceed if we should load the demo system
        if (!$this->should_load_demo_system()) {
            return;
        }
        
        // Add admin notices for demo status (only on demo pages)
        add_action('admin_notices', array($this, 'show_demo_status_notice'));
    }
    
    /**
     * Check if demo system should load (PERFORMANCE OPTIMIZATION - SAFE)
     */
    private function should_load_demo_system() {
        // Don't load on frontend
        if (!is_admin()) {
            return false;
        }
        
        // Handle AJAX requests first (before screen detection)
        if (wp_doing_ajax()) {
            $ajax_actions = array(
                'ocdi_import_demo_data',
                'rakmyat_test_import',
                'refresh_demo_cache',
                'preload_demo_cache'
            );
            
            if (isset($_POST['action']) && in_array($_POST['action'], $ajax_actions)) {
                return true;
            }
            return false;
        }
        
        // Check if get_current_screen function exists and is safe to call
        if (!function_exists('get_current_screen')) {
            return false;
        }
        
        // Only load on specific admin pages
        $screen = get_current_screen();
        if (!$screen || !isset($screen->id)) {
            return false;
        }
        
        // Load only on demo import pages
        $allowed_pages = array(
            'appearance_page_one-click-demo-import',
            'rakmyat_page_rakmyat-demo-import',
            'themes_page_one-click-demo-import'
        );
        
        // Load only on demo-related pages
        return in_array($screen->id, $allowed_pages);
    }
    
    /**
     * Register the theme demo function that the plugin will call
     */
    public function register_theme_demo_function() {
        // This function will be called by the plugin
        if (!function_exists('rakmyat_get_theme_demos')) {
            function rakmyat_get_theme_demos() {
                $demos = Theme_Demo_Controller::get_instance()->get_all_demos();
                error_log('THEME CONTROLLER: rakmyat_get_theme_demos called - returning ' . count($demos) . ' demos');
                return $demos;
            }
            error_log('THEME CONTROLLER: rakmyat_get_theme_demos function registered successfully');
        } else {
            error_log('THEME CONTROLLER: rakmyat_get_theme_demos function already exists');
        }
    }
    
    /**
     * Get available demos (PRIORITY SYSTEM: Local first, Remote fallback)
     */
    public function get_all_demos() {
        // Check for cache refresh request
        if (isset($_GET['refresh_demo_cache']) || isset($_POST['refresh_demo_cache'])) {
            $this->refresh_demo_cache();
            $this->log_debug('Demo cache cleared by user request');
        }
        
        // PRIORITY 1: Local demos (HIGHEST PRIORITY)
        $local_demos = $this->get_local_demos();
        if (!empty($local_demos)) {
            $this->log_debug('Using LOCAL demos ONLY (' . count($local_demos) . ' demos) - Remote demos ignored');
            return $local_demos;
        }
        
        // PRIORITY 2: Remote demos (FALLBACK - only if no local demos)
        $remote_demos = $this->get_remote_demos();
        if (!empty($remote_demos)) {
            $this->log_debug('No local demos found - Using REMOTE demos (' . count($remote_demos) . ' demos)');
            return $remote_demos;
        }
        
        // No demos found at all
        $this->log_debug('No demos found - neither local nor remote');
        return array();
    }
    
    /**
     * Get local demos from theme directory (ENHANCED WITH JSON SUPPORT)
     */
    private function get_local_demos() {
        $this->log_debug('Checking local demos in path: ' . $this->demo_path);
        
        if (!is_dir($this->demo_path)) {
            $this->log_debug('Local demo directory does not exist: ' . $this->demo_path);
            return array();
        }
        
        // PRIORITY 1: Check for local demos.json file
        $local_json_file = $this->demo_path . 'demos.json';
        if (file_exists($local_json_file)) {
            $this->log_debug('Found local demos.json file - Using JSON configuration');
            $json_demos = $this->get_local_demos_from_json($local_json_file);
            if (!empty($json_demos)) {
                return $json_demos;
            }
        }
        
        // PRIORITY 2: Fallback to folder scanning (legacy method)
        $this->log_debug('No local demos.json or empty - Using folder scanning method');
        $demos = array();
        $demo_folders = glob($this->demo_path . '*', GLOB_ONLYDIR);
        $this->log_debug('Found ' . count($demo_folders) . ' potential demo folders');
        
        foreach ($demo_folders as $folder) {
            $demo_slug = basename($folder);
            $content_file = $folder . '/content.xml';
            
            $this->log_debug('Checking demo folder: ' . $demo_slug . ' (content file: ' . $content_file . ')');
            
            // Must have content.xml to be valid demo
            if (!file_exists($content_file)) {
                $this->log_debug('Skipping ' . $demo_slug . ' - no content.xml file');
                continue;
            }
            
            $this->log_debug('Valid demo found: ' . $demo_slug);
            
            $demo_config = array(
                'import_file_name' => $this->get_demo_title($demo_slug, $folder),
                'import_file_description' => $this->get_demo_description($demo_slug, $folder),
                'local_import_file' => $content_file,
                'demo_slug' => $demo_slug,
                'demo_type' => 'local',
                'preview_url' => $this->get_demo_preview_url($demo_slug, $folder),
            );
            
            // Add all optional files
            $this->add_local_optional_files($demo_config, $folder, $demo_slug);
            
            $demos[] = $demo_config;
        }
        
        return $demos;
    }
    
    /**
     * Get local demos from JSON file (UNIFIED JSON SYSTEM)
     */
    private function get_local_demos_from_json($json_file_path) {
        $this->log_debug('Reading local demos from JSON file: ' . $json_file_path);
        
        // Read and parse JSON file
        $json_content = file_get_contents($json_file_path);
        if ($json_content === false) {
            $this->log_debug('Failed to read local demos.json file');
            return array();
        }
        
        $json_data = json_decode($json_content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log_debug('Invalid JSON format in local demos.json: ' . json_last_error_msg());
            return array();
        }
        
        if (!isset($json_data['demos']) || !is_array($json_data['demos'])) {
            $this->log_debug('No demos array found in local JSON file');
            return array();
        }
        
        $demos = array();
        
        foreach ($json_data['demos'] as $demo) {
            if (!isset($demo['folder']) || !isset($demo['name'])) {
                $this->log_debug('Skipping demo - missing required fields (folder/name)');
                continue;
            }
            
            $folder = $demo['folder'];
            $demo_folder_path = $this->demo_path . $folder;
            $content_file = $demo_folder_path . '/content.xml';
            
            // Verify demo folder and content file exist
            if (!is_dir($demo_folder_path)) {
                $this->log_debug('Skipping demo "' . $folder . '" - folder does not exist');
                continue;
            }
            
            if (!file_exists($content_file)) {
                $this->log_debug('Skipping demo "' . $folder . '" - no content.xml file');
                continue;
            }
            
            $this->log_debug('Processing local JSON demo: ' . $demo['name'] . ' (folder: ' . $folder . ')');
            
            $demo_config = array(
                'import_file_name' => $demo['name'],
                'import_file_description' => isset($demo['description']) ? $demo['description'] : 'Demo: ' . $demo['name'],
                'local_import_file' => $content_file,
                'preview_url' => isset($demo['preview_url']) ? $demo['preview_url'] : '',
                'demo_slug' => $folder,
                'demo_type' => 'local-json',
                'category' => isset($demo['category']) ? $demo['category'] : 'general',
                'tags' => isset($demo['tags']) ? $demo['tags'] : array(),
                'featured' => isset($demo['featured']) ? $demo['featured'] : false,
            );
            
            // Add all optional local files
            $this->add_local_optional_files($demo_config, $demo_folder_path, $folder);
            
            // Add preview image from theme directory URI
            $preview_images = array('screenshot.jpg', 'screenshot.png', 'preview.jpg', 'preview.png');
            foreach ($preview_images as $image) {
                if (file_exists($demo_folder_path . '/' . $image)) {
                    $demo_config['import_preview_image_url'] = get_template_directory_uri() . '/inc/demo-import/demo-content/' . $folder . '/' . $image;
                    break;
                }
            }
            
            $demos[] = $demo_config;
            $this->log_debug('Added local JSON demo: ' . $demo['name'] . ' (type: local-json)');
        }
        
        $this->log_debug('Loaded ' . count($demos) . ' demos from local JSON file');
        
        return $demos;
    }
    
    /**
     * Get remote demos from server
     */
    private function get_remote_demos() {
        if (empty($this->remote_config) || !isset($this->remote_config['base_url'])) {
            return array();
        }
        
        $demos = array();
        $base_url = rtrim($this->remote_config['base_url'], '/') . '/';
        
        // Try to get demos from JSON file first
        $json_url = $base_url . 'demos.json';
        $json_demos = $this->get_demos_from_json($json_url, $base_url);
        
        if (!empty($json_demos)) {
            $this->log_debug('Found ' . count($json_demos) . ' demos from JSON file');
            return $json_demos;
        }
        
        // Fallback to configured demos if JSON fails
        $this->log_debug('JSON demos not found, using configured demos');
        return $this->get_configured_remote_demos($base_url);
    }
    
    /**
     * Get demos from JSON file (PERFORMANCE OPTIMIZED WITH CACHING)
     */
    private function get_demos_from_json($json_url, $base_url) {
        // Check cache first (1 hour cache)
        $cache_key = 'rakmyat_demo_json_' . md5($json_url);
        $cached_demos = get_transient($cache_key);
        
        if ($cached_demos !== false) {
            $this->log_debug('Using cached JSON demos (' . count($cached_demos) . ' demos)');
            return $cached_demos;
        }
        
        $response = wp_remote_get($json_url, array(
            'timeout' => 30,
            'sslverify' => false,
            'headers' => array(
                'Cache-Control' => 'no-cache',
                'User-Agent' => 'WordPress/' . get_bloginfo('version') . '; ' . home_url(),
            ),
        ));
        
        if (is_wp_error($response)) {
            $this->log_debug('Failed to fetch JSON file: ' . $response->get_error_message());
            return array();
        }
        
        $body = wp_remote_retrieve_body($response);
        $json_data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->log_debug('Invalid JSON format: ' . json_last_error_msg());
            return array();
        }
        
        if (!isset($json_data['demos']) || !is_array($json_data['demos'])) {
            $this->log_debug('No demos array found in JSON');
            return array();
        }
        
        $demos = array();
        
        // Get cached file existence data
        $file_cache_key = 'rakmyat_demo_files_' . md5($base_url);
        $file_cache = get_transient($file_cache_key);
        if ($file_cache === false) {
            $file_cache = array();
        }
        
        foreach ($json_data['demos'] as $demo) {
            if (!isset($demo['folder']) || !isset($demo['name'])) {
                continue;
            }
            
            $folder = $demo['folder'];
            $demo_url = $base_url . $folder . '/';
            
            // Test if content file exists (with caching)
            $content_url = $demo_url . 'content.xml';
            if (!$this->test_remote_file_cached($content_url, $file_cache)) {
                continue;
            }
            
            $demo_config = array(
                'import_file_name' => $demo['name'],
                'import_file_description' => isset($demo['description']) ? $demo['description'] : 'Demo: ' . $demo['name'],
                'import_file_url' => $content_url,
                'preview_url' => isset($demo['preview_url']) ? $demo['preview_url'] : '',
                'demo_slug' => $folder,
                'demo_type' => 'remote-json',
            );
            
            // Add all optional remote files (with caching)
            $this->add_remote_optional_files_cached($demo_config, $demo_url, $folder, $file_cache);
            
            $demos[] = $demo_config;
            $this->log_debug('Added JSON demo: ' . $demo['name'] . ' from folder: ' . $folder);
        }
        
        // Cache the results
        set_transient($cache_key, $demos, HOUR_IN_SECONDS);
        set_transient($file_cache_key, $file_cache, HOUR_IN_SECONDS);
        
        $this->log_debug('Cached ' . count($demos) . ' JSON demos for 1 hour');
        
        return $demos;
    }
    
    /**
     * Get configured remote demos (fallback)
     */
    private function get_configured_remote_demos($base_url) {
        if (!isset($this->remote_config['demos']) || !is_array($this->remote_config['demos'])) {
            return array();
        }
        
        $demos = array();
        
        foreach ($this->remote_config['demos'] as $demo_slug => $demo_data) {
            $demo_url = $base_url . $demo_slug . '/';
            $content_url = $demo_url . 'content.xml';
            
            // Test if content file exists
            if (!$this->test_remote_file($content_url)) {
                continue;
            }
            
            $demo_config = array(
                'import_file_name' => $demo_data['name'],
                'import_file_description' => isset($demo_data['description']) ? $demo_data['description'] : 'Demo: ' . $demo_data['name'],
                'import_file_url' => $content_url,
                'preview_url' => isset($demo_data['preview_url']) ? $demo_data['preview_url'] : '',
                'demo_slug' => $demo_slug,
                'demo_type' => 'remote-config',
            );
            
            // Add all optional remote files
            $this->add_remote_optional_files($demo_config, $demo_url, $demo_slug);
            
            $demos[] = $demo_config;
            $this->log_debug('Added configured demo: ' . $demo_data['name']);
        }
        
        return $demos;
    }
    
    /**
     * Add local optional files
     */
    private function add_local_optional_files(&$config, $folder, $demo_slug) {
        // Widget import
        if (file_exists($folder . '/widgets.wie')) {
            $config['local_import_widget_file'] = $folder . '/widgets.wie';
        }
        
        // Customizer import
        if (file_exists($folder . '/customizer.dat')) {
            $config['local_import_customizer_file'] = $folder . '/customizer.dat';
        }
        
        // Redux options (theme-options.json)
        if (file_exists($folder . '/theme-options.json')) {
            $config['local_import_redux'] = array(
                array(
                    'file_path' => $folder . '/theme-options.json',
                    'option_name' => $this->get_redux_option_name(),
                ),
            );
        }
        
        // Elementor site settings
        if (file_exists($folder . '/site-settings.json')) {
            $config['elementor_settings_file'] = $folder . '/site-settings.json';
        }
        
        // Manifest file (for demo metadata)
        if (file_exists($folder . '/manifest.json')) {
            $config['manifest_file'] = $folder . '/manifest.json';
        }
        
        // Preview image
        $preview_images = array('screenshot.jpg', 'screenshot.png', 'preview.jpg', 'preview.png');
        foreach ($preview_images as $image) {
            if (file_exists($folder . '/' . $image)) {
                $config['import_preview_image_url'] = get_template_directory_uri() . '/inc/demo-import/demo-content/' . $demo_slug . '/' . $image;
                break;
            }
        }
    }
    
    /**
     * Add remote optional files (CACHED VERSION - PERFORMANCE OPTIMIZED)
     */
    private function add_remote_optional_files_cached(&$config, $demo_url, $demo_slug, &$file_cache) {
        // Widget import
        if ($this->test_remote_file_cached($demo_url . 'widgets.wie', $file_cache)) {
            $config['import_widget_file_url'] = $demo_url . 'widgets.wie';
        }
        
        // Customizer import
        if ($this->test_remote_file_cached($demo_url . 'customizer.dat', $file_cache)) {
            $config['import_customizer_file_url'] = $demo_url . 'customizer.dat';
        }
        
        // Redux options (theme-options.json)
        if ($this->test_remote_file_cached($demo_url . 'theme-options.json', $file_cache)) {
            $config['import_redux'] = array(
                array(
                    'file_url' => $demo_url . 'theme-options.json',
                    'option_name' => $this->get_redux_option_name(),
                ),
            );
        }
        
        // Elementor site settings
        if ($this->test_remote_file_cached($demo_url . 'site-settings.json', $file_cache)) {
            $config['elementor_settings_url'] = $demo_url . 'site-settings.json';
        }
        
        // Manifest file
        if ($this->test_remote_file_cached($demo_url . 'manifest.json', $file_cache)) {
            $config['manifest_url'] = $demo_url . 'manifest.json';
        }
        
        // Preview image
        $preview_images = array('screenshot.jpg', 'screenshot.png', 'preview.jpg', 'preview.png');
        foreach ($preview_images as $image) {
            if ($this->test_remote_file_cached($demo_url . $image, $file_cache)) {
                $config['import_preview_image_url'] = $demo_url . $image;
                break;
            }
        }
    }
    
    /**
     * Add remote optional files (NON-CACHED VERSION - FALLBACK)
     */
    private function add_remote_optional_files(&$config, $demo_url, $demo_slug) {
        // Widget import
        if ($this->test_remote_file($demo_url . 'widgets.wie')) {
            $config['import_widget_file_url'] = $demo_url . 'widgets.wie';
        }
        
        // Customizer import
        if ($this->test_remote_file($demo_url . 'customizer.dat')) {
            $config['import_customizer_file_url'] = $demo_url . 'customizer.dat';
        }
        
        // Redux options (theme-options.json) - FIXED
        if ($this->test_remote_file($demo_url . 'theme-options.json')) {
            $config['import_redux'] = array(
                array(
                    'file_url' => $demo_url . 'theme-options.json',
                    'option_name' => $this->get_redux_option_name(),
                ),
            );
        }
        
        // Elementor site settings - FIXED
        if ($this->test_remote_file($demo_url . 'site-settings.json')) {
            $config['elementor_settings_url'] = $demo_url . 'site-settings.json';
        }
        
        // Manifest file - FIXED  
        if ($this->test_remote_file($demo_url . 'manifest.json')) {
            $config['manifest_url'] = $demo_url . 'manifest.json';
        }
        
        // Preview image
        $preview_images = array('screenshot.jpg', 'screenshot.png', 'preview.jpg', 'preview.png');
        foreach ($preview_images as $image) {
            if ($this->test_remote_file($demo_url . $image)) {
                $config['import_preview_image_url'] = $demo_url . $image;
                break;
            }
        }
    }
    
    /**
     * Test if remote file exists (CACHED VERSION - PERFORMANCE OPTIMIZED)
     */
    private function test_remote_file_cached($url, &$file_cache) {
        // Check cache first
        if (isset($file_cache[$url])) {
            return $file_cache[$url];
        }
        
        // Test file existence
        $response = wp_remote_head($url, array(
            'timeout' => 10,
            'sslverify' => false,
        ));
        
        $exists = !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
        
        // Cache the result
        $file_cache[$url] = $exists;
        
        return $exists;
    }
    
    /**
     * Test if remote file exists (NON-CACHED VERSION - FALLBACK)
     */
    private function test_remote_file($url) {
        $response = wp_remote_head($url, array(
            'timeout' => 10,
            'sslverify' => false,
        ));
        
        return !is_wp_error($response) && wp_remote_retrieve_response_code($response) === 200;
    }
    
    /**
     * Get demo title
     */
    private function get_demo_title($demo_slug, $folder) {
        // Check for manifest.json file
        $manifest_file = $folder . '/manifest.json';
        if (file_exists($manifest_file)) {
            $manifest = json_decode(file_get_contents($manifest_file), true);
            if (isset($manifest['title'])) {
                return $manifest['title'];
            }
            if (isset($manifest['name'])) {
                return $manifest['name'];
            }
        }
        
        // Check for info.json file (legacy)
        $info_file = $folder . '/info.json';
        if (file_exists($info_file)) {
            $info = json_decode(file_get_contents($info_file), true);
            if (isset($info['title'])) {
                return $info['title'];
            }
        }
        
        // Default: convert slug to title
        return ucwords(str_replace(array('-', '_'), ' ', $demo_slug));
    }
    
    /**
     * Get demo description
     */
    private function get_demo_description($demo_slug, $folder) {
        // Check for manifest.json file
        $manifest_file = $folder . '/manifest.json';
        if (file_exists($manifest_file)) {
            $manifest = json_decode(file_get_contents($manifest_file), true);
            if (isset($manifest['description']) && !empty($manifest['description'])) {
                return $manifest['description'];
            }
        }
        
        // Check for info.json file (legacy)
        $info_file = $folder . '/info.json';
        if (file_exists($info_file)) {
            $info = json_decode(file_get_contents($info_file), true);
            if (isset($info['description'])) {
                return $info['description'];
            }
        }
        
        return 'Import ' . $this->get_demo_title($demo_slug, $folder) . ' demo content';
    }
    
    /**
     * Get demo preview URL
     */
    private function get_demo_preview_url($demo_slug, $folder) {
        // Check for manifest.json file
        $manifest_file = $folder . '/manifest.json';
        if (file_exists($manifest_file)) {
            $manifest = json_decode(file_get_contents($manifest_file), true);
            if (isset($manifest['site'])) {
                return $manifest['site'];
            }
        }
        
        // Check for info.json file (legacy)
        $info_file = $folder . '/info.json';
        if (file_exists($info_file)) {
            $info = json_decode(file_get_contents($info_file), true);
            if (isset($info['preview_url'])) {
                return $info['preview_url'];
            }
        }
        
        return '';
    }
    
    /**
     * Get Redux option name
     */
    private function get_redux_option_name() {
        // Check config for custom option name
        if (!empty($this->remote_config['redux_option_name'])) {
            return $this->remote_config['redux_option_name'];
        }
        
        // Common Redux option names
        $theme_slug = get_option('stylesheet');
        $common_names = array(
            'pxl_theme_options',
            'theme_options',
            $theme_slug . '_options',
            'redux_options'
        );
        
        foreach ($common_names as $name) {
            if (get_option($name)) {
                return $name;
            }
        }
        
        return 'pxl_theme_options'; // Default for portfoliocraft theme
    }
    
    /**
     * Load remote configuration
     */
    private function load_remote_config() {
        if (function_exists('get_remote_demo_config')) {
            return get_remote_demo_config();
        }
        return array();
    }
    
    /**
     * Show demo status notice
     */
    public function show_demo_status_notice() {
        $screen = get_current_screen();
        if (!$screen || $screen->id !== 'appearance_page_one-click-demo-import') {
            return;
        }
        
        $local_demos = $this->get_local_demos();
        $remote_demos = $this->get_remote_demos();
        
        if (!empty($local_demos)) {
            // Determine if using JSON or folder scanning
            $json_file = $this->demo_path . 'demos.json';
            $using_json = file_exists($json_file);
            $demo_type = $using_json ? 'JSON-controlled' : 'folder-based';
            
            ?>
            <div class="notice notice-success">
                <p>
                    <strong>🎯 Theme Demo Controller Active!</strong> 
                    Using <strong>LOCAL demos</strong> (<?php echo count($local_demos); ?> demo(s)) - <strong><?php echo $demo_type; ?></strong>
                    <br><code>/inc/demo-import/demo-content/</code>
                    <?php if ($using_json): ?>
                        <span style="color: #2271b1;">✓ Controlled by demos.json</span>
                    <?php endif; ?>
                    <?php if (!empty($remote_demos)): ?>
                        <br><em>Note: <?php echo count($remote_demos); ?> remote demo(s) available but ignored (local demos have priority)</em>
                    <?php endif; ?>
                    | <a href="#" onclick="refreshDemoCache(); return false;">Refresh Cache</a>
                </p>
            </div>
            <script>
            function refreshDemoCache() {
                jQuery.post(ajaxurl, {
                    action: 'refresh_demo_cache',
                    nonce: '<?php echo wp_create_nonce('refresh_demo_cache'); ?>'
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    }
                });
            }
            </script>
            <?php
        } elseif (!empty($remote_demos)) {
            // REMOTE demos (fallback)
            ?>
            <div class="notice notice-success">
                <p>
                    <strong>🎯 Theme Demo Controller Active!</strong> 
                    Using <strong>REMOTE demos</strong> (<?php echo count($remote_demos); ?> demo(s)) from server
                    <br><em>No local demos found in <code>/inc/demo-import/demo-content/</code></em>
                    | <a href="#" onclick="refreshDemoCache(); return false;">Refresh Cache</a>
                </p>
            </div>
            <script>
            function refreshDemoCache() {
                jQuery.post(ajaxurl, {
                    action: 'refresh_demo_cache',
                    nonce: '<?php echo wp_create_nonce('refresh_demo_cache'); ?>'
                }, function(response) {
                    if (response.success) {
                        location.reload();
                    }
                });
            }
            </script>
            <?php
        } else {
            // NO demos found
            ?>
            <div class="notice notice-warning">
                <p>
                    <strong>⚠️ No Demos Found!</strong> 
                    Add demos to <code>/inc/demo-import/demo-content/</code> 
                    or configure remote demos in <code>demo-config.php</code>
                    <br><strong>Local path:</strong> <code><?php echo $this->demo_path; ?></code>
                </p>
            </div>
            <?php
        }
    }
    
    /**
     * Refresh demo cache (PERFORMANCE OPTIMIZED)
     */
    public function refresh_demo_cache() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Clear demo-related transients
        $cleared = 0;
        
        // Clear JSON demo cache
        if (isset($this->remote_config['base_url'])) {
            $base_url = rtrim($this->remote_config['base_url'], '/') . '/';
            $json_url = $base_url . 'demos.json';
            $cache_key = 'rakmyat_demo_json_' . md5($json_url);
            if (delete_transient($cache_key)) {
                $cleared++;
            }
            
            // Clear file existence cache
            $file_cache_key = 'rakmyat_demo_files_' . md5($base_url);
            if (delete_transient($file_cache_key)) {
                $cleared++;
            }
        }
        
        // Clear any other demo-related transients
        global $wpdb;
        $transients_deleted = $wpdb->query(
            "DELETE FROM {$wpdb->options} WHERE option_name LIKE '_transient_rakmyat_demo_%' OR option_name LIKE '_transient_timeout_rakmyat_demo_%'"
        );
        
        $total_cleared = $cleared + intval($transients_deleted / 2); // Each transient has a timeout option too
        
        wp_send_json_success(array(
            'message' => 'Demo cache refreshed',
            'cleared_count' => $total_cleared
        ));
    }
    
    /**
     * Preload demo cache (BACKGROUND OPTIMIZATION)
     */
    public function preload_demo_cache() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        // Only preload if we have remote config
        if (empty($this->remote_config) || !isset($this->remote_config['base_url'])) {
            wp_send_json_error(array('message' => 'No remote configuration available'));
            return;
        }
        
        $base_url = rtrim($this->remote_config['base_url'], '/') . '/';
        $json_url = $base_url . 'demos.json';
        
        // Force refresh the JSON demos (this will populate cache)
        $cache_key = 'rakmyat_demo_json_' . md5($json_url);
        delete_transient($cache_key); // Clear existing cache
        
        $demos = $this->get_demos_from_json($json_url, $base_url);
        
        wp_send_json_success(array(
            'message' => 'Demo cache preloaded',
            'demos_count' => count($demos),
            'cache_key' => $cache_key
        ));
    }
    
    /**
     * Debug logging
     */
    private function log_debug($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Theme Demo Controller: ' . $message);
        }
    }
}

// Initialize the controller
Theme_Demo_Controller::get_instance();