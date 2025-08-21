<?php
/**
 * 404 Error Page Template
 *
 * Displays the 404 error page when a requested page is not found.
 * This template can either display a custom Elementor page or a default 404 layout.
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

// Enqueue 404 page specific styles
wp_enqueue_style('portfoliocraft-404', get_template_directory_uri() . '/assets/css/404.css', array('pxl-style'), '1.0.0');
wp_add_inline_style('portfoliocraft-404', portfoliocraft_inline_styles());

// Get custom 404 page ID from theme options
$page_id = portfoliocraft()->get_theme_opt('404_page', '');
?>

<?php if (!empty($page_id)) : ?>
    <!-- Display custom Elementor 404 page if configured -->
    <?php
    // Check if Elementor plugin is active and page ID is valid
    if (class_exists('Elementor\Plugin') && !empty($page_id)) {
        echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display((int)$page_id);
    }
    ?>
    
<?php else : ?>
    <!-- Default 404 page layout when no custom page is set -->
    <div class="container">
        <div class="inner-">
            <div class="portfoliocraft-404-wrapper">
                
                <!-- Floating decorative elements -->
                <div class="floating-element floating-element-1">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="floating-element floating-element-2">
                    <i class="fas fa-search"></i>
                </div>
                <div class="floating-element floating-element-3">
                    <i class="fas fa-home"></i>
                </div>
                
                <!-- 404 Error number -->
                <div class="portfoliocraft-404-number">
                    <span class="portfoliocraft-404-text">404</span>
                </div>
                
                <!-- Error content -->
                <div class="portfoliocraft-404-content">
                    <!-- Error heading -->
                    <h1 class="portfoliocraft-404-title">
                        <?php echo esc_html__('Oops! Page Not Found', 'portfoliocraft'); ?>
                    </h1>
                    
                    <!-- Description text -->
                    <p class="portfoliocraft-404-description">
                        <?php echo esc_html__('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'portfoliocraft'); ?>
                    </p>
                    
                    <!-- Navigation options -->
                    <div class="portfoliocraft-404-actions">
                        <!-- Back to homepage button -->
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="portfoliocraft-btn portfoliocraft-btn-primary">
                            <i class="fas fa-home"></i>
                            <?php echo esc_html__('Back to Homepage', 'portfoliocraft'); ?>
                        </a>
                        
                        <!-- Go back button -->
                        <button onclick="history.back()" class="portfoliocraft-btn portfoliocraft-btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            <?php echo esc_html__('Go Back', 'portfoliocraft'); ?>
                        </button>
                    </div>
                    
                    <!-- Search form -->
                    <div class="portfoliocraft-404-search">
                        <h3 class="portfoliocraft-search-title">
                            <?php echo esc_html__('Try searching for what you need:', 'portfoliocraft'); ?>
                        </h3>
                        <?php get_search_form(); ?>
                    </div>
                </div>
                
            </div>
        </div>
    </div>
    
<?php endif; ?>

<?php
// Include footer template
get_footer();
?>
