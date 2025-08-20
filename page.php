<?php
/**
 * Single Page Template
 *
 * Displays individual pages with optional sidebar and Elementor support.
 * This template handles both regular pages and Elementor-built pages.
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

// Get sidebar configuration for pages
$sidebar = portfoliocraft()->get_sidebar_value('page'); 

// Determine container class based on Elementor usage
if (class_exists('\Elementor\Plugin')) {
    $page_id = get_the_ID();
    if (is_singular() && \Elementor\Plugin::$instance->documents->get($page_id)->is_built_with_elementor()) {
        $container_classes = 'elementor-container';
    } else {
        $container_classes = 'container';
    }
} else {
    $container_classes = 'container';
}
?>

<!-- Main page container -->
<div class="<?php echo esc_attr($container_classes); ?>">
    
    <!-- Inner content wrapper with sidebar classes -->
    <div class="inner <?php echo esc_attr($sidebar['sidebar_class']); ?>">
        
        <!-- Primary content area -->
        <div id="pxl-content-area" class="pxl-content-area">
            <main id="pxl-content-main">
                
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    
                    <!-- Display page content -->
                    <?php get_template_part('template-parts/content/content', 'page'); ?>
                    
                    <!-- Display comments if enabled -->
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="page-comments-section">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>
                    
                <?php endwhile; ?>
                
            </main>
        </div>
        
        <!-- Conditional sidebar area -->
        <?php if ($sidebar['is_sidebar'] === true) : ?>
            <div id="pxl-sidebar-area" class="pxl-sidebar-area">
                <div class="pxl-sidebar-content">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php endif; ?>
        
    </div>
</div>

<?php
// Include footer template
get_footer();
?>
