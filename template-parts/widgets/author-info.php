<?php 
if (!defined("ABSPATH")) {
    exit;
}
class portfoliocraft_Author_Info extends WP_Widget {
    function __construct() {
        parent::__construct(
            'rmt_author_info', 
            __('portfoliocraft Author Info', 'portfoliocraft'), 
            array('description' => __('Show author information. Only works in single post', 'portfoliocraft')) 
        );
    }

    public function widget($args, $instance) {
        echo wp_kses_post($args['before_widget']);
        $title = apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo wp_kses_post($args['before_title'] . $title . $args['after_title']);
        }
        if(is_single()) {
            portfoliocraft()->blog->get_post_author_info(get_the_ID());
        }
        echo wp_kses_post($args['after_widget']);
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Author Info', 'portfoliocraft');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php echo esc_html__('Title:', 'portfoliocraft'); ?></label>
            <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>">
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? sanitize_text_field($new_instance['title']) : '';
        return $instance;
    }
}

add_action( 'widgets_init', 'rmt_author_info' );
function rmt_author_info(){
    if(function_exists('rmt_register_wp_widget')){
        rmt_register_wp_widget( 'portfoliocraft_Author_Info' );
    }
}
