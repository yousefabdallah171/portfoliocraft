<?php
/**
 * Single Post Template with Sidebar
 *
 * Displays individual blog posts with optional sidebar support.
 * This template handles single post layout with sidebar configuration and post formats.
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

// Get sidebar configuration for single posts
$sidebar = portfoliocraft()->get_sidebar_value('post'); 
?>

<!-- Main container for single post with sidebar -->
<div class="container">
    
    <!-- Inner content wrapper with sidebar classes -->
    <div class="inner <?php echo esc_attr($sidebar['sidebar_class']); ?>">
        
        <!-- Primary content area -->
        <div id="pxl-content-area" class="pxl-content-area">
            <main id="pxl-content-main">
                
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    
                    <!-- Display single post content based on post format -->
                    <?php get_template_part('template-parts/content/content-single', get_post_format()); ?>
                    
                    <!-- Display comments if enabled -->
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="single-post-comments">
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
