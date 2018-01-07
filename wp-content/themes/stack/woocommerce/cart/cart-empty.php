<?php
	/**
	 * @author  TommusRhodus
	 * @version 9.9.9
	 */
	
	if ( ! defined( 'ABSPATH' ) ) {
		exit; // Exit if accessed directly
	}
	
	wc_print_notices();
?>

<h3 class="cart-empty text-center"><?php esc_html_e( 'Your cart is currently empty.', 'stack' ) ?></h3>

<?php do_action( 'woocommerce_cart_is_empty' ); ?>

<?php if ( wc_get_page_id( 'shop' ) > 0 ) : ?>
	<p class="return-to-shop text-center">
		<a class="btn btn--primary btn--uppercase wc-backward" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Return To Shop', 'stack' ) ?>
		</a>
	</p>
<?php endif; ?>
