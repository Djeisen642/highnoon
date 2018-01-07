<?php
/**
 * @author  TommusRhodus
 * @version 9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

wc_print_notices();

do_action( 'woocommerce_before_cart' ); ?>

<form class="cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">

<?php do_action( 'woocommerce_before_cart_table' ); ?>

<div class="row">
	<?php
		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				
				<div class="col-sm-4 <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
					<div class="product-1">
						<div class="product__controls clearfix">
							<div class="col-xs-3">
								<label><?php esc_html_e('Quantity:', 'stack'); ?></label>
							</div>
							<div class="col-xs-6">
								<?php
									if ( $_product->is_sold_individually() ) {
										$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
									} else {
										$product_quantity = woocommerce_quantity_input( array(
											'input_name'  => "cart[{$cart_item_key}][qty]",
											'input_value' => $cart_item['quantity'],
											'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
											'min_value'   => '0'
										), $_product, false );
									}
		
									echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
								?>
							</div>
							<div class="col-xs-3 text-right">
								<?php
									echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
										'<a href="%s" class="checkmark checkmark--cross bg--error" title="%s" data-product_id="%s" data-product_sku="%s"></a>',
										esc_url( WC()->cart->get_remove_url( $cart_item_key ) ),
										__( 'Remove this item', 'stack' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									), $cart_item_key );
								?>
							</div>
						</div>
						<?php
							$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('full'), $cart_item, $cart_item_key );

							if ( ! $product_permalink ) {
								echo wp_kses_post($thumbnail);
							} else {
								printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail );
							}
						?>
						<div>
							<?php
								if ( ! $product_permalink ) {
									echo apply_filters( 'woocommerce_cart_item_name', $_product->get_title(), $cart_item, $cart_item_key ) . '&nbsp;';
								} else {
									echo apply_filters( 'woocommerce_cart_item_name', sprintf( '<h5 class="inline">%s</h5><span>%s</span>', $_product->get_name(), strip_tags(wc_get_product_category_list($_product->get_id())) ), $cart_item, $cart_item_key );
								}
	
								// Meta data
								echo WC()->cart->get_item_data( $cart_item );
	
								// Backorder notification
								if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
									echo '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'stack' ) . '</p>';
								}
							?>
							<br />
							<h4 class="inline">
								<?php
									esc_html_e('Total: ', 'stack');
									echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
								?>
							</h4>
							<span>
								<?php
									echo '(' . apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ) . ' x ' . $cart_item['quantity'] . ')';
								?>
							</span>
						</div>
					</div>
				</div><!--end item-->
				
				<?php
			}
		}

		do_action( 'woocommerce_cart_contents' );
	?>
</div><!--end of row-->

<div class="row">
	<div class="col-md-2 col-md-offset-10 text-right text-center-xs">
		<input type="submit" class="btn btn--primary" name="update_cart" value="<?php esc_attr_e( 'Update Cart', 'stack' ); ?>" />
		
		<?php do_action( 'woocommerce_cart_actions' ); ?>
		<?php wp_nonce_field( 'woocommerce-cart' ); ?>
	</div>
</div><!--end of row-->

<?php if ( wc_coupons_enabled() ) { ?>
<div class="row">
	<div class="col-sm-12">
		<div class="coupon boxed bg--secondary boxed--lg form--horizontal row">
			<div class="col-sm-4">
				<h3><?php esc_html_e('Got a coupon code?', 'stack'); ?></h3>
			</div>
			<div class="col-sm-5">
				<input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'stack' ); ?>" /> 
			</div>
			<div class="col-sm-3">
				<input type="submit" class="btn btn--primary" name="apply_coupon" value="<?php esc_attr_e( 'Apply Coupon', 'stack' ); ?>" />
			</div>
	
			<?php do_action( 'woocommerce_cart_coupon' ); ?>
		</div>
	</div>
</div>
<?php } ?>

<?php do_action( 'woocommerce_after_cart_table' ); ?>

</form>

<div class="cart-collaterals">
	<?php do_action( 'woocommerce_cart_collaterals' ); ?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>
