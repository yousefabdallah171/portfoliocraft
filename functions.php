<?php
/**
 * PortfolioCraft Theme Functions
 *
 * @package PortfolioCraft
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Suppress textdomain warnings for WordPress 6.7+
 * This is necessary because Redux Framework and theme options load before init hook
 */
add_filter('doing_it_wrong_trigger_error', function($trigger, $function) {
    if ($function === '_load_textdomain_just_in_time') {
        return false;
    }
    return $trigger;
}, 10, 2);

/**
 * Load textdomain immediately
 */
load_theme_textdomain('portfoliocraft', get_template_directory() . '/languages');

/**
 * Load Main Theme Class
 */
require_once get_template_directory() . '/inc/classes/class-main.php';

/**
 * Load Admin Files
 */
if (is_admin()) {
    require_once get_template_directory() . '/inc/admin/admin-init.php';
}

/**
 * Load Theme Components
 */
portfoliocraft()->require_folder('inc');
portfoliocraft()->require_folder('inc/classes');
portfoliocraft()->require_folder('inc/theme-options');
portfoliocraft()->require_folder('template-parts/widgets');

/**
 * Load WooCommerce Integration
 */
if (class_exists('WooCommerce')) {
    portfoliocraft()->require_folder('woocommerce');
}

/**
 * Load Demo Import System
 */
if (file_exists(get_template_directory() . '/inc/demo-import/demo-config.php')) {
    require_once get_template_directory() . '/inc/demo-import/demo-config.php';
    require_once get_template_directory() . '/inc/demo-import/theme-demo-controller.php';
}
