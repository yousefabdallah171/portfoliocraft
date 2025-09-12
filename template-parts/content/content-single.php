<?php
if (!defined("ABSPATH")) {
    exit;
}
/**
 * Template part for displaying posts in loop
 *
 * @package portfoliocraft-Themes
 */
$post_tag = portfoliocraft()->get_theme_opt( 'post_tag', true );
$post_navigation = portfoliocraft()->get_theme_opt( 'post_navigation', false );
$post_social_share = portfoliocraft()->get_theme_opt( 'post_social_share', false );
$tags_list = get_the_tag_list();
$sg_post_title = portfoliocraft()->get_theme_opt('sg_post_title', 'default');
$sg_featured_img_size = portfoliocraft()->get_theme_opt('sg_featured_img_size', '960x545');
$post_video_link = get_post_meta(get_the_ID(), 'post_video_link', true);
?>
<article id="pxl-post-<?php the_ID(); ?>" <?php post_class('pxl-single-post'); ?>>
    <?php 
        $post_id = get_the_ID();
        $thumbnail = portfoliocraft_get_image_by_size([
            'img_dimension' => 'full' ,
            'attr' => [
                'class' => 'pxl-post-featured',
            ],
        ], $post_id);
    ?>
    <div class="pxl-post-header">
        <h2 class="pxl-post-title">
            <span class="pxl-title-text">
                <?php the_title(); ?>
            </span>
        </h2>
        <?php portfoliocraft()->blog->get_post_metas($post_id); ?>
        <div class="pxl-post-featured">
            <?php echo wp_kses_post($thumbnail); ?>
        </div>
    </div>
    <div class="pxl-post-content clearfix">
        <?php
            the_content();
            wp_link_pages( array(
                'before'      => '<div class="page-links">',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            ) );
        ?>
    </div>
    <div class="pxl-post-group">
        <?php portfoliocraft()->blog->get_socials_share(); ?>
        <?php portfoliocraft()->blog->get_post_author_info($post_id); ?>
        
        <?php if ($post_tag && $tags_list) : ?>
            <div class="pxl-post-tags">
                <h4 class="pxl-tags-title"><?php esc_html_e('Tags:', 'portfoliocraft'); ?></h4>
                <div class="pxl-tags-list">
                    <?php echo wp_kses_post($tags_list); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</article><!-- #post -->