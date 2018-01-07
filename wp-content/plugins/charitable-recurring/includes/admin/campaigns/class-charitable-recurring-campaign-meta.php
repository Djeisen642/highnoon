<?php
/**
 * The class that defines how campaigns are managed on the admin side.
 * 
 * @package     Charitable_Recurring/Admin/Charitable_Recurring_Campaign_Meta
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Recurring_Campaign_Meta' ) ) : 

/**
 * Charitable_Recurring_Campaign_Meta class.
 *
 * @final
 * @since       1.0.0
 */
final class Charitable_Recurring_Campaign_Meta {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_Campaign_Meta|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * @var     Charitable_Meta_Box_Helper $meta_box_helper
     * @access  private
     */
    private $meta_box_helper;

    /**
     * Create object instance. 
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {    
        $this->meta_box_helper = new Charitable_Meta_Box_Helper( 'charitable-campaign' );
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Campaign_Meta
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Campaign_Meta();
        }

        return self::$instance;
    }    



    /**
     * Adds fields to the campaign donation options metabox. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function recurring_donation_options() {
        /* Get the array of fields to be displayed within the campaign donations metabox. */
        $fields = array(
            'donations'     => array(
                'priority'  => 4, 
                'view'      => 'metaboxes/campaign-donation-options/suggested-recurring-amounts', 
                'label'     => __( 'Suggested Recurring Donation Amounts', 'charitable-recurring' ), 
                'fields'    => apply_filters( 'charitable_campaign_recurring_donation_suggested_amounts_fields', array(
                    'amount'    => array(
                        'column_header' => __( 'Amount', 'charitable-recurring' ), 
                        'placeholder'   => __( 'Amount', 'charitable-recurring' )
                    ), 
                    'description'   => array(
                        'column_header' => __( 'Description (optional)', 'charitable-recurring' ), 
                        'placeholder'   => __( 'Optional Description', 'charitable-recurring' )
                    )
                ) )
            ), 
        );

