<?php
/**
 * Author Archive Template
 *
 * Displays posts by a specific author with a simple, modern layout and theme color support.
 *
 * @package portfoliocraft-Themes
 */

if (!defined('ABSPATH')) {
    exit;
}

get_header();

// Enqueue author archive styles
wp_enqueue_style('portfoliocraft-author-archive', get_template_directory_uri() . '/assets/css/author-archive.css', array('rmt-style'), wp_get_theme()->get('Version'));

// Get theme color options and set CSS variables
$primary_color = portfoliocraft()->get_theme_opt('primary_color', '#667eea');
$background_color = portfoliocraft()->get_theme_opt('background_color', '#f5f6f6');

// Add inline CSS variables for dynamic colors
wp_add_inline_style('portfoliocraft-author-archive', "
    :root {
        --portfoliocraft-primary-color: {$primary_color};
        --portfoliocraft-background-color: {$background_color};
    }
");

$author = get_queried_object();
$author_avatar = get_avatar($author->ID, 80);
$author_name = get_the_author_meta('display_name', $author->ID);
$author_desc = get_the_author_meta('description', $author->ID);
?>

<div class="author-archive-header">
    <div class="author-avatar"><?php echo $author_avatar; ?></div>
    <div class="author-name"><?php echo esc_html($author_name); ?></div>
    <?php if ($author_desc): ?>
        <div class="author-desc"><?php echo esc_html($author_desc); ?></div>
    <?php endif; ?>
</div>

<div class="author-archive-posts">
    <div class="posts-list">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <?php get_template_part('template-parts/content/archive/standard'); ?>
        <?php endwhile; else: ?>
            <p><?php esc_html_e('No posts by this author.', 'portfoliocraft'); ?></p>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?> 