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
            portfoliocraft()->page->get_link_pages();
        ?>
    </div> 
</article> 
