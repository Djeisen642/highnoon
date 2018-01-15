<?php 
	get_header();
	the_post();
	
	if( is_singular( 'product' ) ){
		do_action('woocommerce_before_single_product');
	}
	
	the_content();
	get_footer();