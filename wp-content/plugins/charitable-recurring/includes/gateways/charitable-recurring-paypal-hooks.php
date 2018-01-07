<?php 
/**
 * Charitable Recurring PayPal standard Hooks. 
 *
 * Action/filter hooks used for adding support for recurring donations to PayPal Standard gateway 
 * 
 * @package     Charitable_Recurring/Functions/Gateways
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Define gateways's support for recurring donations 
 *
 * @see Charitable_Recurring_PayPal::add_paypal_support
 */
add_filter( 'charitable_payment_gateway_supports', array( Charitable_Recurring_PayPal::get_instance(), 'add_paypal_support' ), 10, 3 ); 
  

/**
 * Adjust PayPal args
 *
 * @see Charitable_Recurring_PayPal::paypal_args
 */
add_filter( 'charitable_paypal_redirect_args', array( Charitable_Recurring_PayPal::get_instance(), 'paypal_args' ), 10, 3 ); 

/**
 * Handle PayPal Transactions
 *
 * @see Charitable_Recurring_PayPal::process_subscr_signup
 * @see Charitable_Recurring_PayPal::process_subscr_payment
 * @see Charitable_Recurring_PayPal::process_subscr_cancel
 * @see Charitable_Recurring_PayPal::process_subscr_modify
 * @see Charitable_Recurring_PayPal::process_subscr_failed
 * @see Charitable_Recurring_PayPal::process_subscr_eot
 */
add_action( 'charitable_paypal_subscr_signup', array( Charitable_Recurring_PayPal::get_instance(), 'process_subscr_signup' ), 10, 2 );
add_action( 'charitable_paypal_subscr_payment', array( Charitable_Recurring_PayPal::get_instance(), 'process_subscr_payment' ), 10, 2 );
add_action( 'charitable_paypal_subscr_cancel', array( Charitable_Recurring_PayPal::get_instance(), 'process_subscr_cancel' ), 10, 2 );
add_action( 'charitable_paypal_subscr_modify', array( Charitable_Recurring_PayPal::get_instance(), 'process_subscr_modify' ), 10, 2 );
add_action( 'charitable_paypal_subscr_failed', array( Charitable_Recurring_PayPal::get_instance(), 'process_subscr_failed' ), 10, 2 );
add_action( 'charitable_paypal_subscr_eot', array( Charitable_Recurring_PayPal::get_instance(), 'process_subscr_eot' ), 10, 2 );