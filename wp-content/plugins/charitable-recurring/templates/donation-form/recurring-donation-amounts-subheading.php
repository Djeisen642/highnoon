<?php
/**
 * The template used to display the subheading for recurring donation amounts if no JS
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
$campaign = $form->get_campaign();
$suggested_recurring_donations = charitable_recurring_get_suggested_donations( $campaign->ID );

if ( count( $suggested_recurring_donations ) > 0 ) : ?>

<noscript><div class="charitable-form-subheader"><?php _e( 'Your Monthly Donation', 'charitable-recurring' );?></div></noscript>

<?php endif; ?>