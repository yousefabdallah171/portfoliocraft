<?php

/**
 * portfoliocraft Theme Functions
 *
 * Handles theme initialization, script and style enqueueing, required file inclusion, and widget loading.
 *
 * @package portfoliocraft-Themes
 * @since 1.0
 */

// Load theme text domain for translations
// This function loads the translation files for the theme to enable localization
add_action('init', 'portfoliocraft_load_theme_textdomain');
function portfoliocraft_load_theme_textdomain() {
    load_theme_textdomain('portfoliocraft', get_template_directory() . '/languages');
}

// Include main theme class
// This includes the main class file which contains core theme functionality
require_once get_template_directory() . '/inc/classes/class-main.php';

// Load admin files only in admin area
// This condition ensures admin-specific files are loaded only in the WordPress admin dashboard
if (is_admin()) {
    require_once get_template_directory() . '/inc/admin/admin-init.php';
}

// Include required theme folders
// These calls load various theme components such as core functions, classes, theme options, and widgets
portfoliocraft()->require_folder('inc');
portfoliocraft()->require_folder('inc/classes');
portfoliocraft()->require_folder('inc/theme-options');
portfoliocraft()->require_folder('template-parts/widgets');

// Include Fixed Global Colors system (no conflicts)

// Load WooCommerce integration if WooCommerce is active
// This conditionally loads WooCommerce related files if the WooCommerce plugin is installed and active
if (class_exists('WooCommerce')) {
    portfoliocraft()->require_folder('woocommerce');
}

// Enqueue theme styles and scripts
// This function enqueues all necessary CSS stylesheets for the theme
function portfoliocraft_enqueue_scripts() {
    // Enqueue critical CSS first (inline for performance)
    wp_enqueue_style('portfoliocraft-variables', get_template_directory_uri() . '/assets/css/variables.css', array(), '1.0.0');
    
    // Enqueue main theme stylesheet
    wp_enqueue_style('portfoliocraft-style', get_stylesheet_uri(), array('portfoliocraft-variables'), wp_get_theme()->get('Version'));

    // Enqueue widgets CSS with async loading for non-critical CSS
    wp_enqueue_style('portfoliocraft-widgets', get_template_directory_uri() . '/assets/css/widgets.css', array('portfoliocraft-style'), '1.0.0', 'all');

    // Conditionally enqueue homepage-specific styles if on the homepage or using the home template
    if (is_front_page() || is_page_template('home.php')) {
        wp_enqueue_style('portfoliocraft-homepage', get_template_directory_uri() . '/assets/css/home.css', array('portfoliocraft-style'), '1.0.0');
    }
}
add_action('wp_enqueue_scripts', 'portfoliocraft_enqueue_scripts');

// Optimize Google Fonts loading
// This function preloads Google Fonts for better performance
function portfoliocraft_preload_fonts() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">';
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>';
    echo '<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">';
}
add_action('wp_head', 'portfoliocraft_preload_fonts', 1);

// Remove unused CSS/JS for better performance
// This function dequeues unused assets on pages where they're not needed
function portfoliocraft_dequeue_unused_assets() {
    // Remove homepage CSS on non-homepage pages
    if (!is_front_page() && !is_page_template('home.php')) {
        wp_dequeue_style('portfoliocraft-homepage');
    }
    
    // Remove widgets CSS on pages without widgets
    if (!is_active_sidebar('sidebar-main') && !is_active_sidebar('sidebar-shop')) {
        wp_dequeue_style('portfoliocraft-widgets');
    }
}
add_action('wp_enqueue_scripts', 'portfoliocraft_dequeue_unused_assets', 100);

// Create default navigation menu
// This function creates a default main menu if it does not already exist
function portfoliocraft_create_default_menu() {
    $menu_name = 'Main Menu';
    $menu_exists = wp_get_nav_menu_object($menu_name);

    if (!$menu_exists) {
        // Create the menu
        $menu_id = wp_create_nav_menu($menu_name);

        // Add Home menu item as the first item
        wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => __('Home', 'portfoliocraft'),
            'menu-item-url' => home_url('/'),
            'menu-item-status' => 'publish'
        ));

        // Assign the menu to the primary menu location
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }
}
add_action('after_setup_theme', 'portfoliocraft_create_default_menu');

// Load Demo Import System (Priority-based: Local first, Remote fallback)
// Load Theme-Controlled Demo Import System (works with Rakmyat Core OCDI)
if (file_exists(get_template_directory() . '/inc/demo-import/demo-config.php')) {
    require_once get_template_directory() . '/inc/demo-import/demo-config.php';
    require_once get_template_directory() . '/inc/demo-import/theme-demo-controller.php';
}

