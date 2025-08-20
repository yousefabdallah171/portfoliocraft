<?php
/**
 * Theme Utility Functions and Helper Methods
 *
 * This file contains essential utility functions used throughout the portfoliocraft theme.
 * It includes functions for:
 * - HTML content handling and security
 * - Google Fonts management and optimization
 * - Content retrieval and display utilities
 * - Comment template customization
 * - Pagination and AJAX functionality
 * - Color conversion utilities
 * - Search form customization
 * - Shortcodes for text and image highlighting
 * - User field management and social media
 * - Image size handling and optimization
 * - Cookie policy and GDPR compliance
 * - Mouse movement animations
 * - Mega menu and popup builders
 *
 * @package portfoliocraft-Themes
 * @since 1.0.0
 * @author portfoliocraft-Themes
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

/* ==========================================================================
   HTML Content and Security Functions
   ========================================================================== */

/**
 * Safely output HTML content
 * 
 * WARNING: Only use this for trusted content. Do not use with untrusted user input.
 *
 * @param string $html The HTML content to return
 * @return string The unmodified HTML content
 * @since 1.0.0
 */
function portfoliocraft_html($html) {
    return $html;
}

/* ==========================================================================
   Google Fonts Management
   ========================================================================== */

/**
 * Generate Google Fonts URL with performance optimization
 * 
 * Manages the loading of Google Fonts used in the theme with display=swap for performance.
 * Supports multiple font families with various weights and styles:
 * - Montserrat (100-900) - Modern sans-serif for headings
 * - Kanit (100-900, italic) - Thai-inspired font for unique styling
 * - Audiowide (100-800) - Futuristic display font
 * - Sora (100-800) - Clean geometric sans-serif
 * - Inter (100-900, italic, optical sizes) - Optimized for UI
 * - Plus Jakarta Sans (200-800, italic) - Humanist sans-serif
 * - Teko (300-700) - Condensed sans-serif for headers
 * - Playfair Display (400-900, italic) - Elegant serif for luxury feel
 * - Roboto (100-900, italic) - Google's signature font
 * - Public Sans (100-900, italic) - Government-grade accessibility font
 *
 * @return string The Google Fonts URL with selected fonts
 * @since 1.0.0
 */
function portfoliocraft_fonts_url() {
    $fonts_url = '';
    $fonts = array();
    $subsets = 'latin,latin-ext';   

    // Check each font family and add if enabled
    $font_families = array(
        'Montserrat' => 'Montserrat:wght@100..900&display=swap',
        'Kanit' => 'Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap',
        'Audiowide' => 'Audiowide:wght@100..800&display=swap',
        'Sora' => 'Sora:wght@100..800&display=swap',
        'Inter' => 'Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap',
        'Plus Jakarta Sans' => 'Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap',
        'Teko' => 'Teko:wght@300..700&display=swap',
        'Playfair Display' => 'Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap',
        'Roboto' => 'Roboto:ital,wght@0,100..900;1,100..900&display=swap',
        'Public Sans' => 'Public+Sans:ital,wght@0,100..900;1,100..900&display=swap'
    );

    foreach ($font_families as $font_name => $font_url) {
        if ('off' !== _x('on', $font_name . ' font: on or off', 'portfoliocraft')) {
            $fonts[] = $font_url;
        }
    }

    // Build the Google Fonts URL if fonts are selected
    if ($fonts) {
        $fonts_url = add_query_arg(array(
            'family' => implode('&family=', $fonts),
            'subset' => urlencode($subsets),
        ), '//fonts.googleapis.com/css2?');
    }
    
    return $fonts_url;
}

/* ==========================================================================
   Content Retrieval and Display Functions
   ========================================================================== */

/**
 * Get page/post ID by slug
 * 
 * Retrieves the ID of a page or post by its slug.
 * Useful for dynamic content loading and template parts.
 *
 * @param string $slug The page/post slug
 * @param string $post_type The post type (default: 'page')
 * @return int|false The page/post ID or false if not found
 * @since 1.0.0
 */
function portfoliocraft_get_id_by_slug($slug, $post_type = 'page') {
    $content = get_page_by_path($slug, OBJECT, $post_type);
    return $content ? $content->ID : false;
}

