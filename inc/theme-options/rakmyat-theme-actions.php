<?php 
/**
 * Theme Action Hooks and Core Functionality
 *
 * This file contains all the action hooks and core functionality setup for the portfoliocraft theme.
 * It handles theme setup, widget registration, script/style enqueuing, and various other
 * WordPress integration points.
 *
 * @package portfoliocraft
 * @since 1.0.0
 */

add_action('init', 'portfoliocraft_load_textdomain', 1);
function portfoliocraft_load_textdomain(){
    load_theme_textdomain('portfoliocraft', get_template_directory() . '/languages');
}

add_action('after_setup_theme', 'portfoliocraft_setup');
function portfoliocraft_setup(){
    $GLOBALS['content_width'] = apply_filters('portfoliocraft_content_width', 1200);

    // Add theme support for various WordPress features
    add_theme_support('custom-header');
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('customize-selective-refresh-widgets');

    // Set default post thumbnail size
    set_post_thumbnail_size(1170, 710);

    // Register navigation menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Desktop', 'portfoliocraft'),
        'primary-mobile' => esc_html__('Primary Mobile', 'portfoliocraft'),
    ));

    // Add support for custom logo
    add_theme_support('custom-logo', array(
        'height'      => 250,
        'width'       => 250,
        'flex-width'  => true,
        'flex-height' => true,
    ));

    // Remove post-formats support if not used
    // add_theme_support('post-formats', array(''));

    // Add custom image size for portfolio
    add_image_size('portfoliocraft-portfolio', 600, 600, true);

    // Add WooCommerce support
    add_theme_support('woocommerce');
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');
    
    // Remove block editor widgets support
    remove_theme_support('widgets-block-editor');
}

/**
 * Register Widget Areas
 * 
 * Registers the theme's widget areas (sidebars) for different sections of the site.
 * Hooked to 'widgets_init' to ensure proper widget registration.
 * 
 * This function registers:
 * - Blog sidebar (always available)
 * - Page sidebar (if Redux Framework is active)
 * - Shop sidebar (if WooCommerce is active)
 */
add_action('widgets_init', 'portfoliocraft_widgets_position');
function portfoliocraft_widgets_position() {
    // Register blog sidebar
    register_sidebar(array(
        'name'          => esc_html__('Blog Sidebar', 'portfoliocraft'),
        'id'            => 'sidebar-blog',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title"><span>',
        'after_title'   => '</span></h2>',
    ));

    // Register page sidebar unconditionally
    register_sidebar(array(
        'name'          => esc_html__('Page Sidebar', 'portfoliocraft'),
        'id'            => 'sidebar-page',
        'before_widget' => '<section id="%1$s" class="widget %2$s">',
        'after_widget'  => '</section>',
        'before_title'  => '<h2 class="widget-title"><span>',
        'after_title'   => '</span></h2>',
    ));

    // FIX: Use correct class name for WooCommerce
    if (class_exists('WooCommerce')) {
        register_sidebar(array(
            'name'          => esc_html__('Shop Sidebar', 'portfoliocraft'),
            'id'            => 'sidebar-shop',
            'before_widget' => '<section id="%1$s" class="widget %2$s">',
            'after_widget'  => '</section>',
            'before_title'  => '<h2 class="widget-title"><span>',
            'after_title'   => '</span></h2>',
        ));
    }
}

/**
 * Enqueue Front-End Scripts and Styles
 * 
 * Handles the loading of all theme scripts and styles for the front-end.
 * Hooked to 'wp_enqueue_scripts' to ensure proper script/style loading.
 * 
 * This function:
 * - Loads animation libraries (GSAP, ScrollTrigger, Three.js)
 * - Enqueues slider libraries (Swiper)
 * - Registers optional libraries (CircleType, Tilt)
 * - Loads utility scripts (Magnific Popup, WOW.js)
 * - Enqueues theme styles and scripts
 * - Handles WooCommerce integration
 * - Adds AJAX support
 */
