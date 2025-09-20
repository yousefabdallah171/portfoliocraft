<?php
if (!defined("ABSPATH")) {
    exit;
}
/**
 * @package portfoliocraft-Themes
 */
?>
<article id="post-<?php the_ID(); ?>" <?php post_class('col-lg-4 col-md-6 col-sm-12'); ?>>
    <div class="rmt-post--inner">
        <?php if (has_post_thumbnail()) {
            echo '<div class="rmt-post--featured hover-imge-effect3">'; ?>
                <a href="<?php echo esc_url( get_permalink()); ?>"><?php the_post_thumbnail('portfoliocraft-portfolio'); ?></a>
            <?php echo '</div>';
        } ?>
        <div class="rmt-post--overlay"></div>
        <div class="rmt-post--holder">
            <div class="rmt-holder--inner">
                <div class="rmt-post--icon">
                    <i class="fas fa-arrow-up-right"></i>
                </div>
                <h5 class="rmt-post--title"><?php echo esc_html(get_the_title($post->ID)); ?></h5>
            </div>
        </div>
        <a class="rmt-post--link" href="<?php echo esc_url( get_permalink()); ?>" title="<?php the_title(); ?>"></a>
    </div>
</article>