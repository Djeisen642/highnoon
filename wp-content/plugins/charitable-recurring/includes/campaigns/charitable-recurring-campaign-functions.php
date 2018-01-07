<?php

/**
 * Charitable Recurring Campaign Functions.
 *
 * Campaign functions.
 *
 * @author      Kathy Darling
 * @category    Core
 * @package     Charitable Recurring
 * @subpackage  Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

/**
 * Return the gateway plan ID for a recurring donation to a campaign.
 *
 * @param 	int    $campaign_id
 * @param 	string $gateway
 * @param 	array  $args
 * @return  string|false String if a plan ID is found. False otherwise.
 * @since   1.0.0
 */
function charitable_recurring_campaign_get_gateway_plan_id( $campaign_id, $gateway, $args ) {

	$meta_key = $gateway . '_donation_plans';
	$plans    = get_post_meta( $campaign_id, $meta_key, true );

	if ( ! $plans || ! is_array( $plans ) ) {
		return false;
	}

	$mode = charitable_get_option( 'test_mode' ) ? 'test' : 'live';

	/* No plans created yet in this mode, so return false. */
	if ( ! array_key_exists( $mode, $plans ) ) {
		return false;
	}

	$plan_args = charitable_recurring_get_plan_args( $args );

	/* Missing the plan arguments? Proceed no further. */
	if ( ! $plan_args ) {
		return false;
	}

	$key  = charitable_recurring_get_plan_key( $plan_args );	

	if ( ! array_key_exists( $key, $plans[ $mode ] ) ) {
		return false;
	}

	return $plans[ $mode ][ $key ];

}

/**
 * Save a gateway plan ID for a recurring donation to a campaign.
 *
 * @param 	int    $campaign_id
 * @param 	string $gateway
 * @param 	array  $args
 * @return  string|false Plan ID if created. False otherwise.
 * @since   1.0.0
 */
function charitable_recurring_campaign_create_gateway_plan_id( $campaign_id, $gateway, $args ) {

	$meta_key  = $gateway . '_donation_plans';
	$plans     = get_post_meta( $campaign_id, $meta_key, true );

	if ( ! $plans || ! is_array( $plans ) ) {
		$plans = array();
	}

	$plan_args = charitable_recurring_get_plan_args( $args );
	$key       = charitable_recurring_get_plan_key( $plan_args );
	$mode 	   = charitable_get_option( 'test_mode' ) ? 'test' : 'live';

	if ( ! array_key_exists( $mode, $plans ) ) {
		$plans[ $mode ] = array();
	}

	/* Missing the plan arguments? Proceed no further. */
	if ( ! $plan_args ) {
		return false;
	}

	/* Already have the plan created? Return true now; nothing else to be done. */
	if ( array_key_exists( $key, $plans ) ) {
		return $plans[ $mode ][ $key ];
	}

	/* Gateway extensions, use this filter to create the plan in the gateway and return the ID. */
	$plan_id = apply_filters( 'charitable_recurring_create_gateway_plan_' . $gateway, false, $campaign_id, $plan_args, $args );

	if ( ! $plan_id ) {
		return false;
	}

	$plans[ $mode ][ $key ] = $plan_id;

	update_post_meta( $campaign_id, $meta_key, $plans );

	return $plan_id;

}

/**
 * Return the arguments used in saving and creating plans.
 *
 * @param 	array $args
 * @return 	array|false
 * @since 	1.0.0
 */
function charitable_recurring_get_plan_args( $args ) {

	$plan_args = array();

	/* Make sure we have the period, amount and interval. */
	if ( array_key_exists( 'period', $args ) && array_key_exists( 'amount', $args ) ) {

		$plan_args['period']   = $args['period'];
		$plan_args['amount']   = $args['amount'];
		$plan_args['interval'] = array_key_exists( 'interval', $args ) ? $args['interval'] : 1;

	} elseif ( array_key_exists( 'processor', $args ) && is_a( $args['processor'], 'Charitable_Donation_Processor' ) ) {

		$processor             = $args['processor'];
		$plan_args['period']   = strtolower( $processor->get_donation_data_value( 'donation_period', false ) );
		$plan_args['amount']   = charitable_get_donation( $processor->get_donation_id() )->get_total_donation_amount();
		$plan_args['interval'] = $processor->get_donation_data_value( 'donation_interval', 1 );

	} else {

		return false;

	}

	return $plan_args;

}

/**
 * Return the key for a plan, based on a set of args.
 *
 * @param 	array $plan_args
 * @return 	string
 * @since 	1.0.0
 */
function charitable_recurring_get_plan_key( $plan_args ) {
	return $plan_args['period'] . '_' . $plan_args['interval'] . '_' . $plan_args['amount'] . charitable_get_currency();
}

/**
 * Sanitize the custom donations checkbox value.
 *
 * The only condition we're trying to catch here is where someone
 * hasn't specified suggested donation amounts, hasn't enabled
 * custom donations, but has set up some recurring suggested amounts.
 * Charitable would auto-enable the custom donations, but that's not
 * necessary.
 *
 * @param 	mixed $value     The current value.
 * @param 	array $submitted The raw values submitted by the user.
 * @return  int
 * @since 	1.0.3
 */
function charitable_recurring_sanitize_custom_donations( $value, $submitted ) {

	/* The value is false anyway. */
	if ( ! Charitable_Campaign::sanitize_checkbox( $value ) ) {
		return $value;
	}

	/* The user had ticked the checkbox. */
	if ( array_key_exists( '_campaign_allow_custom_donations', $submitted ) && $submitted['_campaign_allow_custom_donations'] ) {
		return $value;
	}

	if ( 'advanced' != $submitted['_campaign_recurring_donations'] ) {
		return $value;
	}

	$suggested_recurring = Charitable_Campaign::sanitize_campaign_suggested_donations( $submitted['_campaign_suggested_recurring_donations'] );

	if ( empty( $suggested_recurring ) ) {
		return $value;
	}

	return 0;
}

/**
 * Check whether a campaign supports one-time donations.
 *
 * @param 	Charitable_Campaign $campaign The campaign we're checking for.
 * @return  boolean
 * @since 	1.0.3
 */
function charitable_recurring_campaign_supports_one_time_donations( Charitable_Campaign $campaign ) {

	return 0 < count( $campaign->get_suggested_donations() )
		|| $campaign->get( 'allow_custom_donations' );

}


/**
 * Check whether a campaign defaults to one-time or recurring tab
 *
 * @param 	Charitable_Campaign $campaign The campaign we're checking for.
 * @return  string
 * @since 	1.0.5
 */
function charitable_recurring_campaign_get_default_tab( Charitable_Campaign $campaign ) {

	$donation = charitable_get_session()->get_donation_by_campaign( $campaign->ID );

	$default_tab = get_post_meta( $campaign->ID, '_campaign_recurring_default_tab', true ) == 'recurring' ? 'recurring' :  'one-time';

	if( is_array( $donation ) && isset( $donation['donation_period'] ) && 'month' == $donation['donation_period'] ) {
	    $default_tab = 'recurring';
	} 

	return $default_tab;

}