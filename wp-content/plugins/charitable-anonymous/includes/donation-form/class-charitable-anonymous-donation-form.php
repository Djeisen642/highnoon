<?php
/**
 * This class is responsible for adding the anonymous donation checkbox to the donation form.
 *
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_Donation_Form
 * @version   1.0.0
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License  
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_Donation_Form' ) ) : 

/**
 * Charitable_Anonymous_Donation_Form
 *
 * @since       1.0.0
 */
class Charitable_Anonymous_Donation_Form {

    /**
     * @var     Charitable_Anonymous_Donation_Form
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
            self::$instance = new Charitable_Anonymous_Donation_Form();
        }

        return self::$instance;
    }

    /**
     * Register the Anonymous Donation field.
     *
     * @since  1.2.0
     *
     * @param  array $fields Default donation fields.
     * @return array
     */
    public function register_anonymous_donation_field( $fields ) {
        $fields['anonymous_donation'] = array(
            'label'          => __( 'Keep donation anonymous', 'charitable' ),
            'data_type'      => 'meta',
            'value_callback' => 'charitable_get_donation_meta_value',
            'donation_form'  => false,
            'admin_form'     => array(
                'type'     => 'checkbox',
                'priority' => 14,
                'required' => false,
                'section'  => 'meta',
                'default'  => 0,
            ),
            'show_in_meta'   => false,
            'email_tag'      => false,
        );

        return $fields;
    }

    /**
     * Add the anonymous donation checkbox to the "Your Details" section of the donation form. 
     *
     * @return  array[]
     * @access  public
     * @since   1.0.0
     */
    public function add_anonymous_donation_field() {
        charitable_anonymous_template( 'donation-form-field.php' );
    }

    /**
     * Capture the submitted value for the anonymous_donation field.    
     *
     * @return  mixed[] 
     * @access  public
     * @since   1.1.0
     */
    public function save_submitted_value( $values ) {
        $values['anonymous_donation'] = isset( $_POST['anonymous_donation'] ) && $_POST['anonymous_donation'] ? 1 : 0;
        return $values;
    }

    /**
     * Add anonymous_donation to the list of meta fields to be saved. 
     *
     * @param   mixed[] $meta
     * @param   int $donation_id
     * @param   Charitable_Donation_Processor $processor
     * @return  mixed[]
     * @access  public
     * @since   1.1.0
     */
    public function add_meta_field( $meta, $donation_id, Charitable_Donation_Processor $processor ) {
        $meta['anonymous_donation'] = $processor->get_donation_data_value( 'anonymous_donation' );
        return $meta;
    }
}

endif; // End class_exists check