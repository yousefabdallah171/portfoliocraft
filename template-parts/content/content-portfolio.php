<?php
if (!defined("ABSPATH")) {
    exit;
}
/**
 * @package portfoliocraft-Themes
 */
?>
<article id="rmt-post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="rmt-entry-content clearfix">
        <?php
            the_content();
            wp_link_pages( array(
                'before'      => '<div class="rmt-page-links">',
                'after'       => '</div>',
                'link_before' => '<span>',
                'link_after'  => '</span>',
            ) );
        ?>
    </div>
</article>