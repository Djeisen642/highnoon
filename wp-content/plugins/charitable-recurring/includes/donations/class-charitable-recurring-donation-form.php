<?php
/**
 * Donation form model class.
 *
 * @version     1.0.0
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Donation_Form
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Donation_Form' ) ) : 

/**
 * Charitable_Recurring_Donation_Form
 *
 * @since       1.0.0
 */
class Charitable_Recurring_Donation_Form {

    /**
     * Return the custom recurring donation amount.  
     *
     * @param   float $amount the submitted donation amount
     * @return  float
     * @access  public
     * @since   1.0.0
     */
    public static function get_donation_amount( $amount ) {

        if ( ! array_key_exists( 'recurring_donation', $_POST ) ) {
            return $amount;
        }

        // Don't touch regular donations.
        if ( 'once' == $_POST['recurring_donation'] ) {

            if ( 'recurring-custom' == $amount ) {
                $amount = array_key_exists( 'custom_donation_amount', $_POST ) ? $_POST['custom_donation_amount'] : 0;
            }

            return $amount;
        }

        // Only switch the amount if in advanced mode
        if ( 'recurring-custom' == $amount && isset( $_POST[ 'custom_recurring_donation_amount' ] ) ) {
            $amount = $_POST[ 'custom_recurring_donation_amount' ];
            $amount = charitable_get_currency_helper()->sanitize_monetary_amount( $amount );
        }       

        return $amount;

    }

    /**
     * Include recurring info in submitted data
     *
     * @param  array $submitted, is the $_POST array 
     * @param obj Charitable_Form class instance
     * @return  array
     * @access  public
     * @since   1.0.2
     */
    public static function form_submitted_values( $submitted, $form ){

        if ( isset( $submitted['recurring_donation'] ) && 'once' != $submitted['recurring_donation'] ) {
            $submitted['donation_period']   = $submitted['recurring_donation']; //  @TODO: Validate against available billing periods.
            $submitted['donation_interval'] = 1;
        }

        return $submitted;

    }


    /**
     * Include recurring info in submission data of main form
     *
     * @param  array $values 
     * @param  array $submitted, a selection of the $_POST array 
     * @param obj Charitable_Form class instance
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public static function submission_values( $values, $submitted, $form ){

        if ( isset( $submitted['donation_period'] ) && 'once' != $submitted['donation_period'] ) {

            // store the recurring data in the processor
            $values['campaigns'][0]['donation_period'] = $submitted['donation_period'];
            $values['campaigns'][0]['donation_interval'] = 1;
            $values['donation_period']   = $submitted['donation_period'];
            $values['donation_interval'] = 1;

        }
    
        return $values;
    } 

    /**
     * Set the value in the session
     *
     * @param   string                   $amount     The amount in the session, formatted.
     * @param   string                   $raw_amount The unformatted amount.
     * @param   Charitable_Donation_Form $form       An instance of `Charitable_Donation_Form`.
     * @return  string
     * @access  public
     * @static
     * @since   1.0.6
     */
    public static function set_session_value_with_donation_form( $amount, $raw_amount, $form ) {
        return self::set_session_value( $amount, $raw_amount, $form->get_campaign()->ID );
    }

    /**
     * Set the value in the session
     *
     * @param   string $amount      The amount in the session, formatted.
     * @param   string $raw_amount  The unformatted amount.
     * @param   int    $campaign_id The campaign ID.
     * @return  string
     * @access  public
     * @static
     * @since   1.0.2
     */
    public static function set_session_value( $amount, $raw_amount, $campaign_id ) {
        $session_donation = charitable_get_session()->get_donation_by_campaign( $campaign_id );

        /* This should never happen, but catch it anyway. */
        if ( ! $session_donation ) {
            return $amount;
        }

        /* This isn't a recurring donation. */
        if ( ! array_key_exists( 'donation_period', $session_donation ) ) {
            return $amount;
        }

        return charitable_recurring_get_recurring_donation_string( array(
            'amount' => $raw_amount,
            'period' => $session_donation['donation_period'],
        ) );

    }

    /**
     * Include recurring info in submission data for widget form
     *
     * @param  array $values 
     * @param  array $submitted, a selection of the $_POST array 
     * @return  array
     * @access  public
     * @since   1.0.2
     */
    public static function widget_submission_values( $values, $submitted ){

        if ( isset( $submitted['donation_period'] ) && 'once' != $submitted['donation_period'] ) {
            $values['donation_period']   = $submitted['donation_period'];
            $values['donation_interval'] = 1;
        }
    
        return $values;
    } 

}

endif; // End class_exists check