<?php
/**
 * Blog Archive Template
 *
 * Displays the blog archive page with posts listing and optional sidebar.
 * This template handles the main blog page layout including header, content area, and sidebar.
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

// Get sidebar configuration for blog page
$sidebar = portfoliocraft()->get_sidebar_value('blog');

// Define paragraph content for the page header
$paragraph = esc_html__('portfoliocraft provides the best digital product design for firms who are launching new products. We have the best 3D artists here to serve the best outputs.', 'portfoliocraft');
?>

<!-- Main container for blog archive page -->
<div class="container">
    
    <!-- Page header section with title and description -->
    <div class="rmt-content-header">
        <div class="rmt-heading-wrapper">
            
            <!-- Subtitle section -->
            <div class="rmt-heading-subtitle heading-subtitle-default">
                <span class="rmt-subtitle-text">
                    <span class="rmt-text-highlight"><?php echo esc_html__('//', 'portfoliocraft'); ?></span>
                    <?php echo esc_html__('Latest news', 'portfoliocraft'); ?>
                </span>
            </div>
            
            <!-- Main title section -->
            <h2 class="rmt-heading-title heading-title-default">
                <span class="rmt-title-text">
                    <span class="rmt-text-highlight"><?php echo esc_html__('Amazing Research', 'portfoliocraft'); ?></span>
                    <?php echo esc_html__('news & blogs', 'portfoliocraft'); ?>
                </span>
            </h2>
        </div>
        
        <!-- Page description paragraph -->
        <p class="rmt-text-paragraph">
            <?php echo $paragraph; ?>
        </p>
    </div>
    
    <!-- Main content area with conditional sidebar -->
    <div class="inner <?php echo esc_attr($sidebar['sidebar_class']); ?>">
        
        <!-- Primary content area -->
        <div id="rmt-content-area" class="rmt-content-area">
            <main id="rmt-content-main">
                
                <?php if (have_posts()) : ?>
                    <!-- Posts container -->
                    <div class="posts-container">
                        <!-- Loop through posts and display them -->
                        <?php while (have_posts()) : ?>
                            <?php the_post(); ?>
                            <?php get_template_part('template-parts/content/archive/standard'); ?>
                        <?php endwhile; ?>
                    </div>
                    
                    <!-- Display pagination for multiple pages -->
                    <?php portfoliocraft()->page->get_pagination(); ?>
                    
                <?php else : ?>
                    <!-- Display message when no posts are found -->
                    <div class="no-posts-found">
                        <?php get_template_part('template-parts/content/content', 'none'); ?>
                    </div>
                <?php endif; ?>
                
            </main>
        </div>
        
        <!-- Conditional sidebar area -->
        <?php if ($sidebar['is_sidebar'] === true) : ?>
            <div id="rmt-sidebar-area" class="rmt-sidebar-area">
                <div class="rmt-sidebar-content">
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