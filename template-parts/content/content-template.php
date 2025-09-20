<?php
if (!defined("ABSPATH")) {
    exit;
}
/**
* Template Name: Blog Classic
* @package portfoliocraft-Themes
*/
get_header();
$portfoliocraft_sidebar = portfoliocraft()->get_sidebar_value(['type' => 'blog', 'content_col'=> '8']);
?>
<div class="container">
    <div class="row <?php echo esc_attr($portfoliocraft_sidebar['wrap_class']) ?>" >
        <div id="rmt-content-area" class="<?php echo esc_attr($portfoliocraft_sidebar['content_class']) ?>">
            <main id="rmt-content-main">
                <?php if ( have_posts() ) {
                    while ( have_posts() ) {
                        the_post();
                        get_template_part( 'template-parts/content/content' );
                    }
                    portfoliocraft()->page->get_pagination();
                } else {
                    get_template_part( 'template-parts/content/content', 'none' );
                } ?>
            </main>
        </div>
        <?php if ($portfoliocraft_sidebar['sidebar_class']) : ?>
            <div id="rmt-sidebar-area" class="<?php echo esc_attr($portfoliocraft_sidebar['sidebar_class']) ?>">
                <div class="rmt-sidebar-content">
                    <?php get_sidebar(); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer();
