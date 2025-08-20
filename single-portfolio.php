<?php
/**
 * Single Post Template
 *
 * Displays individual blog posts with content and comments.
 * This template handles the single post layout including post content and pagination.
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
?>

<!-- Main container for single post -->
<div class="container">
    
    <!-- Inner content wrapper -->
    <div class="inner">
        
        <!-- Primary content area -->
        <div id="pxl-content-area" class="pxl-content-area">
            <main id="pxl-content-main">
                
                <?php while (have_posts()) : ?>
                    <?php the_post(); ?>
                    
                    <!-- Single post article -->
                    <article id="pxl-post-<?php the_ID(); ?>" <?php post_class(); ?>>
                        
                        <!-- Post content -->
                        <div class="post-content">
                            <?php the_content(); ?>
                        </div>
                        
                        <!-- Multi-page post navigation -->
                        <?php
                        wp_link_pages(array(
                            'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__('Pages:', 'portfoliocraft') . '</span>',
                            'after'       => '</div>',
                            'link_before' => '<span>',
                            'link_after'  => '</span>',
                            'pagelink'    => '<span class="screen-reader-text">' . esc_html__('Page', 'portfoliocraft') . ' </span>%',
                            'separator'   => '<span class="screen-reader-text">, </span>',
                        ));
                        ?>
                        
                    </article><!-- #post-<?php the_ID(); ?> -->
                    
                    <!-- Post comments section -->
                    <?php if (comments_open() || get_comments_number()) : ?>
                        <div class="post-comments-section">
                            <?php comments_template(); ?>
                        </div>
                    <?php endif; ?>
                    
                <?php endwhile; ?>
                
            </main>
        </div>
    </div>
</div>

<?php
// Include footer template
get_footer();
?>
