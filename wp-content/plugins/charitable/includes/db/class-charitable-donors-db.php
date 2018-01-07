<?php
/**
 * Charitable Donors DB class.
 *
 * @package   Charitable/Classes/Charitable_Donors_DB
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.5.4
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Donors_DB' ) ) :

	/**
	 * Charitable_Donors_DB
	 *
	 * @since 1.0.0
	 */
	class Charitable_Donors_DB extends Charitable_DB {

		/**
		 * The version of our database table
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		public $version = '1.5.4';

		/**
		 * The name of the primary column
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		public $primary_key = 'donor_id';

		/**
		 * Set up the database table name.
		 *
		 * @since 1.0.0
		 *
		 * @global WPDB $wpdb
		 */
		public function __construct() {
			global $wpdb;

			$this->table_name = $wpdb->prefix . 'charitable_donors';
		}

		/**
		 * Create the table.
		 *
		 * @since 1.0.0
		 *
		 * @global WPDB $wpdb
		 */
		public function create_table() {
			global $wpdb;

			$charset_collate = $wpdb->get_charset_collate();

			$sql = "CREATE TABLE {$this->table_name} (
				donor_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
				user_id bigint(20) unsigned NOT NULL,
				email varchar(100) NOT NULL,
				first_name varchar(255) default '',
				last_name varchar(255) default '',
				date_joined datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
				PRIMARY KEY  (donor_id),
				KEY user_id (user_id),                     
				KEY email (email)
				) $charset_collate;";

			$this->_create_table( $sql );
		}

		/**
		 * Whitelist of columns.
		 *
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_columns() {
			return array(
				'donor_id'    => '%d',
				'user_id'     => '%d',
				'email'       => '%s',
				'first_name'  => '%s',
				'last_name'   => '%s',
				'date_joined' => '%s',
			);
		}

		/**
		 * Default column values.
		 *
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_column_defaults() {
			return array(
				'donor_id'    => '',
				'user_id'     => 0,
				'email'       => '',
				'first_name'  => '',
				'last_name'   => '',
				'date_joined' => date( 'Y-m-d H:i:s' ),
			);
		}

		/**
		 * Add a new campaign donation.
		 *
		 * @since  1.0.0
		 *
		 * @param  array  $data Donor data to insert.
		 * @param  string $type Should always be 'donors'.
		 * @return int The ID of the inserted donor.
		 */
		public function insert( $data, $type = 'donors' ) {
			return parent::insert( $data, $type );
		}

		/**
		 * Return a user's ID, based on their donor ID.
		 *
		 * @since  1.0.0
		 *
		 * @param  int $donor_id The Donor ID.
		 * @return int
		 */
		public function get_user_id( $donor_id ) {
			$user_id = $this->get_column_by( 'user_id', 'donor_id', $donor_id );

			return is_null( $user_id ) ? 0 : (int) $user_id;
		}

		/**
		 * Return a donor ID, based on their user ID.
		 *
		 * @since  1.0.0
		 *
		 * @param  int $user_id Donor User ID.
		 * @return int
		 */
		public function get_donor_id( $user_id ) {
			$donor_id = $this->get_column_by( 'donor_id', 'user_id', $user_id );

			return is_null( $donor_id ) ? 0 : (int) $donor_id;
		}

		/**
		 * Return a donor ID, based on their email address.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $email Donor email address.
		 * @return int
		 */
		public function get_donor_id_by_email( $email ) {
			$donor_id = $this->get_column_by( 'donor_id', 'email', $email );

			return is_null( $donor_id ) ? 0 : (int) $donor_id;
		}

		/**
		 * Count the number of donors with donations.
		 *
		 * @since  1.3.4
		 *
		 * @global WPDB $wpdb
		 * @param  string|array $statuses One or more donation statuses.
		 * @return int
		 */
		public function count_donors_with_donations( $statuses = array( 'charitable-completed' ) ) {
			global $wpdb;

			if ( ! is_array( $statuses ) ) {
				$statuses = array( $statuses );
			}

			if ( empty( $statuses ) ) {
				$status_clause = '';
			} else {
				$statuses 	   = array_filter( $statuses, 'charitable_is_valid_donation_status' );
				$placeholders  = array_fill( 0, count( $statuses ), '%s' );
				$in 		   = implode( ', ', $placeholders );
				$status_clause = "AND p.post_status IN ( $in )";
			}

			$sql = "SELECT COUNT( DISTINCT(d.donor_id) )
				FROM {$wpdb->prefix}charitable_donors d
				INNER JOIN {$wpdb->prefix}charitable_campaign_donations cd ON cd.donor_id = d.donor_id
				INNER JOIN $wpdb->posts p ON cd.donation_id = p.ID
				WHERE 1 = 1
				$status_clause;";

			return $wpdb->get_var( $wpdb->prepare( $sql, $statuses ) );
		}
	}

endif;
