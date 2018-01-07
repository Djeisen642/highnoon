<?php 
/**
 * Charitable Anonymous Admin Hooks
 *
 * Action/filter hooks used to set up the admin form.
 *
 * @package   Charitable Anonymous/Functions/Admin
 * @version   1.1.0
 * @author    Eric Daams 
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Set the admin view path for our views. 
 *
 * @see Charitable_Anonymous_Admin::admin_view_path()
 */
add_filter( 'charitable_admin_view_path', array( Charitable_Anonymous_Admin::get_instance(), 'admin_view_path' ), 10, 3 );

/**
 * Add a note to the donation in the admin to show that the donation was made anonymously.
 *
 * @see Charitable_Anonymous_Admin::add_anonymous_donation_information()
 */
add_action( 'charitable_donation_details_donor_facts', array( Charitable_Anonymous_Admin::get_instance(), 'add_anonymous_donation_information' ), 10, 2 );

/**
 * Filter the name shown for the donation in the Donation List table.
 *
 * @see Charitable_Anonymous_Admin::maybe_disable_anonymous_donation_filter_in_table()
 */
add_filter( 'manage_posts_extra_tablenav', array( Charitable_Anonymous_Admin::get_instance(), 'maybe_disable_anonymous_donation_filter_in_table' ), 12, 2 );

/**
 * On the Donation Details page, show the anonymous donor details.
 *
 * @see Charitable_Anonymous_Admin::maybe_disable_anonymous_donation_filter_in_details()
 */
add_action( 'add_meta_boxes_donation', array( Charitable_Anonymous_Admin::get_instance(), 'maybe_disable_anonymous_donation_filter_in_details' ) );

