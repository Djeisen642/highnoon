<div class="row text-center">
	<?php
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			/**
			 * Get blog posts by blog layout.
			 */
			get_template_part('loop/content-team', 'grid-3');
		
		endwhile;	
		else : 
			
			/**
			 * Display no posts message if none are found.
			 */
			get_template_part('loop/content','none');
			
		endif;
	?>
</div><!--end of row-->