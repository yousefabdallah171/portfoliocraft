<?php defined( 'ABSPATH' ) or exit( -1 );
/**
 * Recent Posts widgets
 * @package portfoliocraft-Themes
 */

class portfoliocraft_Recent_Posts_Widget extends WP_Widget
{
    function __construct()
    {
        parent::__construct(
            'pxl_recent_posts',
            esc_html__( 'portfoliocraft Recent Posts', 'portfoliocraft' ),
            array(
                'description' => esc_html__( 'Your site’s most recent Posts.', 'portfoliocraft' ),
                'customize_selective_refresh' => true,
            )
        );
    }

    function widget( $args, $instance )
    {
        $instance = wp_parse_args( (array) $instance, array(
            'title'         => '',
            'number'        => 3,
            'post_in'        => '',
        ) );

        $title = $instance['title'];
        $title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

        echo wp_kses_post($args['before_widget']);

        echo wp_kses_post($args['before_title']) . wp_kses_post($title) . wp_kses_post($args['after_title']);

        $number = absint( $instance['number'] );
        if ( $number <= 0 || $number > 10)
        {
            $number = 4;
        }
        $post_in = $instance['post_in'];
        $sticky = '';
        if($post_in == 'featured') {
            $sticky = get_option( 'sticky_posts' );
        }
        $r = new WP_Query( array(
            'post_type'           => 'post',
            'posts_per_page'      => $number,
            'no_found_rows'       => true,
            'post_status'         => 'publish',
            'ignore_sticky_posts' => true,
            'post__in'  => $sticky,
            'post__not_in' => [get_the_ID()],
        ) );
        $i = 0;

        if ( $r->have_posts() ) : ?>
            <div class="pxl-post-list">
                <?php while ( $r->have_posts() ) :
                    $r->the_post();
                    global $post; 
                    $thumbnail = portfoliocraft_get_image_by_size([
                        'img_dimension' => 'full' ,
                        'attr' => [
                            'class' => 'pxl-image-featured'
                        ]
                    ], $post->ID);
                    ?>
                    <?php if($i !== 0) : ?>
                        <hr class="pxl-post-divider">
                    <?php endif;?>
                    <div class="pxl-post-item">
                        <div class="pxl-post-featured">
                            <a href="<?php echo esc_url(get_permalink()); ?>">
                                <?php echo wp_kses_post($thumbnail); ?>
                            </a>
                        </div>
                        <div class="pxl-post-content">
                            <?php printf(
                                '<h5 class="pxl-post-title"><a href="%1$s" title="%2$s">%3$s</a></h5>',
                                esc_url( get_permalink() ),
                                esc_attr( get_the_title() ),
                                get_the_title()
                            ); ?>
                            <div class="pxl-post-date">
                                <?php echo get_the_date('F d, Y'); ?>
                            </div>
                        </div>
                    </div>
                <?php  $i++; endwhile; ?>
            </div>
        <?php endif;

        wp_reset_postdata();
        wp_reset_query();

        echo wp_kses_post($args['after_widget']);
    }

    function update( $new_instance, $old_instance )
    {
        $instance = $old_instance;
        $instance['title']         = sanitize_text_field( $new_instance['title'] );
        $instance['number']        = absint( $new_instance['number'] );
        $instance['post_in'] = strip_tags($new_instance['post_in']);
        return $instance;
    }

    function form( $instance )
    {
        $instance = wp_parse_args( (array) $instance, array(
            'title'         => esc_html__( 'Recent Posts', 'portfoliocraft' ),
            'number'        => 4,
        ) );

        $title         = $instance['title'];
        $number        = absint( $instance['number'] );
        $post_in = isset($instance['post_in']) ? esc_attr($instance['post_in']) : '';

        ?>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'portfoliocraft' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <p><label for="<?php echo esc_url($this->get_field_id('post_in')); ?>"><?php esc_html_e( 'Post in', 'portfoliocraft' ); ?></label>
            <select class="widefat" id="<?php echo esc_attr( $this->get_field_id('post_in') ); ?>" name="<?php echo esc_attr( $this->get_field_name('post_in') ); ?>">
                <option value="recent"<?php if( $post_in == 'recent' ){ echo 'selected="selected"';} ?>><?php esc_html_e('Recent', 'portfoliocraft'); ?></option>
                <option value="featured"<?php if( $post_in == 'featured' ){ echo 'selected="selected"';} ?>><?php esc_html_e('Featured', 'portfoliocraft'); ?></option>
            </select>
        </p>

        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_html_e( 'Number of posts to show:', 'portfoliocraft' ); ?></label>
            <input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $number ); ?>" size="3" />
        </p>

        <?php
    }
}
add_action( 'widgets_init', 'portfoliocraft_register_recent_widget' );
function portfoliocraft_register_recent_widget(){
    if(function_exists('pxl_register_wp_widget')){
        pxl_register_wp_widget( 'portfoliocraft_Recent_Posts_Widget' );
    }
}