/**
 * Display content by slug
 * 
 * Outputs the content of a page or post by its slug.
 * Applies WordPress content filters for proper formatting including shortcodes.
 *
 * @param string $slug The content slug
 * @param string $post_type The post type
 * @since 1.0.0
 */
function portfoliocraft_content_by_slug($slug, $post_type = 'page') {
    $content = portfoliocraft_get_content_by_slug($slug, $post_type);
    if ($content) {
        echo apply_filters('the_content', $content);
    }
}

/**
 * Get content by slug
 * 
 * Retrieves the raw content of a page or post by its slug.
 * Used internally by portfoliocraft_content_by_slug().
 *
 * @param string $slug The content slug
 * @param string $post_type The post type
 * @return string|false The content or false if not found
 * @since 1.0.0
 */
function portfoliocraft_get_content_by_slug($slug, $post_type = 'page') {
    $content = get_posts(array(
        'name' => $slug,
        'post_type' => $post_type,
        'numberposts' => 1,
        'post_status' => 'publish'
    ));
    
    return !empty($content) ? $content[0]->post_content : false;
}

/* ==========================================================================
   Comment System Customization
   ========================================================================== */

/**
 * Custom comment list template
 * 
 * Provides a custom template for displaying comments with enhanced styling.
 * Includes:
 * - Comment author avatar with fallback
 * - Author name with proper linking
 * - Formatted comment date
 * - Comment content with proper escaping
 * - Threaded reply functionality
 * - Proper HTML structure for nested comments
 * - Accessibility improvements
 *
 * @param WP_Comment $comment The comment object
 * @param array $args The comment arguments
 * @param int $depth The comment depth for threading
 * @since 1.0.0
 */
function portfoliocraft_comment_list($comment, $args, $depth) {
    if ('div' === $args['style']) {
        $tag = 'div';
        $add_below = 'comment';
    } else {
        $tag = 'li';
        $add_below = 'div-comment';
    }
    ?>
    <<?php echo esc_html($tag); ?> <?php comment_class(empty($args['has_children']) ? '' : 'parent'); ?> id="comment-<?php comment_ID(); ?>">
    <?php if ('div' != $args['style']) : ?>
        <div id="div-comment-<?php comment_ID(); ?>" class="comment-box">
    <?php endif; ?>
        <div class="comment-inner">
            <?php if ($args['avatar_size'] != 0) : ?> 
                <div class="comment-image">
                    <?php echo get_avatar($comment, 90, '', '', array('loading' => 'lazy')); ?>
                </div>
            <?php endif; ?>
            <div class="comment-content">
                <div class="comment-header">
                    <div class="comment-user">
                        <?php printf('%s', get_comment_author_link()); ?>
                    </div>
                    <time class="comment-date" datetime="<?php echo esc_attr(get_comment_date('c')); ?>">
                        <?php echo esc_html(get_comment_date()); ?>
                    </time>
                </div>
                <div class="comment-text">
                    <?php comment_text(); ?>
                    <?php if ($comment->comment_approved == '0') : ?>
                        <p class="comment-awaiting-moderation">
                            <?php esc_html_e('Your comment is awaiting moderation.', 'portfoliocraft'); ?>
                        </p>
                    <?php endif; ?>
                </div>
                <div class="comment-reply">
                    <?php 
                    comment_reply_link(array_merge($args, array(
                        'add_below' => $add_below,
                        'depth' => $depth,
                        'max_depth' => $args['max_depth'],
                        'reply_text' => esc_html__('Reply', 'portfoliocraft')
                    ))); 
                    ?>
                </div>
            </div>
        </div>
    <?php if ('div' != $args['style']) : ?>
        </div>
    <?php endif;
}

/* ==========================================================================
   Pagination and AJAX Functions
   ========================================================================== */

/**
 * Modify pagination links for AJAX functionality
 * 
 * Converts standard pagination links to AJAX-compatible format.
 * Used for dynamic content loading without page refresh.
 * Improves user experience and page performance.
 *
 * @param string $link The pagination link
 * @return string The modified link for AJAX pagination
 * @since 1.0.0
 */
