<div class="row">
    <?php
    	if ( have_posts() ) : while ( have_posts() ) : the_post();
    	
    		/**
    		 * Get blog posts by blog layout.
    		 */
    		get_template_part('loop/content', 'career');
    	
    	endwhile;	
    	else : 
    		
    		/**
    		 * Display no posts message if none are found.
    		 */
    		get_template_part('loop/content','none');
    		
    	endif;
    ?>
</div><!--end of row-->