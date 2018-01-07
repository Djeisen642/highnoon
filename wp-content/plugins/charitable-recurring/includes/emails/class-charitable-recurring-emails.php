<?php
/**
 * Class that sets up the emails. 
 *
 * @version     1.0.0
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Emails
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy is Awesome
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Emails' ) ) : 

/**
 * Charitable_Recurring_Emails
 *
 * @since       1.0.0
 */
class Charitable_Recurring_Emails {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_Emails|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * Set up the class. 
     * 
     * Note that the only way to instantiate an object is with the charitable_start method, 
     * which can only be called during the start phase. In other words, don't try 
     * to instantiate this object. 
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {}

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Emails
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Emails();
        }

        return self::$instance;
    }

    /**
     * Register Charitable Recurring emails. 
     *
     * @param  array $emails
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function register_emails( $emails ) {
        $recurring_emails = array(
            'admin_new_recurring_donation' => 'Charitable_Recurring_Admin_Email_New_Recurring_Donation',
            'admin_new_renewal_donation' => 'Charitable_Recurring_Admin_Email_New_Renewal_Donation',
            'recurring_donation_receipt' => 'Charitable_Recurring_Email_Recurring_Donation_Receipt',
        );

        return array_merge( $emails, $recurring_emails );
    }


    /**
     * Add donation content fields.   
     *
     * @param array $fields
     * @param Charitable_Email object $email
     * @return  array[]
     * @access  public
     * @since   1.0.0
     */
    public function add_donation_content_fields( $fields, Charitable_Email $email ) { 
        
        if ( ! in_array( 'donation', $email->get_object_types() ) ) {
            return $fields;
        }

        $fields[ 'recurring_summary' ] = array(
            'description'   => __( 'A summary of the recurring donation', 'charitable-recurring' ), 
            'callback'      => array( $this, 'get_recurring_donation_summary' )
        );
        
        return $fields;
    }


    /**
     * Add donation content fields' fake data for previews.
     *
     * @param array $fields
     * @param Charitable_Email object $email
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function add_preview_donation_content_fields( $fields, Charitable_Email $email ) {  
        
        if ( ! in_array( 'donation', $email->get_object_types() ) ) {
            return $fields;
        }

        $fields[ 'recurring_summary' ]   = __( 'Fake Campaign: $50.00 / month', 'charitable-recurring' ) . PHP_EOL;
        
        return $fields;
    }


    /**
     * Returns a summary of the donation, including all the campaigns that were donated to.  
     *
     * @param   string $value
     * @param   mixed[] $args
     * @param   Charitable_Email $email
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_recurring_donation_summary( $value, $args, $email ) {

        if ( ! $email->has_valid_donation() ) {
            return $value;
        }

        $output = '';

        foreach ( $email->get_donation()->get_campaign_donations() as $campaign_donation ) {
            
            $donation = charitable_get_donation( $campaign_donation->donation_id );
            $recurring = $donation->get_donation_plan();

            if( $recurring ){
                $line_item = sprintf( '%s: %s%s', $campaign_donation->campaign_name, $recurring->get_recurring_donation_amount( true ), PHP_EOL );
                $output .= apply_filters( 'charitable_recurring_donation_summary_line_item_email', $line_item, $campaign_donation, $args, $email );
            }
        }

        return $output;
        
    }

}

endif; // End class_exists check