function portfoliocraft_ajax_paginate_links($link) {
    $parts = parse_url($link);
    if (!isset($parts['query'])) {
        return $link;
    }
    
    parse_str($parts['query'], $query);
    if (isset($query['page']) && !empty($query['page'])) {
        return '#' . intval($query['page']);
    } else {
        return '#1';
    }
}

/* ==========================================================================
   Color Utility Functions
   ========================================================================== */

/**
 * Convert hex color to RGB
 * 
 * Converts hexadecimal color codes to RGB format for CSS usage.
 * Handles both 3-digit and 6-digit hex codes with validation.
 * Used for dynamic color manipulation and transparency effects.
 *
 * @param string $color The hex color code (with or without #)
 * @return string The RGB color value (e.g., "255,255,255")
 * @since 1.0.0
 */
function portfoliocraft_hex_rgb($color) {
    $default = '0,0,0';
 
    // Return default if no color provided
    if (empty($color)) {
        return $default;
    }
 
    // Sanitize $color if "#" is provided 
    if ($color[0] == '#') {
        $color = substr($color, 1);
    }

    // Check if color has 6 or 3 characters and get values
    if (strlen($color) == 6) {
        $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
    } elseif (strlen($color) == 3) {
        $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
    } else {
        return $default;
    }

    // Convert hexadecimal to RGB
    $rgb = array_map('hexdec', $hex);
    
    // Return RGB color string
    return implode(',', $rgb);
}

/* ==========================================================================
   Search Form Functions
   ========================================================================== */

/**
 * Get custom search form
 * 
 * Retrieves the theme's custom search form template.
 * Ensures consistent search functionality across the site.
 *
 * @return string The search form HTML
 * @since 1.0.0
 */
function portfoliocraft_get_search_form() {
    return get_search_form(false);
}

/**
 * Display mobile header search form
 * 
 * Outputs a search form optimized for mobile header menu.
 * Includes styled search input and submit button with icon.
 * Provides better mobile user experience.
 *
 * @since 1.0.0
 */
function portfoliocraft_header_mobile_search_form() {
    ?>
    <div class="pxl-header-search">
        <?php get_search_form(); ?>
    </div>
    <?php
}

/* ==========================================================================
   Shortcode Functions
   ========================================================================== */

// Text highlight shortcode
if (function_exists('pxl_register_shortcode')) {
    /**
     * Text highlight shortcode
     * 
     * Creates a highlighted text effect using a shortcode.
     * Used for emphasizing important content with custom styling.
     * Usage: [highlight text="Your highlighted text"]
     *
     * @param array $atts Shortcode attributes
     * @return string The highlighted text HTML
     * @since 1.0.0
     */
    function portfoliocraft_text_highlight_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'text' => '',
        ), $atts, 'highlight');

        if (empty($atts['text'])) {
            return '';
        }

        return '<span class="pxl-text-highlight">' . wp_kses_post($atts['text']) . '</span>';
    }
    pxl_register_shortcode('highlight', 'portfoliocraft_text_highlight_shortcode');
}

// Text hidden shortcode
if (function_exists('pxl_register_shortcode')) {
    /**
     * Text hidden shortcode
     * 
     * Creates a hidden text effect that reveals on hover or interaction.
     * Used for interactive content elements and surprise reveals.
     * Usage: [hidden_text text="Your hidden text"]
     *
     * @param array $atts Shortcode attributes
     * @return string The hidden text HTML
     * @since 1.0.0
     */
    function portfoliocraft_text_hidden_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'text' => '',
        ), $atts, 'hidden_text');

        if (empty($atts['text'])) {
            return '';
        }

        return '<span class="pxl-text-hidden">' . wp_kses_post($atts['text']) . '</span>';
    }
    pxl_register_shortcode('hidden_text', 'portfoliocraft_text_hidden_shortcode');
}

