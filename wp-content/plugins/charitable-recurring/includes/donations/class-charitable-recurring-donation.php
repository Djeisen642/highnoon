<?php
/**
 * Recurring Donation model
 *
 * @version     1.0.0
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Donation
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Donation' ) ) : 

/**
 * Recurring Donation Model
 *
 * @since       1.0.0
 */

class Charitable_Recurring_Donation extends Charitable_Abstract_Donation {
    
    /**
     * The donation type 
     * 
     * @var     donation_type @access  public
     */
    public $donation_type = 'recurring';

    /**
     *  Charitable_Donation donation data for the donation in which the recurring donation was purchased (if any)
     * 
     * @var     $parent_donation
     * @access  protected
     */
    protected $parent_donation = false;

    /**
     *  Charitable_Donation donation data for the donation in which the recurring donation was purchased (if any)
     * 
     * @var     $donation_period
     * @access  protected
     */
    protected $donation_period;

    /**
     *  Charitable_Donation donation data for the donation in which the recurring donation was purchased (if any)
     * 
     * @var     $donation_interval
     * @access  protected
     */
    protected $donation_interval;

    /**
     * Initialize the donation object.
     *
     * @param int|Charitable_Donation $donation
     */
    public function __construct( $donation ) {

        parent::__construct( $donation );

    }


    /**
     * The status of this donation.
     *
     * @param   boolean $label Whether to return the label. If not, returns the key.
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_status( $label = false ) {
        $status = $this->donation_data->post_status;

        if ( ! $label ) {
            return $status;
        }

        $statuses = charitable_recurring_get_valid_donation_statuses();
        return isset( $statuses[ $status ] ) ? $statuses[ $status ] : $status;
    } 

    /**
     * Checks the order status against a passed in status.
     *
     * @return bool
     */
    public function has_status( $status ) {
        return apply_filters( 'charitable_recurring_donation_has_status', ( is_array( $status ) && in_array( $this->get_status(), $status ) ) || $this->get_status() === $status ? true : false, $this, $status );
    }
    
    /**
     * Update the status of the donation.
     *
     * @uses    wp_update_post()
     *
     * @param   string $new_status
     * @return  int|WP_Error The value 0 or WP_Error on failure. The donation ID on success.
     * @access  public
     * @since   1.0.0
     */
    public function update_status( $new_status ) {

        $statuses = charitable_recurring_get_valid_donation_statuses();

        if ( false === charitable_recurring_is_valid_donation_status( $new_status ) ) {

            $new_status = array_search( $new_status, $statuses );

            if ( false === $new_status ) {
                charitable_get_deprecated()->doing_it_wrong( __METHOD__, sprintf( '%s is not a valid donation status.', $new_status ), '1.0.0' );
                return 0;
            }
        }

        $old_status = $this->get_status();

        if ( $old_status == $new_status ) {
            return 0;
        }

        /* This actually updates the post status */
        $this->donation_data->post_status = $new_status;

        $donation_id = wp_update_post( $this->donation_data );

        $message = sprintf(
            __( 'Recurring Donation status updated from %s to %s.', 'charitable-recurring' ),
            isset( $statuses[ $old_status ] ) ? $statuses[ $old_status ] : $old_status,
            isset( $statuses[ $new_status ] ) ? $statuses[ $new_status ] : $new_status
        );

        $this->update_donation_log( $message );

        return $donation_id;
    }

    /**
     * Set the recurring donation to failed, with an error message.
     *
     * @param   string $message
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function set_to_failed( $message = '' ) {
        
        if ( strlen( $message ) ) {
            $this->update_donation_log( $message );
        }        

        $this->update_status( 'charitable-failed' );

        do_action( 'charitable_recurring_subscription_failed', $this, $message );

    }

    /**
     * The total amount donated over life of subscription.
     *
     * @param   boolean $sanitize
     * @return  decimal
     * @access  public
     * @since   1.0.0
     */
    public function get_total_donation_amount( $sanitize = false ) {
        if ( ! isset( $this->total_donation_amount ) ) {
            $related_donations = $this->get_related_donations();  
            $this->total_donation_amount = ! empty( $related_donations ) ? $this->get_campaign_donations_db()->get_donation_total_amount( $related_donations ) : 0;
        }

        if ( $sanitize ) {
            return charitable_format_money( $this->total_donation_amount );
        } else {
            return $this->total_donation_amount;
        }

    }


