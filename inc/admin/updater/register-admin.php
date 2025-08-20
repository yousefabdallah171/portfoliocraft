<?php
/**
 * portfoliocraft Theme Registration Class
 * 
 * This class handles theme license registration, validation, and management
 * for the portfoliocraft WordPress theme. It provides secure license verification,
 * automatic updates, and admin interface for license management.
 * 
 * Features:
 * - License key validation via remote API
 * - Automatic theme updates for valid licenses
 * - Admin notices for registration status
 * - Secure license removal functionality
 * - Integration with WordPress update system
 * 
 * @package portfoliocraft
 * @version 1.0.0
 */

// Security: Prevent direct access
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Theme Registration and License Management Class
 * 
 * Manages all aspects of theme licensing including validation,
 * registration forms, admin notices, and update system integration
 */
class portfoliocraft_Register {

    /**
     * Remote API URL
     * 
     * The base URL for the license validation and update API server
     * Used for all license-related communications
     * 
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $remote_api_url = null;
    
    /**
     * Theme Slug Identifier
     * 
     * Unique identifier for the theme used in WordPress systems
     * and database option storage
     * 
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $theme_slug = null;
    
    /**
     * Theme Display Name
     * 
     * Human-readable theme name used in admin interfaces
     * and license validation requests
     * 
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $theme_name = null;
    
    /**
     * Theme Version
     * 
     * Current theme version used for update checking
     * and compatibility validation
     * 
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $version = null;
    
    /**
     * Theme Author
     * 
     * Author identifier used in license validation
     * and API authentication
     * 
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $author = null;
    
    /**
     * License Renewal URL
     * 
     * URL for license renewal or purchase
     * Used in admin notices and help links
     * 
     * @since 1.0.0
     * @access protected
     * @var string
     */
    protected $renew_url = null;
    
    /**
     * Localization Strings
     * 
     * Array of translatable strings used throughout
     * the registration interface
     * 
     * @since 1.0.0
     * @access protected
     * @var array
     */
    protected $strings = null;

    /**
     * Constructor - Initialize Registration System
     * 
     * Sets up the registration system with configuration parameters
     * and hooks into WordPress admin system for license management
     * 
     * @since 1.0.0
     * @param array $config Configuration parameters for registration
     * @param array $strings Localization strings for admin interface
     */
    public function __construct($config = array(), $strings = array()) {
        
        // Parse configuration with defaults
        $config = wp_parse_args($config, array(
            'remote_api_url' => 'http://api.portfoliocraft-themes.net/',
            'theme_slug'     => portfoliocraft()->get_slug(),
            'theme_name'     => portfoliocraft()->get_name(),
            'version'        => '',
            'author'         => 'Pixelart team',
            'renew_url'      => ''
        ));

        // Set configuration properties with proper sanitization
        $this->remote_api_url = $config['remote_api_url'];
        $this->theme_slug     = sanitize_key($config['theme_slug']);
        $this->theme_name     = $config['theme_name'];
        $this->version        = $config['version'];
        $this->author         = $config['author'];
        $this->renew_url      = $config['renew_url'];

        // Populate version fallback from theme data
        if ('' == $config['version']) {
            $theme = wp_get_theme($this->theme_slug);
            $this->version = $theme->get('Version');
        }

        // Store localization strings
        $this->strings = $strings;

        // Hook into WordPress admin system
        add_action('admin_init', array($this, 'register_option'), 12);
        add_action('admin_init', array($this, 'remove_key'), 13);
        add_action('admin_init', array($this, 'updater'), 14);
        add_action('admin_init', array($this, 'pxl_notice'), 15);
        add_filter('http_request_args', array($this, 'disable_wporg_request'), 5, 2);
    }

    /**
     * Initialize Theme Updater
     * 
     * Creates the theme updater instance for automatic updates
     * Only activated when valid license is present
     * 
     * @since 1.0.0
     * @return void
     */
    function updater() {

        // Only allow updates with valid license
        if (get_option($this->theme_slug . '_purchase_code_status', false) != 'valid') {
            // Remove TGMPA notices when license is invalid
            remove_action('admin_notices', array(TGM_Plugin_Activation::$instance, 'notices'));  
            return;
        }

        // Load updater class if not already loaded
        if (!class_exists('portfoliocraft_Updater')) {
            get_template_part('inc/admin/updater/updater-class');
        }

        // Initialize theme updater with license information
        new portfoliocraft_Updater(
            array(
                'remote_api_url' => $this->remote_api_url,
                'version'        => $this->version,
                'license'        => trim(get_option($this->theme_slug . '_purchase_code')),
            ),
            $this->strings
        );
    }
    
