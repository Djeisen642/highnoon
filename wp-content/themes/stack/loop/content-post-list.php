<article id="post-<?php the_ID(); ?>" <?php post_class('masonry__item col-sm-12'); ?>>

	<div class="article__title text-center">
		<?php 
			the_title('<a href="'. get_permalink() .'"><h2>', '</h2></a>'); 
			get_template_part('inc/content-post', 'meta');	
		?>
	</div><!--end article title-->
	
	<div class="article__body">
		<?php the_content(esc_html__('Continue reading &raquo;', 'stack')); ?>
	</div>
	
</article><!--end item-->