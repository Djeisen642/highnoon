<?php
/**
 * The template used to display the subheading for regular donation amounts if no JS
 *
 * @author  Kathy Darling
 * @since   1.0.0
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! isset( $view_args[ 'form' ] ) ) {
	return;
}

/**
 * @var Charitable_Donation_Form
 */
$form = $view_args[ 'form' ];
?>

<noscript><div class="charitable-form-subheader show-if-no-js"><?php _e( 'Your One-Time Donation', 'charitable-recurring' ) ?></div></noscript>