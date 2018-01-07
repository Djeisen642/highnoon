<?php
	global $wp_query;

	$class = 'col-sm-6 col-md-3 testimonial-avatar';
	
	if( $wp_query->post_count == '2' ){
		$class = 'col-sm-6 col-md-6 testimonial-avatar';	
	} elseif( $wp_query->post_count == '3' ){
		$class = 'col-sm-6 col-md-4 testimonial-avatar';	
	}
?>

<div class="<?php echo esc_attr($class); ?>">
	<div class="testimonial testimonial-2">
		<div class="testimonial__body boxed boxed--border bg--secondary">
			<?php the_content(); ?>
		</div>
		<div class="testimonial__image">
			<?php 
				the_post_thumbnail('thumbnail');
				the_title('<h5>', '</h5><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); 
			?>
		</div>
	</div>
</div>