    /**
     * Return the campaigns donated to in this donation. 
     *
     * @return  object[]
     * @access  public
     * @since   1.0.0
     */
    public function get_campaign_donations() {
        if ( ! isset( $this->campaign_donations ) ) {
            $this->campaign_donations = get_post_meta( $this->donation_id, 'campaigns', true );
        }
        return $this->campaign_donations;
    }
    
    /**
     * Returns the donor ID of the donor.
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_donor_id() { 
        if ( ! isset( $this->donor_id ) ) {
            $this->donor_id = get_post_meta( $this->donation_id, 'donor_id', true );
        }
        return $this->donor_id;
    }

    /**
     * Returns the ID of the initial donation.
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_first_donation_id() { 
        if ( ! isset( $this->first_donation_id ) ) {
            $related_donations_ids = $this->get_related_donations();
            $this->first_donation_id = $related_donations_ids ? array_pop( $related_donations_ids ) : 0;                              
        }
        return $this->first_donation_id;
    }

    /**
     * Returns the ID of the latest renewal donation.
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_most_recent_donation_id() { 
        if ( ! isset( $this->most_recent_donation_id ) ) {
            $related_donations_ids = $this->get_related_donations();
            $this->most_recent_donation_id = $related_donations_ids ? array_shift( $related_donations_ids ) : 0;                              
        }
        return $this->most_recent_donation_id;
    }

    /**
     * Returns the donor who made this donation.
     *
     * @return  Charitable_Donor
     * @access  public
     * @since   1.0.0
     */
    public function get_donor() {
        if ( ! isset( $this->donor ) ) {
            $this->donor = new Charitable_Donor( $this->get_donor_id(), $this->donation_id );
        }

        return $this->donor;
    }


    /**
     * The amount donated on a recurring basis.
     *
     * @param   boolean $formatted Whether to return the amount as a float or a formatted string.
     * @return  float|string
     * @access  public
     * @since   1.0.0
     */
    public function get_recurring_donation_amount( $formatted = false ) {

        if( ! isset( $this->recurring_donation_amount ) ){
            $this->recurring_donation_amount = array_sum( wp_list_pluck( $this->get_campaign_donations(), 'amount' ) );
        }

        $amount = charitable_get_currency_helper()->sanitize_database_amount( $this->recurring_donation_amount );

        if ( $formatted ) {
            $period = $this->get_donation_period();
            $amount = charitable_recurring_get_recurring_donation_string( array( 'amount' => $amount, 'period' => $period ) );
        }        

        return $amount;
    }


    /**
     * Return the billing period for the recurring donation
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_donation_period() {
        if( ! isset( $this->donation_period ) ){
            $this->donation_period = get_post_meta( $this->donation_id, 'donation_period', true );
            $this->donation_period = $this->donation_period ? $this->donation_period : 'month';
        }
        return apply_filters( 'charitable_recurring_donation_period', $this->donation_period, $this );
    }

    /**
     * Return the billing interval for the recurring donation
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_donation_interval() {
        if( ! isset( $this->donation_interval ) ){
            $this->donation_interval = get_post_meta( $this->donation_id, 'donation_interval', true );
            $this->donation_interval = $this->donation_interval ? $this->donation_interval : 1;
        }
        return apply_filters( 'charitable_recurring_donation_interval', absint( $this->donation_interval ), $this );
    }

    /**
     * Extracting the query from get_related_donations and get_last_donation so it can be moved in a cached
     * value.
     *
     * @return array
     */
    public function get_related_donations_query() {
        $related_donation_ids = get_posts( array(
            'posts_per_page' => -1,
            'post_type'      => Charitable::DONATION_POST_TYPE,
            'post_status'    => array_keys( charitable_get_valid_donation_statuses() ),
            'fields'         => 'ids',
            'orderby'        => 'date',
            'order'          => 'DESC',
            'post_parent'    => $this->donation_id
        ) );
        return $related_donation_ids;
    }

