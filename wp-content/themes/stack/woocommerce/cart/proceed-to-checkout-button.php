<?php
/**
 * @author  TommusRhodus
 * @version 9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>

<a href="<?php echo esc_url( wc_get_checkout_url() ) ;?>" class="btn btn--primary">
	<?php echo esc_html__( 'Proceed to Checkout', 'stack' ); ?>
</a>
