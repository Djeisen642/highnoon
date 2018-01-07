<?php
/**
 * @author  TommusRhodus
 * @version 9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $related_products ) : 

$layout = get_option('stack_single_product_layout', 'single-product');
$columns = ( 'single-product-sidebar' == $layout ) ? 'column-3' : 'column-4';

?>
	
	<div class="row">
		<div class="col-sm-12">
			<h3><?php esc_html_e( 'Related Products', 'stack' ); ?></h3>
			<div class="masonry">
				<div class="row">
					<div class="masonry__container">
					
						<?php
							if( 'single-product-sidebar' == $layout ){
								echo '<div class="masonry__item col-sm-4"></div>';
							} else {
								echo '<div class="masonry__item col-sm-3"></div>';	
							}
						?>
						
						<?php woocommerce_product_loop_start(); ?>
						
							<?php foreach ( $related_products as $related_product ) : ?>
				
								<?php
								 	$post_object = get_post( $related_product->get_id() );
				
									setup_postdata( $GLOBALS['post'] =& $post_object );
				
									get_template_part('loop/content-product', $columns); ?>
				
							<?php endforeach; ?>
				
						<?php woocommerce_product_loop_end(); ?>
					</div><!--end masonry container-->
				</div><!--end of row-->
			</div><!--end masonry-->
		</div>
	</div><!--end of row-->

<?php endif;

wp_reset_postdata();