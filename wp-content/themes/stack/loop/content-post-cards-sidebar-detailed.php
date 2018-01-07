<div id="post-<?php the_ID(); ?>" <?php post_class('masonry__item col-sm-6 col-md-6'); ?>>
	<article class="feature feature-1 pricing-1">
	
		<a href="<?php the_permalink(); ?>" class="block">
			<?php the_post_thumbnail('large'); ?>
		</a>
		
		<div class="feature__body boxed boxed--border">
		
			<?php
				if( is_sticky() ){
					echo '<span class="label">'. esc_html__('Sticky', 'stack') .'</span>';	
				}
			?>
			
			<span class="type--fine-print">
				<?php 
					the_time(get_option('date_format'));
					if( comments_open() ){
						comments_number( 
							esc_html__(' &bull; 0 Comments','stack'), 
							esc_html__(' &bull; 1 Comment','stack'), 
							esc_html__(' &bull; % Comments','stack') 
						); 
					}
				?>
			</span>
			
			<?php 
				the_title('<a class="h5 display-block" href="'. get_permalink() .'">', '</a>'); 
				echo wpautop(wp_trim_words(get_the_excerpt(), 15, '...')); 
			?>
			
			<a href="<?php the_permalink(); ?>" class="btn"><?php esc_html_e('Read More', 'stack'); ?></a>
			
		</div>
		
	</article>
</div><!--end item-->