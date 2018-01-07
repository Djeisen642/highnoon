<div class="row">
    <div class="masonry">
        
        <?php if( 'yes' == get_option('stack_show_loop_categories', 'yes') ) : ?>
        	<div class="masonry-filter-container">
        		<?php get_template_part('inc/content-post', 'categories'); ?>
        	</div>
        <?php endif; ?>
        
        <div class="masonry__container">
        	<?php
        		if ( have_posts() ) : while ( have_posts() ) : the_post();
        		
        			/**
        			 * Get blog posts by blog layout.
        			 */
        			get_template_part('loop/content-post', 'portfolio');
        		
        		endwhile;	
        		else : 
        			
        			/**
        			 * Display no posts message if none are found.
        			 */
        			get_template_part('loop/content','none');
        			
        		endif;
        	?>
        </div><!--end of masonry container-->
        
    </div><!--end masonry-->
</div><!--end of row-->