// Image highlight shortcode
if (function_exists('pxl_register_shortcode')) {
    /**
     * Image highlight shortcode
     * 
     * Creates a highlighted image effect using a shortcode.
     * Supports both regular images and SVG files with proper handling.
     * Usage: [highlight_image img_id="123"]
     *
     * @param array $atts Shortcode attributes
     * @return string The highlighted image HTML
     * @since 1.0.0
     */
    function portfoliocraft_image_highlight_shortcode($atts = array()) {
        $atts = shortcode_atts(array(
            'img_id' => '',
        ), $atts, 'highlight_image');

        if (empty($atts['img_id'])) {
            return '';
        }

        global $wp_filesystem;
        
        if (function_exists('pxl_get_image_by_size')) {
            $img = pxl_get_image_by_size(array(
                'attach_id' => $atts['img_id'],
                'thumb_size' => 'full',
            ));
            
            if ($img && isset($img['url'])) {
                // Handle SVG files differently
                if (pathinfo($img['url'], PATHINFO_EXTENSION) === 'svg') {
                    $img_urls = explode('uploads', $img['url']);
                    if (count($img_urls) > 1) {
                        $upload_dir = wp_upload_dir();
                        $img_path = $upload_dir['basedir'] . $img_urls[1];
                        
                        if ($wp_filesystem && $wp_filesystem->exists($img_path)) {
                            $img_content = $wp_filesystem->get_contents($img_path);
                            return $img_content;
                        }
                    }
                } else {
                    return '<span class="pxl-image-highlight" style="background-image: url(' . esc_url($img['url']) . ');"></span>';
                }
            }
        }
        
        return '';
    }
    pxl_register_shortcode('highlight_image', 'portfoliocraft_image_highlight_shortcode');
}

/* ==========================================================================
   Widget Enhancement Functions
   ========================================================================== */

/**
 * Archive count widget enhancement
 * 
 * Modifies archive widget links to include styled post counts.
 * Used in sidebar widgets and archive pages for better UX.
 *
 * @param string $links The archive links
 * @return string The modified links with styled counts
 * @since 1.0.0
 */
function portfoliocraft_wg_archive_count($links) {
    $links = str_replace('</a>&nbsp;(', ' <span class="pxl-count">', $links);
    $links = str_replace(')', '</span></a>', $links);
    return $links;
}

/**
 * WooCommerce category count enhancement
 * 
 * Modifies WooCommerce category links to include styled product counts.
 * Used in shop sidebar and category pages for consistency.
 *
 * @param string $links The category links
 * @return string The modified links with styled counts
 * @since 1.0.0
 */
function portfoliocraft_wc_cat_count_span($links) {
    $links = str_replace('</a> <span class="count">(', ' <span class="pxl-count">', $links);
    $links = str_replace(')</span>', '</span></a>', $links);
    return $links;
}

/* ==========================================================================
   Menu and Builder Functions
   ========================================================================== */

/**
 * Get mega menu builder IDs
 * 
 * Retrieves all mega menu template IDs used in navigation menus.
 * Used for dynamic mega menu generation and caching.
 *
 * @return array Array of mega menu template IDs
 * @since 1.0.0
 */
function portfoliocraft_get_mega_menu_builder_id() {
    $mn_id = array();
    $menus = get_terms('nav_menu', array('hide_empty' => false));
    
    if (is_array($menus) && !empty($menus)) {
        foreach ($menus as $menu) {
            if (is_object($menu)) {
                $menu_obj = get_term($menu->term_id, 'nav_menu');
                $menu = wp_get_nav_menu_object($menu_obj);
                if ($menu) {
                    $menu_items = wp_get_nav_menu_items($menu->term_id, array('update_post_term_cache' => false));
                    if ($menu_items) {
                        foreach ($menu_items as $menu_item) {
                            if (!empty($menu_item->pxl_megaprofile)) {
                                $mn_id[] = (int)$menu_item->pxl_megaprofile;
                            }
                        }
                    }
                }
            }
        }
    }
    return array_unique($mn_id);
}

/**
 * Get page popup builder IDs
 * 
 * Retrieves all popup template IDs used in navigation menus.
 * Used for dynamic popup generation and preloading.
 *
 * @return array Array of popup template IDs
 * @since 1.0.0
 */
