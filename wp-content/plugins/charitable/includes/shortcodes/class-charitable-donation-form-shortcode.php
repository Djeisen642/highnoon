<?php
/**
 * Donation Form shortcode class.
 * @package  Charitable/Shortcodes/Donation Form
 * @category Class
 * @author   Eric Daams
 * @since    1.5.0
 * @version  1.5.7
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Donation_Form_Shortcode' ) ) :

	/**
	 * Charitable_Donation_Form_Shortcode class.
	 *
	 * @since 1.5.0
	 */
	class Charitable_Donation_Form_Shortcode {

		/**
		 * The callback method for the donation form shortcode.
		 *
		 * This receives the user-defined attributes and passes the logic off to the class.
		 *
		 * @since  1.5.0
		 *
		 * @param  array $atts User-defined shortcode attributes.
		 * @return string
		 */
		public static function display( $atts ) {
			$defaults = array (
		 		'campaign_id' => 0
			);
	
			// Parse incoming $atts into an array and merge it with $defaults
			$args = shortcode_atts( $defaults, $atts, 'charitable_donation_form' );


		    if ( Charitable::CAMPAIGN_POST_TYPE !== get_post_type( $args['campaign_id'] ) ) {
		        return '';
		    }

		    ob_start();

		    if ( ! wp_script_is( 'charitable-script', 'enqueued' ) ) {
		        Charitable_Public::get_instance()->enqueue_donation_form_scripts();
		    }
		    
		    $form = charitable_get_campaign( $args['campaign_id'] )->get_donation_form();

		    do_action( 'charitable_donation_form_before', $form );

		    $args['form'] = $form;
		    $args['campaign'] = $form->get_campaign();
		    
		    charitable_template( 'donation-form/form-donation.php', $args );
		    
		    do_action( 'charitable_donation_form_after', $form );
		    
		    return ob_get_clean();

		}
	}

endif;
