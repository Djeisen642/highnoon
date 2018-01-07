<div class="row">

    <div class="col-sm-12 col-md-7">
    
        <ul class="results-list">
        	<?php
        		if ( have_posts() ) : while ( have_posts() ) : the_post();
        		
        			/**
        			 * Get blog posts by blog layout.
        			 */
        			get_template_part('loop/content-post', 'row-detailed');
        		
        		endwhile;	
        		else : 
        			
        			/**
        			 * Display no posts message if none are found.
        			 */
        			get_template_part('loop/content','none');
        			
        		endif;
        	?>
        </ul>
        
        <?php get_template_part('inc/content-pagination', get_option('stack_pagination_layout', 'text')); ?>
        
    </div>
    
    <div class="col-md-1 col-sm-12"></div>
    
    <?php get_template_part('sidebar'); ?>
    
</div><!--end of row-->