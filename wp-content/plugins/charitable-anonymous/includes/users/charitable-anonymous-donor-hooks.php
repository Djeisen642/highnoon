<?php 
/**
 * Charitable Anonymous Donor Hooks
 *
* Action/filter hooks used to customize the output of the Charitable_Donor and Charitable_Donor_Query objects.
 *
 * @package   Charitable Anonymous/Functions/Donor
 * @author    Eric Daams 
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.2.1
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Marks a Donor object as anonymous when returning it from a query.
 *
 * @see Charitable_Anonymous_Donor::set_donor_object_anonymity()
 */
add_filter( 'charitable_donor_query_donor_object', array( Charitable_Anonymous_Donor::get_instance(), 'set_donor_object_anonymity' ), 10, 2 );

/**
 * When a donation was made anonymously, set the donor name to anonymous.
 *
 * @see Charitable_Anonymous_Donor::set_donor_name_to_anonymous()
 */
add_filter( 'charitable_donor_name', array( Charitable_Anonymous_Donor::get_instance(), 'set_donor_name_to_anonymous' ), 10, 2 );

/**
 * Filter the donor amount for grouped donations.
 *
 * @see Charitable_Anonymous_Donor::get_raw_donor_amount()
 */
add_filter( 'charitable_donor_loop_donor_amount', array( Charitable_Anonymous_Donor::get_instance(), 'get_raw_donor_amount' ), 10, 2 );

/**
 * Make sure the donor's full name is included in emails.
 *
 * @see Charitable_Anonymous_Donor::include_donor_name_in_emails()
 */
add_filter( 'charitable_email_content_field_value_donor', array( Charitable_Anonymous_Donor::get_instance(), 'include_donor_name_in_emails' ), 10, 3 );
    
/**
 * When the donation was made anonymously, show the placeholder avatar.
 *
 * @see Charitable_Anonymous_Donor::force_anonymous_gravatar()
 */
add_filter( 'charitable_donor_avatar', array( Charitable_Anonymous_Donor::get_instance(), 'force_anonymous_gravatar' ), 10, 2 );

/**
 * Set both the Donors shortcode and widget to respect anonymity.
 *
 * @see Charitable_Anonymous_Donor::setup_donor_loop_query_args()
 */
add_filter( 'charitable_donors_widget_donor_query_args', array( Charitable_Anonymous_Donor_Query::get_instance(), 'setup_donor_loop_query_args' ) );
add_filter( 'charitable_donors_shortcode_donor_query_args', array( Charitable_Anonymous_Donor_Query::get_instance(), 'setup_donor_loop_query_args' ) );

/**
 * Set up Charitable_Donor_Query customizations.
 *
 * @see Charitable_Anonymous_Donor_Query::setup_donor_query()
 */
add_filter( 'charitable_donor_query_default_args', array( Charitable_Anonymous_Donor_Query::get_instance(), 'setup_donor_query' ) );