    /**
     * Display Admin Notices
     * 
     * Shows appropriate admin notices based on license status
     * and current admin page context
     * 
     * @since 1.0.0
     * @return void
     */
    public function pxl_notice() {
        // Skip notices in development mode
        $dev_mode = (defined('DEV_MODE') && DEV_MODE);
        if ($dev_mode === true) return;
        
        // Only show notices if license is not valid
        if ('valid' != get_option($this->theme_slug . '_purchase_code_status', false)) {

            // Show different notices based on current page
            if ((!isset($_GET['page']) || 'pxlart' != sanitize_text_field($_GET['page']))) {
                // Show error notice on other admin pages
                add_action('admin_notices', array($this, 'admin_error'));
            } else {
                // Show info notice on theme admin page
                add_action('admin_notices', array($this, 'admin_notice'));
            }
        }
    }
    
    /**
     * Display Admin Error Notice
     * 
     * Shows error notice with registration link on admin pages
     * when theme is not properly registered
     * 
     * @return void
     */
    function admin_error() {
        echo '<div class="error"><p>' . 
            sprintf(
                wp_kses_post(esc_html__('The %s theme needs to be registered. %sRegister Now%s', 'portfoliocraft')), 
                portfoliocraft()->get_name(), 
                '<a href="' . admin_url('admin.php?page=pxlart') . '">', 
                '</a>'
            ) . '</p></div>';
    }
    
    /**
     * Display Admin Info Notice
     * 
     * Shows informational notice about invalid purchase code
     * on the theme registration page
     * 
     * @return void
     */
    function admin_notice() {
        echo '<div class="notice"><p>' . 
            esc_html__('Purchase code is invalid. Need a license for activation', 'portfoliocraft') . 
            '</p></div>';
    }

    /**
     * Display Registration Messages
     * 
     * Main method for displaying registration interface
     * Shows either registration form or license validation results
     * 
     * @param bool $merlin Whether called from setup wizard
     * @return void
     */
    function messages($merlin = false) {
        $purchase_code = trim(get_option($this->theme_slug . '_purchase_code'));
        
        if (!$purchase_code) {
            // Show registration form if no purchase code exists
            ?>
            <div class="pxl-dsb-box-head-inner">
                <h6><?php echo esc_html__('Register License', 'portfoliocraft'); ?></h6>
            </div>
            <?php 
            $this->form();
            ?>
            <div class="pxl-dsb-box-foot">
                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">
                    <?php esc_html_e('Can\'t find your purchase code?', 'portfoliocraft'); ?>
                </a>
            </div>
            <?php 
        } else {  
            // Check existing license if purchase code exists
            $this->check_license($merlin);
        }
    } 

