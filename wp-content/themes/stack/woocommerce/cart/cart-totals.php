<?php
/**
 * @author 		TommusRhodus
 * @version     9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>

<div class="row">
	<div class="col-sm-12">
		<div class="boxed boxed--border cart-total <?php if ( WC()->customer->has_calculated_shipping() ) echo 'calculated_shipping'; ?>">
			<?php do_action( 'woocommerce_before_cart_totals' ); ?>
			
			<div>
				<div class="col-xs-6">
					<span class="h5"><?php esc_html_e('Cart Subtotal:', 'stack'); ?></span>
				</div>
				<div class="col-xs-6 text-right">
					<span><?php wc_cart_totals_subtotal_html(); ?></span>
				</div>
			</div>
			
			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<div class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<div class="col-xs-6">
						<span class="h5"><?php wc_cart_totals_coupon_label( $coupon ); ?></span>
					</div>
					<div class="col-xs-6 text-right">
						<span><?php wc_cart_totals_coupon_html( $coupon ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
			
			<div>
				<div class="col-sm-12">
					<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
					
						<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			
						<?php wc_cart_totals_shipping_html(); ?>
			
						<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>
			
					<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>
			
						<tr class="shipping">
							<th><?php esc_html_e( 'Shipping', 'stack' ); ?></th>
							<td data-title="<?php esc_attr_e( 'Shipping', 'stack' ); ?>"><?php woocommerce_shipping_calculator(); ?></td>
						</tr>
			
					<?php endif; ?>
				</div>
			</div>
	
			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<div>
					<div class="col-xs-6">
						<span class="h5"><?php echo esc_html( $fee->name ); ?></span>
					</div>
					<div class="col-xs-6 text-right">
						<span><?php wc_cart_totals_fee_html( $fee ); ?></span>
					</div>
				</div>
			<?php endforeach; ?>
	
			<?php if ( wc_tax_enabled() && 'excl' === WC()->cart->tax_display_cart ) :
				$taxable_address = WC()->customer->get_taxable_address();
				$estimated_text  = WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping()
						? sprintf( ' <small>(' . esc_html__( 'estimated for %s', 'stack' ) . ')</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] )
						: '';
	
				if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : ?>
						<div class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
							<div class="col-xs-6">
								<span class="h5"><?php echo esc_html( $tax->label ) . $estimated_text; ?></span>
							</div>
							<div class="col-xs-6 text-right">
								<span><?php echo wp_kses_post( $tax->formatted_amount ); ?></span>
							</div>
						</div>
					<?php endforeach; ?>
				<?php else : ?>
					<div class="tax-rate tax-rate-<?php echo sanitize_title( $code ); ?>">
						<div class="col-xs-6">
							<span class="h5"><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; ?></span>
						</div>
						<div class="col-xs-6 text-right">
							<span><?php wc_cart_totals_taxes_total_html(); ?></span>
						</div>
					</div>
				<?php endif; ?>
			<?php endif; ?>
	
			<hr />
			
			<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
					
			<div>
				<div class="col-xs-6">
					<span class="h5"><?php esc_html_e('Total:', 'stack'); ?></span>
				</div>
				<div class="col-xs-6 text-right">
					<span class="h5"><?php wc_cart_totals_order_total_html(); ?></span>
				</div>
			</div>
			
			<?php do_action( 'woocommerce_cart_totals_after_order_total' ); ?>
			
		</div>
	</div>
</div><!--end of row-->
<div class="row">
	<div class="col-md-4 col-md-offset-8 text-right text-center-xs">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
</div><!--end of row-->

<?php do_action( 'woocommerce_after_cart_totals' ); ?>