<?php 
	$prev = get_previous_posts_link();
	$next = get_next_posts_link();
?>

<div class="pagination">

	<?php if( $next ) : ?>
		<?php preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $next, $next_url); ?>
		<div class="col-xs-6 text-left">
			<a class="type--fine-print" href="<?php echo esc_url($next_url[0][0]); ?>"><?php esc_html_e('&laquo; Older Posts', 'stack'); ?></a>
		</div>
	<?php endif; ?>
	
	<?php 
		if( $prev && !$next )
			echo '<div class="col-xs-6"></div>';
	?>
	
	<?php if( $prev ) : ?>
		<?php preg_match_all('#\bhttps?://[^\s()<>]+(?:\([\w\d]+\)|([^[:punct:]\s]|/))#', $prev, $prev_url); ?>
		<div class="col-xs-6 text-right">
			<a class="type--fine-print" href="<?php echo esc_url($prev_url[0][0]); ?>"><?php esc_html_e('Newer Posts &raquo;', 'stack'); ?></a>
		</div>
	<?php endif; ?>
	
</div>