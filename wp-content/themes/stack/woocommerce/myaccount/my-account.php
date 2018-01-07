<?php
	/**
	 * @author 		TommusRhodus
	 * @version     9.9.9
	 */
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	wc_print_notices();
?>

<div class="row">
	<div class="col-md-4">
		<div class="boxed boxed--lg boxed--border">
			<?php
				/**
				 * My Account navigation.
				 * @since 2.6.0
				 */
				do_action( 'woocommerce_account_navigation' ); 
			?>
		</div>
	</div>
	
	<div class="col-md-8">
		<div class="boxed boxed--lg boxed--border">
			<?php
				/**
				 * My Account content.
				 * @since 2.6.0
				 */
				do_action( 'woocommerce_account_content' );
			?>
		</div>
	</div>
</div>