add_action('wp_enqueue_scripts', 'portfoliocraft_scripts');
function portfoliocraft_scripts() {  
    $portfoliocraft_version = wp_get_theme(get_template());

    // Load GSAP and related scripts if smooth scroll is enabled
    $smooth_scroll = portfoliocraft()->get_theme_opt('smooth_scroll', 'off');
    if($smooth_scroll === 'on') {
        wp_enqueue_script('scroll-smoother', get_template_directory_uri() . '/assets/js/gsap/ScrollSmoother.min.js', array('jquery'), '3.12.5', true);
    }
    
    // Enqueue core animation libraries
    wp_enqueue_script('gsap', get_template_directory_uri() . '/assets/js/gsap/gsap.min.js', array('jquery'), '3.12.5', true);
    wp_enqueue_script('scroll-trigger', get_template_directory_uri() . '/assets/js/gsap/ScrollTrigger.min.js', array('jquery'), '3.12.5', true);

    // Enqueue animation libraries
    wp_enqueue_style('wow-animate', get_template_directory_uri() . '/assets/css/animate.min.css', array(), '1.1.0');
    wp_enqueue_script('wow-animate', get_template_directory_uri() . '/assets/js/libs/wow.min.js', array('jquery'), '1.0.0', true);

    // Enqueue WooCommerce scripts if WooCommerce is active
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('rmt-woocommerce', get_template_directory_uri() . '/woocommerce/js/woocommerce.js', array('jquery'), $portfoliocraft_version->get('Version'), true);
    }

    // Register cookie handling script
    wp_register_script('rmt-cookie', get_template_directory_uri() . '/assets/js/libs/cookie.js', array('jquery'), '1.4.1', true);

    // Enqueue theme styles
    $r = rand();
    wp_enqueue_style('rmt-grid', get_template_directory_uri() . '/assets/css/grid.css', array(), $portfoliocraft_version->get('Version'));
    wp_enqueue_style('rmt-style', get_template_directory_uri() . '/assets/css/style.css', array(), $r);
    wp_add_inline_style('rmt-style', portfoliocraft_inline_styles());
    
    // Enqueue text color variables CSS
    wp_enqueue_style('portfoliocraft-text-colors', get_template_directory_uri() . '/assets/css/text-color-variables.css', array('rmt-style'), $portfoliocraft_version->get('Version'));
    wp_add_inline_style('portfoliocraft-text-colors', portfoliocraft_inline_styles());
    
    wp_enqueue_style('rmt-base', get_template_directory_uri() . '/style.css', array(), $portfoliocraft_version->get('Version'));
    wp_enqueue_style('rmt-google-fonts', portfoliocraft_fonts_url(), array(), null);

    // Enqueue theme scripts
    wp_enqueue_script('rmt-main', get_template_directory_uri() . '/assets/js/theme.js', array('jquery'), $portfoliocraft_version->get('Version'), true);
    wp_enqueue_script('rmt-menu', get_template_directory_uri() . '/assets/js/menu.js', array('jquery'), $portfoliocraft_version->get('Version'), true);

    // Localize script with AJAX URL
    wp_localize_script('rmt-main', 'main_data', array('ajax_url' => admin_url('admin-ajax.php')));

    // Enqueue comment reply script if needed
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }

    // Allow plugins to add their scripts
    do_action('portfoliocraft_scripts');
}

/**
 * Enqueue Back-End Scripts and Styles
 * 
 * Handles the loading of all theme scripts and styles for the admin area.
 * Hooked to 'admin_enqueue_scripts' to ensure proper script/style loading.
 * 
 * This function:
 * - Loads admin-specific styles
 * - Enqueues icon fonts
 * - Adds Magnific Popup for admin interface
 */
add_action('admin_enqueue_scripts', 'portfoliocraft_admin_style');
function portfoliocraft_admin_style() {
    $theme = wp_get_theme(get_template());
    // Using Font Awesome for GPL compatibility
    wp_enqueue_style('font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/css/all.min.css', array(), '6.0.0');

    wp_enqueue_style('magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css', array(), '1.1.0');
    wp_enqueue_script('magnific-popup', get_template_directory_uri() . '/assets/js/libs/magnific-popup.min.js', array('jquery'), '1.1.0', true);
}

