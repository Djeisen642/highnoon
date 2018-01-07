<div class="row">

	<div class="col-sm-12 col-md-8">
		<div class="masonry">
		
			<?php if( 'yes' == get_option('stack_show_loop_categories', 'yes') ) : ?>
				<div class="masonry-filter-container">
					<?php get_template_part('inc/content-post', 'categories'); ?>
				</div>
				
				<hr>
			<?php endif; ?>
			
			<div class="row">
				<div class="masonry__container">
				
					<div class="masonry__item col-sm-6"></div>
					
					<?php
						if ( have_posts() ) : while ( have_posts() ) : the_post();
						
							/**
							 * Get blog posts by blog layout.
							 */
							get_template_part('loop/content-post', 'cards-sidebar');
						
						endwhile;	
						else : 
							
							/**
							 * Display no posts message if none are found.
							 */
							get_template_part('loop/content','none');
							
						endif;
					?>
	
				</div><!--end of masonry container-->
			</div><!--end row-->
			
			<?php get_template_part('inc/content-pagination', get_option('stack_pagination_layout', 'text')); ?>
			
		</div><!--end masonry-->
	</div>
	
	<?php get_template_part('sidebar'); ?>

</div>