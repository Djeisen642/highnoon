<li>
	<div class="row">
		<div class="testimonial text-center">
			<div class="col-sm-12">
				<span class="h3">&ldquo;<?php echo get_the_content(); ?>&rdquo;
				</span>
				<?php the_title('<h5>', '</h5><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
			</div>
		</div>
	</div><!--end of row-->
</li>