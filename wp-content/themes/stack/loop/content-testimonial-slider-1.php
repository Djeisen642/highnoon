<li>
	<div class="row">
		<div class="testimonial">
			<div class="col-md-2 col-md-offset-1 col-sm-4 col-xs-12 text-center">
				<?php the_post_thumbnail('large', array('class' => 'testimonial__image')); ?>
			</div>
			<div class="col-md-7 col-md-offset-1 col-sm-8 col-xs-12">
				<span class="h3">&ldquo;<?php echo get_the_content(); ?>&rdquo;
				</span>
				<?php the_title('<h5>', '</h5><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
			</div>
		</div>
	</div><!--end of row-->
</li>