/**
 * Enqueue Elementor Editor Scripts and Styles
 * 
 * Loads additional styles for the Elementor editor interface.
 * This ensures consistent styling between the front-end and Elementor editor.
 */
add_action('elementor/editor/before_enqueue_scripts', function() {
    // Using Font Awesome for GPL compatibility
    wp_enqueue_style('elementor-font-awesome', get_template_directory_uri() . '/assets/fonts/font-awesome/css/all.min.css', array(), '6.0.0');
});

/**
 * Add Pingback Header
 * 
 * Adds a pingback url auto-discovery header for singularly identifiable articles.
 * Hooked to 'wp_head' to ensure it's added to the document head.
 * 
 * This is used for trackback/pingback functionality in WordPress.
 */
add_action('wp_head', 'portfoliocraft_pingback_header');
function portfoliocraft_pingback_header() {
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
}

/**
 * Hidden Panel Template Hook
 * 
 * Hook for displaying hidden panel templates.
 * These templates are used for off-canvas menus, search panels, etc.
 * 
 * @return void
 */
add_action('rmt_anchor_target', 'portfoliocraft_hook_anchor_templates_hidden_panel');
function portfoliocraft_hook_anchor_templates_hidden_panel() {
    $hidden_templates = portfoliocraft_get_templates_slug('hidden-panel');
    if(empty($hidden_templates)) return;

    foreach ($hidden_templates as $slug => $values) {
        $args = [
            'slug' => $slug,
            'post_id' => $values['post_id']
        ];
        portfoliocraft_hook_anchor_hidden_panel($args);
    }
}

/**
 * Hidden Panel Template Display
 * 
 * Displays a specific hidden panel template.
 * 
 * @param array $args Template arguments including slug and post ID
 */
function portfoliocraft_hook_anchor_hidden_panel($args) {
    $slug = $args['slug'];
    $post_id = $args['post_id'];
    ?>
    <div id="<?php echo esc_attr($slug); ?>" class="rmt-hidden-panel">
        <div class="rmt-hidden-panel-inner">
            <?php echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post_id); ?>
        </div>
    </div>
    <?php
}

/**
 * Popup Template Hook
 * 
 * Hook for displaying popup templates.
 * These templates are used for modal windows and popups.
 * 
 * @return void
 */
add_action('rmt_anchor_target', 'portfoliocraft_hook_anchor_templates_popup');
function portfoliocraft_hook_anchor_templates_popup() {
    $popup_templates = portfoliocraft_get_templates_slug('popup');
    if(empty($popup_templates)) return;

    foreach ($popup_templates as $slug => $values) {
        $args = [
            'slug' => $slug,
            'post_id' => $values['post_id']
        ];
        portfoliocraft_hook_anchor_popup($args);
    }
}

/**
 * Popup Template Display
 * 
 * Displays a specific popup template.
 * 
 * @param array $args Template arguments including slug and post ID
 */
function portfoliocraft_hook_anchor_popup($args) {
    $slug = $args['slug'];
    $post_id = $args['post_id'];
    ?>
    <div id="<?php echo esc_attr($slug); ?>" class="rmt-popup">
        <div class="rmt-popup-inner">
            <?php echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post_id); ?>
        </div>
    </div>
    <?php
}

/**
 * Page Popup Template Hook
 * 
 * Hook for displaying page-specific popup templates.
 * These templates are used for page-specific modal windows.
 * 
 * @return void
 */
add_action('rmt_anchor_target', 'portfoliocraft_hook_anchor_templates_page_popup');
function portfoliocraft_hook_anchor_templates_page_popup() {
    $page_popup_templates = portfoliocraft_get_templates_slug('page-popup');
    if(empty($page_popup_templates)) return;

    foreach ($page_popup_templates as $slug => $values) {
        $args = [
            'slug' => $slug,
            'post_id' => $values['post_id']
        ];
        portfoliocraft_hook_anchor_page_popup($args);
    }
}

