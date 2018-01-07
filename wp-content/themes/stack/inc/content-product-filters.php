<div class="masonry-filter-container">
	<span><?php esc_html_e('Category:', 'stack'); ?></span>
	<div class="masonry-filter-holder masonry-filter-holder-post">
		<div class="masonry__filters blog-filters">
			<ul>
				<?php 
					echo '<li class="active js-no-action">'. esc_html__('All Categories', 'stack') .'</li>';
					wp_list_categories('taxonomy=product_cat&title_li='); 
				?>
			</ul>
		</div>
	</div>
</div>