    /**
     * Get the related donations for a recurring donation
     *
     * @param string $return_fields The columns to return, either 'all' or 'ids'
     * @since 1.0
     */
    public function get_related_donations( $return_fields = 'ids' ) {

        $return_fields = ( 'ids' == $return_fields ) ? $return_fields : 'all';

        $related_donations = array();

        // @todo: cache this
        //$related_donation_ids = wp_cache_get( $this->donation_id, 'charitable_recurring_related_donations' );
        $related_donation_ids = false;


        if ( false === $related_donation_ids ) {

            $related_donation_ids = $this->get_related_donations_query();

            //wp_cache_set( $this->donation_id, $related_donation_ids, 'charitable_recurring_related_donations' );

        }

        if ( 'all' == $return_fields ) {

            foreach ( $related_donation_ids as $post_id ) {
                $related_donations[ $post_id ] = charitable_get_donation( $post_id );
            }

        } else {

            // Return IDs only
            foreach ( $related_donation_ids as $post_id ) {
                $related_donations[ $post_id ] = $post_id;
            }

        }

        return apply_filters( 'charitable_recurring_related_donations', $related_donations, $this, $return_fields );
    }


    /**
     * Function to create a donation from this recurring donation. 
     * 
     * @param array $args
     * @return int New donation id
     */
    public function create_renewal_donation( $args = array() ) {

        global $wpdb;

        try {

            $wpdb->query( 'START TRANSACTION' );

            $defaults = array(            
                'is_renewal' => true,
                'donation_plan' => $this->donation_id,
                'gateway' => $this->get_gateway(), 
                'campaigns' => $this->get_campaign_donations(),
                'user' => $this->get_donor_data(),
                'user_id' => $this->get_donor_id(),
                'status' => 'charitable-pending',
                'meta' => array( '_renewal_donation' => $this->donation_id )
            );

            $args = wp_parse_args( $args, $defaults );

            $donation_id = charitable_create_donation( $args );

            // If we got here, the recurring donation was created without problems
            $wpdb->query( 'COMMIT' );

            return $donation_id;

        } catch ( Exception $e ) {
            // There was an error adding the subscription
            $wpdb->query( 'ROLLBACK' );
            return new WP_Error( 'renewal-donation-error', $e->getMessage() );
        }
    }


    /**
     * Save the gateway's subscription ID.
     *
     * @param   mixed $subscription_id
     * @return  int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
     * @access  public
     * @since   1.0.0
     */
    public function set_gateway_subscription_id( $subscription_id ) {
        $subscription_id = charitable_sanitize_donation_meta( $subscription_id, '_gateway_subscription_id' );
        return update_post_meta( $this->donation_id, '_gateway_subscription_id', $subscription_id );
    }

    /**
     * Save the gateway's subscriber ID.
     *
     * @param   mixed $subscriber_id
     * @return  int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
     * @access  public
     * @since   1.0.0
     */
    public function set_gateway_subscriber_id( $subscriber_id ) {
        return update_post_meta( $this->donation_id, '_gateway_subscriber_id', sanitize_text_field( $subscriber_id ) );
    }

    /**
     * Get the gateway's subscription ID.
     *
     * @return  mxied string|false
     * @access  public
     * @since   1.0.0
     */
    public function get_gateway_subscription_id() {
        if ( ! isset( $this->gateway_subscription_id ) ) {
            $this->gateway_subscription_id = get_post_meta( $this->donation_id, '_gateway_subscription_id', true );
        }

        return $this->gateway_subscription_id;
    }

    /**
     * Get the gateway's subscriber ID.
     *
     * @param   mixed $subscriber_id
     * @return  int|bool Meta ID if the key didn't exist, true on successful update, false on failure.
     * @access  public
     * @since   1.0.0
     */
    public function get_gateway_subscriber_id() {
        if ( ! isset( $this->gateway_subscriber_id ) ) {
            $this->gateway_subscriber_id = get_post_meta( $this->donation_id, '_gateway_subscriber_id', true );
        }

        return $this->gateway_subscriber_id;

    }

}

endif; // End class_exists check