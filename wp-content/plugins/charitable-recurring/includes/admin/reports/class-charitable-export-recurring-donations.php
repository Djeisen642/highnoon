<?php
/**
 * Class that is responsible for generating a CSV export of recurring donations.
 *
 * @package     Charitable_Recurring/Classes/Charitable_Export_Recurring_Donations
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License
 */

/* Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Export_Recurring_Donations' ) ) :

/* Include Charitable_Export base class. */
if ( ! class_exists( 'Charitable_Export' ) ) {
	require_once( charitable()->get_path( 'admin' ) . 'reports/abstract-class-charitable-export.php' );
}

/**
 * Charitable_Export_Recurring_Donations
 *
 * @since       1.0.0
 */
class Charitable_Export_Recurring_Donations extends Charitable_Export {

	/**
	 * @var     string  The type of export.
	 */
	const EXPORT_TYPE = 'recurring_donations';

	/**
	 * @var     mixed[] Array of default arguments.
	 * @access  protected
	 */
	protected $defaults = array(
		'campaign_id' => 'all',
		'status'      => 'all',
	);

	/**
	 * @var     string[] List of donation statuses.
	 * @access  protected
	 */
	protected $statuses;

	/**
	 * Create class object.
	 *
	 * @param   mixed[] $args
	 * @access  public
	 * @since   1.0.0
	 */
	public function __construct( $args ) {
		$this->statuses = charitable_recurring_get_valid_donation_statuses();

		parent::__construct( $args );
	}

	/**
	 * Export the CSV file.
	 *
	 * @return  void
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function export() {

		$rows = array();

		foreach ( $this->get_data() as $recurring_donation ) {

			$rows[] = $this->map_data( $recurring_donation );

		}

		$this->print_headers();

		/* Create a file pointer connected to the output stream */
		$output = fopen( 'php://output', 'w' );

		/* Print first row headers. */
		fputcsv( $output, array_values( $this->columns ) );

		/* Print the data */
		foreach ( $rows as $row ) {
			fputcsv( $output, $row );
		}

		fclose( $output );

		exit();
	}

	/**
	 * Receives a recurring donation and maps it to the keys defined in the columns.
	 *
	 * @param   Charitable_Recurring_Donation $recurring_donation
	 * @return  array
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function map_data( $recurring_donation ) {

		$campaigns      = $recurring_donation->get_campaign_donations();

		$campaign_ids = wp_list_pluck( $campaigns, 'campaign_id' );
		$campaign_names = array();

		// Grab the campaign titles since Recurring doesn't save them in a custom table like Charitable does.
		foreach( $campaign_ids as $campaign_id ) {
			$campaign_names[] = get_the_title( $campaign_id );
		}

		$campaign_ids   = implode( ', ', $campaign_ids );
		$campaign_names = implode( ', ', $campaign_names );
		$donor 		    = $recurring_donation->get_donor();
		$address        = str_replace( '<br/>', PHP_EOL, $recurring_donation->get_donor_address() );

		$row = array(
			'donation_id'       => $recurring_donation->ID,
			'campaign_id'       => $campaign_ids,
			'campaign_name'     => $campaign_names,
			'period' 			=> $recurring_donation->get_donation_period(),
			'amount'            => $recurring_donation->get_recurring_donation_amount( false ),
			'total' 			=> $recurring_donation->get_total_donation_amount(),
			'date'              => $recurring_donation->get_date( 'l, F j, Y' ),
			'time'              => $recurring_donation->get_date( 'H:i A' ),
			'first_name'        => $donor->get_donor_meta( 'first_name' ),
			'last_name'         => $donor->get_donor_meta( 'last_name' ),
			'email'             => $donor->get_donor_meta( 'email' ),
			'address'           => $donor->get_donor_meta( 'address' ),
			'address_2'         => $donor->get_donor_meta( 'address_2' ),
			'city'			    => $donor->get_donor_meta( 'city' ),
			'state'			    => $donor->get_donor_meta( 'state' ),
			'postcode'		    => $donor->get_donor_meta( 'postcode' ),
			'country' 		    => $donor->get_donor_meta( 'country' ),
			'phone'             => $donor->get_donor_meta( 'phone' ),
			'address_formatted' => $address,
			'status'            => $recurring_donation->get_status(),
			'donation_gateway'  => $recurring_donation->get_gateway(),
		);

		return apply_filters( 'charitable_recurring_donation_export', $row, $recurring_donation );

	}

	/**
	 * Return the CSV column headers.
	 *
	 * The columns are set as a key=>label array, where the key is used to retrieve the data for that column.
	 *
	 * @return  string[]
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function get_csv_columns() {
		$columns = array(
			'donation_id'       => __( 'Recurring Donation ID', 'charitable-recurring' ),
			'campaign_id'       => __( 'Campaign ID', 'charitable-recurring' ),
			'campaign_name'     => __( 'Campaign Title', 'charitable-recurring' ),
			'period' 			=> __( 'Billing Period', 'charitable-recurring' ),
			'amount'            => __( 'Billing Amount', 'charitable-recurring' ),
			'total' 			=> __( 'Total Amount Donated', 'charitable-recurring' ),
			'date'              => __( 'Start Date', 'charitable-recurring' ),
			'time'              => __( 'Start Time', 'charitable-recurring' ),
			'end_date' 			=> __( 'End Date', 'charitable-recurring' ),
			'first_name'        => __( 'Donor First Name', 'charitable-recurring' ),
			'last_name'         => __( 'Donor Last Name', 'charitable-recurring' ),
			'email'             => __( 'Donor Email', 'charitable-recurring' ),
			'address'           => __( 'Address', 'charitable-recurring' ),
			'address_2'         => __( 'Address 2', 'charitable-recurring' ),
			'city'			    => __( 'City', 'charitable-recurring' ),
			'state'			    => __( 'State', 'charitable-recurring' ),
			'postcode'		    => __( 'Postcode', 'charitable-recurring' ),
			'country' 		    => __( 'Country', 'charitable-recurring' ),
			'phone'             => __( 'Phone Number', 'charitable-recurring' ),
			'address_formatted' => __( 'Address Formatted', 'charitable-recurring' ),
			'status'            => __( 'Recurring Donation Status', 'charitable-recurring' ),
			'donation_gateway'  => __( 'Recurring Donation Gateway', 'charitable-recurring' ),
		);

		return apply_filters( 'charitable_export_donations_columns', $columns, $this->args );
	}

	/**
	 * Get the data to be exported.
	 *
	 * @return  array
	 * @access  protected
	 * @since   1.0.0
	 */
	protected function get_data() {
		$query_args = array();

		if ( 'all' != $this->args['campaign_id'] ) {
			$query_args['campaign_id'] = $this->args['campaign_id'];
		}

		if ( 'all' != $this->args['status'] ) {
			$query_args['status'] = $this->args['status'];
		}

		$query_args = apply_filters( 'charitable_export_recurring_donations_query_args', $query_args, $this->args );

		return new Charitable_Recurring_Donation_Query( $query_args );

	}
}

endif; // End class_exists check
