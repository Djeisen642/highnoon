<?php
	if( 'no' == get_option('stack_product_rating', 'no') ){
		return false;	
	}
	
	global $product;
?>

<span class="block"><?php echo wc_get_rating_html( $product->get_average_rating() ); ?></span>