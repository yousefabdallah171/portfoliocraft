<?php
/**
 * portfoliocraft Base Class
 * 
 * This file contains the base class for the portfoliocraft theme
 * Provides core functionality and helper methods for WordPress hooks
 * 
 * @package portfoliocraft-Themes
 * @version 1.0.0
 */

// Security: Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

/**
 * Base Class for portfoliocraft Theme
 * 
 * Provides foundational methods for WordPress hook management
 * This class serves as a parent class for other theme components
 * Simplifies the process of adding actions and filters with proper context
 */
if (!class_exists('portfoliocraft_Base')) :

class portfoliocraft_Base {

    /**
     * Add WordPress Action Hook
     * 
     * Wrapper method for WordPress add_action function
     * Automatically binds the callback to the current class instance
     * 
     * @param string $hook The WordPress action hook name
     * @param string $function_to_add The method name to call from this class
     * @param int $priority Optional. Priority level for the hook (default: 10)
     * @param int $accepted_args Optional. Number of arguments the method accepts (default: 1)
     * @return void
     */
    public function add_action($hook, $function_to_add, $priority = 10, $accepted_args = 1) {
        add_action($hook, array(&$this, $function_to_add), $priority, $accepted_args);
    }

    /**
     * Add WordPress Filter Hook
     * 
     * Wrapper method for WordPress add_filter function
     * Automatically binds the callback to the current class instance
     * 
     * @param string $tag The WordPress filter hook name
     * @param string $function_to_add The method name to call from this class
     * @param int $priority Optional. Priority level for the hook (default: 10)
     * @param int $accepted_args Optional. Number of arguments the method accepts (default: 1)
     * @return void
     */
    public function add_filter($tag, $function_to_add, $priority = 10, $accepted_args = 1) {
        add_filter($tag, array(&$this, $function_to_add), $priority, $accepted_args);
    }
}

endif;

// Helper Functions Section ---------------------------------------

/**
 * rmt Action Helper Function
 * 
 * Provides a convenient way to trigger custom theme actions
 * Automatically prefixes action names with 'rmttheme_' for consistency
 * Supports variable number of arguments to pass to the action
 * 
 * Usage: rmt_action('init', $arg1, $arg2, ...);
 * This will trigger: do_action('rmttheme_init', $arg1, $arg2, ...);
 * 
 * @return void
 */
if (!function_exists('rmt_action')) :
    function rmt_action() {

        // Get all arguments passed to this function
        $args = func_get_args();

        // Validate that we have at least one argument (the action name)
        if (!isset($args[0]) || empty($args[0])) {
            return;
        }

        // Build the full action name with theme prefix
        $action = 'rmttheme_' . $args[0];
        
        // Remove the action name from arguments array
        // Remaining arguments will be passed to the action
        unset($args[0]);

        // Trigger the WordPress action with all remaining arguments
        do_action_ref_array($action, $args);
    }
endif;
