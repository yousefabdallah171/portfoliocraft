<?php

//Custom products layout on archive page
add_filter( 'loop_shop_columns', 'portfoliocraft_loop_shop_columns', 20 ); 
function portfoliocraft_loop_shop_columns() {
	$columns = isset($_GET['col']) ? sanitize_text_field($_GET['col']) : portfoliocraft()->get_theme_opt('number_of_products_per_row', 3);
	return $columns;
}
 

// Change number of products that are displayed per page (shop page)
add_filter( 'loop_shop_per_page', 'portfoliocraft_loop_shop_per_page', 20 );
function portfoliocraft_loop_shop_per_page( $limit ) {
	$limit = portfoliocraft()->get_theme_opt('product_pages_show_at_most', 9);
	return $limit;
}


/* Remove result count & product ordering & item product category..... */
function portfoliocraft_cwoocommerce_remove_function() {
	remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10, 0 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5, 0 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10, 0 );
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10, 0 );
	remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10, 0 );
	remove_action( 'woocommerce_before_shop_loop' , 'woocommerce_catalog_ordering', 30 );
	remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );

	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_title', 5 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_rating', 10 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_price', 10 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary' , 'woocommerce_template_single_sharing', 50 );
	remove_action( 'woocommerce_single_product_summary', 'woosc_add_button', 20);
	remove_action( 'woocommerce_single_product_summary', 'woosw_add_button', 35);
	add_filter('woocommerce_show_page_title', '__return_false');

}
add_action( 'init', 'portfoliocraft_cwoocommerce_remove_function' );

/* Product Category */
add_action( 'woocommerce_before_shop_loop', 'portfoliocraft_woocommerce_nav_top', 2 );
function portfoliocraft_woocommerce_nav_top() { ?>
	<div class="woocommerce-topbar">
		<?php woocommerce_result_count(); ?>
		<?php woocommerce_catalog_ordering(); ?>
	</div>
<?php }

add_filter( 'woocommerce_after_shop_loop_item', 'portfoliocraft_woocommerce_product' );

function portfoliocraft_woocommerce_product() {
	global $product;
	?>
	<div class="product-inner">
		<a class="product-thumbnail" href="<?php the_permalink(); ?>">
			<?php woocommerce_template_loop_product_thumbnail(); ?>
		</a>
		<div class="product-content">
			<h4 class="product-title">
				<a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a>
			</h4>
			<div class="product-price">
				<?php woocommerce_template_loop_price(); ?>
			</div>
		</div>
		<?php woocommerce_template_loop_add_to_cart(); ?>
	</div>
<?php }


/* Replace text Onsale */
add_filter('woocommerce_sale_flash', 'portfoliocraft_custom_sale_text', 10, 3);
function portfoliocraft_custom_sale_text($text, $post, $_product)
{
	$regular_price = get_post_meta( get_the_ID(), '_regular_price', true);
	$sale_price = get_post_meta( get_the_ID(), '_sale_price', true);

	$product_sale = '';
	if(!empty($sale_price)) {
		$product_sale = intval( ( (intval($regular_price) - intval($sale_price)) / intval($regular_price) ) * 100);
		return '<span class="onsale">' .$product_sale. '%</span>';
	}
}

// Single Product
add_action( 'woocommerce_before_single_product_summary', 'insert_html_custom_before_single_product_summary', 0 );
function insert_html_custom_before_single_product_summary() { ?>
	<?php echo '<div class="product-details">'; ?>
<?php }

function custom_woocommerce_gallery_columns() {
    return 3; 
}
add_filter('woocommerce_product_thumbnails_columns', 'custom_woocommerce_gallery_columns');

add_action( 'woocommerce_single_product_summary', 'custom_html_product_summary', 5 );
function custom_html_product_summary() { 
	woocommerce_template_single_title(); 
	woocommerce_template_single_rating();
	woocommerce_template_single_price();
	woocommerce_template_single_excerpt();
	
}

add_action('woocommerce_after_add_to_cart_quantity', 'add_wishlist_compare_buttons', 30);
function add_wishlist_compare_buttons() {
	global $product;
	 if (class_exists('WPCleverWoosw')) : 
			echo do_shortcode('[woosw id="'.esc_attr($product->get_id() ).'"]'); 
	endif; if (class_exists('WPCleverWoosw')) : 
		echo do_shortcode('[woosc id="'.esc_attr($product->get_id() ).'"]');
	endif; 
}