        $this->meta_box_helper->display_fields( apply_filters( 'charitable_campaign_recurring_donation_options_fields', $fields ) );
    }

    /**
     * Add the fields for recurring donations to the donation options metabox.
     *
     * @param   array $fields
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function recurring_donation_fields( $fields ) {
        global $post;

        // $fields[ 'section-recurring-separator' ] = array(
        //     'view'     => 'metaboxes/field-types/hr',
        //     'priority' => 8
        // );

        // $fields[ 'section-recurring-heading' ] = array(
        //     'view'     => 'metaboxes/field-types/heading',
        //     'priority' => 9, 
        //     'title'    => __( 'Recurring Donations', 'charitable-recurring' )
        // );

        $fields[ 'recurring-mode' ] = array(
            'view'     => 'metaboxes/field-types/select',
            'priority' => 10,
            'label'    => __( 'Recurring Donations Mode', 'charitable-recurring' ), 
            'meta_key' => '_campaign_recurring_donations', 
            'options'  => array(
                'simple'   => __( 'Simple &mdash; Allow donors to make their donation recurring', 'charitable-recurring' ),
                'advanced' => __( 'Advanced &mdash; Define unique giving options for recurring donations', 'charitable-recurring' ),
                'disabled' => __( 'Disabled &mdash; No recurring donations are accepted', 'charitable-recurring' ),
            ),
            'default'  => 'disabled',
        );


        $fields[ 'recurring-suggested-options' ] = array(
            'view'     => 'metaboxes/campaign-donation-options/suggested-recurring-amounts',
            'priority' => 15,
            'label'    => __( 'Suggested Recurring Donation Amounts', 'charitable-recurring' ), 
            'fields'   => apply_filters( 'charitable_campaign_recurring_donation_suggested_amounts_fields', array(
                'amount'    => array(
                    'column_header' => __( 'Amount', 'charitable-recurring' ), 
                    'placeholder'   => __( 'Amount', 'charitable-recurring' )
                ), 
                'description'   => array(
                    'column_header' => __( 'Description (optional)', 'charitable-recurring' ), 
                    'placeholder'   => __( 'Optional Description', 'charitable-recurring' )
                )
            ) ),
        );

        $fields[ 'recurring-default-tab' ] = array(
            'view'     => 'metaboxes/field-types/select',
            'priority' => 20,
            'label'    => __( 'Default Tab', 'charitable-recurring' ), 
            'meta_key' => '_campaign_recurring_default_tab', 
            'options'  => array(
                'one-time'   => __( 'One Time Donation Amounts', 'charitable-recurring' ),
                'recurring' => __( 'Recurring Donation Amounts', 'charitable-recurring' ),
            ),
            'default'  => 'one-time',
        );

        return $fields;
    }

    /**
     * Adds fields to the campaign donation options metabox. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function admin_view_path( $path, $view, $view_args ) {

        if( $view == 'metaboxes/campaign-donation-options/suggested-recurring-amounts' ){
            $path = charitable_recurring()->get_path( 'includes' ) . 'admin/views/metaboxes/campaign-donation-options/suggested-recurring-amounts.php';
        }
        return $path;
    }


    /**
     * Add keys so Charitable will save meta automatically. 
     * 
     * @param   array $keys
     * @return  array
     * @access  public 
     * @since   1.0.5
     */
    public static function campaign_meta_keys( $keys ) {
        
        return array_merge( $keys, apply_filters( 'charitable_recurring_campaign_meta_keys', array(
                '_campaign_recurring_donations',
                '_campaign_suggested_recurring_donations',
                '_campaign_recurring_default_tab'
            ) ) );

    }


    /**
     * Sanitize the donation mode.
     *
     * @param   string $value
     * @return  string|int
     * @access  public
     * @static
     * @since   1.0.5
     */
    public static function sanitize_donation_mode( $value ) {
        $mode = isset( $_POST['_campaign_recurring_donations' ] ) ?  sanitize_text_field( $_POST['_campaign_recurring_donations'] ) : 'simple';

        if ( ! in_array( $mode, array( 'simple', 'advanced', 'disabled' ) ) ) {
            $mode = 'simple';
        }

        return charitable_get_currency_helper()->sanitize_monetary_amount( $value );
    }

    /**
     * Sanitize the default tab.
     *
     * @param   string $value
     * @return  string|int
     * @access  public
     * @static
     * @since   1.0.5
     */
    public static function sanitize_default_tab( $value ) {
       return isset( $_POST['_campaign_recurring_default_tab'] ) && 'recurring' == $_POST['_campaign_recurring_default_tab'] ? 'recurring' : 'one-time';
    }

    /**
     * Save meta for the campaign. 
     * 
     * @param   int $campaign_id
     * @param   WP_Post $post
     * @return  void
     * @access  public 
     * @since   1.0.0
     */
    public function save_recurring_options( $post ) {

        _deprecated_function( 'Charitable_Recurring_Campaign_Meta::save_recurring_options', '1.0.5', 'Meta keys are now saved automatically by Charitable core.' );

        if ( ! $this->meta_box_helper->user_can_save( $post->ID ) ) {
            return;
        }
       
        // Recurring donation mode. 
        $mode = isset( $_POST['_campaign_recurring_donations' ] ) ?  sanitize_text_field( $_POST['_campaign_recurring_donations'] ) : 'simple';

        if ( ! in_array( $mode, array( 'simple', 'advanced', 'disabled' ) ) ) {
            $mode = 'simple';
        }

        update_post_meta( $post->ID, '_campaign_recurring_donations', $mode );

        // Suggested recurring donations. 
        $suggested = isset( $_POST['_campaign_suggested_recurring_donations' ] ) ?  sanitize_text_field( $_POST['_campaign_recurring_donations'] ) : array();

        $suggested = apply_filters( 'charitable_sanitize_campaign_meta_campaign_suggested_recurring_donations', $_POST['_campaign_suggested_recurring_donations'] );

        update_post_meta( $post->ID, '_campaign_suggested_recurring_donations', $suggested );

        // Default tab, one-time or recurring.
        $default_tab = isset( $_POST['_campaign_recurring_default_tab'] ) && 'recurring' == $_POST['_campaign_recurring_default_tab'] ? 'recurring' : 'one-time';

        update_post_meta( $post->ID, '_campaign_recurring_default_tab', $default_tab );

    }   

}

endif; // End class_exists check