<div class="row">
	<div class="col-sm-12">
		<div class="masonry">
			<?php get_template_part('inc/content-product', 'filters'); ?>
			<div class="row">
				<div class="masonry__container">
					<div class="masonry__item col-sm-4"></div>
					<?php
						if ( have_posts() ) : while ( have_posts() ) : the_post();
						
							/**
							 * Get blog posts by blog layout.
							 */
							get_template_part('loop/content-product', 'column-3');
						
						endwhile;	
						else : 
							
							/**
							 * Display no posts message if none are found.
							 */
							get_template_part('loop/content','none');
							
						endif;
					?>
				</div><!--end masonry container-->
			</div><!--end of row-->
		</div><!--end masonry-->
	</div>
</div><!--end of row-->

<?php woocommerce_pagination(); ?>