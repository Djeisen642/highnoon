<?php
/**
 * @author 		TommusRhodus
 * @version     9.9.9
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
?>

<?php if ( $price_html = $product->get_price_html() ) : ?>
	<?php echo wp_kses_post($price_html); ?>
<?php endif; ?>
