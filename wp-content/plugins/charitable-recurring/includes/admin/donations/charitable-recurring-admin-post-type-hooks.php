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
 * Set up the recurring donation metaboxes
 *
 * @see     Charitable_Recurring_Admin_Post_Type::add_meta_boxes()
 * @see     Charitable_Recurring_Admin_Post_Type::remove_meta_boxes()
 */
add_action( 'add_meta_boxes', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'add_meta_boxes' ) );
add_action( 'add_meta_boxes', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'remove_meta_boxes' ), 20 );

/**
 * Recurring donation
 *
 * @see     Charitable_Recurring_Admin_Post_Type::post_messages()
 */
add_filter( 'post_updated_messages', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'post_messages' ) );

/**
 * Columns for the dashboard listing of recurring donations.
 *
 * @see     Charitable_Recurring_Admin_Post_Type::dashboard_columns()
 * @see     Charitable_Recurring_Admin_Post_Type::dashboard_column_item()
 * @see     Charitable_Recurring_Admin_Post_Type::sortable_columns()
 * @see     Charitable_Recurring_Admin_Post_Type::list_table_primary_column()
 * @see     Charitable_Recurring_Admin_Post_Type::row_actions()
 */
add_filter( 'manage_edit-' . Charitable_Recurring::POST_TYPE . '_columns',         array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'dashboard_columns' ), 11, 1 );
add_filter( 'manage_' . Charitable_Recurring::POST_TYPE .'_posts_custom_column',  array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'dashboard_column_item' ), 11, 2 );
add_filter( 'manage_edit-' . Charitable_Recurring::POST_TYPE . '_sortable_columns', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'sortable_columns' ) );
add_filter( 'list_table_primary_column', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'primary_column' ), 10, 2 );
add_filter( 'post_row_actions', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'row_actions' ), 2, 100 );

/**
 * Post status counts
 *
 * @see     Charitable_Recurring_Admin_Post_Type::set_status_views()
 */
add_filter( 'views_edit-' . Charitable_Recurring::POST_TYPE, array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'set_status_views' ) );

/**
 * Bulk Edit
 *
 * @see     Charitable_Recurring_Admin_Post_Type::remove_bulk_actions()
 * @see     Charitable_Recurring_Admin_Post_Type::bulk_admin_footer()
 * @see     Charitable_Recurring_Admin_Post_Type::bulk_admin_notices()
 * @see     Charitable_Recurring_Admin_Post_Type::bulk_messages()
 */
add_filter( 'bulk_actions-edit-' . Charitable_Recurring::POST_TYPE, array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'remove_bulk_actions' ) );
add_action( 'admin_footer', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'bulk_admin_footer' ), 10 );
add_action( 'load-edit.php', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'process_bulk_action' ) );
add_action( 'admin_notices', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'bulk_admin_notices' ) );
add_filter( 'bulk_post_updated_messages', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'bulk_messages' ), 10, 2 );

/**
 * Filters and Sorting
 *
 * @see     Charitable_Recurring_Admin_Post_Type::disable_months_dropdown()
 * @see     Charitable_Recurring_Admin_Post_Type::restrict_manage_posts()
 * @see     Charitable_Recurring_Admin_Post_Type::extra_tablenav()
 * @see     Charitable_Recurring_Admin_Post_Type::request_query()
 * @see     Charitable_Recurring_Admin_Post_Type::posts_clauses()
 */
add_filter( 'disable_months_dropdown', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'disable_months_dropdown' ), 10, 2 );
add_action( 'restrict_manage_posts', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'restrict_manage_posts' ) );
add_action( 'manage_posts_extra_tablenav', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'extra_tablenav' ) );
add_filter( 'request', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'request_query' ) );
add_filter( 'posts_clauses', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'posts_clauses' ) );


/**
 * Load scripts/styles for export donations.
 *
 * @see     Charitable_Recurring_Admin_Post_Type::load_scripts()
 * @see     Charitable_Recurring_Admin_Post_Type::modal_forms()
 */
add_action( 'admin_enqueue_scripts', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'load_scripts' ), 11 );
add_action( 'admin_footer-edit.php', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'modal_forms' ) );

/**
 * Load custom views for Recurring Donations.
 *
 * @see     Charitable_Recurring_Admin_Post_Type::admin_view_path()
 */
add_filter( 'charitable_admin_view_path', array( Charitable_Recurring_Admin_Post_Type::get_instance(), 'admin_view_path' ), 10, 3 );
