<?php
if (!defined("ABSPATH")) {
    exit;
}
// Properly check if sticky header is set
$has_header_sticky = !empty($args['header_layout_sticky']) && $args['header_layout_sticky'] > 0;
// Get sticky header behavior (scroll-up or scroll-down)
$sticky_scroll = isset($args['sticky_scroll']) ? $args['sticky_scroll'] : 'scroll-down';
?>
<header id="rmt-header-elementor" class="rmt-header">
    <div id="rmt-header-desktop">
        <?php if(isset($args['header_layout']) && $args['header_layout'] > 0) : ?>
            <div class="rmt-header-main">
                <div class="rmt-header-inner">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout']); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div id="rmt-header-mobile">
        <?php if(isset($args['header_layout']) && $args['header_layout'] > 0) : ?>
            <div class="rmt-header-main">
                <div class="rmt-header-inner">
                    <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout']); ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php if($has_header_sticky) : ?>
        <div class="rmt-header-sticky <?php echo esc_attr($sticky_scroll); ?>">
            <div class="rmt-header-inner">
                <?php echo Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $args['header_layout_sticky']); ?>
            </div>
        </div>
    <?php endif; ?>
</header>