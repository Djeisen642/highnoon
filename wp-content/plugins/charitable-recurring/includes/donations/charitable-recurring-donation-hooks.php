<?php 
/**
 * Charitable Recurring Donation Hooks. 
 *
 * Action/filter hooks used for Charitable recurring donations. 
 * 
 * @package     Charitable_Recurring/Functions/Donations
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Make sure the amount is correct and accounts for recurring custom
 *
 * @see Charitable_Recurring_Donation_Form::get_donation_amount()
 */
add_filter( 'charitable_donation_form_amount', array( 'Charitable_Recurring_Donation_Form', 'get_donation_amount' ) );

/**
 * Add recurring values from $_POST to $submitted
 *
 * @see Charitable_Recurring_Donation_Form::form_submitted_values()
 */
add_filter( 'charitable_form_submitted_values', array( 'Charitable_Recurring_Donation_Form', 'form_submitted_values' ), 10, 2 );

/**
 * Preserve recurring values in session
 * Applies to the main donation form.
 *
 * @see Charitable_Recurring_Donation_Form::submission_values()
 */
add_filter( 'charitable_donation_form_submission_values', array( 'Charitable_Recurring_Donation_Form', 'submission_values' ), 10, 3 );

/**
 * Show that the value in session is a monthly amount.
 *
 * @see Charitable_Recurring_Donation_Form::set_session_value()
 */
if ( version_compare( charitable()->get_version(), '1.5', '<' ) ) {
    add_filter( 'charitable_session_donation_amount_formatted', array( 'Charitable_Recurring_Donation_Form', 'set_session_value_with_donation_form' ), 10, 3 );
} else {
    add_filter( 'charitable_session_donation_amount_formatted', array( 'Charitable_Recurring_Donation_Form', 'set_session_value' ), 10, 3 );
}

/**
 * Show that the value in session is a monthly amount.
 *
 * @see Charitable_Recurring_Donation_Form::set_session_value()
 */
add_filter( 'charitable_session_donation_amount_formatted', array( 'Charitable_Recurring_Donation_Form', 'set_session_value' ), 10, 3 );

/**
 * Preserve recurring values in session
 * Applies to the donation amount form (i.e. the donation widget). 
 *
 * @see Charitable_Recurring_Donation_Form::widget_submission_values()
 */
add_filter( 'charitable_donation_amount_form_submission_values', array( 'Charitable_Recurring_Donation_Form', 'widget_submission_values' ), 10, 2 );

/**
 * Confirm the chosen gateway supports recurring donations
 *
 * @see Charitable_Recurring_Donation_Processor::validate_donation_gateway()
 */
add_filter( 'charitable_validate_donation_form_submission_gateway', array( 'Charitable_Recurring_Donation_Processor', 'validate_donation_gateway' ), 10, 3 );

/**
 * Ensure the widget passes the billing period into session
 *
 * @see Charitable_Recurring_Donation_Processor::maybe_add_recurring_to_session()
 */
add_filter( 'charitable_after_process_donation_amount_form', array( 'Charitable_Recurring_Donation_Processor', 'maybe_add_recurring_to_session' ), 10, 2 );

/**
 * Save a recurring donation.
 *
 * This is when a recurring donation is saved to the database.
 *
 * @see Charitable_Recurring_Donation_Processor::maybe_save_recurring_donation()
 */
add_action( 'charitable_before_save_donation', array( 'Charitable_Recurring_Donation_Processor', 'maybe_save_recurring_donation' ) );

/**
 * Update the subscription with the donation's data
 *
 * @see Charitable_Recurring_Donation_Processor::maybe_update_recurring_donation()
 */
add_action( 'charitable_after_save_donation', array( 'Charitable_Recurring_Donation_Processor', 'maybe_update_recurring_donation' ), 10, 2 );
