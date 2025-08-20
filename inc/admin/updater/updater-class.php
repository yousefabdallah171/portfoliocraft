<?php
/**
 * portfoliocraft Theme Updater Class
 * 
 * This class handles automatic theme updates by communicating with a remote API server
 * to check for new versions and provide update notifications in the WordPress admin.
 * Integrates with WordPress's built-in update system for seamless theme management.
 * 
 * Features:
 * - Remote version checking via API
 * - WordPress update system integration
 * - License validation for updates
 * - Changelog display in admin notices
 * - Transient caching for performance
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Updater Class
 * 
 * Manages automatic theme updates by interfacing with remote API
 * and WordPress's native update system for seamless integration
 */
class portfoliocraft_Updater {
    
    /**
     * Remote API URL
     * 
     * The base URL for the remote API server that provides
     * version information and update packages
     * 
     * @access private
     * @var string
     */
    private $remote_api_url;
    
    /**
     * Request Data Array
     * 
     * Additional data to send with API requests
     * for authentication and identification
     * 
     * @access private
     * @var array
     */
    private $request_data;
    
    /**
     * Response Cache Key
     * 
     * Transient key used for caching API responses
     * to improve performance and reduce server load
     * 
     * @access private
     * @var string
     */
    private $response_key;
    
    /**
     * Theme Slug Identifier
     * 
     * Unique identifier for the theme used in
     * WordPress systems and API communications
     * 
     * @access private
     * @var string
     */
    private $theme_slug;
    
    /**
     * Purchase Code/License Key
     * 
     * License key or purchase code for validating
     * update permissions and access rights
     * 
     * @access private
     * @var string
     */
    private $purchase_code;
    
    /**
     * Current Theme Version
     * 
     * Current installed version of the theme
     * used for comparison with remote versions
     * 
     * @access private
     * @var string
     */
    private $version;
    
    /**
     * Theme Author
     * 
     * Author identifier for API authentication
     * and theme validation purposes
     * 
     * @access private
     * @var string
     */
    private $author;
    
    /**
     * Localization Strings
     * 
     * Array of translatable strings used in
     * update notifications and admin messages
     * 
     * @access protected
     * @var array|null
     */
    protected $strings = null;

    /**
     * Constructor - Initialize Theme Updater
     * 
     * Sets up the updater with configuration parameters and hooks
     * into WordPress update system for automatic update checking
     * 
     * @param array $args Configuration arguments for the updater
     * @param array $strings Localization strings for admin messages
     */
    function __construct($args = array(), $strings = array()) {

        // Parse arguments with defaults
        $args = wp_parse_args($args, array(
            'remote_api_url' => 'http://api.portfoliocraft-themes.net/',
            'request_data' => array(),
            'theme_slug' => portfoliocraft()->get_slug(),
            'item_name' => '',
            'license' => '',
            'version' => '',
            'author' => ''
        ));
        
        // Extract arguments to class properties
        extract($args);

        // Set class properties with proper sanitization
        $this->license = $license;
        $this->item_name = $item_name;
        $this->version = $version;
        $this->theme_slug = sanitize_key($theme_slug);
        $this->remote_api_url = $remote_api_url;
        $this->response_key = $this->theme_slug . '-update-response';
        $this->strings = $strings;

        // Hook into WordPress update system
        add_filter('site_transient_update_themes', array(&$this, 'theme_update_transient'));
        add_filter('delete_site_transient_update_themes', array(&$this, 'delete_theme_update_transient'));
        add_action('load-update-core.php', array(&$this, 'delete_theme_update_transient'));
        add_action('load-themes.php', array(&$this, 'delete_theme_update_transient'));
        add_action('load-themes.php', array(&$this, 'load_themes_screen'));
    }

    /**
     * Load Themes Screen Setup
     * 
     * Prepares the themes admin screen by adding necessary scripts
     * and hooking update notifications into admin notices
     * 
     * @return void
     */
    function load_themes_screen() {
        // Add thickbox for changelog modal display
        add_thickbox();
        
        // Hook update notification into admin notices
        add_action('admin_notices', array(&$this, 'update_nag'));
    }

