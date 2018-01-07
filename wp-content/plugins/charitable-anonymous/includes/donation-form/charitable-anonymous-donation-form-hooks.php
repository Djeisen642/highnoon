<?php
/**
 * Charitable Anonymous Donation Form Hooks
 *
 * Action/filter hooks used to set up the donation form.
 *
 * @package   Charitable Anonymous/Functions/Donation Form
 * @version   1.1.0
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Register the Anonymous Donation field for use in the admin form.
 *
 * @see Charitable_Anonymous_Donation_Form::register_anonymous_donation_field()
 */
add_filter( 'charitable_default_donation_fields', array( Charitable_Anonymous_Donation_Form::get_instance(), 'register_anonymous_donation_field' ) );

/**
 * Add the anonymous donation field to the donation form.
 *
 * @see Charitable_Anonymous_Donation_Form::add_anonymous_donation_field()
 */
add_filter( 'charitable_donation_form_donor_fields_after', array( Charitable_Anonymous_Donation_Form::get_instance(), 'add_anonymous_donation_field' ) );

/**
 * Add the anonymous_donation field to the stored array of submitted values.
 *
 * @see Charitable_Anonymous_Donation_Form::save_submitted_value()
 */
add_filter( 'charitable_donation_values', array( Charitable_Anonymous_Donation_Form::get_instance(), 'save_submitted_value' ) );

/**
 * Add the anonymous_donation field to the meta to be saved.
 *
 * @see Charitable_Anonymous_Donation_Form::add_meta_field()
 */
add_filter( 'charitable_donation_meta', array( Charitable_Anonymous_Donation_Form::get_instance(), 'add_meta_field' ), 10, 3 );
