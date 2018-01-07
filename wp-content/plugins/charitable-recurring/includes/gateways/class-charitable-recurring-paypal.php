<?php
/**
 * Modify the default Paypal Payment Gateway class
 *
 * @version     1.0.0
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_PayPal
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy is Awesome
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_PayPal' ) ) : 

/**
 * Paypal Payment Gateway 
 *
 * @since       1.0.0
 */
class Charitable_Recurring_PayPal {
 
    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_PayPal|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_PayPal
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_PayPal();
        }

        return self::$instance;
    }   


   /**
     * Send custom args to paypal to convert into recurring donation
     *
     * @param  bool $supported 
     * @param str $feature
     * @param obj Charitable_Gateway class instance
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public static function add_paypal_support( $supported, $feature, $gateway ){

        if ( 'paypal' === $gateway->get_gateway_id() && 'recurring' === $feature ) {
            $supported = true;
        }

        return $supported;
    } 


    /**
     * Send custom args to paypal to convert into recurring donation
     *
     * @param  array $paypal_args 
     * @param int $donation_id
     * @param obj Charitable_Donation_Processor class instance
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public static function paypal_args( $paypal_args, $donation_id, $processor ){

        // get the recurring donation info from the charitable processor
        $donation_period = $processor->get_donation_data_value( 'donation_period', false );
        $recurring_id    = $processor->get_donation_data_value( 'donation_plan' );

        if ( $donation_period && $recurring_id ) {

            $donation_interval = $processor->get_donation_data_value( 'donation_interval', 1 );
            $recurring_key     = $processor->get_donation_data_value( 'recurring_donation_key' );
            $donation_key      = $processor->get_donation_data_value( 'donation_key' );

            // Convert period strings into PayPay's format
            switch ( strtolower( $donation_period ) ) {
                case 'day':
                    $donation_period = 'D';
                    break;
                case 'week':
                    $donation_period = 'W';
                    break;
                case 'year':
                    $donation_period = 'Y';
                    break;
                case 'month':
                    $donation_period = 'M';
                    break;
            }
        
            $paypal_args['cmd'] = "_xclick-subscriptions";
            $paypal_args['a3'] = $paypal_args['amount'];
            $paypal_args['t3'] = strtoupper($donation_period);
            $paypal_args['p3'] = $donation_interval > 0 ? $donation_interval : 1;
            $paypal_args['src'] = 1;
            $paypal_args['sra'] = 1;
            $paypal_args['invoice'] = $recurring_key;
            $paypal_args['custom'] = json_encode( array( 'donation_id' => $donation_id, 'recurring_id' => $recurring_id, 'donation_key' => $donation_key, 'recurring_key' => $recurring_key ) );
            unset( $paypal_args['amount'] );

        }
        
        return $paypal_args;
    } 
        
    /**
     * Receives verified IPN data from PayPal and processes the payment profile. 
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_subscr_signup( $data, $donation_id ) { 
        
        $donations = self::get_valid_recurring_donation( $data, $donation_id );

        $recurring = $donations['recurring'];

        $subscr_id = isset( $data[ 'subscr_id' ] ) ? trim( $data[ 'subscr_id' ] ) : '';
        $payer_id = isset( $data[ 'payer_id' ] ) ? trim( $data[ 'payer_id' ] ) : '';
        
        // update donation with subscription profile details   
        $recurring->set_gateway_subscription_id( $subscr_id );
        $recurring->set_gateway_subscriber_id( $payer_id );

        // update the recurring donation log
        $recurring->update_donation_log( sprintf( __( 'PayPal subscription ID: %s.', 'charitable-recurring' ), $subscr_id ) );
        $recurring->update_donation_log( sprintf( __( 'PayPal subscriber ID: %s.', 'charitable-recurring' ), $payer_id ) );
        
        die( __( 'Recurring Donation IPN: Subscriber profile created', 'charitable-recurring' ) );
    } 

    /**
     * Receives verified IPN data from PayPal and processes the donation. 
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_subscr_payment( $data, $donation_id ) {
    
        $donations = self::get_valid_recurring_donation( $data, $donation_id );

        $recurring = $donations['recurring'];
        $donation = $donations['donation'];

        // is this the first donation or a renewal
        $is_first_donation = $donation->get_gateway_transaction_id() == '' ? true : false;     

        // Mark a payment as failed.
        $payment_status = isset( $data['payment_status'] ) ? strtolower( trim( $data['payment_status'] ) ) : '';

        if ( in_array( $payment_status, array( 'declined', 'failed', 'denied', 'expired', 'voided' ) ) ) {

            $message = sprintf( '%s: %s', __( 'The donation has failed with the following status', 'charitable-recurring' ), $payment_status );
            $recurring_message = sprintf( '%s: %s', __( 'The recurring donation has failed with the following status', 'charitable-recurring' ), $payment_status );

            $recurring->set_to_failed( $recurring_message );
  
            if( $is_first_donation ){
                $donation->update_donation_log( $message );
                $donation->update_status( 'charitable-failed' );
            }

            die( __( 'Recurring Donation IPN: Failed payment status', 'charitable-recurring' ) );

        }

        // Verify that the amount in the IPN matches the amount we expected.
        $amount = isset( $data['mc_gross'] ) ? trim( $data['mc_gross'] ): '';
        
        if ( $amount != $recurring->get_recurring_donation_amount() ) {

            $message = sprintf( '%s %s', __( 'The amount in the IPN response does not match the expected donation amount. IPN data:', 'charitable-recurring' ), json_encode( $data ) );

            $recurring->set_to_failed( $message );

            if( $is_first_donation ){
                $donation->update_donation_log( $message );
                $donation->update_status( 'charitable-failed' );
            }

            die( __( 'Recurring Donation IPN: donation amount mismatch', 'charitable-recurring' ) );

        }

        // Process a completed donation.
        if ( 'completed' == $payment_status ) {

            $txn_id = isset( $data['txn_id'] ) ? trim( $data['txn_id'] ): '';
    
            // set subscription to active        
            $recurring->update_status( 'charitable-active' );

            // save transaction ID to log
            $message = sprintf( '%s: %s', __( 'PayPal Transaction ID', 'charitable-recurring' ), $txn_id );

            // mark first donataion as completed and update with transaction id meta
            if( $is_first_donation ){
                $donation->update_donation_log( $message );
                $donation->set_gateway_transaction_id( $txn_id );
                $donation->update_status( 'charitable-completed' );
                die( __( 'Recurring Donation IPN: Initial donation completed', 'charitable-recurring' ) );               
            // if renewal, create new completed donation
            } else {
                $renewal_id = $recurring->create_renewal_donation( array( 'status' => 'charitable-completed' ) );
                if( $renewal_id && ! is_wp_error( $renewal_id ) ){
                    $renewal = charitable_get_donation( $renewal_id );
                    $renewal->set_gateway_transaction_id( $txn_id );
                    $renewal->update_donation_log( $message );
                    die( __( 'Recurring Donation IPN: Renewal donation completed', 'charitable-recurring' ) );
                } else {
                    die( __( 'Recurring Donation IPN Error: Renewal donation failed', 'charitable-recurring' ) );
                }
            }
            
        }

        // If the donation is set to pending but has a pending_reason provided, save that to the log.
        if ( 'pending' == $payment_status ) {

            $message = '';

            if ( isset( $data['pending_reason'] ) ) {
                $message = $gateway->get_pending_reason_note( strtolower( $data[ 'pending_reason' ] ) );
                $recurring->update_donation_log( $message );
            }

            $recurring->update_status( 'charitable-pending' );

            if( $is_first_donation ){
                if( $message ) {
                    $donation->update_donation_log( $message );
                }
                $donation->update_status( 'charitable-pending' );
            }

            die( __( 'Recurring Donation IPN: Donation pending', 'charitable-recurring' ) );

        }

        die( __( 'Recurring Donation IPN: Nothing left to process', 'charitable-recurring' ) );

    }
  

    /**
     * Receives verified IPN data from PayPal and cancels the recurring donataion
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_subscr_cancel( $data, $donation_id ) {

        $donations = self::get_valid_recurring_donation( $data, $donation_id );

        $recurring = $donations['recurring'];

        $message = __( 'Recurring donation cancelled by PayPal.', 'charitable-recurring' );

        $recurring->update_donation_log( $message );

        $recurring->update_status( 'charitable-cancel' );

        die( __( 'Recurring Donation IPN: Recurring Donation Cancelled', 'charitable-recurring' ) );

    }

    /**
     * Receives verified IPN data from PayPal and modifies the recurring donataion
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_subscr_modify( $data, $donation_id ) {

        $donations = self::get_valid_recurring_donation( $data, $donation_id );

        $recurring = $donations['recurring'];

        die( __( 'Recurring Donation IPN: Modify Subscription', 'charitable-recurring' ) );
    }

    /**
     * Receives verified IPN data from PayPal and marks the failed donataion
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_subscr_failed( $data, $donation_id ) {

        $donations = self::get_valid_recurring_donation( $data, $donation_id );

        $recurring = $donations['recurring'];

        $message = __( 'Recurring donation failed with PayPal.', 'charitable-recurring' );
        
        $recurring->update_donation_log( $message );

        $recurring->update_status( 'charitable-failed' );

        die( __( 'Recurring Donation IPN Error: Subscription Failed', 'charitable-recurring' ) );
    }

    /**
     * Receives verified IPN data from PayPal at the end of the subscription
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function process_subscr_eot( $data, $donation_id ) {

        $donations = self::get_valid_recurring_donation( $data, $donation_id );

        $recurring = $donations['recurring'];

        $message = __( 'Recurring donation expired with PayPal.', 'charitable-recurring' );
        
        $recurring->update_donation_log( $message );

        $recurring->update_status( 'charitable-expired' );

        die( __( 'Recurring Donation IPN: Subscription Expired', 'charitable-recurring' ) );
    }


    /**
     * Abstract some checks that will be performed on every IPN transaction
     *
     * @return  void
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function get_valid_recurring_donation( $data, $donation_id ) {

        // get the recurring donation id out of the custom parameter
       $recurring_data = self::parse_recurring_id_and_key( $data );

       // get the recurring donation
       $recurring = charitable_get_donation( $recurring_data['recurring_id'] );
       $recurring_key = $recurring_data['recurring_key'];

       // get the original donation
       $donation = charitable_get_donation( $donation_id );

       // the original donation has a transaction ID then it has been processed already 
       $is_first_donation = $donation->get_gateway_transaction_id() == '' ? true : false;  

       // validate the donation exists
        if ( empty( $recurring ) ) {
            die( __( 'Recurring Donation IPN Error: No matching recurring donation found.', 'charitable-recurring' ) );
        }

        // Verify that the donation key matches the one stored for the recurring donation.
        if ( $recurring_key != $recurring->get_donation_key() ) {
                    
            $message = sprintf( '%s %s', __( 'Recurring Donation key in the IPN response does not match the recurring donation. IPN data:', 'charitable-recurring' ), json_encode( $data ) );
            $recurring->update_donation_log( $message );
            $recurring->update_status( 'charitable-failed' );

            if( $is_first_donation ){
                $donation->update_donation_log( $message );
                $donation->update_status( 'charitable-failed' );
            }

            die( __( 'Recurring Donation IPN Error: Recurring Donation Key does not match invoice.', 'charitable-recurring' ) );

        }

        // verify that the gateway is PayPal
        if ( 'paypal' != $recurring->get_gateway() ) {
            die( __( 'Recurring Donation IPN Error: Gateway mismatch.', 'charitable-recurring' ) );
        }
       
        $gateway        = new Charitable_Gateway_Paypal();       

        // Verify that the business email matches the PayPal email in the settings
        $business_email = isset( $data[ 'business' ] ) && is_email( $data[ 'business' ] ) ? trim( $data[ 'business' ] ) : trim( $data[ 'receiver_email' ] );

        if ( strcasecmp( $business_email, trim( $gateway->get_value( 'paypal_email' ) ) ) != 0 ) {
            
            $message = sprintf( '%s %s', __( 'Invalid PayPal Business email. IPN data:', 'charitable-recurring' ), json_encode( $data ) );

            $recurring->update_donation_log( $message );
            $recurring->update_status( 'charitable-failed' );

            if( $is_first_donation ){
                $donation = charitable_get_donation( $donation_id );
                $donation->update_donation_log( $message );
                $donation->update_status( 'charitable-failed' );
            }

            die( __( 'Recurring Donation IPN Error: Invalid Business email.', 'charitable-recurring' ) );

        }

        // Verify that the currency matches.
        $currency_code  = isset( $data['mc_currency'] ) ? strtoupper( $data['mc_currency'] ) : '';

        if ( $currency_code != charitable_get_currency() ) {

            $message = sprintf( '%s %s', __( 'The currency in the IPN response does not match the site currency. IPN data:', 'charitable-recurring' ), json_encode( $data ) );

            $recurring->update_donation_log( $message );
            $recurring->update_status( 'charitable-failed' );

            if( $is_first_donation ){
                $donation = charitable_get_donation( $donation_id );
                $donation->update_donation_log( $message );
                $donation->update_status( 'charitable-failed' );
            }

            die( __( 'Recurring Donation IPN Error: Currency mismatch.', 'charitable-recurring' ) );

        }

        return array( 'recurring' => $recurring, 'donation' => $donation );

    }


    /**
     * Get the recurring donation id and key from the PayPal custom parameter
     *
     * @since 1.0
     */
    public static function parse_recurring_id_and_key( $data ) {

        $recurring_id = $recurring_key = '';

        if ( ! isset( $data['custom'] ) ) {
            die( __( 'missing PayPal custom data', 'charitable-recurring' ) );
        }

        // parse the JSON in the custom param
        $custom = json_decode( stripslashes( $data['custom'] ) );

        $recurring_id = isset( $custom->recurring_id ) ? absint( $custom->recurring_id ) : 0; 
        $recurring_key = isset( $custom->recurring_key ) ? $custom->recurring_key : ''; 

        return array( 'recurring_id' => (int) $recurring_id, 'recurring_key' => $recurring_key );
    }     
 
}

endif; // End class_exists check
