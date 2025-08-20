<?php
/**
 * Theme Check Compliance and WordPress Standards
 *
 * This file ensures the theme meets WordPress standards and best practices.
 * It handles theme setup, block editor support, and various WordPress core features.
 *
 * @package portfoliocraft
 * @since 1.0.0
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Check Compliance Class
 * 
 * Singleton class that handles theme compatibility and WordPress standards compliance.
 * Ensures the theme follows WordPress best practices and supports modern features.
 * 
 * This class implements the singleton pattern to ensure only one instance exists,
 * preventing duplicate initialization of theme features and settings.
 * 
 * The class handles:
 * - Theme setup and initialization
 * - WordPress core feature support
 * - Block editor integration
 * - Navigation menus and widget areas
 * - Custom block styles and patterns
 */
class portfoliocraft_Theme_Check {
    /**
     * Instance
     *
     * @var portfoliocraft_Theme_Check
     */
    private static $instance = null;

    /**
     * Get instance
     * 
     * Implements singleton pattern to ensure only one instance exists.
     * This prevents duplicate initialization of theme features and settings.
     *
     * @return portfoliocraft_Theme_Check
     */
    public static function get_instance() {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Constructor
     * 
     * Sets up theme hooks for initialization and setup.
     * Hooks into WordPress core actions to ensure proper theme setup and functionality.
     * 
     * The constructor:
     * - Hooks into after_setup_theme for theme configuration
     * - Hooks into init for feature initialization
     * - Hooks into enqueue_block_editor_assets for editor styles
     */
    private function __construct() {
        add_action('after_setup_theme', array($this, 'setup_theme'));
        add_action('init', array($this, 'init'));
        add_action('enqueue_block_editor_assets', array($this, 'enqueue_editor_assets'));
    }

    /**
     * Theme setup
     * 
     * Configures theme support for various WordPress features and standards.
     * This includes:
     * - RSS feed links
     * - Document title management
     * - Post thumbnails
     * - Responsive embeds
     * - Block styles
     * - Editor styles
     * - Wide/full align images
     * - Custom logo
     * - Custom header
     * - Custom background
     * - HTML5 markup
     * - Widget refresh
     * - WooCommerce support
     * - RTL support
     * 
     * Each feature is added with appropriate configuration options
     * to ensure proper functionality and compatibility.
     */
    public function setup_theme() {
        // Add default posts and comments RSS feed links to head
        add_theme_support('automatic-feed-links');

        // Let WordPress manage the document title
        add_theme_support('title-tag');

        // Enable support for Post Thumbnails on posts and pages
        add_theme_support('post-thumbnails');

        // Add support for responsive embeds
        add_theme_support('responsive-embeds');

        // Add support for Block Styles
        add_theme_support('wp-block-styles');

        // Add support for editor styles
        add_theme_support('editor-styles');

        // Add support for full and wide align images
        add_theme_support('align-wide');

        // Add support for custom logo
        add_theme_support('custom-logo', array(
            'height'      => 250,
            'width'       => 250,
            'flex-width'  => true,
            'flex-height' => true,
        ));

        // Add support for custom header
        add_theme_support('custom-header', array(
            'default-image' => '',
            'width'         => 1600,
            'height'        => 250,
            'flex-width'    => true,
            'flex-height'   => true,
        ));

        // Add support for custom background
        add_theme_support('custom-background', array(
            'default-color' => 'ffffff',
            'default-image' => '',
        ));

        // Add support for HTML5 markup
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ));

        // Add support for selective refresh for widgets
        add_theme_support('customize-selective-refresh-widgets');

        // Add WooCommerce support
        add_theme_support('woocommerce');
        add_theme_support('wc-product-gallery-zoom');
        add_theme_support('wc-product-gallery-lightbox');
        add_theme_support('wc-product-gallery-slider');

        // Add RTL support
        add_theme_support('rtl');

        // Add editor styles
        add_editor_style('assets/css/editor-style.css');
    }

    /**
     * Initialize theme features
     * 
     * Sets up navigation menus, widget areas, and block patterns.
     * This includes:
     * - Primary and footer navigation menus
     * - Default sidebar widget area
     * - Custom block styles
     * - Block patterns for common layouts
     * 
     * The function:
     * - Registers navigation menus for different locations
     * - Sets up widget areas with proper markup
     * - Adds custom block styles for enhanced typography
     * - Registers block patterns for common layouts
     */
    public function init() {
        // Register navigation menus
        register_nav_menus(array(
            'primary' => esc_html__('Primary Menu', 'portfoliocraft'),
            'footer'  => esc_html__('Footer Menu', 'portfoliocraft'),
        ));

        // Register default sidebar
        register_sidebar(array(
            'name'          => esc_html__('Sidebar', 'portfoliocraft'),
            'id'            => 'sidebar-1',
            'description'   => esc_html__('Add widgets here.', 'portfoliocraft'),
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ));

        // Register custom block styles
        register_block_style('core/paragraph', array(
            'name'         => 'lead',
            'label'        => __('Lead', 'portfoliocraft'),
            'inline_style' => '.wp-block-paragraph.is-style-lead { font-size: 1.25em; line-height: 1.6; }',
        ));

        // Register block patterns
        register_block_pattern(
            'portfoliocraft/hero-section',
            array(
                'title'       => __('Hero Section', 'portfoliocraft'),
                'description' => __('A hero section with title, description and button.', 'portfoliocraft'),
                'categories'  => array('header'),
                'content'     => '<!-- wp:group {"className":"pxl-hero-section"} -->
                <div class="wp-block-group pxl-hero-section">
                    <!-- wp:heading {"level":1,"className":"pxl-hero-title"} -->
                    <h1 class="pxl-hero-title">Welcome to portfoliocraft</h1>
                    <!-- /wp:heading -->
                    
                    <!-- wp:paragraph {"className":"pxl-hero-description"} -->
                    <p class="pxl-hero-description">A modern WordPress theme for your business.</p>
                    <!-- /wp:paragraph -->
                    
                    <!-- wp:buttons -->
                    <div class="wp-block-buttons">
                        <!-- wp:button {"className":"pxl-hero-button"} -->
                        <div class="wp-block-button pxl-hero-button">
                            <a class="wp-block-button__link">Get Started</a>
                        </div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->',
            )
        );
    }

    /**
     * Enqueue editor assets
     * 
     * Loads custom styles for the block editor.
     * This ensures the editor matches the front-end appearance
     * and provides a consistent editing experience.
     * 
     * The function:
     * - Enqueues the editor style CSS file
     * - Ensures proper versioning for cache busting
     * - Maintains consistent styling between editor and front-end
     */
    public function enqueue_editor_assets() {
        wp_enqueue_style(
            'portfoliocraft-editor-style',
            get_template_directory_uri() . '/assets/css/editor-style.css',
            array(),
            '1.0.0'
        );
    }
}

// Initialize the theme check class
portfoliocraft_Theme_Check::get_instance(); 