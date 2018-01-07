<div class="article__share text-center">

	<a class="btn bg--facebook btn--icon" target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>">
		<span class="btn__text">
			<i class="socicon socicon-facebook"></i>
			<?php esc_html_e('Share on Facebook', 'stack'); ?>
		</span>
	</a>
	
	<a class="btn bg--twitter btn--icon" target="_blank" href="https://twitter.com/share?url=<?php the_permalink(); ?>">
		<span class="btn__text">
			<i class="socicon socicon-twitter"></i>
			<?php esc_html_e('Share on Twitter', 'stack'); ?>
		</span>
	</a>
	
</div>