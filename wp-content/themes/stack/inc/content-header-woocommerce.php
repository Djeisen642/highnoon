<?php
	global $woocommerce;
	$cart_url = wc_get_cart_url();
?>

<a class="cart-link" href="<?php echo esc_url($cart_url); ?>">
	<i class="stack-basket"></i>
	<div class="ebor-cart-count"><?php echo $woocommerce->cart->get_cart_contents_count(); ?></div>
</a>