    /**
     * Validate License with Remote API
     * 
     * Communicates with remote API to validate license status
     * and displays appropriate success or error messages
     * 
     * @param bool $merlin Whether called from setup wizard
     * @return void
     */
    function check_license($merlin) {
        // Get server information for support links
        $pxl_server_info = apply_filters('pxl_server_info', [
            'docs_url' => 'https://doc.portfoliocraft-themes.net/', 
            'support_url' => 'https://portfoliocraft-themes.ticksy.com/'
        ]);
        
        $purchase_code = trim(get_option($this->theme_slug . '_purchase_code'));
        
        // Prepare API request parameters
        $api_params = array(
            'action'     => 'check_license',
            'license'    => $purchase_code,
            'item_name'  => $this->theme_name,
            'url'        => rawurlencode(home_url())
        );
          
        // Make API request for license validation
        $license_data = $this->get_api_response($api_params);

        // Handle license validation failure
        if (false === $license_data->success) {
            
            // Generate appropriate error message based on error type
            switch ($license_data->error) {
                case 'missing':
                    $message = esc_html__('This appears to be an invalid license key. Please try again or contact support.', 'portfoliocraft');
                    break;
                case 'item_name_mismatch':
                    $message = sprintf(esc_html__('This appears to be an invalid license key for %s.', 'portfoliocraft'), $this->theme_name);
                    break;
                case 'license_exists':
                    $message = esc_html__('Your license is not active for this URL.', 'portfoliocraft');
                    break;
                default:
                    $message = esc_html__('An error occurred, please try again.', 'portfoliocraft');
                    break;
            }
            
            // Display error message with support links
            ?>
            <div class="pxl-dsb-confirmation fail">
                <h6><?php echo esc_html__('Active false', 'portfoliocraft'); ?></h6>
                <p>
                    <?php echo wp_kses_post($message); ?> 
                    <a href="<?php echo esc_url($pxl_server_info['docs_url']); ?>" target="_blank">
                        <?php echo esc_html__('our help center', 'portfoliocraft'); ?>
                    </a> or 
                    <a href="<?php echo esc_url($pxl_server_info['support_url']); ?>" target="_blank">
                        <?php echo esc_html__('submit a ticket', 'portfoliocraft'); ?>
                    </a>
                </p>
            </div>
            <?php 
            $this->form(); 
            ?>
            <div class="pxl-dsb-box-foot">
                <a href="https://help.market.envato.com/hc/en-us/articles/202822600-Where-Is-My-Purchase-Code-" target="_blank">
                    <?php esc_html_e('Can\'t find your purchase code?', 'portfoliocraft'); ?>
                </a>
            </div>
            <?php 
        } else {
            // Handle successful license validation
            if ('valid' === $license_data->license) {
                // Update license status in database
                update_option($this->theme_slug . '_purchase_code_status', $license_data->license);
                
                // Display success message with deactivation option
                ?>
                <div class="pxl-dsb-box-head"> 
                    <div class="pxl-dsb-confirmation success">
                        <h6><?php echo esc_html__('Thanks for the verification!', 'portfoliocraft'); ?></h6>
                        <p>
                            <?php echo esc_html__('You can now enjoy and build great websites. Looking for help? Visit', 'portfoliocraft'); ?> 
                            <a href="<?php echo esc_url($pxl_server_info['support_url']); ?>" target="_blank">
                                <?php echo esc_html__('submit a ticket', 'portfoliocraft'); ?>
                            </a>.
                        </p>
                    </div>
                    
                    <!-- License removal form -->
                    <div class="pxl-dsb-deactive">
                        <form method="POST" action="<?php echo admin_url('admin.php?page=pxlart'); ?>">
                            <input type="hidden" name="action" value="removekey"/>
                            <button class="btn button" type="submit">
                                <?php esc_html_e('Remove Purchase Code', 'portfoliocraft'); ?>
                            </button>
                        </form>
                    </div> 
                </div> 
                <?php 
                
                // Redirect to setup wizard if called from Merlin
                if ($merlin) {
                    wp_redirect(admin_url('admin.php?page=pxlart-setup&step=plugins'));
                }
            } else {
                // Handle unexpected response
                $message = esc_html__('Response return null.', 'portfoliocraft');
                ?>
                <div class="pxl-dsb-confirmation fail">
                    <h6><?php echo esc_html__('Active false', 'portfoliocraft'); ?></h6>
                    <p>
                        <?php echo wp_kses_post($message); ?> 
                        <a href="<?php echo esc_url($pxl_server_info['docs_url']); ?>" target="_blank">
                            <?php echo esc_html__('our help center', 'portfoliocraft'); ?>
                        </a> or 
                        <a href="<?php echo esc_url($pxl_server_info['support_url']); ?>" target="_blank">
                            <?php echo esc_html__('submit a ticket', 'portfoliocraft'); ?>
                        </a>
                    </p>
                </div>
                <?php 
                $this->form(); 
            }
        }
    }
       
