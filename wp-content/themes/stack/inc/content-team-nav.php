<?php 
	$prev_post = get_adjacent_post(false, '', true);
	$next_post = get_adjacent_post(false, '', false);
	
	if( empty($prev_post) && empty($next_post) ){
		return false;
	}
	
	$image = get_option('stack_team_breadcrumb_image', '');
?>

<section class="unpad text-center ">
    <div class="row--gapless">
        
        <?php if( !empty( $prev_post ) ) : ?>
	        <div class="col-sm-4 col-xs-12">
	            <a href="<?php echo esc_url( get_permalink( $prev_post->ID ) ); ?>" class="block">
	                <div class="feature feature-7 boxed imagebg" data-overlay="5">
	                    <div class="background-image-holder">
	                        <?php echo get_the_post_thumbnail( $prev_post->ID, 'large' ); ?>
	                    </div>
	                    <h4 class="pos-vertical-center">
	                    	<span><?php esc_html_e( 'Previous Team Member', 'stack' ); ?></span>
	                    	<?php echo esc_html( $prev_post->post_title ); ?>
	                    </h4>
	                </div>
	            </a>
	        </div>
        <?php endif; ?>
        
        <div class="col-sm-4 col-xs-12">
            <a href="<?php echo get_post_type_archive_link( 'team' ); ?>" class="block">
                <div class="feature feature-7 boxed imagebg" data-overlay="5">
                    <div class="background-image-holder">
                        <?php 
                        	if( $image ){
                        		echo '<img alt="background" src="'. esc_url( $image ) .'">';
                        	}
                        ?>
                    </div>
                    <h4 class="pos-vertical-center">
                    	<span><?php echo get_option( 'stack_team_title', 'Our Team' ); ?></span>
                    </h4>
                </div>
            </a>
        </div>
        
        <?php if( !empty( $next_post ) ) : ?>
	        <div class="col-sm-4 col-xs-12">
	            <a href="<?php echo esc_url( get_permalink( $next_post->ID ) ); ?>" class="block">
	                <div class="feature feature-7 boxed imagebg" data-overlay="5">
	                    <div class="background-image-holder">
	                        <?php echo get_the_post_thumbnail( $next_post->ID, 'large' ); ?>
	                    </div>
	                    <h4 class="pos-vertical-center">
	                    	<span><?php esc_html_e( 'Next Team Member', 'stack' ); ?></span>
	                    	<?php echo esc_html( $next_post->post_title ); ?>
	                    </h4>
	                </div>
	            </a>
	        </div>
        <?php endif; ?>
        
    </div><!--end of row-->
</section>