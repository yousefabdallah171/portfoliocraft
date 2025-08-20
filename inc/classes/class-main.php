<?php
/**
 * portfoliocraft Main Theme Class
 * 
 * This is the core class that manages all theme functionality including
 * theme options, page options, and various theme components like header,
 * footer, page, and blog functionality. Implements singleton pattern
 * for consistent access throughout the theme.
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Include base class
get_template_part('inc/classes/class-base');

/**
 * Main Theme Class
 * 
 * Extends portfoliocraft_Base and manages all core theme functionality
 * Uses singleton pattern to ensure single instance throughout theme
 * Handles theme options, page options, and component initialization
 */
if (!class_exists('portfoliocraft_Main')) { 
    class portfoliocraft_Main extends portfoliocraft_Base
    {
        /**
         * Singleton instance
         * @var portfoliocraft_Main|null
         */
        private static $instance = null;
        
        /**
         * Theme options cache
         * @var array
         */
        protected static $options = array();
        
        /**
         * Theme options name in database
         * @var string
         */
        private $option_name = 'pxl_theme_options';
        
        /**
         * Header component instance
         * @var portfoliocraft_Header
         */
        public $header;
        
        /**
         * Page component instance
         * @var portfoliocraft_Page
         */
        public $page;
        
        /**
         * Blog component instance
         * @var portfoliocraft_Blog
         */
        public $blog;
        
        /**
         * Footer component instance
         * @var portfoliocraft_Footer
         */
        public $footer;

        /**
         * Constructor
         * 
         * Initializes all theme components and loads required classes
         * Sets up header, footer, page, and blog functionality
         */
        function __construct() {
            
            // Initialize Header component
            $header_file = get_template_directory() . '/inc/classes/class-header.php';
            if (file_exists($header_file)) {
                require $header_file;
                $this->header = new portfoliocraft_Header();
            }

            // Initialize Footer component
            $footer_file = get_template_directory() . '/inc/classes/class-footer.php';
            if (file_exists($footer_file)) {
                require $footer_file;
                $this->footer = new portfoliocraft_Footer();
            }
 
            // Initialize Page component
            $page_file = get_template_directory() . '/inc/classes/class-page.php';
            if (file_exists($page_file)) {
                require $page_file;
                $this->page = new portfoliocraft_Page();
            }

            // Initialize Blog component
            $blog_file = get_template_directory() . '/inc/classes/class-blog.php';
            if (file_exists($blog_file)) {
                require $blog_file;
                $this->blog = new portfoliocraft_Blog();
            }
        }

        /**
         * Get Singleton Instance
         * 
         * Returns the single instance of the class, creating it if necessary
         * Ensures only one instance exists throughout the theme lifecycle
         * 
         * @return portfoliocraft_Main The singleton instance
         */
        public static function getInstance() {
            if (null === self::$instance) {
                self::$instance = new portfoliocraft_Main();
            }
            return self::$instance;
        }

        /**
         * Require All Files in Folder
         * 
         * Dynamically loads all PHP files from a specified folder
         * Useful for loading multiple related files at once
         * Includes security checks to prevent loading non-PHP files
         * 
         * @param string $foldername The folder name to load files from
         * @param string $path The base path (defaults to theme directory)
         * @return void
         */
        public function require_folder($foldername, $path = '') {
            // Set default path to theme directory
            if ($path === '') {
                $path = get_template_directory();
            }
            
            // Build full directory path
            $dir = $path . DIRECTORY_SEPARATOR . $foldername;
            
            // Check if directory exists
            if (!is_dir($dir)) {
                return;
            }
            
            // Get all files in directory, excluding . and ..
            $files = array_diff(scandir($dir), array('..', '.'));
            
            // Load each PHP file
            foreach ($files as $file) {
                $file_path = $dir . DIRECTORY_SEPARATOR . $file;
                
                // Security check: only load PHP files that exist
                if (file_exists($file_path) && 
                    pathinfo($file, PATHINFO_EXTENSION) === 'php' && 
                    is_readable($file_path)) {
                    require_once $file_path;
                }
            }
        }

        /**
         * Get Theme Option Name
         * 
         * Returns the appropriate option name based on context
         * Supports multilingual sites using WPML
         * Handles AJAX requests with custom option names
         * 
         * @return string The option name to use
         */
        public function get_option_name() {
            // Handle AJAX requests with custom option name
            if (isset($_POST['opt_name']) && !empty($_POST['opt_name'])) {
                return sanitize_key($_POST['opt_name']);
            }
            
            // Handle WPML multilingual support
            if (defined('ICL_LANGUAGE_CODE')) {
                if (ICL_LANGUAGE_CODE != 'all' && !empty(ICL_LANGUAGE_CODE)) {
                    return $this->option_name . '_' . sanitize_key(ICL_LANGUAGE_CODE);
                }
            }
            
            return $this->option_name;
        }

        /**
         * Get Theme Name
         * 
         * Retrieves the theme name from WordPress theme data
         * Handles both parent and child themes correctly
         * 
         * @return string The theme name
         */
        public function get_name() {
            $theme = wp_get_theme();
            
            // Handle child themes - get parent theme name
            if ($theme->parent_theme) {
                $template_dir = basename(get_template_directory());
                $theme = wp_get_theme($template_dir);
            }
            
            return $theme->get('Name');
        }

        /**
         * Get Theme Slug
         * 
         * Returns the theme template directory name
         * 
         * @return string The theme slug
         */
        public function get_slug() { 
            return get_template();
        }

        /**
         * Set Option Name
         * 
         * Allows changing the option name used for theme options
         * Returns $this for method chaining
         * 
         * @param string $option_name The new option name
         * @return portfoliocraft_Main Current instance for chaining
         */
        public function set_option_name($option_name) {
            $this->option_name = sanitize_key($option_name);
            return $this;
        }

        /**
         * Get Theme Version
         * 
         * Retrieves the current theme version from style.css header
         * 
         * @return string The theme version
         */
        public function get_version() {
            $theme = wp_get_theme();
            return $theme->get('Version');
        }

        /**
         * Get Theme Option
         * 
         * Retrieves theme option values with fallback support
         * Supports nested array options and default values
         * Caches options for performance
         * 
         * @param string|null $setting The option key to retrieve
         * @param mixed $default Default value if option doesn't exist
         * @param string|false $subset Specific array key to retrieve
         * @return mixed The option value or default
         */
        public function get_theme_opt($setting = null, $default = false, $subset = false) {
            // Validate setting parameter
            if (is_null($setting) || empty($setting)) {
                return '';
            }

            // Load options if not cached
            if (empty(self::$options)) {
                self::$options = self::$instance->get_options();
            }
 
            // Return default if option doesn't exist or is empty
            if (empty(self::$options) || 
                !isset(self::$options[$setting]) || 
                self::$options[$setting] === '') {
                
                if ($subset && !empty($subset)) {
                    return is_array($default) && isset($default[$subset]) ? $default[$subset] : $default;
                } else {
                    return $default;
                }
            }
 
            // Handle array options with default fallbacks
            if (is_array(self::$options[$setting])) {
                if (is_array($default)) {
                    foreach (self::$options[$setting] as $key => $value) {
                        if (empty(self::$options[$setting][$key]) && isset($default[$key])) {
                            self::$options[$setting][$key] = $default[$key];
                        }
                    }
                } else {
                    foreach (self::$options[$setting] as $key => $value) {
                        if (empty(self::$options[$setting][$key]) && isset($default)) {
                            self::$options[$setting][$key] = $default;
                        }
                    }
                }
            } 

            // Return subset if requested
            if (!$subset || empty($subset)) {
                return self::$options[$setting];
            }

            if (isset(self::$options[$setting][$subset])) {
                return self::$options[$setting][$subset];
            }

            return self::$options;
        }

        /**
         * Get Page Option
         * 
         * Retrieves page-specific meta options with fallback support
         * Handles WooCommerce shop page and other special cases
         * Supports nested array options
         * 
         * @param string|null $setting The meta key to retrieve
         * @param mixed $default Default value if meta doesn't exist
         * @param string|false $subset Specific array key to retrieve
         * @return mixed The meta value or default
         */
        public function get_page_opt($setting = null, $default = false, $subset = false) {
            // Validate setting parameter
            if (is_null($setting) || empty($setting)) {
                return '';
            }

            $id = get_the_ID();

            // Handle WooCommerce shop page
            if (class_exists('WooCommerce') && is_shop()) {
                $real_page = get_post(wc_get_page_id('shop'));
            } else {
                $real_page = get_queried_object();
            }

            // Get correct page ID
            if ($real_page instanceof WP_Post) {
                $id = $real_page->ID;
            }
            
            // Get meta value with validation
            $meta_value = !empty($id) ? get_post_meta($id, $setting, true) : '';
            
            if (!empty($id) && ('' !== $meta_value)) {
                $options = $meta_value;
                
                // Handle array options with default fallbacks
                if (is_array($options)) {
                    if (is_array($default)) {
                        foreach ($options as $key => $value) {
                            if (empty($options[$key]) && isset($default[$key])) {
                                $options[$key] = $default[$key];
                            }
                        }
                    } else {
                        foreach ($options as $key => $value) {
                            if (empty($options[$key]) && isset($default)) {
                                $options[$key] = $default;
                            }
                        }
                    }
                }
            } else {
                $options = $default;
            }
            
            // Return subset if requested
            if ($subset && !empty($subset)) {  
                if (is_array($options) && isset($options[$subset])) {
                    $options = $options[$subset];
                }
            } 
  
            return $options;
        }

        /**
         * Get Combined Option
         * 
         * Combines theme options with page options, giving priority to page options
         * Handles inheritance logic (-1 values inherit from theme options)
         * Supports array merging for complex options
         * 
         * @param string|null $setting The option key to retrieve
         * @param mixed $default Default value if option doesn't exist
         * @param string|false $subset Specific array key to retrieve
         * @return mixed The combined option value
         */
        public function get_opt($setting = null, $default = false, $subset = false) {
            // Validate setting parameter
            if (is_null($setting) || empty($setting)) {
                return '';
            }
             
            // Get theme and page options
            $theme_opt = $this->get_theme_opt($setting, $default);
            $page_opt = $this->get_page_opt($setting, $theme_opt);
            
            // Use page option if it exists and is not inherit value
            if ($page_opt !== null && $page_opt !== '' && $page_opt !== '-1') {
                // Handle array merging
                if (is_array($page_opt) && is_array($theme_opt)) {
                    foreach ($page_opt as $key => $value) {
                        // Use theme option for empty or 'px' values
                        if (empty($page_opt[$key]) || $page_opt[$key] === 'px') {
                            $page_opt[$key] = isset($theme_opt[$key]) ? $theme_opt[$key] : '';
                        }
                    }
                }
                $theme_opt = $page_opt;
            }
 
            // Return subset if requested
            if ($subset && !empty($subset)) {  
                if (is_array($theme_opt) && isset($theme_opt[$subset])) {
                    $theme_opt = $theme_opt[$subset];
                }
            }
 
            return $theme_opt;
        }

        /**
         * Set Theme Option
         * 
         * Updates a theme option in the database
         * Refreshes the options cache after update
         * 
         * @param string $setting The option key to set
         * @param mixed $value The value to set
         * @return portfoliocraft_Main Current instance for chaining
         */
        public function set_options($setting, $value) {
            // Load current options if not cached
            if (empty(self::$options)) {
                self::$options = self::get_options();
            }

            $options = self::$options;
            $options[sanitize_key($setting)] = $value;

            // Update database
            update_option($this->get_option_name(), $options);

            // Update cache
            self::$options = $options;

            return $this;
        }

        /**
         * Get All Theme Options
         * 
         * Retrieves all theme options from database with filter support
         * Applies 'case/setting/options' filter for customization
         * 
         * @return array All theme options
         */
        public static function get_options() {
            $options = get_option(self::$instance->get_option_name(), array());
            
            // Apply filter for option customization
            $options = apply_filters('case/setting/options', $options);

            return $options;
        }

        /**
         * Get Sidebar Configuration
         * 
         * Determines sidebar settings based on current page context
         * Handles different post types and URL parameters
         * Returns sidebar class and status information
         * 
         * @param string $page The page context (default: 'blog')
         * @return array Sidebar configuration array
         */
        public function get_sidebar_value($page = 'blog') {
            // Determine sidebar registration key
            $sidebar_reg = (is_singular('post') || is_search()) ? 'blog' : $page;
            $sidebar_reg = is_singular('product') ? 'shop' : $sidebar_reg;

            // Check if sidebar is active
            $sidebar_active = is_active_sidebar('sidebar-' . $sidebar_reg);
            
            // Get sidebar setting
            $sidebar_value = $this->get_opt($page . '_sidebar', 'disable');
            
            // Handle search page specifically
            if ($page === 'search') {
                $sidebar_value = $this->get_theme_opt('search_sidebar', 'disable');
            } else {
                // Handle URL parameter overrides
                if (isset($_GET['sidebar-blog'])) {
                    $sidebar_value = sanitize_key($_GET['sidebar-blog']);
                }
                if (isset($_GET['sidebar-shop'])) {
                    $sidebar_value = sanitize_key($_GET['sidebar-shop']);
                }
            }
            
            // Default configuration (no sidebar)
            $args = array(
                'sidebar_class' => 'no-sidebar',
                'is_sidebar' => false,
            );
            
            // Configure sidebar if enabled
            if ($sidebar_value !== 'disable' && $sidebar_active) {
                $args = array(
                    'sidebar_class' => sprintf(
                        'has-sidebar sidebar-%s sidebar-position-%s',
                        esc_attr($page),
                        esc_attr($sidebar_value)
                    ),
                    'is_sidebar' => true,
                );
            }
            
            return $args;
        }

        /**
         * Get Current Sidebar Name
         * 
         * Determines which sidebar to display based on current page context
         * Handles WooCommerce pages, regular pages, and blog pages
         * 
         * @return string The sidebar name to use
         */
        public function get_sidebar() {
            // WooCommerce pages
            if (class_exists('WooCommerce') && 
                (is_product_category() || is_shop() || is_product())) {
                $sidebar = 'sidebar-shop';
            } 
            // Regular pages
            elseif (is_singular('page')) {
                $sidebar = 'sidebar-page';
            } 
            // Blog and other pages
            else {
                $sidebar = 'sidebar-blog';
            }
            
            return $sidebar;
        }
    }
}

/**
 * Global Function to Access Theme Instance
 * 
 * Provides easy access to the theme singleton instance
 * Used throughout the theme for accessing theme functionality
 * 
 * @return portfoliocraft_Main The theme instance
 */
function portfoliocraft() {
    return portfoliocraft_Main::getInstance();
}

// Initialize theme
portfoliocraft(); 

// Trigger theme initialization hook
pxl_action('init');
