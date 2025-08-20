<?php
/**
 * Universal Demo Import System
 * 
 * File: /inc/demo-import/class-demo-import.php
 * 
 * Priority System:
 * 1. Local demos in /demo-content/ (HIGHEST PRIORITY)
 * 2. Remote URLs from config file (FALLBACK)
 * 
 * No conflicts, no duplicated code, clean implementation
 */

if (!defined('ABSPATH')) {
    exit;
}

class Universal_Demo_Import {
    
    private static $instance = null;
    private $theme_slug;
    private $demo_path;
    private $config_file;
    private $remote_config = array();
    
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
        $this->theme_slug = get_option('stylesheet');
        $this->demo_path = get_template_directory() . '/inc/demo-import/demo-content/';
        $this->config_file = get_template_directory() . '/inc/demo-import/demo-config.php';
        
        $this->init();
    }
    
    /**
     * Initialize system
     */
    private function init() {
        // Load configuration
        $this->load_config();
        
        // Load OCDI
        $this->load_ocdi();
        
        // Hook into OCDI
        add_filter('ocdi/import_files', array($this, 'get_import_files'));
        add_action('ocdi/after_import', array($this, 'after_import_setup'));
        add_action('ocdi/before_import', array($this, 'optimize_import_environment'));
        
        // Admin notices
        add_action('admin_notices', array($this, 'admin_notices'));
        
        // AJAX for testing connections
        add_action('wp_ajax_test_demo_connection', array($this, 'ajax_test_connection'));
    }
    
    /**
     * Load configuration
     */
    private function load_config() {
        if (file_exists($this->config_file)) {
            require_once $this->config_file;
            
            if (function_exists('get_remote_demo_config')) {
                $this->remote_config = get_remote_demo_config();
            }
        }
    }
    
    /**
     * Load OCDI plugin
     */
    private function load_ocdi() {
        // Check if OCDI is already loaded (from Rakmyat Core plugin or elsewhere)
        if (class_exists('OCDI\OneClickDemoImport')) {
            $this->log_debug('OCDI already loaded from plugin');
            return;
        }
        
        // Try to load from theme as fallback
        $ocdi_path = get_template_directory() . '/inc/demo-import/ocdi/one-click-demo-import.php';
        
        if (file_exists($ocdi_path)) {
            // Define OCDI constants
            if (!defined('OCDI_PATH')) {
                define('OCDI_PATH', dirname($ocdi_path) . '/');
            }
            if (!defined('OCDI_URL')) {
                define('OCDI_URL', get_template_directory_uri() . '/inc/demo-import/ocdi/');
            }
            
            require_once $ocdi_path;
            $this->log_debug('OCDI loaded from theme');
        } else {
            $this->log_debug('OCDI not found - may be loaded by plugin');
        }
    }
    
    /**
     * Get import files (PRIORITY SYSTEM)
     */
    public function get_import_files($import_files) {
        $demos = array();
        
        // PRIORITY 1: Check local demos first
        $local_demos = $this->get_local_demos();
        if (!empty($local_demos)) {
            $demos = $local_demos;
            $this->log_debug('Using LOCAL demos (priority 1): ' . count($local_demos) . ' demos');
        }
        
        // PRIORITY 2: Check remote demos if no local found
        if (empty($demos)) {
            $remote_demos = $this->get_remote_demos();
            if (!empty($remote_demos)) {
                $demos = $remote_demos;
                $this->log_debug('Using REMOTE demos (priority 2): ' . count($remote_demos) . ' demos');
            }
        }
        
        // If still no demos, show helpful message
        if (empty($demos)) {
            $this->log_debug('NO demos found - check setup');
        }
        
        // Return ONLY our demos, don't merge with existing import_files to avoid duplication
        return $demos;
    }
    
    /**
     * Get local demos from /demo-content/ folder
     */
    private function get_local_demos() {
        if (!is_dir($this->demo_path)) {
            return array();
        }
        
        $demos = array();
        $demo_folders = glob($this->demo_path . '*', GLOB_ONLYDIR);
        
        foreach ($demo_folders as $folder) {
            $demo_slug = basename($folder);
            $content_file = $folder . '/content.xml';
            
            // Must have content.xml to be valid demo
            if (!file_exists($content_file)) {
                continue;
            }
            
            $demo_config = array(
                'import_file_name' => $this->get_demo_title($demo_slug, $folder),
                'import_file_description' => $this->get_demo_description($demo_slug, $folder),
                'local_import_file' => $content_file,
                'demo_slug' => $demo_slug,
                'demo_type' => 'local',
            );
            
            // Add optional files
            $this->add_optional_local_files($demo_config, $folder, $demo_slug);
            
            $demos[] = $demo_config;
        }
        
        return $demos;
    }
    
    /**
     * Get remote demos from JSON file
     */
    private function get_remote_demos() {
        if (empty($this->remote_config) || !isset($this->remote_config['base_url'])) {
            return array();
        }
        
        $demos = array();
        $base_url = rtrim($this->remote_config['base_url'], '/') . '/';
        
        // Try to get demos from JSON file first
        $json_url = $base_url . 'demos.json';
        $json_demos = $this->get_demos_from_json($json_url);
        
        if (!empty($json_demos)) {
            $this->log_debug('Found ' . count($json_demos) . ' demos from JSON file');
            return $json_demos;
        }
        
        // Fallback to auto-discovery if JSON fails
        $this->log_debug('JSON demos not found, falling back to auto-discovery');
        return $this->auto_discover_remote_demos($base_url);
    }
    
    /**
     * Get demos from JSON file
     */
    private function get_demos_from_json($json_url) {
        $response = wp_remote_get($json_url, array(
            'timeout' => 30,
            'sslverify' => false,
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
        $base_url = rtrim($this->remote_config['base_url'], '/') . '/';
        
        foreach ($json_data['demos'] as $demo) {
            if (!isset($demo['folder']) || !isset($demo['name'])) {
                continue;
            }
            
            $folder = $demo['folder'];
            $demo_url = $base_url . $folder;
            
            // Test if content file exists
            $content_url = $demo_url . '/content.xml';
            if (!$this->test_remote_file($content_url)) {
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
            
            // Add optional remote files
            $this->add_optional_remote_files($demo_config, $demo_url . '/', $folder, $demo);
            
            $demos[] = $demo_config;
            $this->log_debug('Added JSON demo: ' . $demo['name'] . ' from folder: ' . $folder);
        }
        
        return $demos;
    }
    
    /**
     * Auto-discover remote demos from server
     */
    private function auto_discover_remote_demos($base_url) {
        $demos = array();
        
        // Common demo folder names to check
        $demo_folders = array(
            'sass', 'business', 'portfolio', 'agency', 'creative', 'shop', 'restaurant', 
            'corporate', 'medical', 'education', 'blog', 'magazine', 'photography',
            'construction', 'fitness', 'beauty', 'travel', 'hotel', 'real-estate'
        );
        
        foreach ($demo_folders as $demo_slug) {
            $content_url = $base_url . $demo_slug . '/content.xml';
            
            if ($this->test_remote_file($content_url)) {
                $demo_config = array(
                    'import_file_name' => ucwords(str_replace('-', ' ', $demo_slug)) . ' Demo',
                    'import_file_description' => 'Auto-discovered demo: ' . ucwords(str_replace('-', ' ', $demo_slug)),
                    'import_file_url' => $content_url,
                    'demo_slug' => $demo_slug,
                    'demo_type' => 'remote-auto',
                );
                
                // Add optional remote files
                $this->add_optional_remote_files($demo_config, $base_url, $demo_slug, array());
                
                $demos[] = $demo_config;
                
                $this->log_debug('Auto-discovered demo: ' . $demo_slug);
            }
        }
        
        return $demos;
    }
    
    /**
     * Add optional local files
     */
    private function add_optional_local_files(&$config, $folder, $demo_slug) {
        // Widget import
        if (file_exists($folder . '/widgets.wie')) {
            $config['local_import_widget_file'] = $folder . '/widgets.wie';
        }
        
        // Customizer import
        if (file_exists($folder . '/customizer.dat')) {
            $config['local_import_customizer_file'] = $folder . '/customizer.dat';
        }
        
        // Redux options
        if (file_exists($folder . '/theme-options.json')) {
            $config['local_import_redux'] = array(
                array(
                    'file_path' => $folder . '/theme-options.json',
                    'option_name' => $this->get_redux_option_name(),
                ),
            );
        }
        
        // Preview image
        $preview_images = array('screenshot.jpg', 'screenshot.png', 'preview.jpg', 'preview.png');
        foreach ($preview_images as $image) {
            if (file_exists($folder . '/' . $image)) {
                $config['import_preview_image_url'] = get_template_directory_uri() . '/inc/demo-import/demo-content/' . $demo_slug . '/' . $image;
                break;
            }
        }
        
        // Elementor settings
        if (file_exists($folder . '/site-settings.json')) {
            $config['elementor_settings_file'] = $folder . '/site-settings.json';
        }
    }
    
    /**
     * Add optional remote files
     */
    private function add_optional_remote_files(&$config, $base_url, $demo_slug, $demo_data) {
        $demo_url = $base_url . $demo_slug . '/';
        
        // Widget import
        if ($this->test_remote_file($demo_url . 'widgets.wie')) {
            $config['import_widget_file_url'] = $demo_url . 'widgets.wie';
        }
        
        // Customizer import
        if ($this->test_remote_file($demo_url . 'customizer.dat')) {
            $config['import_customizer_file_url'] = $demo_url . 'customizer.dat';
        }
        
        // Redux options
        if ($this->test_remote_file($demo_url . 'theme-options.json')) {
            $config['import_redux'] = array(
                array(
                    'file_url' => $demo_url . 'theme-options.json',
                    'option_name' => $this->get_redux_option_name(),
                ),
            );
        }
        
        // Preview image
        $preview_images = array('screenshot.jpg', 'screenshot.png', 'preview.jpg');
        foreach ($preview_images as $image) {
            if ($this->test_remote_file($demo_url . $image)) {
                $config['import_preview_image_url'] = $demo_url . $image;
                break;
            }
        }
        
        // Elementor settings
        if ($this->test_remote_file($demo_url . 'site-settings.json')) {
            $config['elementor_settings_url'] = $demo_url . 'site-settings.json';
        }
    }
    
    /**
     * Test if remote file exists
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
        // Check for info.json file
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
        // Check for info.json file
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
     * Get Redux option name
     */
    private function get_redux_option_name() {
        // Check config for custom option name
        if (!empty($this->remote_config['redux_option_name'])) {
            return $this->remote_config['redux_option_name'];
        }
        
        // Common Redux option names
        $common_names = array(
            'theme_options',
            $this->theme_slug . '_options',
            'redux_options',
            'pxl_theme_options'
        );
        
        foreach ($common_names as $name) {
            if (get_option($name)) {
                return $name;
            }
        }
        
        return 'theme_options'; // Default fallback
    }
    
    /**
     * Optimize import environment
     */
    public function optimize_import_environment() {
        // Increase memory limit using WordPress function (GPL compliant)
        if (function_exists('wp_raise_memory_limit')) {
            wp_raise_memory_limit('admin');
        }
        
        // Use WordPress-safe time limit handling
        if (function_exists('set_time_limit') && !ini_get('safe_mode')) {
            @set_time_limit(600);
        }
        
        // Disable output buffering
        if (ob_get_level()) {
            ob_end_clean();
        }
    }
    
    /**
     * After import setup
     */
    public function after_import_setup($selected_import) {
        // Import Elementor settings
        $this->import_elementor_settings($selected_import);
        
        // Setup menus and pages
        $this->setup_theme_defaults();
        
        // Clear caches
        $this->clear_caches();
        
        // Log success
        $this->log_debug('Import completed for: ' . $selected_import['import_file_name']);
    }
    
    /**
     * Import Elementor settings
     */
    private function import_elementor_settings($selected_import) {
        if (!class_exists('\Elementor\Plugin')) {
            return;
        }
        
        $settings_content = '';
        
        // Get settings from local file
        if (isset($selected_import['elementor_settings_file'])) {
            $settings_content = file_get_contents($selected_import['elementor_settings_file']);
        }
        // Get settings from remote URL
        elseif (isset($selected_import['elementor_settings_url'])) {
            $response = wp_remote_get($selected_import['elementor_settings_url']);
            if (!is_wp_error($response)) {
                $settings_content = wp_remote_retrieve_body($response);
            }
        }
        
        if (!empty($settings_content)) {
            $settings_data = json_decode($settings_content, true);
            if ($settings_data && isset($settings_data['settings'])) {
                \Elementor\Plugin::$instance->kits_manager->save_kit_settings($settings_data['settings']);
            }
        }
    }
    
    /**
     * Setup theme defaults
     */
    private function setup_theme_defaults() {
        // Set front page
        $front_page = get_page_by_title('Home');
        if ($front_page) {
            update_option('show_on_front', 'page');
            update_option('page_on_front', $front_page->ID);
        }
        
        // Set blog page
        $blog_page = get_page_by_title('Blog');
        if ($blog_page) {
            update_option('page_for_posts', $blog_page->ID);
        }
        
        // Assign menus to locations
        $this->assign_menus_to_locations();
    }
    
    /**
     * Assign menus to locations
     */
    private function assign_menus_to_locations() {
        $menus = wp_get_nav_menus();
        if (empty($menus)) {
            return;
        }
        
        $menu_locations = array();
        $registered_locations = get_registered_nav_menus();
        
        foreach ($menus as $menu) {
            $menu_name = strtolower($menu->name);
            
            // Map menu names to locations
            if (strpos($menu_name, 'main') !== false || strpos($menu_name, 'primary') !== false) {
                if (isset($registered_locations['primary'])) {
                    $menu_locations['primary'] = $menu->term_id;
                }
            }
            
            if (strpos($menu_name, 'footer') !== false) {
                if (isset($registered_locations['footer'])) {
                    $menu_locations['footer'] = $menu->term_id;
                }
            }
            
            if (strpos($menu_name, 'header') !== false) {
                if (isset($registered_locations['header'])) {
                    $menu_locations['header'] = $menu->term_id;
                }
            }
        }
        
        if (!empty($menu_locations)) {
            set_theme_mod('nav_menu_locations', $menu_locations);
        }
    }
    
    /**
     * Clear caches
     */
    private function clear_caches() {
        // WordPress cache
        wp_cache_flush();
        
        // Common cache plugins
        if (function_exists('w3tc_flush_all')) {
            w3tc_flush_all();
        }
        
        if (function_exists('wp_rocket_clean_domain')) {
            wp_rocket_clean_domain();
        }
        
        if (function_exists('litespeed_purge_all')) {
            litespeed_purge_all();
        }
    }
    
    /**
     * Admin notices
     */
    public function admin_notices() {
        $screen = get_current_screen();
        if (!$screen || $screen->id !== 'appearance_page_one-click-demo-import') {
            return;
        }
        
        // Check if demos are available
        $local_demos = $this->get_local_demos();
        $remote_demos = $this->get_remote_demos();
        
        if (empty($local_demos) && empty($remote_demos)) {
            ?>
            <div class="notice notice-warning">
                <h3>Demo Import Setup Required</h3>
                <p><strong>No demos found!</strong> To add demos:</p>
                <ol>
                    <li><strong>Local demos:</strong> Create demo folders in <code>/inc/demo-import/demo-content/</code> with <code>content.xml</code> files</li>
                    <li><strong>Remote demos:</strong> Configure demo URLs in <code>/inc/demo-import/demo-config.php</code></li>
                </ol>
                <p><a href="#" onclick="jQuery('#demo-setup-help').toggle(); return false;">Show detailed setup instructions</a></p>
                <div id="demo-setup-help" style="display:none; margin-top:15px; padding:15px; background:#f9f9f9; border-left:4px solid #0073aa;">
                    <h4>Setup Instructions:</h4>
                    <p><strong>For local demos:</strong></p>
                    <pre style="background:#fff; padding:10px; overflow:auto;">/inc/demo-import/demo-content/
├── business/
│   ├── content.xml (required)
│   ├── theme-options.json
│   └── screenshot.jpg
└── portfolio/
    ├── content.xml (required)
    └── widgets.wie</pre>
                    <p><strong>For remote demos:</strong> Edit <code>/inc/demo-import/demo-config.php</code> and set your server URL.</p>
                </div>
            </div>
            <?php
        } else {
            $total_demos = count($local_demos) + count($remote_demos);
            $local_count = count($local_demos);
            $remote_count = count($remote_demos);
            
            ?>
            <div class="notice notice-success">
                <p><strong>Demo Import Ready!</strong> Found <?php echo $total_demos; ?> demo(s) 
                   (<?php echo $local_count; ?> local, <?php echo $remote_count; ?> remote)</p>
            </div>
            <?php
        }
    }
    
    /**
     * AJAX test connection
     */
    public function ajax_test_connection() {
        if (!current_user_can('manage_options')) {
            wp_die('Unauthorized');
        }
        
        $url = esc_url_raw($_POST['url'] ?? '');
        if (empty($url)) {
            wp_send_json_error('No URL provided');
        }
        
        $result = $this->test_remote_file($url);
        wp_send_json_success(array('accessible' => $result));
    }
    
    /**
     * Debug logging
     */
    private function log_debug($message) {
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('Universal Demo Import: ' . $message);
        }
    }
}

// Initialize the system
Universal_Demo_Import::get_instance();