<?php 
/**
 * Charitable Template Functions. 
 *
 * Functions used with template hooks.
 * 
 * @package     Charitable_Recurring/Functions/Templates
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly



/**********************************************/
/* DONATION FORM SUBTITLES
/**********************************************/

if ( ! function_exists( 'charitable_recurring_suggested_donation_subtitle' ) ) :
    /**
     * Add a subheading to regular donation amounts to distinguish from recurring sections
     *
     * @param   obj $form
     * @return  void
     * @since   1.0.0
     */
    function charitable_recurring_suggested_donation_subtitle( $form ) {

        if ( 'disabled' != $form->get_campaign()->get( 'recurring_donations' ) ) {
            charitable_recurring_template( 'donation-form/donation-amounts-subheading.php', array( 'form' => $form ) );
        }

    }
endif;

if ( ! function_exists( 'charitable_recurring_suggested_recurring_donation_subtitle' ) ) :
    /**
     * Add a subheading to regular donation amounts to distinguish from recurring sections
     *
     * @param   obj $form
     * @return  void
     * @since   1.0.0
     */
    function charitable_recurring_suggested_recurring_donation_subtitle( $form ) {

        if ( 'disabled' != $form->get_campaign()->get( 'recurring_donations' ) ) {
            charitable_recurring_template( 'donation-form/recurring-donation-amounts-subheading.php', array( 'form' => $form ) );
        }

    }
endif;


/**********************************************/
/* DONATION RECEIPT
/**********************************************/

if ( ! function_exists( 'charitable_recurring_template_recurring_donation_details' ) ) :
    /**
     * Add the recurring info to the donation receipt. This can be seen in the [donation_receipt] shortcode or through `the_content` filter.
     *
     * @param   string  $content
     * @return  string
     * @since   1.0.0
     */
    function charitable_recurring_template_recurring_donation_details( Charitable_Donation $donation ) {
        $has_recurring = false;

        foreach ( $donation->get_campaign_donations() as $campaign_donation ) {

            $recurring_id = charitable_recurring_get_recurring_for_donation( $campaign_donation->donation_id );

            if( $recurring_id ){
                $recurring = charitable_get_donation( $recurring_id );
                $has_recurring = true;
                break;
            }
        }

        if( $has_recurring ){
            charitable_recurring_template( 'donation-receipt/recurring-details.php', array( 'recurring' => $recurring ) ); 
        }
               
    }
endif;

/**
 * Add extra styles for Recurring Donations.
 *
 * @param   string $highlight_colour
 * @return  void
 * @since   1.0.0
 */
if ( ! function_exists( 'charitable_recurring_custom_styles' ) ) :
    function charitable_recurring_custom_styles( $highlight_colour ) {
        echo '.charitable-donation-form .recurring-donation .recurring-donation-option.selected > label { color: ' . $highlight_colour . ';
        }';
    }
endif;
