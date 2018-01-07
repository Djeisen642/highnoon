<?php 
	if( isset($wp_query->slider_options) ){
		$options = $wp_query->slider_options;
		$params = 'data-paging="'. $options['paging'] .'" data-arrows="'. $options['arrows'] .'" data-timing="'. $options['timing'] .'"';
	} else {
		$params = 'data-arrows="true"';
	}
?>

<div class="slider" <?php echo wp_kses_post($params); ?>>
    <ul class="slides slides--gapless">
        <?php
        	if ( have_posts() ) : while ( have_posts() ) : the_post();
        	
        		/**
        		 * Get blog posts by blog layout.
        		 */
        		get_template_part('loop/content-post', 'carousel');
        	
        	endwhile;	
        	else : 
        		
        		/**
        		 * Display no posts message if none are found.
        		 */
        		get_template_part('loop/content','none');
        		
        	endif;
        ?>
    </ul>
</div>