function portfoliocraft_get_page_popup_builder_id() {
    $pp_id = array();
    $page_popup = get_terms('nav_menu', array('hide_empty' => false));
    
    if (is_array($page_popup) && !empty($page_popup)) {
        foreach ($page_popup as $page) {
            if (is_object($page)) {
                $page_obj = get_term($page->term_id, 'nav_menu');
                $page = wp_get_nav_menu_object($page_obj);
                if ($page) {
                    $page_items = wp_get_nav_menu_items($page->term_id, array('update_post_term_cache' => false));
                    if ($page_items) {
                        foreach ($page_items as $page_item) {
                            if (!empty($page_item->pxl_page_popup)) {
                                $pp_id[] = (int)$page_item->pxl_page_popup;
                            }
                        }
                    }
                }
            }
        }
    }
    return array_unique($pp_id);
}

/* ==========================================================================
   Interactive Features
   ========================================================================== */

/**
 * Mouse move animation handler
 * 
 * Generates JavaScript for mouse movement animations and custom cursor.
 * Used for interactive elements and modern parallax effects.
 * Enhances user experience with smooth animations.
 *
 * @since 1.0.0
 */
function portfoliocraft_mouse_move_animation() { 
    $mouse_move_animation = portfoliocraft()->get_theme_opt('mouse_move_animation', 'off'); 
    
    if ($mouse_move_animation == 'on') {
        wp_enqueue_script('portfoliocraft-cursor', get_template_directory_uri() . '/assets/js/libs/cursor.js', array('jquery'), '1.0.0', true); 
        ?>  
        <div class="pxl-cursor pxl-js-cursor">
            <div class="pxl-cursor-wrapper">
                <div class="pxl-cursor--follower pxl-js-follower"></div>
                <div class="pxl-cursor--label pxl-js-label"></div>
                <div class="pxl-cursor--drap pxl-js-drap"></div>
                <div class="pxl-cursor--icon pxl-js-icon"></div>
            </div>
        </div>
        <?php 
    }
}

/* ==========================================================================
   GDPR and Privacy Functions
   ========================================================================== */

/**
 * Cookie policy handler
 * 
 * Handles cookie policy consent and management for GDPR compliance.
 * Displays customizable cookie notice with proper styling and functionality.
 * Ensures legal compliance for cookie usage tracking.
 *
 * @since 1.0.0
 */
function portfoliocraft_cookie_policy() {
    $cookie_policy = portfoliocraft()->get_theme_opt('cookie_policy', 'hide');
    $cookie_policy_description = portfoliocraft()->get_theme_opt('cookie_policy_description');
    $cookie_policy_btntext = portfoliocraft()->get_theme_opt('cookie_policy_btntext', __('Learn More', 'portfoliocraft'));
    $cookie_policy_link = get_permalink(portfoliocraft()->get_theme_opt('cookie_policy_link')); 
    
    wp_enqueue_script('pxl-cookie');
    
    if ($cookie_policy == 'show' && !empty($cookie_policy_description)) : ?>
        <div class="pxl-cookie-policy" role="dialog" aria-labelledby="cookie-policy-title" aria-describedby="cookie-policy-desc">
            <div class="pxl-item--icon pxl-mr-8">
                <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/cookie.png'); ?>" 
                     alt="<?php esc_attr_e('Cookie', 'portfoliocraft'); ?>" 
                     loading="lazy" />
            </div>
            <div class="pxl-item--description">
                <span id="cookie-policy-desc"><?php echo esc_html($cookie_policy_description); ?></span>
                <?php if ($cookie_policy_link) : ?>
                    <a class="pxl-item--link" 
                       href="<?php echo esc_url($cookie_policy_link); ?>" 
                       target="_blank" 
                       rel="noopener noreferrer">
                        <?php echo esc_html($cookie_policy_btntext); ?>
                    </a>
                <?php endif; ?>
            </div>
            <button class="pxl-item--close pxl-close" 
                    aria-label="<?php esc_attr_e('Close cookie notice', 'portfoliocraft'); ?>">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    <?php endif;
}

/* ==========================================================================
   User Profile Enhancement
   ========================================================================== */

// Add custom fields to user profile
add_action('show_user_profile', 'portfoliocraft_user_fields');
add_action('edit_user_profile', 'portfoliocraft_user_fields');

/**
 * User custom fields display
 * 
 * Adds custom fields to the user profile for extended information.
 * Includes author details and social media links for team members.
 * Used for author bio enhancement and social media integration.
 *
 * @param WP_User $user The user object
 * @since 1.0.0
 */
