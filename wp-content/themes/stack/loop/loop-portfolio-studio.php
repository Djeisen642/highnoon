<div class="masonry masonry--gapless">
    <div class="masonry__container">
    	<?php
    		$i = 0;
    		$haystack = array(2,3,4,5,8,9,10,11,14,15,16,17,20,21,22,23);
    		
    		if ( have_posts() ) : while ( have_posts() ) : the_post();
    		
    			$i++;
    			
    			if( in_array($i, $haystack) ){
    				get_template_part('loop/content-portfolio', 'studio');
    			} else {
    				get_template_part('loop/content-portfolio', 'studio-large');
    			}
    		
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