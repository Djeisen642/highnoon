<?php 
	do_action( 'ebor_after_content' );
	
	do_action( 'ebor_before_footer' );
	
	/**
	 * First, we need to check if we're going to override the header layout (with post meta)
	 * Or if we're going to display the global choice from the theme options.
	 * This is what ebor_get_header_layout is in charge of.
	 * 
	 * Oh yeah, exactly the same for the footer as well.
	 */
	get_template_part( 'inc/layout-footer', ebor_get_footer_layout() ); 
	
	do_action( 'ebor_after_footer' );
?>

</div><!-- /main-container -->

<?php 
	if( 'yes' == get_option( 'stack_btt_button', 'yes' ) ){
		get_template_part( 'inc/content', 'btt-button' );
	}
		
	wp_footer(); 
?>
</body>
</html>