function portfoliocraft_user_fields($user) {
    // Get existing user meta values
    $user_name = get_user_meta($user->ID, 'user_name', true);
    $user_position = get_user_meta($user->ID, 'user_position', true);
    $user_facebook = get_user_meta($user->ID, 'user_facebook', true);
    $user_twitter = get_user_meta($user->ID, 'user_twitter', true);
    $user_instagram = get_user_meta($user->ID, 'user_instagram', true);
    $user_linkedin = get_user_meta($user->ID, 'user_linkedin', true);
    $user_youtube = get_user_meta($user->ID, 'user_youtube', true);
    ?>
    <h3><?php esc_html_e('Theme Custom Fields', 'portfoliocraft'); ?></h3>
    <table class="form-table" role="presentation">
        <tr>
            <th scope="row">
                <label for="user_name"><?php esc_html_e('Author Display Name', 'portfoliocraft'); ?></label>
            </th>
            <td>
                <input id="user_name" 
                       name="user_name" 
                       type="text" 
                       value="<?php echo esc_attr($user_name); ?>" 
                       class="regular-text" />
                <p class="description"><?php esc_html_e('Display name for author bio and posts.', 'portfoliocraft'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="user_position"><?php esc_html_e('Author Position', 'portfoliocraft'); ?></label>
            </th>
            <td>
                <input id="user_position" 
                       name="user_position" 
                       type="text" 
                       value="<?php echo esc_attr($user_position); ?>" 
                       class="regular-text" />
                <p class="description"><?php esc_html_e('Job title or position (e.g., Senior Developer, Content Writer).', 'portfoliocraft'); ?></p>
            </td>
        </tr>
        <tr>
            <th scope="row"><?php esc_html_e('Social Media Links', 'portfoliocraft'); ?></th>
            <td>
                <fieldset>
                    <legend class="screen-reader-text"><?php esc_html_e('Social Media Links', 'portfoliocraft'); ?></legend>
                    <p>
                        <label for="user_facebook"><?php esc_html_e('Facebook URL', 'portfoliocraft'); ?></label><br>
                        <input id="user_facebook" 
                               name="user_facebook" 
                               type="url" 
                               value="<?php echo esc_attr($user_facebook); ?>" 
                               class="regular-text" />
                    </p>
                    <p>
                        <label for="user_twitter"><?php esc_html_e('Twitter URL', 'portfoliocraft'); ?></label><br>
                        <input id="user_twitter" 
                               name="user_twitter" 
                               type="url" 
                               value="<?php echo esc_attr($user_twitter); ?>" 
                               class="regular-text" />
                    </p>
                    <p>
                        <label for="user_instagram"><?php esc_html_e('Instagram URL', 'portfoliocraft'); ?></label><br>
                        <input id="user_instagram" 
                               name="user_instagram" 
                               type="url" 
                               value="<?php echo esc_attr($user_instagram); ?>" 
                               class="regular-text" />
                    </p>
                    <p>
                        <label for="user_linkedin"><?php esc_html_e('LinkedIn URL', 'portfoliocraft'); ?></label><br>
                        <input id="user_linkedin" 
                               name="user_linkedin" 
                               type="url" 
                               value="<?php echo esc_attr($user_linkedin); ?>" 
                               class="regular-text" />
                    </p>
                    <p>
                        <label for="user_youtube"><?php esc_html_e('YouTube URL', 'portfoliocraft'); ?></label><br>
                        <input id="user_youtube" 
                               name="user_youtube" 
                               type="url" 
                               value="<?php echo esc_attr($user_youtube); ?>" 
                               class="regular-text" />
                    </p>
                </fieldset>
            </td>
        </tr>
    </table>
    <?php
}

// Save custom user fields
add_action('personal_options_update', 'portfoliocraft_save_user_custom_fields');
add_action('edit_user_profile_update', 'portfoliocraft_save_user_custom_fields');

/**
 * Save user custom fields
 * 
 * Saves custom fields from the user profile with proper validation.
 * Ensures data persistence and security for user information.
 *
 * @param int $user_id The user ID
 * @return bool False if user cannot edit, true on success
 * @since 1.0.0
 */
function portfoliocraft_save_user_custom_fields($user_id) {
    // Check user permissions
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    // Verify nonce for security
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'update-user_' . $user_id)) {
        return false;
    }

    // Save each field with proper sanitization
    $fields = array(
        'user_name' => 'sanitize_text_field',
        'user_position' => 'sanitize_text_field',
        'user_facebook' => 'esc_url_raw',
        'user_twitter' => 'esc_url_raw',
        'user_instagram' => 'esc_url_raw',
        'user_linkedin' => 'esc_url_raw',
        'user_youtube' => 'esc_url_raw'
    );

    foreach ($fields as $field => $sanitize_function) {
        if (isset($_POST[$field])) {
            $value = call_user_func($sanitize_function, $_POST[$field]);
            update_user_meta($user_id, $field, $value);
        }
    }

    return true;
}

