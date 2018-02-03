<?php
	if ( have_posts() ) : 
		
		echo '<div class="masonry"><div class="row masonry__container">';
		
	while ( have_posts() ) : the_post();
		
		/**
		 * Get blog posts by blog layout.
		 */
		get_template_part('loop/content-post', 'search');
	
	endwhile;	
	
	echo '</div></div>';
	
	else : 
		
		/**
		 * Display no posts message if none are found.
		 */
		get_template_part('loop/content', 'search-none');
		
	endif;
	
	get_template_part( 'inc/content-pagination', get_option( 'stack_pagination_layout', 'text' ) );