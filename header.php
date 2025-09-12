<?php
/**
 * The header for our theme
 * 
 * This is the template that displays all of the <head> section and everything up until <div id="pxl-main">
 *
 * @package portfoliocraft-Themes
 * @since portfoliocraft 1.0
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <!-- Meta tags for character encoding and responsive viewport -->
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="profile" href="//gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php 
        // WordPress 5.2+ body open hook
        wp_body_open(); 
        
        // Get smooth scroll option from theme settings
        $smooth_scroll = portfoliocraft()->get_opt('smooth_scroll', 'off'); 
    ?>
    <!-- Skip to main content link for accessibility -->
    <a class="skip-link screen-reader-text" href="#pxl-main"><?php esc_html_e('Skip to content', 'portfoliocraft'); ?></a>
    
    <!-- Main wrapper for the entire site -->
    <div id="pxl-wrapper" class="pxl-wrapper">
    <?php 
        // Display site loader if enabled
        portfoliocraft()->page->get_site_loader();
        
        // Smooth scroll wrapper if enabled
        if($smooth_scroll === 'on') : ?>
            <div id="smooth-wrapper">
                <div id="smooth-content">
        <?php endif; ?>
        
        <?php 
            // Display the header
            portfoliocraft()->header->getHeader();
            
            // Display appropriate page title based on page type
            if((!is_single() && !is_404() && !is_search())) {
                // Regular page title
                portfoliocraft()->page->get_page_title();
            }elseif(!is_404()) {
                // Single post title
                portfoliocraft()->page->get_post_title();
            }
        ?>
        <!-- Main content wrapper -->
        <div id="pxl-main">
