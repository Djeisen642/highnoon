<?php 
/**
 * Charitable Public class. 
 *
 * @package 	Charitable/Classes/Charitable_Recurring_Public
 * @version     1.0.0
 * @author 		Kathy Darling
 * @copyright 	Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Public' ) ) : 

/**
 * Charitable Public class. 
 *
 * @final
 * @since 	    1.0.0
 */
final class Charitable_Recurring_Public {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_Public|null
     * @access  private
     * @static
     */
    private static $instance = null;    

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Public
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Public();
        }

        return self::$instance;
    }

	/**
	 * Set up the class. 
	 *
	 * @access 	private
	 * @since 	1.0.0
	 */
	private function __construct() {				

        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts'), 20 );

        add_action( 'after_setup_theme', array( $this, 'load_template_files' ) );

        // add recurring fields to donation form
        add_filter( 'charitable_donation_form_donation_fields', array( $this, 'recurring_donation_fields' ), 10, 2 );

        add_filter( 'charitable_form_field_template', array( $this, 'recurring_donation_templates' ), 10, 2 );

		do_action( 'charitable_recurring_public_start', $this );
	}    

    
	/**
	 * Loads public facing scripts and stylesheets. 
	 *
	 * @return 	void
	 * @access 	public
	 * @since 	1.0.0
	 */
	public function wp_enqueue_scripts() {		

        $helper = charitable_get_helper( 'gateways' );

        if( $helper->any_gateway_supports( 'recurring' ) ){			

            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    		wp_register_script(
                'charitable-recurring-script',
                charitable_recurring()->get_path( 'assets', false ) . 'js/charitable-recurring' . $suffix . '.js',
                array( 'jquery', 'charitable-script' ),
                charitable_recurring()->get_version(),
                true
            );

            wp_enqueue_script( 'charitable-recurring-script' );

            wp_localize_script(
                'charitable-recurring-script',
                'Charitable_Recurring',
                array( 'supported_gateways' => array_keys( charitable_recurring_get_supporting_gateways() ) )
            );

    		wp_register_style(
                'charitable-recurring-styles',
                charitable_recurring()->get_path( 'assets', false ) . 'css/charitable-recurring' . $suffix . '.css',
                array(),
                charitable_recurring()->get_version() 
            );

    		wp_enqueue_style( 'charitable-recurring-styles' );

        }

	}

    /**
     * Load the template functions after theme is loaded. 
     *
     * This gives themes time to override the functions. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function load_template_files() {
        require_once( 'charitable-recurring-template-functions.php' );        
    }


    /**
     * Add the recurring donation fields to the page.
     *
     * @param   array                    $fields
     * @param   Charitable_Donation_Form $form
     * @return  array
     * @since   1.0.0
     */
    public function recurring_donation_fields( $fields, Charitable_Donation_Form $form ) {

        $helper = charitable_get_helper( 'gateways' );

        if ( ! $helper->any_gateway_supports( 'recurring' ) ) {
            return $fields;
        }

        $campaign = $form->get_campaign();

        $mode = $campaign->get( 'recurring_donations' );

        if ( 'disabled' == $mode ) {
            return $fields;
        }

        // Just add the checkbox
        if ( 'simple' == $mode ) {

            $donation = charitable_get_session()->get_donation_by_campaign( $campaign->ID );

            $is_recurring   = is_array( $donation ) && isset( $donation['donation_period'] ) && 'month' == $donation['donation_period'] ? true : false;

            $fields['recurring_donation'] = array(
                'type'     => 'checkbox',
                'priority' => 5,
                'label'    => __( 'Make it monthly', 'charitable-recurring' ),
                'value'    => 'month',
                'class'    => 'recurring-donation-option',
                'checked'  => $is_recurring,
            );

        }

        // Add the tabbed layout for the advanced mode
        if ( 'advanced' == $mode ) {

            $fields['recurring_donation'] = array(
                'type'     => 'recurring-donation', 
                'priority' => 2,
                'required' => false,
            );

            $fields['recurring_donation_amount'] = array(
                'type'     => 'recurring-donation-amount', 
                'priority' => 3,
                'required' => false,
            );

        }

        return $fields;

    }


    /**
     * Use custom template for some form fields.
     *
     * @param   string|false $custom_template
     * @param   array   $field
     * @return  string|false|Charitable_Template
     * @access  public
     * @since   1.0.0
     */
    public function recurring_donation_templates( $custom_template, $field ) {
        $donation_form_templates = array( 'recurring-donation', 'recurring-donation-amount' );

        if ( in_array( $field[ 'type' ], $donation_form_templates ) ) {

            $template_name = 'donation-form/' . $field[ 'type' ] . '.php';
            $custom_template = new Charitable_Recurring_Template( $template_name, false  );

        }

        return $custom_template;
    }

}

endif;