/**
 * Page Popup Template Display
 * 
 * Displays a specific page popup template.
 * 
 * @param array $args Template arguments including slug and post ID
 */
function portfoliocraft_hook_anchor_page_popup($args) {
    $slug = $args['slug'];
    $post_id = $args['post_id'];
    ?>
    <div id="<?php echo esc_attr($slug); ?>" class="rmt-page-popup">
        <div class="rmt-page-popup-inner">
            <?php echo \Elementor\Plugin::$instance->frontend->get_builder_content_for_display($post_id); ?>
        </div>
    </div>
    <?php
}

/**
 * Cart Template Hook
 * 
 * Hook for displaying the shopping cart template.
 * Used for WooCommerce integration.
 * 
 * @return void
 */
add_action('rmt_anchor_target', 'portfoliocraft_hook_anchor_cart');
function portfoliocraft_hook_anchor_cart() {
    if (!class_exists('WooCommerce')) return;
    if (is_cart()) {
        ?>
        <div id="rmt-cart" class="rmt-cart">
            <div class="rmt-cart-inner">
                <?php woocommerce_mini_cart(); ?>
            </div>
        </div>
        <?php
    }
}

/**
 * Added to Cart Message
 * 
 * Displays a sweet alert message when a product is added to cart.
 * Used for WooCommerce integration.
 * 
 * @return void
 */
add_action('wp_footer', 'portfoliocraft_addedtocart_sweet_message');
function portfoliocraft_addedtocart_sweet_message() {
    if (!class_exists('WooCommerce')) return;
    ?>
    <script>
        jQuery(document).ready(function($) {
            $(document.body).on('added_to_cart', function() {
                Swal.fire({
                    title: '<?php echo esc_html__('Added to cart!', 'portfoliocraft'); ?>',
                    text: '<?php echo esc_html__('Your product has been added to cart.', 'portfoliocraft'); ?>',
                    icon: 'success',
                    showConfirmButton: false,
                    timer: 1500
                });
            });
        });
    </script>
    <?php
}

/**
 * Cart Hidden Sidebar
 * 
 * Displays the cart in a hidden sidebar.
 * Used for WooCommerce integration.
 * 
 * @return void
 */
add_action('wp_footer', 'portfoliocraft_cart_hidden_sidebar');
function portfoliocraft_cart_hidden_sidebar() {
    if (!class_exists('WooCommerce')) return;
    ?>
    <div id="rmt-cart-sidebar" class="rmt-cart-sidebar">
        <div class="rmt-cart-sidebar-inner">
            <?php woocommerce_mini_cart(); ?>
        </div>
    </div>
    <?php
}

// Add default widgets to blog sidebar on theme activation
add_action('after_switch_theme', 'portfoliocraft_setup_default_widgets');
function portfoliocraft_setup_default_widgets() {
    // Get existing widgets
    $sidebars_widgets = get_option('sidebars_widgets');
    
    // Check if search widget is already in blog sidebar
    $has_search = false;
    if (!empty($sidebars_widgets['sidebar-blog'])) {
        foreach ($sidebars_widgets['sidebar-blog'] as $widget) {
            if (strpos($widget, 'search') !== false) {
                $has_search = true;
                break;
            }
        }
    }
    
    // Add search widget if it doesn't exist
    if (!$has_search) {
        // Get existing search widgets
        $search_widgets = get_option('widget_search');
        if (!is_array($search_widgets)) {
            $search_widgets = array();
        }
        
        // Add new search widget
        $search_widgets[] = array(
            'title' => esc_html__('Search', 'portfoliocraft')
        );
        
        // Update search widgets option
        update_option('widget_search', $search_widgets);
        
        // Add widget to sidebar
        if (!isset($sidebars_widgets['sidebar-blog'])) {
            $sidebars_widgets['sidebar-blog'] = array();
        }
        $sidebars_widgets['sidebar-blog'][] = 'search-' . (count($search_widgets) - 1);
        
        // Update sidebars widgets option
        update_option('sidebars_widgets', $sidebars_widgets);
    }
}

