<?php 

if(!( function_exists('ebor_woocommerce_page_template') )){
	function ebor_woocommerce_page_template( $template ){
		global $post;
		
		if( is_archive() || is_404() || is_home() || !( isset($post->post_content) ) || is_search() )
			return $template;
			
		if( 
			has_shortcode($post->post_content, 'woocommerce_cart') || 
			has_shortcode($post->post_content, 'woocommerce_checkout') || 
			has_shortcode($post->post_content, 'woocommerce_my_account') 
		){
			
			$new_template = locate_template( array( 'page_woocommerce.php' ) );
			
			if (!( '' == $new_template )){
				return $new_template;
			}
			
		}
		
		return $template;
	}
	add_filter( 'template_include', 'ebor_woocommerce_page_template', 99 );
}

//Show cart count
if(!( function_exists('ebor_ajax_cart_total') )){
	function ebor_ajax_cart_total( $fragments ) {
		global $woocommerce;
		ob_start();
		echo '<div class="ebor-cart-count">'. $woocommerce->cart->get_cart_contents_count() .'</div>';
		$fragments['div.ebor-cart-count'] = ob_get_clean();
		return $fragments;
	}
	add_filter('woocommerce_add_to_cart_fragments', 'ebor_ajax_cart_total');
}

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

if(!( function_exists('ebor_price_html') )){
	function ebor_price_html( $price, $product ){
		$find = array('<span class="woocommerce-Price-amount amount">', '<del><span class="h4 inline-block">', '</del>', '<ins><span class="h4 inline-block">', '</ins>');
		$replace = array('<span class="h4 inline-block">', '<span class="h4 inline-block type--strikethrough">', '', '<span class="h4 inline-block">', '');
		$price = str_replace($find, $replace, $price);                                              
	    return $price;
	}
	add_filter( 'woocommerce_get_price_html', 'ebor_price_html', 100, 2 );
}