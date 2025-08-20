<?php
/**
 * @package portfoliocraft-Themes
 */
$archive_readmore_text = portfoliocraft()->get_theme_opt('archive_readmore_text', esc_html__('Read More', 'portfoliocraft'));
$featured_img_size = portfoliocraft()->get_theme_opt('featured_img_size', '960x460');
$post_video_link = get_post_meta(get_the_ID(), 'post_video_link', true);
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('pxl-post-item pxl-item-archive pxl-post-standard'); ?>>
    <?php 
        $post_id = get_the_id();
        $thumbnail = portfoliocraft_get_image_by_size([
            'img_dimension' => [
                'width' => 1300,
                'height' => 653,
            ],
            'attr' => [
                'class' => 'pxl-post-featured',
            ],
        ], $post_id);
        $author_id = get_post_field ('post_author', $post_id);
    ?>
    <div class="pxl-post-featured hover-image-zoom-in">
        <a class="pxl-featured-link" href="<?php echo esc_url(get_permalink($post_id)); ?>">
            <?php pxl_print_html($thumbnail); ?>
        </a>
    </div>
    <div class="pxl-post-content">
        <div class="pxl-content-container">
            <div class="pxl-post-date">
                <span>
                    <?php echo get_the_date('d M Y', $post_id); ?>
                </span>
            </div>
            <h4 class="pxl-post-title hover-text-underline--slide-ltr">
                <a class="pxl-title-link" href="<?php echo esc_url(get_permalink($post_id)); ?>">
                    <span class="pxl-title-text">
                        <?php echo esc_html(get_the_title($post_id)); ?>
                    </span>
                </a>
            </h4>
            <p class="pxl-post-excerpt">
                <?php echo wp_trim_words( get_the_excerpt($post_id), 15, $more = null ); ?>
            </p>
            <span class="pxl-post-author">
                <a href="<?php echo esc_url(get_author_posts_url($author_id)); ?>" class="pxl-author-link">
                    <?php echo esc_attr('BY '.get_the_author_meta('display_name', $author_id)); ?>
                </a>
            </span>
        </div>
    </div>
</article>