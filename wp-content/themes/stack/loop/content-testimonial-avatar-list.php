<div class="col-sm-12 testimonial-avatar avatar-list">
	<div class="testimonial testimonial-2">
		<div class="testimonial__body boxed boxed--border bg--secondary lead">
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