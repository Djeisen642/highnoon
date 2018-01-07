<?php
/**
 * Charitable Recurring Campaign Hooks.
 *
 * @package     Charitable Recurring/Hooks/Campaigns
 * @version     1.0.3
 * @author      Kathy Darling
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Sanitize the custom donations option.
 *
 * @see     Charitable_Campaign::sanitize_campaign_goal()
 * @see     Charitable_Campaign::sanitize_campaign_end_date()
 * @see     Charitable_Campaign::sanitize_campaign_suggested_donations()
 * @see     Charitable_Campaign::sanitize_custom_donations()
 * @see     Charitable_Campaign::sanitize_campaign_description()
 */
add_filter( 'charitable_sanitize_campaign_meta_campaign_allow_custom_donations', 'charitable_recurring_sanitize_custom_donations', 11, 2 );
