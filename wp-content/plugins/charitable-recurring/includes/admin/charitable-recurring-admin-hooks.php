<?php
/**
 * Charitable Admin Hooks.
 *
 * @package     Charitable_Recurring/Functions/Admin
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Enqueue Charitable Recurring's admin script.
 *
 * @see     Charitable_Recurring_Admin::load_admin_script()
 */
add_action( 'admin_enqueue_scripts', array( Charitable_Recurring_Admin::get_instance(), 'load_admin_script' ), 11 );

/**
 * Adds recurring pages to list of admin pages where Charitable's admin-area scripts & styles are enqueued (in case we need them)
 *
 * @see     Charitable_Recurring_Admin::add_admin_screens()
 */
add_filter( 'charitable_admin_screens', array( Charitable_Recurring_Admin::get_instance(), 'add_admin_screens' ) );

/**
 * Add a generic body class to donations page
 *
 * @see     Charitable_Recurring_Admin::add_admin_body_class()
 */
add_filter( 'admin_body_class', array( Charitable_Recurring_Admin::get_instance(), 'add_admin_body_class' ) );

/**
 * Add recurring donations admin page.
 *
 * @see     Charitable_Recurring_Admin::add_submenu_page()
 */
add_filter( 'charitable_submenu_pages', array( Charitable_Recurring_Admin::get_instance(), 'add_submenu_page' ) );

/**
 * Donations related to specific Recurring Donation.
 *
 * @see     Charitable_Recurring_Admin::filter_donations()
 */
add_filter( 'posts_where', array( Charitable_Recurring_Admin::get_instance(), 'filter_donations' ) );

/**
 * Export recurring donations.
 *
 * @see     Charitable_Recurring_Admin::export_recurring_donations()
 */
add_action( 'charitable_export_recurring_donations', array( Charitable_Recurring_Admin::get_instance(), 'export_recurring_donations' ) );

