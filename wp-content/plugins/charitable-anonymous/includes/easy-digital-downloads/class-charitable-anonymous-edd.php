<?php
/**
 * Sets up the integration with Easy Digital Downloads.
 *
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_EDD
 * @version   1.1.0
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_EDD' ) ) : 

/**
 * Charitable_Anonymous_EDD
 *
 * @since       1.1.0
 */
class Charitable_Anonymous_EDD {

    /**
     * @var     Charitable_Anonymous_EDD
     * @access  private
     * @static
     * @since   1.1.0
     */
    private static $instance = null;

    /**
     * Create class object. Private constructor. 
     * 
     * @access  private
     * @since   1.1.0
     */
    private function __construct() {
    }

    /**
     * Create and return the class object.
     *
     * @access  public
     * @static
     * @since   1.1.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Anonymous_EDD();            
        }

        return self::$instance;
    }

    /**
     * Add the field to the EDD checkout form.  
     *
     * @return  void
     * @access  public
     * @since   1.1.0
     */
    public function add_checkout_field() {
        charitable_anonymous_template( 'easy-digital-downloads/checkout-form-field.php' );
    }

    /**
     * Save the anonymous donation value.  
     *
     * @param   mixed[] $payment_meta
     * @return  mixed[]
     * @access  public
     * @since   1.1.0
     */
    public function save_anonymous_donation_to_edd_payment( $payment_meta ) {
        $payment_meta[ 'anonymous_donation' ] = isset ( $_POST[ 'anonymous_donation' ] ) && $_POST[ 'anonymous_donation' ];

        return $payment_meta;
    }

    /**
     * Save the anonymous donation value to the Charitable donation data.  
     *
     * @param   mixed[] $donation_args
     * @param   int $payment_id
     * @return  mixed[]
     * @access  public
     * @since   1.1.0
     */
    public function save_anonymous_donation_to_donation( $donation_args, $payment_id ) {
        $payment_meta = edd_get_payment_meta( $payment_id );

        $donation_args[ 'anonymous_donation' ] = $payment_meta[ 'anonymous_donation' ];

        return $donation_args;
    }    
}

endif; // End class_exists check