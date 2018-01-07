<?php 
/**
 * Charitable Recurring Email Hooks. 
 *
 * Action/filter hooks used for Charitable Recurring emails. 
 * 
 * @package     Charitable_Recurring/Functions/Emails
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy is Awesome
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Register Charitable Recurring emails.
 *
 * @see     Charitable_Recurring_Emails::register_emails()
 */
add_filter( 'charitable_emails', array( Charitable_Recurring_Emails::get_instance(), 'register_emails' ) );


/**
 * Send the Donation Receipt and Donation Notification emails.
 * 
 * Both of these emails are sent immediately after a donation has been completed.
 *
 * @see     Charitable_Recurring_Admin_Email_New_Recurring_Donation::send_with_donation_id()
 * @see     Charitable_Recurring_Admin_Email_New_Renewal_Donation::send_with_donation_id()
 * @see     Charitable_Recurring_Email_Recurring_Donation_Receipt::send_with_donation_id()
 */
add_action( 'charitable_after_save_donation', array( 'Charitable_Recurring_Admin_Email_New_Recurring_Donation', 'send_with_donation_id' ), 5 );
add_action( 'charitable_after_save_donation', array( 'Charitable_Recurring_Admin_Email_New_Renewal_Donation', 'send_with_donation_id' ), 5 );
add_action( 'charitable_after_save_donation', array( 'Charitable_Recurring_Email_Recurring_Donation_Receipt', 'send_with_donation_id' ), 5 );

foreach ( charitable_get_approval_statuses() as $status ) {

    add_action( $status . '_' . Charitable::DONATION_POST_TYPE, array( 'Charitable_Recurring_Admin_Email_New_Recurring_Donation', 'send_with_donation_id' ), 5 );
    add_action( $status . '_' . Charitable::DONATION_POST_TYPE, array( 'Charitable_Recurring_Admin_Email_New_Renewal_Donation', 'send_with_donation_id' ), 5 );
    add_action( $status . '_' . Charitable::DONATION_POST_TYPE, array( 'Charitable_Recurring_Email_Recurring_Donation_Receipt', 'send_with_donation_id' ), 5 );

}


/**
 * Add recurring details to email
 *
 * @see     Charitable_Recurring_Emails::add_donation_content_fields()
 * @see     Charitable_Recurring_Emails::add_preview_donation_content_fields()
 */
add_filter( 'charitable_email_content_fields', array( Charitable_Recurring_Emails::get_instance(), 'add_donation_content_fields' ), 10, 2 );
add_filter( 'charitable_email_preview_content_fields', array( Charitable_Recurring_Emails::get_instance(), 'add_preview_donation_content_fields' ), 10, 2 );