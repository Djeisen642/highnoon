<?php
	global $product;
	
	// Ensure visibility
	if ( ! $product || ! $product->is_visible() ) {
		return;
	}
?>

<div class="masonry__item col-sm-6">
	<div class="product product product--tile bg--secondary text-center">
	
		<?php 
			if( $product->is_on_sale() ){
				echo '<span class="label">'. esc_html__('Sale', 'stack') .'</span>';
			}
		?>
		
		<?php if( has_post_thumbnail() ) : ?>
			<a href="<?php the_permalink(); ?>">
				<?php the_post_thumbnail('large'); ?>
			</a>
		<?php endif; ?>
		
		<a class="block" href="<?php the_permalink(); ?>">
			<div>
				<?php the_title('<h5>', '</h5><span>'. ebor_the_terms('product_cat', ', ', 'name') .'</span>'); ?>
				<?php get_template_part('inc/content-product', 'rating'); ?>
				<?php get_template_part('inc/content-product', 'excerpt'); ?>
			</div>
			<div>
				<?php woocommerce_template_loop_price(); ?>
			</div>
		</a>
		
		<?php echo woocommerce_template_loop_add_to_cart(); ?>
		
	</div>
</div><!--end item-->