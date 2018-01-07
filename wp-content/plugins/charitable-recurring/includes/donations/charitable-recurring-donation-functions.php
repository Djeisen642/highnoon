<?php 

/**
 * Charitable Recurring Donation Functions. 
 *
 * Recurring donation related functions.
 * 
 * @package     Charitable_Recurring/Functions/Donation
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


/**
 * Return array of valid donations statuses.
 * warning: post_status cannot be longer than 20 characters in database! 
 *
 * @return  array
 * @since   1.0.0
 */
function charitable_recurring_get_valid_donation_statuses() {
    return apply_filters( 'charitable_recurring_donation_statuses', array(
        'charitable-pending'   => _x( 'Pending', 'Recurring donation status', 'charitable-recurring' ),
        'charitable-active'    => _x( 'Active', 'Recurring donation status', 'charitable-recurring' ),
        'charitable-onhold'    => _x( 'On hold', 'Recurring donation status', 'charitable-recurring' ),
        'charitable-cancelled' => _x( 'Cancelled', 'Recurring donation status', 'charitable-recurring' ),
        'charitable-expired'   => _x( 'Expired', 'Recurring donation status', 'charitable-recurring' ),
        'charitable-failed'    => _x( 'Failed', 'Recurring donation status', 'charitable-recurring' ),
        'charitable-cancel'    => _x( 'Pending Cancellation', 'Recurring donation status', 'charitable-recurring' ), 
    ) );
} 

/**
 * Returns whether the donation status is valid. 
 *
 * @return  boolean
 * @since   1.0.0
 */
function charitable_recurring_is_valid_donation_status( $status ) {
    return array_key_exists( $status, charitable_recurring_get_valid_donation_statuses() );
}


/**
 * Returns an array of subscription dates
 *
 * @since  1.0
 * @return array
 */
function charitable_recurring_get_date_types() {

	$dates = array(
		'start'        => _x( 'Start Date', 'table column header', 'charitable-recurring' ),
		'trial_end'    => _x( 'Trial End', 'table column header', 'charitable-recurring' ),
		'next_payment' => _x( 'Next Payment', 'table column header', 'charitable-recurring' ),
		'last_payment' => _x( 'Last Payment', 'table column header', 'charitable-recurring' ),
		'end'          => _x( 'End Date', 'table column header', 'charitable-recurring' ),
	);

	return apply_filters( 'charitable_recurring_dates', $dates );
}

/**
 * Get the meta key value for storing a date in the subscription's post meta table.
 *
 * @param string $date_type Internally, 'trial_end', 'next_payment' or 'end', but can be any string
 * @since 2.0
 */
function charitable_recurring_get_date_meta_key( $date_type ) {
	if ( ! is_string( $date_type ) ) {
		return new WP_Error( 'charitable_recurring_wrong_date_type_format', __( 'Date type is not a string.', 'charitable-recurring' ) );
	} elseif ( empty( $date_type ) ) {
		return new WP_Error( 'charitable_recurring_wrong_date_type_format', __( 'Date type can not be an empty string.', 'charitable-recurring' ) );
	}
	return apply_filters( 'charitable_recurring_date_meta_key_prefix', sprintf( '_schedule_%s', $date_type ), $date_type );
}


/**
 * PHP on Windows does not have strptime function. Therefore this is what we're using to check
 * whether the given time is of a specific format.
 *
 * @param  string $time the mysql time string
 * @return boolean      true if it matches our mysql pattern of YYYY-MM-DD HH:MM:SS
 */
function charitable_recurring_is_datetime_mysql_format( $time ) {
	if ( ! is_string( $time ) ) {
		return false;
	}

	if ( function_exists( 'strptime' ) ) {
		$valid_time = $match = ( false !== strptime( $time, '%Y-%m-%d %H:%M:%S' ) ) ? true : false;
	} else {
		// parses for the pattern of YYYY-MM-DD HH:MM:SS, but won't check whether it's a valid timedate
		$match = preg_match( '/\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2}/', $time );

		// parses time, returns false for invalid dates
		$valid_time = strtotime( $time );
	}

	// magic number -2209078800 is strtotime( '1900-01-00 00:00:00' ). Needed to achieve parity with strptime
	return ( $match && false !== $valid_time && -2209078800 <= $valid_time ) ? true : false;
}


/**
 * Get the related recurring donations for a specific donation
 *
 * @param  int $donation_id 
 * @return int
 */
function charitable_recurring_get_recurring_for_donation( $donation_id ) {

	$recurring_id = false;

	$donation = charitable_get_donation( $donation_id );

	// @todo: perhaps test type? and/or create renewal type that extends simple?
	if( $donation->get_donation_plan_id() > 0 ){
		$recurring_id = $donation->get_donation_plan_id();
	} else {
		$args = array(
	        'posts_per_page' => 1,
	        'post_parent' => $donation_id, 
	        'post_type'      => CHARITABLE_RECURRING::POST_TYPE,
	        'post_status'   => array_keys( charitable_recurring_get_valid_donation_statuses() ),
	        'fields'         => 'ids',
	    );

		$recurring_donations =  get_posts($args);

		if( ! is_wp_error( $recurring_donations ) ){
			$recurring_id = current( $recurring_donations );
		} 
	}
	return $recurring_id;
}

/**
 * Return a recurring donation for the given gateway subscription ID and gateway.
 *
 * @param 	WPDB   $wpdb
 * @param 	mixed  $gateway_subscription_id
 * @param 	string $gateway
 * @return 	Charitable_Recurring_Donation|false
 * @since 	1.0.0
 */
function charitable_recurring_get_subscription_by_gateway_id( $gateway_subscription_id, $gateway ) {	

	$cache_key    = $gateway_subscription_id . '_' . $gateway;
	$recurring_id = wp_cache_get( $cache_key, 'charitable_recurring_id_from_gateway_subscription' );

	if ( false === $recurring_id ) {

		global $wpdb;

		$sql = "SELECT p1.post_id
				FROM $wpdb->postmeta p1 
				INNER JOIN $wpdb->postmeta p2
				ON p2.post_id = p1.post_id
				WHERE p1.meta_key = '_gateway_subscription_id'
				AND p1.meta_value = %s
				AND p2.meta_key = 'donation_gateway'
				AND p2.meta_value = %s;";

		$recurring_id = $wpdb->get_var( $wpdb->prepare( $sql, strval( $gateway_subscription_id ), $gateway ) );

		if ( ! $recurring_id ) {
			wp_cache_set( $cache_key, 0, 'charitable_recurring_id_from_gateway_subscription' );
			return false;
		}

		wp_cache_set( $cache_key, $recurring_id, 'charitable_recurring_id_from_gateway_subscription' );

	}

	return charitable_get_donation( $recurring_id );

}