    /**
     * Display Update Notification
     * 
     * Shows admin notice when theme updates are available
     * Includes changelog link and update action button
     * with proper security measures and user confirmation
     * 
     * @return void
     */
    function update_nag() {

        // Get localization strings
        $strings = $this->strings;

        // Get current theme object
        $theme = wp_get_theme($this->theme_slug);

        // Get cached API response
        $api_response = get_transient($this->response_key);

        // Exit if no API response available
        if (false === $api_response) {
            return;
        }

        // Generate secure update URL with nonce
        $update_url = wp_nonce_url(
            'update.php?action=upgrade-theme&amp;theme=' . urlencode($this->theme_slug), 
            'upgrade-theme_' . $this->theme_slug
        );
        
        // JavaScript confirmation for update action
        $update_onclick = ' onclick="if ( confirm(\'' . 
            esc_js('Updating this theme will lose any customizations you have made. \'Cancel\' to stop, \'OK\' to update.') . 
            '\') ) {return true;}return false;"';

        // Display update notice if new version is available
        if (version_compare($this->version, $api_response->new_version, '<')) {

            echo '<div id="update-nag">';
            printf(
                wp_kses_post(esc_html__(
                    '<strong>%1$s %2$s</strong> is available. <a href="%3$s" class="thickbox" title="%4s">Check out what\'s new</a> or <a href="%5$s"%6$s>update now</a>.', 
                    'portfoliocraft'
                )),
                $theme->get('Name'),
                $api_response->new_version,
                '#TB_inline?width=640&amp;inlineId=' . $this->theme_slug . '_changelog',
                $theme->get('Name'),
                $update_url,
                $update_onclick
            );
            echo '</div>';
            
            // Hidden changelog content for thickbox modal
            echo '<div id="' . $this->theme_slug . '_' . 'changelog" style="display:none;">';
            echo wpautop($api_response->sections['changelog']);
            echo '</div>';
        }
    }

    /**
     * Filter Theme Update Transient
     * 
     * Hooks into WordPress update system to inject theme update data
     * when updates are available from the remote API
     * 
     * @param object $value The update transient object
     * @return object Modified transient with theme update data
     */
    function theme_update_transient($value) { 
         
        // Check for available updates
        $update_data = $this->check_for_update();
        
        // Add update data to transient if available
        if ($update_data) {
            $value->response[$this->theme_slug] = $update_data;
        }
        
        return $value;
    }

    /**
     * Delete Theme Update Transient
     * 
     * Clears cached update data to force fresh API check
     * Called when update pages are loaded or transients are cleared
     * 
     * @return void
     */
    function delete_theme_update_transient() {
        delete_transient($this->response_key);
    }

    /**
     * Check for Theme Updates
     * 
     * Communicates with remote API to check for available updates
     * Handles caching, error recovery, and response validation
     * 
     * @return array|false Update data array or false if no update
     */
    function check_for_update() {

        // Try to get cached update data first
        $update_data = get_transient($this->response_key);
         
        // If no cached data, make API request
        if (false === $update_data) {
            $failed = false;

            // Prepare API request parameters
            $api_params = array(
                'action'  => 'get_version',
                'license' => $this->license,
                'slug'    => $this->theme_slug,
                'author'  => $this->author
            );
        
            // Make remote API request with timeout
            $response = wp_remote_get($this->remote_api_url, array(
                'timeout' => 15, 
                'body' => $api_params
            ));

            // Validate response success
            if (is_wp_error($response) || 200 != wp_remote_retrieve_response_code($response)) {
                $failed = true;
            }

            // Decode JSON response
            $update_data = json_decode(wp_remote_retrieve_body($response));
 
            // Validate response data structure
            if (!is_object($update_data)) {
                $failed = true;
            }

            /**
             * Handle Failed Requests
             * 
             * If the API request fails, cache current version data
             * for 30 minutes before trying again to prevent spam
             */
            if ($failed) {
                $data = new stdClass;
                $data->new_version = $this->version;
                set_transient($this->response_key, $data, strtotime('+30 minutes'));
                return false;
            }

            /**
             * Cache Successful Response
             * 
             * Process and cache valid API response for 12 hours
             * to reduce server load and improve performance
             */
            if (!$failed) {
                $update_data->sections = maybe_unserialize($update_data->sections);
                set_transient($this->response_key, $update_data, strtotime('+12 hours'));
            }
        }

        // Return false if current version is up to date
        if (version_compare($this->version, $update_data->new_version, '>=')) {
            return false;
        }

        // Return update data as array for WordPress update system
        return (array) $update_data;
    }
}
