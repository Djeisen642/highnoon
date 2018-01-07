<?php
/**
* @author 		TommusRhodus
* @version     9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;

$heading = apply_filters( 'woocommerce_product_additional_information_heading', esc_html__( 'Additional Information', 'stack' ) );

?>

<?php if ( $heading ): ?>
	<h3><?php echo esc_html($heading); ?></h3>
<?php endif; ?>

<?php $product->list_attributes(); ?>
