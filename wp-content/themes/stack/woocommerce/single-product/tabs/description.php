<?php
/**
 * @author 		TommusRhodus
 * @version     9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $post;

$heading = esc_html( apply_filters( 'woocommerce_product_description_heading', esc_html__( 'Product Description', 'stack' ) ) );

?>

<?php if ( $heading ): ?>
  <h3><?php echo esc_html($heading); ?></h3>
<?php endif; ?>

<?php the_content(); ?>
