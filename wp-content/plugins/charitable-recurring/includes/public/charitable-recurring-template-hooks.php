<?php 
/**
 * Charitable Template Hooks. 
 *
 * Action/filter hooks used for Charitable functions/templates
 * 
 * @package     Charitable_Recurring/Functions/Templates
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Set up custom template locations. 
 *
 * @see     Charitable_Templates::template_loader()
 */
//add_filter( 'template_include', array( Charitable_Templates::get_instance(), 'template_loader' ), 12 );


/**
 * Add a toggle input for deciding whether the donation is one-time or monthly 
 *
 * @see     charitable_recurring_form_fields()
 */
//add_action( 'charitable_form_field', 'charitable_recurring_suggested_donations', 20 );

/** 
 * Donation receipt, after the page content (if there is any).
 *
 * @see     charitable_recurring_template_recurring_donation_details()
 */
add_action( 'charitable_donation_receipt', 'charitable_recurring_template_recurring_donation_details', 10 );

/**
 * Add extra styles to the custom styles output.
 *
 * @see     charitable_recurring_custom_styles()
 */
add_action( 'charitable_custom_styles', 'charitable_recurring_custom_styles' );

/** 
 * Add <noscript> subheaders to Suggested Amounts
 *
 * @see     charitable_recurring_suggested_donation_subtitle()
 * @see 	charitable_recurring_suggested_recurring_donation_subtitle()
 */
add_action( 'charitable_donation_form_before_donation_amounts', 'charitable_recurring_suggested_donation_subtitle' );
add_action( 'charitable_recurring_donation_form_before_donation_amounts', 'charitable_recurring_suggested_recurring_donation_subtitle' );


