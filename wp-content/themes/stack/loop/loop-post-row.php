<?php
	if ( have_posts() ) : while ( have_posts() ) : the_post();
	
		/**
		 * Get blog posts by blog layout.
		 */
		get_template_part('loop/content-post', 'row');
	
	endwhile;	
	else : 
		
		/**
		 * Display no posts message if none are found.
		 */
		get_template_part('loop/content','none');
		
	endif;
?>

<section>
	<div class="container">
		<div class="row">
			<?php get_template_part( 'inc/content-pagination', get_option( 'stack_pagination_layout', 'text' ) ); ?>
		</div>
	</div>
</section>