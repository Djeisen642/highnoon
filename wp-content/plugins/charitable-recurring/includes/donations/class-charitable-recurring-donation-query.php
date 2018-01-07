<?php
/**
 * The class responsible for querying recurring donations.
 *
 * @package     Charitable/Classes/Charitable_Recurring_Donation_Query
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy is Awesome
 * @license     https://opensource.org/licenses/gpl-3.0.html GNU Public License
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Recurring_Donation_Query' ) ) :

	/**
	 * Charitable_Recurring_Donation_Query
	 *
	 * @since       1.0.0
	 */
	class Charitable_Recurring_Donation_Query extends Charitable_Query {

		/**
		 * Create new query object.
		 *
		 * @param   array $args
		 * @access  public
		 * @since   1.0.0
		 */
		public function __construct( $args = array() ) {

			$defaults = array(
				'output'   => 'recurring_donations', // Use 'posts' to get standard post objects.
				'status'   => false,  // Set to an array with statuses to only show certain statuses.
				'orderby'  => 'date', // Currently only supports 'date'.
				'order'    => 'DESC',
				'number'   => 20,
				'paged'    => 1,
				'campaign' => 0,
				'donor_id' => 0,
			);

			$this->args = wp_parse_args( $args, $defaults );

			$this->position = 0;
			$this->prepare_query();
			$this->results = $this->get_recurring_donations();

		}

		/**
		 * Return list of recurring donations.
		 *
		 * @return  object[]
		 * @access  public
		 * @since   1.0.0
		 */
		public function get_recurring_donations() {

			$records = $this->query();

			/**
			 * Return array with the count.
			 */
			if ( 'count' == $this->get( 'output' ) ) {
				return $records;
			}

			/**
			 * Return Donations objects.
			 */
			if ( 'recurring_donations' == $this->get( 'output' ) ) {

				$recurring_donations = array();

				foreach ( $records as $record ) {
					$recurring_donations[] = charitable_get_donation( $record->ID );
				}

				return $recurring_donations;

			}

			$currency_helper = charitable_get_currency_helper();

			/**
			 * When the currency uses commas for decimals and periods for thousands,
			 * the amount returned from the database needs to be sanitized.
			 */
			// if ( $currency_helper->is_comma_decimal() ) {

			// 	foreach ( $records as $i => $row ) {

			// 		$records[ $i ]->amount = $currency_helper->sanitize_database_amount( $row->amount );

			// 	}
			// }

			return $records;

		}

		/**
		 * Set up fields query argument.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function setup_fields() {

			/* If we are returning Recurring_Donation objects, we only need to return the IDs. */
			if ( 'recurring_donations' == $this->get( 'output' ) ) {
				return;
			}

			// add_filter( 'charitable_query_fields', array( $this, 'donation_fields' ), 4 );
			// add_filter( 'charitable_query_fields', array( $this, 'donation_calc_fields' ), 5 );

		}

		/**
		 * Set up orderby query argument.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function setup_orderby() {

			$orderby = $this->get( 'orderby', false );

			if ( ! $orderby ) {
				return;
			}

			switch ( $orderby ) {

				case 'date' :
					add_filter( 'charitable_query_orderby', array( $this, 'orderby_date' ) );
					break;

			}

		}

		/**
		 * Filter query by post type.
		 *
		 * @global  WPBD   $wpdb
		 * @param 	string $where_statement
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function where_post_type_is( $where_statement ) {

			global $wpdb;

			$where_statement .= sprintf( " AND {$wpdb->posts}.post_type = '%s'", Charitable_Recurring::POST_TYPE );

			return $where_statement;

		}

		/**
		 * Filter query by status of the post.
		 *
		 * @global  WPBD   $wpdb
		 * @param   string $where_statement
		 * @return  string
		 * @access  public
		 * @since   1.0.0
		 */
		public function where_status_is_in( $where_statement ) {

			global $wpdb;

			$status = $this->get( 'status', false );

			if ( ! $status ) {
				return $where_statement;
			}

			if ( ! is_array( $status ) ) {
				$status = array( $status );
			}

			$status = array_filter( $status, 'charitable_recurring_is_valid_donation_status' );

			$placeholders = $this->get_placeholders( count( $status ), '%s' );

			$this->add_parameters( $status );

			$where_statement .= " AND {$wpdb->posts}.post_status IN ({$placeholders})";

			return $where_statement;

		}

		/**
		 * Remove any hooks that have been attached by the class to prevent contaminating other queries.
		 *
		 * @return  void
		 * @access  public
		 * @since   1.0.0
		 */
		public function unhook_callbacks() {

			remove_action( 'charitable_pre_query',     array( $this, 'setup_fields' ) );
			remove_action( 'charitable_pre_query',     array( $this, 'setup_orderby' ) );
			remove_filter( 'charitable_query_where',   array( $this, 'where_status_is_in' ), 5 );
			remove_filter( 'charitable_query_where',   array( $this, 'where_campaign_is_in' ), 6 );
			remove_filter( 'charitable_query_orderby', array( $this, 'orderby_date' ) );
			remove_action( 'charitable_post_query',    array( $this, 'unhook_callbacks' ) );

		}

		/**
		 * Set up callbacks for WP_Query filters.
		 *
		 * @return  void
		 * @access  protected
		 * @since   1.0.0
		 */
		protected function prepare_query() {

			add_action( 'charitable_pre_query',   array( $this, 'setup_fields' ) );
			add_action( 'charitable_pre_query',   array( $this, 'setup_orderby' ) );
			add_filter( 'charitable_query_where', array( $this, 'where_post_type_is' ), 4 );
			add_filter( 'charitable_query_where', array( $this, 'where_status_is_in' ), 5 );
			add_filter( 'charitable_query_where', array( $this, 'where_campaign_is_in' ), 6 );
			add_action( 'charitable_post_query',  array( $this, 'unhook_callbacks' ) );

		}
	}

endif; // End class_exists check