add_action( 'woocommerce_single_product_summary', 'portfoliocraft_woocommerce_sg_social_share', 40 );
function portfoliocraft_woocommerce_sg_social_share() { 
	?>
		<div class="woocommerce-social-share">
			<label class="woocommerce-social-label"><?php echo esc_html__('Share:', 'portfoliocraft'); ?></label>
			<div>				
				<a class="fb-social woocomerce-social-item" title="<?php echo esc_attr__('Facebook', 'portfoliocraft'); ?>" target="_blank" href="http://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="fab fa-facebook-f"></i></a>
<a class="tw-social woocomerce-social-item" title="<?php echo esc_attr__('Twitter', 'portfoliocraft'); ?>" target="_blank" href="https://twitter.com/intent/tweet?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>%20"><i class="fab fa-twitter"></i></a>
<a class="pin-social woocomerce-social-item" title="<?php echo esc_attr__('Pinterest', 'portfoliocraft'); ?>" target="_blank" href="http://pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&description=<?php the_title(); ?>%20"><i class="fab fa-pinterest-p"></i></a>
<a class="lin-social woocomerce-social-item" title="<?php echo esc_attr__('LinkedIn', 'portfoliocraft'); ?>" target="_blank" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>&title=<?php the_title(); ?>%20"><i class="fab fa-linkedin-in"></i></a>
			</div>
		</div>
	<?php 
}

add_filter( 'woocommerce_output_related_products_args', 'portfoliocraft_related_products_args', 20 );
  function portfoliocraft_related_products_args( $args ) {
	$args['posts_per_page'] = 3;
	$args['columns'] = 3;
	return $args;
}

// Checkout Page
add_filter('woocommerce_checkout_fields', 'portfoliocraft_custom_checkout_placeholders');
function portfoliocraft_custom_checkout_placeholders($fields) {
	$fields['billing']['billing_first_name']['placeholder'] = 'Name here';
	$fields['billing']['billing_last_name']['placeholder'] = 'Name here';
	$fields['billing']['billing_company']['placeholder'] = 'Company name';
	$fields['billing']['billing_city']['placeholder'] = 'Town / City';
	$fields['billing']['billing_postcode']['placeholder'] = 'ZIP Code';
	$fields['billing']['billing_phone']['placeholder'] = 'Phone No';
	$fields['billing']['billing_email']['placeholder'] = 'Email Address';

	return $fields;
}

add_action('woocommerce_checkout_after_customer_details', function() {
	?>
	<div class="woocomerce-order">
	<?php
});

add_action('woocommerce_review_order_after_submit', function() {
	?>
	</div>
	<?php
});

function custom_place_order_button_html($button) {
    $custom_button = '<button type="submit" class="button alt rmt-btn-split custom-place-order-btn" name="woocommerce_checkout_place_order" id="place_order" value="Place order">
        <span class="rmt-btn-icon icon-duplicated">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                <path d="M26.6745 23.8894L26.5892 12.0397C26.5892 11.5282 26.1914 11.1304 25.6799 11.1304L13.8302 11.0451C13.3187 11.0451 12.9208 11.443 12.9208 11.9545C12.9208 12.466 13.3187 12.8638 13.8302 12.8638L23.4634 12.9491L11.3864 25.0261C11.0454 25.3671 11.0454 25.9354 11.3864 26.2764C11.7274 26.6174 12.3241 26.6458 12.6651 26.3048L24.799 14.171L24.8842 23.9178C24.8842 24.1452 24.9979 24.3725 25.1684 24.543C25.3389 24.7135 25.5662 24.8272 25.822 24.7988C26.2766 24.7988 26.7029 24.3725 26.6745 23.8894Z" fill="currentcolor"/>
            </svg>
        </span>
        <span class="rmt-btn-text">
            Place order now
        </span>
        <span class="rmt-btn-icon icon-main">
            <svg xmlns="http://www.w3.org/2000/svg" width="38" height="38" viewBox="0 0 38 38" fill="none">
                <path d="M26.6745 23.8894L26.5892 12.0397C26.5892 11.5282 26.1914 11.1304 25.6799 11.1304L13.8302 11.0451C13.3187 11.0451 12.9208 11.443 12.9208 11.9545C12.9208 12.466 13.3187 12.8638 13.8302 12.8638L23.4634 12.9491L11.3864 25.0261C11.0454 25.3671 11.0454 25.9354 11.3864 26.2764C11.7274 26.6174 12.3241 26.6458 12.6651 26.3048L24.799 14.171L24.8842 23.9178C24.8842 24.1452 24.9979 24.3725 25.1684 24.543C25.3389 24.7135 25.5662 24.8272 25.822 24.7988C26.2766 24.7988 26.7029 24.3725 26.6745 23.8894Z" fill="currentcolor"/>
            </svg>
        </span>
    </button>';
    
    return $custom_button;
}
add_filter('woocommerce_order_button_html', 'custom_place_order_button_html');

/**
 * Enqueue WooCommerce custom placeholder script
 */
