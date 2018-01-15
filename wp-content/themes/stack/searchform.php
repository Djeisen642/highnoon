<form class="form--horizontal" method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div class="col-sm-8">
		<input type="text" name="s" placeholder="<?php esc_attr_e( 'Type search keywords here', 'stack' ); ?>" />
	</div>
	<div class="col-sm-4">
		<button type="submit" class="btn btn--primary type--uppercase"><?php esc_html_e( 'Search', 'stack' ); ?></button>
	</div>
</form>