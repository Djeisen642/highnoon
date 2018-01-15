<?php $class = ( 0 == $wp_query->current_post ) ? 'active' : ''; ?>

<div class="col-sm-1 col-xs-2 text-center thumbnail-trigger <?php echo esc_attr( $class ); ?>">
	<?php the_post_thumbnail('thumbnail'); ?>
</div>