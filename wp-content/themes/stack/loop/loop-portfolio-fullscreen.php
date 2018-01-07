<?php 
	if( isset($wp_query->slider_options) ){
		$options = $wp_query->slider_options;
		$params = 'data-paging="'. $options['paging'] .'" data-arrows="'. $options['arrows'] .'" data-timing="'. $options['timing'] .'"';
	} else {
		$params = 'data-paging="true" data-arrows="true" data-timing="9000"';
	}
?>

<section class="cover cover-fullscreen height-100 imagebg slider text-center" <?php echo wp_kses_post($params); ?>>
    <ul class="slides">
        <?php
        	if ( have_posts() ) : while ( have_posts() ) : the_post();
        	
        		/**
        		 * Get blog posts by blog layout.
        		 */
        		get_template_part('loop/content-portfolio', 'fullscreen');
        	
        	endwhile;	
        	else : 
        		
        		/**
        		 * Display no posts message if none are found.
        		 */
        		get_template_part('loop/content','none');
        		
        	endif;
        ?>
    </ul>
</section>