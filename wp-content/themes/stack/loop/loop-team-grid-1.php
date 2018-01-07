<?php
	$i = 0;
	if ( have_posts() ) : while ( have_posts() ) : the_post();
		
		$i++;
		
		/**
		 * Get blog posts by blog layout.
		 */
		if(!( $i % 2 == 0 )){
			get_template_part('loop/content-team', 'grid-1');
		} else {
			get_template_part('loop/content-team', 'grid-1-alt');
		}
	
	endwhile;	
	else : 
		
		/**
		 * Display no posts message if none are found.
		 */
		get_template_part('loop/content','none');
		
	endif;