<?php $tax_query = $wp_query->get( 'tax_query' ); ?>

<span><?php esc_html_e('Category:', 'stack'); ?></span>
<div class="masonry-filter-holder masonry-filter-holder-post">
	<div class="masonry__filters blog-filters">
		<ul>
			<?php 
				if( $tax_query ){
					$idObj = get_category_by_slug($tax_query[0]['terms']);
					echo '<li class="active js-no-action">'. $idObj->name .'</li>';
					wp_list_categories('child_of='. $idObj->term_id .'&title_li=');
				} else {
					echo '<li class="active js-no-action">'. esc_html__('All Categories', 'stack') .'</li>';
					wp_list_categories('title_li='); 
				}
			?>
		</ul>
	</div>
</div>