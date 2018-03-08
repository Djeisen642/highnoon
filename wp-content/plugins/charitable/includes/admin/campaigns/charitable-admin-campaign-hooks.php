<?php
/**
 * Charitable Admin Campaign Hooks.
 *
 * Action/filter hooks used for setting up donations in the admin.
 *
 * @package     Charitable/Functions/Admin
 * @version     1.5.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2018, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

/**
 * Sets the placeholder text of the campaign title field.
 *
 * @see Charitable_Campaign_Meta_Boxes::campaign_enter_title()
 */
add_filter( 'enter_title_here', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'campaign_enter_title' ), 10, 2 );

/**
 * Display fields at the very top of the page.
 *
 * @see Charitable_Campaign_Meta_Boxes::campaign_form_top()
 */
add_action( 'edit_form_after_title', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'campaign_form_top' ) );

/**
 * Campaign Metaboxes.
 *
 * @see Charitable_Campaign_Meta_Boxes::add_meta_boxes()
 * @see Charitable_Campaign_Meta_Boxes::campaign_donation_options_metabox()
 * @see Charitable_Campaign_Meta_Boxes::wrap_editor()
 */
add_action( 'add_meta_boxes', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'add_meta_boxes' ) );
add_action( 'charitable_campaign_donation_options_metabox', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'campaign_donation_options_metabox' ) );
add_action( 'add_meta_boxes_campaign', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'wrap_editor' ) );
add_filter( 'get_user_option_meta-box-order_campaign', '__return_false' );

/**
 * Save the campaign.
 *
 * @see Charitable_Campaign_Meta_Boxes::save_campaign()
 * @see Charitable_Campaign_Meta_Boxes::set_default_post_content()
 */
add_action( 'save_post_' . Charitable::CAMPAIGN_POST_TYPE,  array( Charitable_Campaign_Meta_Boxes::get_instance(), 'save_campaign' ), 10, 2 );
add_filter( 'wp_insert_post_data', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'set_default_post_content' ) );

/**
 * Set up admin messages & notifications displayed based on actions taken.
 *
 * @see Charitable_Campaign_Meta_Boxes::post_messages()
 * @see Charitable_Campaign_Meta_Boxes::bulk_messages()
 */
add_filter( 'post_updated_messages', array( Charitable_Campaign_Meta_Boxes::get_instance(), 'post_messages' ) );

/**
 * Set the table columns for campaigns.
 *
 * @see Charitable_Campaign_List_Table::dashboard_columns()
 */
add_filter( 'manage_edit-campaign_columns', array( Charitable_Campaign_List_Table::get_instance(), 'dashboard_columns' ), 11 );

/**
 * Set the content of each column item.
 *
 * @see Charitable_Campaign_List_Table::dashboard_column_item()
 */
add_filter( 'manage_campaign_posts_custom_column', array( Charitable_Campaign_List_Table::get_instance(), 'dashboard_column_item' ), 11, 2 );

/**
 * Set up admin messages & notifications for bulk actions.
 *
 * @see Charitable_Campaign_List_Table::bulk_messages()
 */
add_filter( 'bulk_post_updated_messages', array( Charitable_Campaign_List_Table::get_instance(), 'bulk_messages' ), 10, 2 );
