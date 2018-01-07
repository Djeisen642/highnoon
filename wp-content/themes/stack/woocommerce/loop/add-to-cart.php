<?php
/**
 * @author 		TommusRhodus
 * @version     9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if( 'traditional' == get_option('stack_add_to_cart', 'modern') ){
	
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s btn btn--icon btn--primary type--uppercase"><span class="btn__text"><i class="stack-basket"></i> '. esc_html__('Add To Cart', 'stack') .'</span></a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			str_replace('button p', 'p', esc_attr( isset( $class ) ? $class : 'button' )),
			esc_html( $product->add_to_cart_text() )
		),
	$product );
	
} else {
	
	echo apply_filters( 'woocommerce_loop_add_to_cart_link',
		sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s btn btn--cart"><i class="stack-basket"></i></a>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			str_replace('button p', 'p', esc_attr( isset( $class ) ? $class : 'button' )),
			esc_html( $product->add_to_cart_text() )
		),
	$product );
	
}