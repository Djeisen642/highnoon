<div class="masonry masonry--gapless">

	<?php get_template_part('inc/content-portfolio', 'filters'); ?>
	
	<div class="masonry__container bg--secondary">
		<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();
			
				/**
				 * Get blog posts by blog layout.
				 */
				get_template_part('loop/content-portfolio', 'fullwidth-2');
			
			endwhile;	
			else : 
				
				/**
				 * Display no posts message if none are found.
				 */
				get_template_part('loop/content','none');
				
			endif;
		?>
	</div><!--end of masonry__container-->
	
</div><!--end of masonry-->