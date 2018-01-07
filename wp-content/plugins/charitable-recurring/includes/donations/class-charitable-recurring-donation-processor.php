<?php
/**
 * The class that is responsible for responding to recurring donation events. 
 * Latches on to the Charitable Donation Processor
 *
 * @version     1.0.0
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Donation_Processor
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Donation_Processor' ) ) : 

/**
 * Charitable Donation Processor.
 *
 * @since       1.0.0
 */
class Charitable_Recurring_Donation_Processor extends Charitable_Donation_Processor {


    /**
     * The single instance of this class.
     *
     * @var     Charitable_Donation_Processor|null
     * @access  private
     * @static
     */
    private static $instance = null;


    /**
     * The charitable donation parent processor 
     *
     * @var     obj
     * @access  protected
     */
    protected $processor = false;


    /**
     * Create class object. A protected constructor, so this is used in a singleton context. 
     * 
     * @access  protected
     * @since   1.0.0
     */
    protected function __construct() {}

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Donation_Processor
     * @access  public
     * @since   1.0.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Donation_Processor();
        }

        return self::$instance;
    }
    

    /**
     * Return the parent processor
     *
     * @return  int
     * @access  public
     * @since   1.0.0
     */
    public function get_processor() {
        if( ! $this->processor ){
            $this->processor = charitable_get_donation_processor();
        }
        return $this->processor;
    }


    /**
     * Validate the gateway supports recurring donations
     *
     * @param   boolean $valid
     * @param   string $gateway
     * @param   mixed[] $values
     * @return  boolean
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function validate_donation_gateway( $valid, $gateway, $values ) {

        $gateway = charitable_get_helper( 'gateways' )->get_gateway_object( $gateway );

        if( isset( $values['donation_period'] ) && ( ! is_object( $gateway ) || ! $gateway->supports( 'recurring' ) ) ){
            $valid = false;
            charitable_get_notices()->add_error( __( 'The chosen payment gateway does not support recurring donations. Please choose another payment processor.', 'charitable-recurring' ) );
        }   

        return $valid;
    }


    /**
     * Adds the period from a campaign widget to the session
     *
     * @param   obj Charitable_Donation_Processor $processor
     * @param   array $submitted
     * @return  void
     * @access  public
     * @static
     * @since   1.0.2
     */
    public static function maybe_add_recurring_to_session( $processor, $submitted ) {

        $campaign_id = $submitted['campaign_id'];

        $donations = charitable_get_session()->get( 'donations' );

        if( isset( $donations[$campaign_id] ) && isset( $submitted['donation_period'] ) ){

            $donations[$campaign_id]['donation_period'] = $submitted['donation_period'];

            charitable_get_session()->set( 'donations', $donations );
        }
    }
    

    /**
     * Maybe inserts a new donation.
     *
     * @param   obj $processor - Charitable_Donation_Processor class object
     * @return  int $recurring_id    Returns 0 in case of failure. Positive recurring donation ID otherwise.
     * @access  public
     * @since   1.0.0
     */
    public static function maybe_save_recurring_donation( $processor ) {

        // check if has a billing period and *not* a renewal
        if( $processor->get_donation_data_value( 'donation_period', false ) && ! $processor->get_donation_data_value( 'is_renewal', false ) ){

            // get instance of the recurring processor
            $recurring_processor = self::get_instance();

            // fill with data from processor
            $values = $processor->get_donation_data();

            // save the recurring donations
            $recurring_id = $recurring_processor->save_recurring_donation( $values );

            // set the donation parent
            $processor->set_donation_data_value( 'donation_plan', $recurring_id );

        }

    }


    /**
     * Inserts a new recurring donation.
     *
     * This method is designed to be completely form agnostic. 
     *
     * @param   mixed[] $values
     * @return  int $donation_id    Returns 0 in case of failure. Positive donation ID otherwise.
     * @access  public
     * @since   1.0.0
     */
    public function save_recurring_donation( array $values ) {

        $recurring_processor = self::get_instance();

        /**
         * @hook charitable_recurring_donation_values
         */        
        $recurring_processor->donation_data = apply_filters( 'charitable_recurring_donation_values', $values );

        /**
         * @hook charitable_recurring_before_save_donation
         */
        do_action( 'charitable_recurring_before_save_donation', $recurring_processor );

        $recurring_id = wp_insert_post( $recurring_processor->parse_donation_data() );

        if ( is_wp_error( $recurring_id ) ) {
            charitable_get_notices()->add_errors_from_wp_error( $recurring_id );
            return 0;
        }

        if ( 0 == $recurring_id ) {
            charitable_get_notices()->add_error( __( 'We were unable to save the recurring donation. Please try again.', 'charitable-recurring' ) );
            return 0;
        }

        $recurring_processor->set_recurring_donation_key( $recurring_id ); 

        $recurring_processor->save_donation_meta( $recurring_id );             

        $recurring_processor->update_donation_log( $recurring_id, __( 'Recurring donation created.', 'charitable-recurring' ) );

        /**
         * @hook charitable_recurring_after_save_donation
         */
        do_action( 'charitable_recurring_after_save_donation', $recurring_id, $recurring_processor );        

        return $recurring_id;
    }

    /**
     * Maybe save some meta to subscription after donation has been created
     *
     * @param  int $donation_id
     * @param   obj $processor - Charitable_Donation_Processor class object
     * @access  public
     * @since   1.0.0
     */
    public static function maybe_update_recurring_donation( $donation_id, $processor ) {

        // check if has a billing period and *not* a renewal
        if( $processor->get_donation_data_value( 'donation_plan', false ) > 0 && ! $processor->get_donation_data_value( 'is_renewal', false ) ){

            // the recurring donations
            $recurring_id = $processor->get_donation_data_value( 'donation_plan' );

            self::update_recurring_donation_meta( $donation_id, $recurring_id );

        }

    }


    /**
     * Parse the donation data, based on the passed $values array. 
     *
     * @return  array
     * @access  protected
     * @since   1.0.0
     */
    protected function parse_donation_data() {  

        $core_values = array(
            'post_type'     => Charitable_Recurring::POST_TYPE, 
            'post_author'   => $this->get_donation_data_value( 'user_id', get_current_user_id() ), 
            'post_status'   => $this->get_donation_status(),
            'post_content'  => $this->get_donation_data_value( 'note', '' ), 
            'post_parent'   => $this->get_donation_data_value( 'donation_plan', 0 ),
            'post_date_gmt' => $this->get_donation_data_value( 'date_gmt', current_time( 'mysql', true ) ),
            'post_title'    => sprintf( '%s &ndash; %s Recurring Donation', $this->get_donor_name(), $this->get_campaign_names() )
        );

        $core_values[ 'post_date' ] = get_date_from_gmt( $core_values[ 'post_date_gmt' ] );

        return apply_filters( 'charitable_recurring_donation_values_core', $core_values, $this );
    }


    /**
     * Set a unique key for the recurring donation. 
     *
     * @return  void
     * @access  protected
     * @since   1.0.0
     */
    protected function set_recurring_donation_key() {
        $donation_key = apply_filters( 'charitable_recurring_generate_donation_key', strtolower( md5( uniqid( 'cr_' ) ) ) );
        $this->get_processor()->set_donation_data_value( 'recurring_donation_key', strtolower( $donation_key ) );
        $this->set_donation_data_value( 'recurring_donation_key', strtolower( $donation_key ) );
    }


    /**
     * Returns the donation status. Defaults to charitable-pending.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_donation_status() {
        $status = $this->get_donation_data_value( 'status', 'charitable-pending' );

        if ( ! charitable_recurring_is_valid_donation_status( $status ) ) {
            $status = 'charitable-pending';
        }

        return $status;
    }


     /**
     * Save the meta for the donation.  
     *
     * @param   int $recurring_id
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function save_donation_meta( $recurring_id ) {
    
        $meta = array(
            'donation_gateway'  => $this->get_donation_data_value( 'gateway' ), 
            'donor'             => $this->get_donation_data_value( 'user' ), 
            'donor_id'          => $this->get_donor_id(),
            'test_mode'         => charitable_get_option( 'test_mode', 0 ), 
            'donation_key'      => $this->get_donation_data_value( 'recurring_donation_key' ),
            'donation_period'      => $this->get_donation_data_value( 'donation_period' ),
            'donation_interval'      => $this->get_donation_data_value( 'donation_interval' ),
            'campaigns'         => $this->get_campaign_donations_data()
        );

        if ( $this->get_donation_data_value( 'meta' ) ) {
            $meta = array_merge( $meta, $this->get_donation_data_value( 'meta' ) );
        }

        $meta = apply_filters( 'charitable_recurring_donation_meta', $meta, $recurring_id, $this );

        foreach ( $meta as $meta_key => $value ) {
            $value = apply_filters( 'charitable_sanitize_donation_meta', $value, $meta_key );
            update_post_meta( $recurring_id, $meta_key, $value );
        }
    }


    /**
     * Update subscription meta after donation has been created
     *
     * @param  int $donation_id
     * @param   int $recurring_id
     * @access  public
     * @since   1.0.0
     */
    public static function update_recurring_donation_meta( $donation_id, $recurring_id ) {
        update_post_meta( $recurring_id, '_first_donation', $donation_id );
        update_post_meta( $recurring_id, '_most_recent_donation', $donation_id );
    }

     /**
     * Recurring Recurring?
     * check campaigns for recurring donation
     *
     * @return  array
     * @access  public
     * @since   1.0.0
     */   
    public function has_recurring( $values = array() ) {
        $periods = wp_list_pluck( $values, 'donation_period' );
        return ! empty( $periods );
    }


}

endif; // End class_exists check.