<?php 
/**
 * Charitable Recurring Admin Post Type Hooks. 
 * 
 * @package     Charitable_Recurring/Functions/Admin
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Add and save additional options for Campaign metabox
 *
 * @see     Charitable_Recurring_Campaign_Meta::recurring_donation_options()
 * @see     Charitable_Recurring_Campaign_Meta::admin_view_path()
 * @see     Charitable_Recurring_Campaign_Meta::campaign_meta_keys()
 * @see     Charitable_Recurring_Campaign_Meta::sanitize_donation_mode()
 * @see     Charitable_Recurring_Campaign_Meta::sanitize_default_tab()
 * @see     Charitable_Campaign::sanitize_campaign_suggested_donations()
 */
add_filter( 'charitable_campaign_donation_options_fields', array( Charitable_Recurring_Campaign_Meta::get_instance(), 'recurring_donation_fields' ) );
add_action( 'charitable_admin_view_path', array( Charitable_Recurring_Campaign_Meta::get_instance(), 'admin_view_path' ), 10, 3 );
add_filter( 'charitable_campaign_meta_keys', array( 'Charitable_Recurring_Campaign_Meta', 'campaign_meta_keys' ), 10 );
add_filter( 'charitable_sanitize_campaign_meta_campaign_campaign_recurring_donations', array( 'Charitable_Recurring_Campaign_Meta', 'sanitize_donation_mode' ) );
add_filter( 'charitable_sanitize_campaign_meta_campaign_campaign_recurring_default_tab', array( 'Charitable_Recurring_Campaign_Meta', 'sanitize_default_tab' ) );
add_filter( 'charitable_sanitize_campaign_meta_campaign_suggested_recurring_donations', array( 'Charitable_Campaign', 'sanitize_campaign_suggested_donations' ) );