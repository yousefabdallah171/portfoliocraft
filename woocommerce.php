<?php
/**
 * WooCommerce Shop Template
 *
 * Displays the WooCommerce shop page with products listing and optional sidebar.
 * This template handles the main shop layout including product grids, filters, and sidebar.
 *
 * @package portfoliocraft-Themes
 * @since 1.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Include header template
get_header();

// Get sidebar configuration for shop page
$sidebar = portfoliocraft()->get_sidebar_value('shop'); 
?>

<!-- Main container for WooCommerce shop page -->
<div class="container">
    
    <!-- Inner content wrapper with sidebar classes -->
    <div class="inner <?php echo esc_attr($sidebar['sidebar_class']); ?>">
        
        <!-- Primary content area for shop products -->
        <div id="rmt-content-area" class="rmt-content-area">
            <main id="rmt-content-main">
                
                <!-- WooCommerce shop content -->
                <div class="woocommerce-shop-wrapper">
                    <?php woocommerce_content(); ?>
                </div>
                
            </main>
        </div>

        <!-- Conditional sidebar area for shop filters and widgets -->
        <?php if ($sidebar['is_sidebar'] === true) : ?>
            <aside id="rmt-sidebar-area" class="rmt-sidebar-area">
                <div class="rmt-sidebar-content">
                    <?php get_sidebar(); ?>
                </div>
            </aside>
        <?php endif; ?>
        
    </div>
</div>

<?php
// Include footer template
get_footer();
?>
