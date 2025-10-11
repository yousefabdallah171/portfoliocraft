<?php
if (!defined('ABSPATH')) {
    exit;
}

get_header();

wp_enqueue_style('portfoliocraft-404', get_template_directory_uri() . '/assets/css/404.css', array('rmt-style'), '1.0.0');
wp_add_inline_style('portfoliocraft-404', portfoliocraft_inline_styles());

$page_id = portfoliocraft()->get_theme_opt('404_page', '');
?>

<?php if (!empty($page_id)) : ?>
    <?php
    if (class_exists('Elementor\Plugin') && !empty($page_id)) {
        echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display((int)$page_id);
    }
    ?>

<?php else : ?>
    <div class="container">
        <div class="inner-">
            <div class="portfoliocraft-404-wrapper">

                <div class="floating-element floating-element-1">
                    <i class="fas fa-question-circle"></i>
                </div>
                <div class="floating-element floating-element-2">
                    <i class="fas fa-search"></i>
                </div>
                <div class="floating-element floating-element-3">
                    <i class="fas fa-home"></i>
                </div>

                <div class="portfoliocraft-404-number">
                    <span class="portfoliocraft-404-text">404</span>
                </div>

                <div class="portfoliocraft-404-content">
                    <h1 class="portfoliocraft-404-title">
                        <?php echo esc_html__('Oops! Page Not Found', 'portfoliocraft'); ?>
                    </h1>

                    <p class="portfoliocraft-404-description">
                        <?php echo esc_html__('The page you are looking for might have been removed, had its name changed, or is temporarily unavailable.', 'portfoliocraft'); ?>
                    </p>

                    <div class="portfoliocraft-404-actions">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="portfoliocraft-btn portfoliocraft-btn-primary">
                            <i class="fas fa-home"></i>
                            <?php echo esc_html__('Back to Homepage', 'portfoliocraft'); ?>
                        </a>

                        <button onclick="history.back()" class="portfoliocraft-btn portfoliocraft-btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            <?php echo esc_html__('Go Back', 'portfoliocraft'); ?>
                        </button>
                    </div>

                    <div class="portfoliocraft-404-search">
                        <h2 class="portfoliocraft-search-title">
                            <?php echo esc_html__('Try searching for what you need:', 'portfoliocraft'); ?>
                        </h2>
                        <?php get_search_form(); ?>
                    </div>
                </div>

            </div>
        </div>
    </div>

<?php endif; ?>

<?php
get_footer();
?>
