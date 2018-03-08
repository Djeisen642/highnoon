<?php
/**
 * Charitable Campaign Functions.
 *
 * Campaign related functions.
 *
 * @package     Charitable/Functions/Campaign
 * @version     1.0.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2018, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Returns the given campaign.
 *
 * @since  1.0.0
 *
 * @param  int $campaign_id The campaign ID.
 * @return Charitable_Campaign
 */
function charitable_get_campaign( $campaign_id ) {
	return new Charitable_Campaign( $campaign_id );
}

/**
 * Create a campaign.
 *
 * @since  1.5.9
 *
 * @param  array $args Values for the campaign.
 * @return int
 */
function charitable_create_campaign( array $args ) {
	$processor = new Charitable_Campaign_Processor( $args );
	return $processor->save();
}


/**
 * Returns the current campaign.
 *
 * @since  1.0.0
 *
 * @return Charitable_Campaign
 */
function charitable_get_current_campaign() {
	return charitable_get_request()->get_current_campaign();
}

/**
 * Returns the current campaign ID.
 *
 * @since  1.0.0
 *
 * @return int
 */
function charitable_get_current_campaign_id() {
	return charitable_get_request()->get_current_campaign_id();
}


/**
 * Returns whether the current user is the creator of the given campaign.
 *
 * @since  1.0.0
 *
 * @param  int $campaign_id The campaign ID.
 * @return boolean
 */
function charitable_is_current_campaign_creator( $campaign_id = null ) {
	if ( is_null( $campaign_id ) ) {
		$campaign_id = charitable_get_current_campaign_id();
	}

	return get_post_field( 'post_author', $campaign_id ) == get_current_user_id();
}
