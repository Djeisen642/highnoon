<?php 

/**
 * Charitable Recurring Core Functions. 
 *
 * General core functions.
 *
 * @author      Studio164a
 * @category    Core
 * @package     Charitable Recurring
 * @subpackage  Functions
 * @version     1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * This returns the original Charitable_Recurring object. 
 *
 * Use this whenever you want to get an instance of the class. There is no
 * reason to instantiate a new object, though you can do so if you're stubborn :)
 *
 * @return  Charitable_Recurring
 * @since   1.0.0
 */
function charitable_recurring() {
    return Charitable_Recurring::get_instance();
}


/**
 * Returns the Charitable_Recurring_Donation_Processor class instance. 
 *
 * @return  Charitable_Recurring_Donation_Processor
 * @since   1.0.0
 */
function charitable_recurring_get_donation_processor() {
    return Charitable_Recurring_Donation_Processor::get_instance();
}

/**
 * Displays a template. 
 *
 * @param   string|array    $template_name      A single template name or an ordered array of template
 * @param   arary           $args               Optional array of arguments to pass to the view.
 * @return  Charitable_Recurring_Template
 * @since   1.0.0
 */
function charitable_recurring_template( $template_name, array $args = array() ) {
    if ( empty( $args ) ) {
        $template = new Charitable_Recurring_Template( $template_name ); 
    }
    else {
        $template = new Charitable_Recurring_Template( $template_name, false ); 
        $template->set_view_args( $args );
        $template->render();
    }

    return $template;
}

/**
 * get array of gateways that support recurring donations
 * @TODO: store value in $helper?
 *
 * @return  array
 * @since   1.0.0
 */
function charitable_recurring_get_supporting_gateways() {
    $helper     = charitable_get_helper( 'gateways' );
    $gateways   = $helper->get_active_gateways();
    $supported_gateways = array();

    foreach ( $gateways as $id => $class ) {
        $gateway = $helper->get_gateway_object( $id );
        if ( ! is_null( $gateway ) && $gateway->supports( 'recurring' ) ) {
            $supported_gateways[$id] = $gateway;
        }
    }   

    return $supported_gateways;
}

/**
 * Get the suggestion donation amounts
 *
 * @param   int     $campaign_id
 * @return  array
 * @access  public
 * @since   1.0.0
 */
function charitable_recurring_get_suggested_donations( $campaign_id ) {
    $meta_name = '_campaign_suggested_recurring_donations'; 
    $suggested_donations = get_post_meta( $campaign_id, $meta_name, true );

    if ( ! is_array( $suggested_donations ) ) {
        $suggested_donations = array();
    }

    return apply_filters( 'charitable_recurring_get_suggested_donations', $suggested_donations, $campaign_id );
}


/**
 * Return the recurring "donation" price + terms
 *
 * @param   array     $args
 * @return  string
 * @since   1.0.0
 */
function charitable_recurring_get_recurring_donation_string( $args = array() ) {

    if( ! isset( $args['amount'] ) ){
        return '';
    }

    $defaults = array(
        'amount' => 0,
        'period' => "month",
        'interval' => 1,
        'length' => 0
    );

    $args = wp_parse_args( $args, $defaults );

    $currency_helper = charitable_get_currency_helper();

    $html = $currency_helper->get_monetary_amount( $args[ 'amount' ] );

    $donation_interval    = $args['interval'];
    $donation_period      = $args['period']; 
    $donation_length = $args['length']; 

    $html .= ' <span class="recurring-details">';

    // translators: 1$: <price> / 2$: <interval> (e.g. "$15 / month" or "$15 every 2nd month")
    $html = sprintf( _n( '%1$s / %2$s', ' %1$s every %2$s', $donation_interval, 'charitable-recurring' ), $html, charitable_recurring_get_donation_period_strings( $donation_interval, $donation_period ) );
    
    // Add the length to the end
    if ( 0 != $donation_length ) {
        // translators: 1$: recurring string (e.g. "$15 on March 15th every 3 years"), 2$: lenght (e.g. "for 6 years")
        $html = sprintf( __( '%1$s for %2$s', 'charitable-recurring' ), $html, $ranges[ $donation_length ] );
    }

    $html .= '</span>';

    return apply_filters( 'charitable_recurring_donation_string', $html );

}

/**
 * Return an i18n'ified associative array of all possible subscription periods.
 *
 * @param int (optional) An interval in the range 1-6
 * @param string (optional) One of day, week, month or year. If empty, all subscription periods are returned.
 * @since 1.0.0
 */
function charitable_recurring_get_donation_period_strings( $number = 1, $period = '' ) {

    $translated_periods = apply_filters( 'charitable_recurring_periods',
        array(
            'day'   => sprintf( _n( 'day', '%s days', $number, 'charitable-recurring' ), $number ),
            'week'  => sprintf( _n( 'week', '%s weeks', $number, 'charitable-recurring' ), $number ),
            'month' => sprintf( _n( 'month', '%s months', $number, 'charitable-recurring' ), $number ),
            'year'  => sprintf( _n( 'year', '%s years', $number, 'charitable-recurring' ), $number ),
        )
    );

    return ( ! empty( $period ) ) ? $translated_periods[ $period ] : $translated_periods;
}


/**
 * Return an i18n'ified associative array of all possible billing periods.
 *
 * @param string (Optional) Either 'singular' for singular trial periods or 'plural'.
 * @since 1.0.0
 * @deprecated 1.0.6 Was never used, so should eventually be removed
 */
function charitable_recurring_get_available_time_periods( $form = 'singular' ) {

    charitable_get_deprecated()->deprecated_function(
        __FUNCTION__,
        '1.0.6',
        'charitable_recurring_get_donation_period_strings()'
    );

    $number = ( 'singular' === $form ) ? 1 : 2;

    return charitable_recurring_get_donation_period_strings( $number );

}