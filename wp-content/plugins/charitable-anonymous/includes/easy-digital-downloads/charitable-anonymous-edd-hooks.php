<?php 
/**
 * Charitable Anonymous Easy Digital Downloads Hooks
 *
* Action/filter hooks used to set up the EDD checkout form.
 *
 * @package   Charitable Anonymous/Functions/Easy Digital Downloads
 * @version   1.1.0
 * @author    Eric Daams 
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

$object = Charitable_Anonymous_EDD::get_instance();

/**
 * Insert the anonymous donations field into the EDD checkout process.
 *
 * @see     Charitable_Anonymous_EDD::add_checkout_field()
 */
add_action( 'edd_purchase_form_user_info', array( $object, 'add_checkout_field' ) );

/**
 * Save the anonymous donation data to the EDD payment.
 *
 * @see     Charitable_Anonymous_EDD::save_anonymous_donation_to_edd_payment()
 */
add_filter( 'edd_payment_meta', array( $object, 'save_anonymous_donation_to_edd_payment' ) );

/**
 * Save the anonymous donation data to the Charitable donation.
 *
 * @see     Charitable_Anonymous_EDD::save_anonymous_donation_to_donation()
 */
add_filter( 'charitable_edd_donation_values', array( $object, 'save_anonymous_donation_to_donation' ), 10, 2 );
