<div id="post-<?php the_ID(); ?>" <?php post_class('masonry__item col-sm-6'); ?>>
	<article class="feature feature-1">
	
		<a href="<?php the_permalink(); ?>" class="block">
			<?php the_post_thumbnail('large'); ?>
		</a>
		
		<div class="feature__body boxed boxed--border">
			<?php
				if( is_sticky() ){
					echo '<span class="label">'. esc_html__('Sticky', 'stack') .'</span>';	
				}
			?>
			<span><?php the_time(get_option('date_format')); ?></span>
			<?php the_title('<a class="h5 display-block" href="'. get_permalink() .'">', '</a>'); ?>
			<a href="<?php the_permalink(); ?>"><?php esc_html_e('Read More', 'stack'); ?></a>
		</div>
		
	</article>
</div><!--end item-->