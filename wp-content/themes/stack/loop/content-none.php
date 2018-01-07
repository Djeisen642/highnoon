<?php 
	global $wp_query; 
	
	if( isset($wp_query->query['post_type']) ){
		$post_type = $wp_query->query['post_type'];	
	}
?>

<?php if( isset($post_type) ) : ?>

	<h3><?php esc_html_e('This', 'stack'); ?> <?php echo esc_html($post_type); ?> <?php esc_html_e('feed contains no content.', 'stack'); ?></h3>
	<p><?php esc_html_e('Please add some', 'stack'); ?> <?php echo esc_html($post_type); ?> <?php esc_html_e('posts to load them here.', 'stack'); ?></p>
	<a href="<?php echo esc_url(admin_url('/edit.php?post_type=' . $post_type)); ?>" class="btn btn--primary"><span class="btn__text"><?php esc_html_e('Add', 'stack'); ?> <?php echo esc_html(ucfirst($post_type)); ?> <?php esc_html_e('Posts Now', 'stack'); ?> &rarr;</span></a>

<?php else : ?>

	<h3><?php esc_html_e('This feed contains no content.', 'stack'); ?></h3>

<?php endif; ?>
