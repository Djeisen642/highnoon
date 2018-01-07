<li class="text-center">
	<div class="testimonial">
		<blockquote>
			&ldquo;<?php echo get_the_content(); ?>&rdquo;	
		</blockquote>
		<?php the_title('<h5>', '</h5><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
	</div>
</li>