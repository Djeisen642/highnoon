<div class="row">
    <div class="masonry">
        
        <?php get_template_part( 'inc/content-portfolio', 'filters' ); ?>
        
        <div class="masonry__container">
        	<?php
        		if ( have_posts() ) : while ( have_posts() ) : the_post();
        		
        			/**
        			 * Get blog posts by blog layout.
        			 */
        			get_template_part( 'loop/content-portfolio', 'creative' );
        		
        		endwhile;	
        		else : 
        			
        			/**
        			 * Display no posts message if none are found.
        			 */
        			get_template_part( 'loop/content','none' );
        			
        		endif;
        	?>
        </div><!--end of masonry container-->
        
    </div><!--end masonry-->
</div><!--end of row-->