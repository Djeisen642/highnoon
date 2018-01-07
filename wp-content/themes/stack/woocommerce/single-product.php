<?php
	/**
	 * @author 		TommusRhodus
	 * @version     9.9.9
	 */
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	
	get_header(); 
	echo ebor_breadcrumb_section( get_option('stack_shop_title', 'Our Shop') );
	get_template_part('woocommerce/layout', get_option('stack_single_product_layout', 'single-product'));
	get_footer( 'shop' );