    /**
     * Display Registration Form
     * 
     * Outputs the HTML form for license registration
     * with proper WordPress settings integration
     * 
     * @since 1.0.0
     * @return void
     */
    function form() {
        $strings = $this->strings;
        $license = trim(get_option($this->theme_slug . '_purchase_code'));
        $status = get_option($this->theme_slug . '_purchase_code_status', false);

        ?>
        <form action="options.php" method="post" class="pxl-dsb-register-form">
            <?php settings_fields($this->theme_slug . '-license'); ?>
            
            <!-- Purchase code input field -->
            <input id="<?php echo esc_attr($this->theme_slug); ?>_purchase_code" 
                   name="<?php echo esc_attr($this->theme_slug); ?>_purchase_code" 
                   type="text" 
                   value="<?php echo esc_attr($license); ?>" 
                   placeholder="<?php esc_attr_e('Enter your purchase code', 'portfoliocraft'); ?>">
            
            <!-- Registration submit button -->
            <input type="submit" 
                   class="res-purchase-code" 
                   value="<?php esc_attr_e('Register your purchase code', 'portfoliocraft'); ?>">
        </form>
        <?php
    }
    
    /**
     * Register License Option
     * 
     * Registers the WordPress option used to store the license key
     * with proper sanitization callback
     * 
     * @since 1.0.0
     * @return void
     */
    function register_option() {
        register_setting(
            $this->theme_slug . '-license',
            $this->theme_slug . '_purchase_code',
            array($this, 'sanitize_license')
        );
    }
     
    /**
     * Sanitize License Input
     * 
     * Sanitizes license input and resets status when changed
     * Ensures license revalidation when new code is entered
     * 
     * @param string $new New license value
     * @return string Sanitized license value
     */
    function sanitize_license($new) {
        $old = get_option($this->theme_slug . '_purchase_code');

        // Reset license status if code has changed
        if ($old && $old != $new) {
            delete_option($this->theme_slug . '_purchase_code_status');
        }

        return $new;
    }

    /**
     * Remove License Key
     * 
     * Handles license removal requests by communicating with API
     * and clearing local license data
     * 
     * @return void
     */
    function remove_key() {
        // Check for removal action
        if (isset($_POST['action']) && sanitize_text_field($_POST['action']) === 'removekey') {
            
            $purchase_code = trim(get_option($this->theme_slug . '_purchase_code'));
            
            // Prepare API request for license removal
            $api_params = array(
                'action'  => 'remove_license',
                'license' => $purchase_code,
                'url'     => rawurlencode(get_home_url())
            );
              
            // Make API request to remove license
            $license_data = $this->get_api_response($api_params);
             
            // Clear local license data if removal was successful
            if (true === $license_data->success) {
                delete_option($this->theme_slug . '_purchase_code');
                delete_option($this->theme_slug . '_purchase_code_status');
            }
        }
    }

    /**
     * Make API Request
     * 
     * Communicates with remote API server for license operations
     * Handles timeouts and error responses gracefully
     * 
     * @since 1.0.0
     * @param array $api_params Parameters for API request
     * @return object|false Decoded JSON response or false on error
     */
    function get_api_response($api_params) {

        // Make remote API request with timeout and SSL verification disabled
        $response = wp_remote_get(
            add_query_arg($api_params, $this->remote_api_url),
            array('timeout' => 15, 'sslverify' => false)
        );

        // Handle request errors
        if (is_wp_error($response)) {
            return false;
        }

        // Decode and return JSON response
        $response = json_decode(wp_remote_retrieve_body($response));
        return $response;
    }

    /**
     * Disable WordPress.org Update Requests
     * 
     * Prevents WordPress from checking wp.org for theme updates
     * when using custom update system
     * 
     * @since 1.0.0
     * @param array $r Request arguments
     * @param string $url Request URL
     * @return array Modified request arguments
     */
    function disable_wporg_request($r, $url) {

        // Only filter WordPress.org theme update requests
        if (0 !== strpos($url, 'https://api.wordpress.org/themes/update-check/1.1/')) {
            return $r;
        }

        // Decode the JSON request body
        $themes = json_decode($r['body']['themes']);

        // Remove active parent and child themes from update check
        $parent = get_option('template');
        $child = get_option('stylesheet');
        unset($themes->themes->$parent);
        unset($themes->themes->$child);

        // Encode the modified request body
        $r['body']['themes'] = json_encode($themes);

        return $r;
    }
}

// Initialize the registration system
new portfoliocraft_Register;
