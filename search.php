<?php
/**
 * Search Results Template
 *
 * Displays search results with posts listing and optional sidebar.
 * This template handles the search results page layout including header, content area, and sidebar.
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

// Enqueue consolidated search styles (includes all search-related CSS from entire theme)
wp_enqueue_style('portfoliocraft-consolidated-search', get_template_directory_uri() . '/assets/css/consolidated-search.css', array('rmt-style'), '1.0.1');
wp_add_inline_style('portfoliocraft-consolidated-search', portfoliocraft_inline_styles());

// Get search query for display
$search_query = get_search_query();
$search_count = $wp_query->found_posts;
?>

<!-- Main container for search results page -->
<div class="container">
    
    <!-- Search results header section -->
    <div class="portfoliocraft-search-header">
        <div class="portfoliocraft-search-info">
            <?php if (!empty($search_query)) : ?>
                <h1 class="portfoliocraft-search-title">
                    <?php
                    printf(
                        esc_html__('Search Results for: %s', 'portfoliocraft'),
                        '<span class="search-query">' . esc_html($search_query) . '</span>'
                    );
                    ?>
                </h1>
                <p class="portfoliocraft-search-count">
                    <?php
                    printf(
                        esc_html(_n('Found %d result', 'Found %d results', $search_count, 'portfoliocraft')),
                        $search_count
                    );
                    ?>
                </p>
            <?php else : ?>
                <h1 class="portfoliocraft-search-title">
                    <?php echo esc_html__('Search Results', 'portfoliocraft'); ?>
                </h1>
            <?php endif; ?>
        </div>
        
        <!-- Search form for new searches -->
        <div class="portfoliocraft-search-form-wrapper">
            <?php get_search_form(); ?>
        </div>
    </div>
    
    <!-- Main content area without sidebar -->
    <div class="inner no-sidebar">
        
        <!-- Primary content area -->
        <section id="rmt-content-area" class="rmt-content-area">
            <main id="rmt-content-main">
                
                <?php if (have_posts()) : ?>
                    <!-- Search results container -->
                    <div class="portfoliocraft-search-results">
                        
                        <?php while (have_posts()) : ?>
                            <?php the_post(); ?>
                            <?php get_template_part('template-parts/content/content-search'); ?>
                        <?php endwhile; ?>
                        
                    </div>
                    
                    <!-- Pagination for search results -->
                    <?php portfoliocraft()->page->get_pagination(); ?>
                    
                <?php else : ?>
                    <!-- No results found message -->
                    <div class="portfoliocraft-no-results">
                        <?php get_template_part('template-parts/content/content', 'none'); ?>
                        
                        <!-- Additional search suggestions -->
                        <div class="portfoliocraft-search-suggestions">
                            <h3 class="suggestions-title">
                                <?php echo esc_html__('Search Suggestions:', 'portfoliocraft'); ?>
                            </h3>
                            <ul class="suggestions-list">
                                <li><?php echo esc_html__('Check your spelling and try again', 'portfoliocraft'); ?></li>
                                <li><?php echo esc_html__('Try using fewer or different keywords', 'portfoliocraft'); ?></li>
                                <li><?php echo esc_html__('Use more general terms', 'portfoliocraft'); ?></li>
                                <li><?php echo esc_html__('Browse our categories below', 'portfoliocraft'); ?></li>
                            </ul>
                            
                            <!-- Popular categories or recent posts -->
                            <div class="portfoliocraft-popular-content">
                                <h4><?php echo esc_html__('Popular Categories', 'portfoliocraft'); ?></h4>
                                <?php
                                $popular_categories = get_categories(array(
                                    'orderby' => 'count',
                                    'order' => 'DESC',
                                    'number' => 5,
                                    'hide_empty' => true
                                ));
                                
                                if ($popular_categories) : ?>
                                    <div class="popular-categories">
                                        <?php foreach ($popular_categories as $category) : ?>
                                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" 
                                               class="category-link">
                                                <?php echo esc_html($category->name); ?>
                                                <span class="post-count">(<?php echo $category->count; ?>)</span>
                                            </a>
                                        <?php endforeach; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
            </main>
        </section>
        
    </div>
</div>

<?php
// Include footer template
get_footer();
?>