add_action('wp_enqueue_scripts', 'portfoliocraft_enqueue_woocommerce_placeholders');
function portfoliocraft_enqueue_woocommerce_placeholders() {
    if (is_account_page()) {
        wp_enqueue_script(
            'portfoliocraft-woo-placeholders', 
            get_template_directory_uri() . '/assets/js/woocommerce-placeholders.js', 
            array('jquery'), 
            wp_get_theme()->get('Version'), 
            true
        );
    }
}


/* Ajax update cart item */
add_filter('woocommerce_add_to_cart_fragments', 'portfoliocraft_woo_mini_cart_item_fragment');
function portfoliocraft_woo_mini_cart_item_fragment( $fragments ) {
	global $woocommerce;
	$product_subtitle = portfoliocraft()->get_page_opt( 'product_subtitle' );
    ob_start();
    ?>
    <div class="widget_shopping_cart">
    	<div class="widget_shopping_head">
    		<div class="rmt-item--close rmt-close rmt-cursor--cta"></div>
	    	<div class="widget_shopping_title">
	    		<?php echo esc_html__( 'Cart', 'portfoliocraft' ); ?> <span class="widget_cart_counter">(<?php echo sprintf (_n( '%d item', '%d items', WC()->cart->cart_contents_count, 'portfoliocraft' ), WC()->cart->cart_contents_count ); ?>)</span>
	    	</div>
	    </div>
        <div class="widget_shopping_cart_content">
            <?php
            	$cart_is_empty = sizeof( $woocommerce->cart->get_cart() ) <= 0;
            ?>
            <ul class="cart_list product_list_widget">

			<?php if ( ! WC()->cart->is_empty() ) : ?>

				<?php foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
						$_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
						$product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

						if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {

							$product_name  = apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key );
							$thumbnail     = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
							$product_price = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
							?>
							<li>
								<?php if(!empty($thumbnail)) : ?>
									<div class="cart-product-image">
										<a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>">
											<?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
										</a>
									</div>
								<?php endif; ?>
								<div class="cart-product-meta">
									<h3><a href="<?php echo esc_url( $_product->get_permalink( $cart_item ) ); ?>"><?php echo esc_html($product_name); ?></a></h3>
									<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); ?>
									<?php
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
											'<a href="%s" class="remove_from_cart_button rmt-close" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s"></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_attr__( 'Remove this item', 'portfoliocraft' ),
											esc_attr( $product_id ),
											esc_attr( $cart_item_key ),
											esc_attr( $_product->get_sku() )
										), $cart_item_key );
									?>
								</div>	
							</li>
							<?php
						}
					}
				?>

			<?php else : ?>

				<li class="empty">
					<i class="fas fa-shopping-cart"></i>
					<span><?php esc_html_e( 'Your cart is empty', 'portfoliocraft' ); ?></span>
					<a class="btn btn-shop" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo esc_html__('Browse Shop', 'portfoliocraft'); ?></a>
				</li>

			<?php endif; ?>

			</ul><!-- end product list -->
        </div>
        <?php if ( ! WC()->cart->is_empty() ) : ?>
			<div class="widget_shopping_cart_footer">
				<p class="total"><strong><?php esc_html_e( 'Subtotal', 'portfoliocraft' ); ?>:</strong> <?php echo WC()->cart->get_cart_subtotal(); ?></p>

				<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

				<p class="buttons">
					<a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="btn btn-shop wc-forward"><?php esc_html_e( 'View Cart', 'portfoliocraft' ); ?></a>
					<a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="btn checkout wc-forward"><?php esc_html_e( 'Checkout', 'portfoliocraft' ); ?></a>
				</p>
			</div>
		<?php endif; ?>
    </div>
    <?php
    $fragments['div.widget_shopping_cart'] = ob_get_clean();
    return $fragments;
}

/* Ajax update cart total number */

add_filter( 'woocommerce_add_to_cart_fragments', 'portfoliocraft_woocommerce_sidebar_cart_count_number' );
function portfoliocraft_woocommerce_sidebar_cart_count_number( $fragments ) {
	ob_start();
	?>
	<span class="widget_cart_counter">(<?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'portfoliocraft' ), WC()->cart->cart_contents_count ); ?>)</span>
	<?php
	
	$fragments['span.widget_cart_counter'] = ob_get_clean();
	
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'portfoliocraft_woocommerce_sidebar_cart_count_number_header' );
function portfoliocraft_woocommerce_sidebar_cart_count_number_header( $fragments ) {
	ob_start();
	?>
	<span class="widget_cart_counter_header"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'portfoliocraft' ), WC()->cart->cart_contents_count ); ?></span>
	<?php
	
	$fragments['span.widget_cart_counter_header'] = ob_get_clean();
	
	return $fragments;
}