/* ==========================================================================
   Image Handling Functions
   ========================================================================== */

if (!function_exists('portfoliocraft_get_image_by_size')) {
    /**
     * Get image by size with optimization
     * 
     * Retrieves an image with specific dimensions and proper attributes.
     * Used for responsive image handling with lazy loading and SEO optimization.
     * Includes fallback handling and proper alt text generation.
     *
     * @param array $params Image parameters including ID, dimensions, and attributes
     * @param int|null $post_id The post ID for context
     * @return string The optimized image HTML
     * @since 1.0.0
     */
    function portfoliocraft_get_image_by_size($params = array(), $post_id = null) {
        // Set default parameters
        $params = array_merge(array(
            'img_id' => '',
            'img_dimension' => 'thumbnail',
            'attr' => array(),
        ), $params);        
        
        // Get image ID from post thumbnail if not provided
        $params['img_id'] = !is_null($post_id) ? get_post_thumbnail_id($post_id) : ($params['img_id'] ?? '');
        
        // Return empty if no image ID
        if (empty($params['img_id'])) {
            return '';
        }
        
        // Apply filters and get image details
        $img_id = apply_filters('pxl_object_id', $params['img_id']);
        $img_dimension = $params['img_dimension'] ?? 'thumbnail';
        $img_attr = $params['attr'] ?? array();
        
        // Set default attributes
        $img_attr['class'] = $img_attr['class'] ?? '';
        $img_attr['alt'] = $img_attr['alt'] ?? trim(wp_strip_all_tags(get_post_meta($img_id, '_wp_attachment_image_alt', true)));
        $img_attr['loading'] = 'lazy';
        
        // Get post context for better alt text
        if (!empty($post_id)) {
            $post = get_post($post_id);
            if (!empty($post)) {
                $post_title = trim(wp_strip_all_tags($post->post_title, true));
                $post_excerpt = trim(wp_strip_all_tags($post->post_excerpt, true));
                if (empty(trim($img_attr['alt']))) {
                    $img_attr['alt'] = !empty($post_excerpt) ? $post_excerpt : $post_title;
                }
            }
        }

        // Generate image HTML based on dimension type
        if (!is_array($img_dimension)) {
            $img_attr['class'] .= ' attachment-' . $img_dimension;
            $thumbnail = wp_get_attachment_image($img_id, $img_dimension, false, $img_attr);
        } else {
            $img_w = $img_dimension['width'];
            $img_h = $img_dimension['height'];

            // Use custom resize function if available
            if (function_exists('pxl_resize')) {
                $img_crop = pxl_resize($img_id, null, $img_w, $img_h, true);
                if (!isset($img_crop['url'])) {
                    return '';
                }
                
                // Build attributes string
                if (function_exists('pxl_stringify_attributes')) {
                    $img_attr = pxl_stringify_attributes(array_merge(array(
                        'src' => $img_crop['url'],
                        'width' => $img_w,
                        'height' => $img_h,
                    ), $img_attr));
                    $thumbnail = '<img ' . $img_attr . ' />';
                } else {
                    $thumbnail = wp_get_attachment_image($img_id, $img_dimension, false, $img_attr);
                }
            } else {
                $thumbnail = wp_get_attachment_image($img_id, 'full', false, $img_attr);
            }
        } 
        
        return $thumbnail;
    }
}
