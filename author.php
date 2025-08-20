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

// Get theme color options (example: primary, secondary, background)
$primary_color = portfoliocraft()->get_theme_opt('primary_color', '#667eea');
$background_color = portfoliocraft()->get_theme_opt('background_color', '#f5f6f6');

$author = get_queried_object();
$author_avatar = get_avatar($author->ID, 80);
$author_name = get_the_author_meta('display_name', $author->ID);
$author_desc = get_the_author_meta('description', $author->ID);
?>

<style>
.author-archive-header {
    background: <?php echo esc_attr($background_color); ?>;
    padding: 40px 0 24px 0;
    text-align: center;
    border-bottom: 1px solid #eee;
}
.author-archive-header .author-avatar {
    border-radius: 50%;
    margin-bottom: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    display: inline-block;
}
.author-archive-header .author-name {
    font-size: 1.5rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: <?php echo esc_attr($primary_color); ?>;
}
.author-archive-header .author-desc {
    font-size: 1rem;
    color: #666;
    max-width: 420px;
    margin: 0 auto;
    opacity: 0.85;
}
.author-archive-posts {
    background: #fff;
    padding: 32px 0;
}
.author-archive-posts .posts-list {
    max-width: 1200px;
    margin: 0 auto;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 32px;
}
</style>

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