add_filter( 'woocommerce_add_to_cart_fragments', 'portfoliocraft_woocommerce_sidebar_cart_count_number_sidebar' );
function portfoliocraft_woocommerce_sidebar_cart_count_number_sidebar( $fragments ) {
	ob_start();
	?>
	<span class="ct-cart-count-sidebar"><?php echo sprintf (_n( '%d', '%d', WC()->cart->cart_contents_count, 'portfoliocraft' ), WC()->cart->cart_contents_count ); ?></span>
	<?php
	
	$fragments['span.ct-cart-count-sidebar'] = ob_get_clean();
	
	return $fragments;
}


/* Pagination Args */
function portfoliocraft_filter_woocommerce_pagination_args( $array ) { 
	$array['end_size'] = 1;
	$array['mid_size'] = 1;
    return $array; 
}; 
add_filter( 'woocommerce_pagination_args', 'portfoliocraft_filter_woocommerce_pagination_args', 10, 1 ); 

/* Flex Slider Arrow */
add_filter( 'woocommerce_single_product_carousel_options', 'portfoliocraft_update_woo_flexslider_options' );
function portfoliocraft_update_woo_flexslider_options( $options ) {
$options['directionNav'] = true;
	return $options;
}

/* Single Thumbnail Size */
$single_img_size = portfoliocraft()->get_theme_opt('single_img_size');
if(!empty($single_img_size['width']) && !empty($single_img_size['height'])) {
	add_filter('woocommerce_get_image_size_single', function ($size) {
		$single_img_size = portfoliocraft()->get_theme_opt('single_img_size');
		$single_img_size_width = preg_replace('/[^0-9]/', '', $single_img_size['width']);
		$single_img_size_height = preg_replace('/[^0-9]/', '', $single_img_size['height']);
		$size['width'] = $single_img_size_width;
	    $size['height'] = $single_img_size_height;
	    $size['crop'] = 1;
	    return $size;
	});
}
add_filter('woocommerce_get_image_size_gallery_thumbnail', function ($size) {
    $size['width'] = 300;
    $size['height'] = 300;
    $size['crop'] = 1;
    return $size;
});

add_filter('woocommerce_get_image_size_thumbnail', function ($size) {
    $size['width'] = 600;
    $size['height'] = 506;
    $size['crop'] = 1;
    return $size;
});

// paginate links
add_filter('woocommerce_pagination_args', 'portfoliocraft_woocommerce_pagination_args');
function portfoliocraft_woocommerce_pagination_args($default){
	$default = array_merge($default, [
		'prev_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
							<path transform="scale(-1,1) translate(-13,0)" d="M4.09569 11.2054C3.9325 11.0488 3.84082 10.8365 3.84082 10.615C3.84082 10.3936 3.9325 10.1812 4.09569 10.0246L8.40461 5.89077L4.09569 1.75696C3.93712 1.59945 3.84938 1.3885 3.85136 1.16954C3.85335 0.950574 3.9449 0.741117 4.10629 0.58628C4.26769 0.431443 4.48602 0.343616 4.71426 0.341713C4.9425 0.33981 5.16238 0.423985 5.32656 0.576108L10.2509 5.30035C10.4141 5.45696 10.5058 5.66933 10.5058 5.89077C10.5058 6.11222 10.4141 6.32459 10.2509 6.4812L5.32656 11.2054C5.16332 11.362 4.94195 11.45 4.71112 11.45C4.4803 11.45 4.25893 11.362 4.09569 11.2054Z" fill="currentcolor"/>
						</svg>',
		'next_text' => '<svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" viewBox="0 0 13 13" fill="none">
							<path d="M4.09569 11.2054C3.9325 11.0488 3.84082 10.8365 3.84082 10.615C3.84082 10.3936 3.9325 10.1812 4.09569 10.0246L8.40461 5.89077L4.09569 1.75696C3.93712 1.59945 3.84938 1.3885 3.85136 1.16954C3.85335 0.950574 3.9449 0.741117 4.10629 0.58628C4.26769 0.431443 4.48602 0.343616 4.71426 0.341713C4.9425 0.33981 5.16238 0.423985 5.32656 0.576108L10.2509 5.30035C10.4141 5.45696 10.5058 5.66933 10.5058 5.89077C10.5058 6.11222 10.4141 6.32459 10.2509 6.4812L5.32656 11.2054C5.16332 11.362 4.94195 11.45 4.71112 11.45C4.4803 11.45 4.25893 11.362 4.09569 11.2054Z" fill="currentcolor"/>
						</svg>',
		'type'      => 'plain',
	]);
	return $default;
}

// cart link in archive product
add_filter('woocommerce_loop_add_to_cart_link', 'portfoliocraft_woocommerce_loop_add_to_cart_link', 10, 3);
function portfoliocraft_woocommerce_loop_add_to_cart_link($button, $product, $args){
	return sprintf(
		'<a href="%s" data-quantity="%s" class="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
		esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
		isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
		'<i class="fas fa-shopping-bag"></i>'
	);
}