<?php $footer_display = portfoliocraft()->get_page_opt('footer_display', 'show'); ?>
if (!defined("ABSPATH")) {
    exit;
}
<footer id="rmt-footer-elementor" class="rmt-footer rmt-footer-<?php echo esc_attr($footer_display); ?>">
    <?php if(isset($args['footer_layout']) && $args['footer_layout'] > 0) : ?>
        <div class="footer-elementor-inner">
            <?php $post = get_post($args['footer_layout']);
            if (!is_wp_error($post) && function_exists('rmt_print_html')){
                $content = \Elementor\Plugin::$instance->frontend->get_builder_content( $args['footer_layout'] );
                rmt_print_html($content);
            } ?>
        </div>
    <?php endif; ?>
</footer>