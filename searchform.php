<?php
/**
 * Search Form Template
 *
 * Template for displaying search forms throughout the theme.
 * This template provides a consistent search form design across all pages.
 *
 * @package portfoliocraft-Themes
 * @since 1.0
 */

// Prevent direct access to this file
if (!defined('ABSPATH')) {
    exit;
}

// Enqueue search form specific styles and scripts
wp_enqueue_style('portfoliocraft-search-form', get_template_directory_uri() . '/assets/css/search-form.css', array('portfoliocraft-style'), '1.0.0');
wp_enqueue_script('portfoliocraft-search-form', get_template_directory_uri() . '/assets/js/search-form.js', array('jquery'), '1.0.0', true);

// Generate unique form ID for accessibility
$unique_id = wp_unique_id('search-form-');

// Pass data to JavaScript
wp_localize_script('portfoliocraft-search-form', 'portfoliocraftSearchForm', array(
    'formId' => $unique_id,
    'clearText' => esc_html__('Clear search', 'portfoliocraft'),
    'searchText' => esc_html__('Search', 'portfoliocraft'),
));
?>

<!-- Search form container -->
<form role="search" 
      method="get" 
      class="portfoliocraft-search-form search-form" 
      action="<?php echo esc_url(home_url('/')); ?>"
      id="<?php echo esc_attr($unique_id); ?>">
    
    <!-- Search input field -->
    <div class="search-field-wrapper">
        <label for="<?php echo esc_attr($unique_id); ?>-field" class="screen-reader-text">
            <?php echo esc_html_x('Search for:', 'label', 'portfoliocraft'); ?>
        </label>
        <input type="search" 
               id="<?php echo esc_attr($unique_id); ?>-field"
               class="search-field" 
               placeholder="<?php echo esc_attr_x('Search for articles, news...', 'placeholder', 'portfoliocraft'); ?>" 
               value="<?php echo esc_attr(get_search_query()); ?>" 
               name="s" 
               title="<?php echo esc_attr_x('Search for:', 'label', 'portfoliocraft'); ?>"
               autocomplete="off"
               required />
        
        <!-- Clear search button (appears when there's text) -->
        <button type="button" 
                class="search-clear" 
                aria-label="<?php echo esc_attr__('Clear search', 'portfoliocraft'); ?>"
                title="<?php echo esc_attr__('Clear search', 'portfoliocraft'); ?>">
            <i class="fas fa-times"></i>
        </button>
    </div>
    
    <!-- Search submit button -->
    <button type="submit" 
            class="search-submit" 
            aria-label="<?php echo esc_attr_x('Search', 'submit button', 'portfoliocraft'); ?>"
            title="<?php echo esc_attr_x('Search', 'submit button', 'portfoliocraft'); ?>">
        <i class="fas fa-search" aria-hidden="true"></i>
        <span class="search-text"><?php echo esc_html_x('Search', 'submit button', 'portfoliocraft'); ?></span>
    </button>
    
</form>
