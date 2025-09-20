;(function ($) {

    "use strict";

    $(document).ready(function () {


        $('.single_variation_wrap').addClass('clearfix');
        $('.woocommerce-variation-add-to-cart').addClass('clearfix');

        $('.cart-total-wrap').on('click', function () {
            $('.widget-cart-sidebar').toggleClass('open');
            $(this).toggleClass('cart-open');
            $('.site-overlay').toggleClass('open');
        });

        $('.site-overlay').on('click', function () {
            $(this).removeClass('open');
            $(this).parents('#page').find('.widget-cart-sidebar').removeClass('open');
        });

        $('.woocommerce-tab-heading').on('click', function () {
            $(this).toggleClass('open');
            $(this).parent().find('.woocommerce-tab-content').slideToggle('');
        });

        $('.site-menu-right .h-btn-cart, .mobile-menu-cart .h-btn-cart').on('click', function (e) {
            e.preventDefault();
            $(this).parents('#ct-header-wrap').find('.widget_shopping_cart').toggleClass('open');
            $('.ct-hidden-sidebar').removeClass('open');
            $('.ct-search-popup').removeClass('open');
        });

        $('.woocommerce-add-to-cart a.button.product_type_grouped:not(".no-animate")').append( '<i class="fas fa-link"></i>' );

        $('.woocommerce-add-to-cart a.button').on('click', function () {
            $(this).parents('.woocommerce-product-inner').addClass('cart-added');
        });

        $('.woocommerce-archive-layout .layout-grid').on('click', function () {
            $(this).addClass('active');
            $(this).parent().find('.layout-list').removeClass('active');
            $(this).parents('.site-main').find('ul.products').addClass('ct-products-grid').removeClass('ct-products-list');
        });
         $('.woocommerce-archive-layout .layout-list').on('click', function () {
            $(this).addClass('active');
            $(this).parent().find('.layout-grid').removeClass('active');
            $(this).parents('.site-main').find('ul.products').addClass('ct-products-list').removeClass('ct-products-grid');
        });

        $('.woocommerce-archive-layout .layout-list.active').parents('.site-main').find('ul.products').addClass('ct-products-list').removeClass('ct-products-grid');

        setTimeout(function () {
            $('.ct-grid .product_type_variable, .ct-slick-slider .product_type_variable').removeAttr('data-product_id');
            $('.ct-product-carousel6.woocommerce .woocommerce-product-inner .woocommerce-add-to--cart .button').append( '<i class="fas fa-shopping-cart"></i>' );
            $('.ct-product-carousel9.woocommerce .woocommerce-product-inner .woocommerce-add-to--cart .button').append( '<i class="fas fa-shopping-cart"></i>' );
        }, 300);

        $(".woocommerce .products").on("click", ".quantity input", function() {
            return false;
        });
        $(".woocommerce .products").on("change input", ".quantity .qty", function() {
            var add_to_cart_button = $(this).parents( ".product" ).find(".add_to_cart_button");
            add_to_cart_button.attr('data-quantity', $(this).val());
            add_to_cart_button.attr("href", "?add-to-cart=" + add_to_cart_button.attr("data-product_id") + "&quantity=" + $(this).val());
        });
        $('.flex-viewport').parents('.woocommerce-gallery-inner').addClass('flex-slider-active');

        /* Add Placeholder Review Form */
        var $text_name = $('.single-product #review_form .comment-form-author label').text();
        $('.single-product #review_form .comment-form-author input').each(function (ev) {
            if (!$(this).val()) {
                $(this).attr("placeholder", $text_name);
            }
        });
        var $text_email = $('.single-product #review_form .comment-form-email label').text();
        $('.single-product #review_form .comment-form-email input').each(function (ev) {
            if (!$(this).val()) {
                $(this).attr("placeholder", $text_email);
            }
        });
        var $text_comment = $('.single-product #review_form .comment-form-comment label').text();
        $('.single-product #review_form .comment-form-comment textarea').each(function (ev) {
            if (!$(this).val()) {
                $(this).attr("placeholder", $text_comment);
            }
        });

        $('.rmt-item--attr .rmt-button--info').on('click', function () {
            $(this).toggleClass('active');
        });
    });

    $(window).on('load', function() {
        setTimeout(function() {
            let gallery = $('body.woocommerce .woocommerce-product-gallery');
            if (gallery.length > 0) {
                let viewport = gallery.find('.flex-viewport');
                if (viewport.length === 0) {
                    gallery.addClass('woocommerce-gallery-single')
                }
            }
        }, 250); 
    });

})(jQuery);




jQuery( document ).on( 'qv_loader_stop', function () {
    jQuery( this ).ready( function ( $ ) {
        $('#yith-quick-view-modal .quantity').append('<span class="quantity-icon quantity-down rmt-icon--caretdown"></span><span class="quantity-icon quantity-up rmt-icon--caretup"></span>');
        $('#yith-quick-view-modal .quantity-up').on('click', function () {
            $(this).parents('.quantity').find('input[type="number"]').get(0).stepUp();
        });
        $('#yith-quick-view-modal .quantity-down').on('click', function () {
            $(this).parents('.quantity').find('input[type="number"]').get(0).stepDown();
        });
    } );
} );
