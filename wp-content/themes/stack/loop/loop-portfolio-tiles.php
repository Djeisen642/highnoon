<div class="masonry masonry--gapless">
    <div class="masonry__container masonry--active">
    	<?php
    		$i = 0;
    		
    		if ( have_posts() ) : while ( have_posts() ) : the_post();
    			
    			$i++;
    			
    			if( 4 == $i || 1 == $i ){
    				echo '<div class="masonry__item col-md-4 col-sm-6 col-xs-12">';
    			} else {
    				echo '<div class="masonry__item col-md-8 col-sm-6 col-xs-12">';
    			}
    			
    			if( 4 == $i ){
    				$i = 0;	
    			}
    			
    			/**
    			 * Get blog posts by blog layout.
    			 */
    			get_template_part('loop/content-portfolio', 'tiles');
    			
    			echo '</div>';
    		
    		endwhile;	
    		else : 
    			
    			/**
    			 * Display no posts message if none are found.
    			 */
    			get_template_part('loop/content','none');
    			
    		endif;
    	?>
    </div>
</div>