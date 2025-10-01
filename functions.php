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
require_once get_template_directory() . '/inc/classes/class-main.php';
if (is_admin()) {
    require_once get_template_directory() . '/inc/admin/admin-init.php';
}
portfoliocraft()->require_folder('inc');

require_once get_template_directory() . '/inc/classes/class-base.php';
require_once get_template_directory() . '/inc/classes/class-header.php';
require_once get_template_directory() . '/inc/classes/class-footer.php';

add_action('init', function() {
    require_once get_template_directory() . '/inc/classes/class-blog.php';
    require_once get_template_directory() . '/inc/classes/class-page.php';
    require_once get_template_directory() . '/inc/classes/class-breadcrumb.php';
    
    portfoliocraft()->require_folder('inc/theme-options');
    portfoliocraft()->require_folder('template-parts/widgets');
    if (class_exists('WooCommerce')) {
        portfoliocraft()->require_folder('woocommerce');
    }
    if (file_exists(get_template_directory() . '/inc/demo-import/demo-config.php')) {
        require_once get_template_directory() . '/inc/demo-import/demo-config.php';
        require_once get_template_directory() . '/inc/demo-import/theme-demo-controller.php';
    }
}, 10);