// Function to manually add search widget to blog sidebar
function portfoliocraft_add_search_widget_to_sidebar() {
    // Get existing widgets
    $sidebars_widgets = get_option('sidebars_widgets');
    
    // Check if search widget is already in blog sidebar
    $has_search = false;
    if (!empty($sidebars_widgets['sidebar-blog'])) {
        foreach ($sidebars_widgets['sidebar-blog'] as $widget) {
            if (strpos($widget, 'search') !== false) {
                $has_search = true;
                break;
            }
        }
    }
    
    // Only add search widget if it doesn't exist
    if (!$has_search) {
        // Get existing search widgets
        $search_widgets = get_option('widget_search');
        if (!is_array($search_widgets)) {
            $search_widgets = array();
        }
        
        // Add new search widget
        $search_widgets[] = array(
            'title' => esc_html__('Search', 'portfoliocraft')
        );
        
        // Update search widgets option
        update_option('widget_search', $search_widgets);
        
        // Add widget to sidebar
        if (!isset($sidebars_widgets['sidebar-blog'])) {
            $sidebars_widgets['sidebar-blog'] = array();
        }
        $sidebars_widgets['sidebar-blog'][] = 'search-' . (count($search_widgets) - 1);
        
        // Update sidebars widgets option
        update_option('sidebars_widgets', $sidebars_widgets);
    }
}

add_action('init', 'portfoliocraft_add_search_widget_to_sidebar', 20);

// Output Back to Top button CSS variables in the head
add_action('wp_head', function() {
    $bg = portfoliocraft()->get_opt('back_to_top_bg', '#222222');
    $color = portfoliocraft()->get_opt('back_to_top_color', '#ffffff');
    $bg_hover = portfoliocraft()->get_opt('back_to_top_bg_hover', '#444444');
    $color_hover = portfoliocraft()->get_opt('back_to_top_color_hover', '#ffffff');
    $size = portfoliocraft()->get_opt('back_to_top_size', 'medium');
    $size_custom = portfoliocraft()->get_opt('back_to_top_size_custom', array('width' => 48, 'height' => 48));
    $position = portfoliocraft()->get_opt('back_to_top_position', 'right');
    $width = '48px';
    $height = '48px';
    if ($size === 'small') { $width = $height = '36px'; }
    elseif ($size === 'medium') { $width = $height = '48px'; }
    elseif ($size === 'large') { $width = $height = '64px'; }
    elseif ($size === 'custom' && !empty($size_custom['width']) && !empty($size_custom['height'])) {
        $width = intval($size_custom['width']) . 'px';
        $height = intval($size_custom['height']) . 'px';
    }
    $side = $position === 'left' ? 'left' : 'right';
    if (portfoliocraft()->get_opt('back_to_top', false)) {
        echo '<style>:root{--rmt-back-to-top-bg:' . esc_attr($bg) . ';--rmt-back-to-top-color:' . esc_attr($color) . ';--rmt-back-to-top-bg-hover:' . esc_attr($bg_hover) . ';--rmt-back-to-top-color-hover:' . esc_attr($color_hover) . ';--rmt-back-to-top-width:' . esc_attr($width) . ';--rmt-back-to-top-height:' . esc_attr($height) . ';--rmt-back-to-top-side:' . esc_attr($side) . ';}</style>';
    }
});

// Unify body classes for CPT singles with post singles
if (!function_exists('portfoliocraft_unify_body_classes_for_cpt')) {
    add_filter('body_class', function(array $classes) {
        if (is_singular(['portfolio', 'services'])) {
            $classes[] = 'single-post';
            $classes[] = 'post-template-default';
            $classes[] = 'single-format-standard';
        }
        return array_values(array_unique($classes));
    });
}

// Unify post classes for CPT singles with post singles
if (!function_exists('portfoliocraft_unify_post_classes_for_cpt')) {
    add_filter('post_class', function(array $classes, $class = '', $post_id = 0) {
        if (is_singular(['portfolio', 'services'])) {
            $classes[] = 'post';
            $classes[] = 'type-post';
            $classes[] = 'format-standard';
        }
        return array_values(array_unique($classes));
    }, 10, 3);
}