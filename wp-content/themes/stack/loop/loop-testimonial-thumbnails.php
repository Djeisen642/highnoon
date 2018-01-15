<div class="slider thumbnails-slider" data-paging="false" data-autoplay="true">
	
	<ul class="slides">
		<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();
			
				/**
				 * Get blog posts by blog layout.
				 */
				get_template_part( 'loop/content-testimonial', 'thumbnails' );
			
			endwhile;	
			else : 
				
				/**
				 * Display no posts message if none are found.
				 */
				get_template_part( 'loop/content', 'none' );
				
			endif;
			
			rewind_posts();
		?>
	</ul>
	
	<div class="row text-center">
		<?php
			if ( have_posts() ) : while ( have_posts() ) : the_post();
			
				/**
				 * Get blog posts by blog layout.
				 */
				get_template_part( 'loop/content-testimonial', 'thumbnails-trigger' );
			
			endwhile;	
			else : 
				
				/**
				 * Display no posts message if none are found.
				 */
				get_template_part( 'loop/content', 'none' );
				
			endif;
		?>
	</div>
	
</div>