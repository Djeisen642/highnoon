<div class="row">
	<div class="col-md-10 col-md-offset-1">

		<div class="text-block">
		    <?php the_title('<h2>', '</h2><span>'. get_post_meta($post->ID, '_ebor_the_job_title', 1) .'</span>'); ?>
		</div>
		
		<div class="article__body">
			<?php the_content(); ?>
		</div>
		
	</div>
</div>