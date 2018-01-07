<?php 
	/**
	 * @author  TommusRhodus
	 * @version 9.9.9
	 */
	get_header(); 
	
	$display = get_option( 'woocommerce_shop_page_display' );
	global $wp_query;
	
	echo ebor_breadcrumb_section( get_option('stack_shop_title', 'Our Shop'), false, get_option('stack_shop_breadcrumb_image', '') );
?>

<section class="space--sm">
	<div class="container">
		<?php 
			if( 'both' == $display || 'subcategories' == $display ){
				
				get_template_part('loop/loop-product-categories', 'before');
				woocommerce_product_subcategories();
				get_template_part('loop/loop-product-categories', 'after');
				
			}
			
			if( have_posts() ){
				get_template_part('loop/loop-product', get_option('stack_shop_layout', 'column-3')); 
			}
		?>
	</div><!--end of container-->
</section>
            
<?php get_footer();