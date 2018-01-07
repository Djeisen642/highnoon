<?php
/**
 * Class that models the donation receipt email.
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Recurring_Email_Recurring_Donation_Receipt
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy is Awesome
 * @license     https://opensource.org/licenses/gpl-3.0.html GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Recurring_Email_Recurring_Donation_Receipt' ) ) : 

/**
 * Donation Receipt Email 
 *
 * @since       1.0.0
 */
class Charitable_Recurring_Email_Recurring_Donation_Receipt extends Charitable_Email {

    /**
     * @var     string
     */
    CONST ID = 'recurring_donation_receipt';

    /**
     * @var     string[] Array of supported object types (campaigns, donations, donors, etc).
     * @access  protected
     * @since   1.0.0
     */
    protected $object_types = array( 'donation' );

    /**
     * Instantiate the email class, defining its key values.
     *
     * @param   array   $objects
     * @access  public
     * @since   1.0.0
     */
    public function __construct( $objects = array() ) {
        parent::__construct( $objects );
        
        $this->name = apply_filters( 'charitable_email_name', __( 'Donor: Recurring Donation Receipt', 'charitable-recurring' ), $this );        
    }

    /**
     * Returns the current email's ID.  
     *
     * @return  string
     * @access  public
     * @static
     * @since   1.0.3
     */
    public static function get_email_id() {
        return self::ID;
    }

    /**
     * Static method that is fired right after a donation is completed, sending the donation receipt.
     *
     * @param   int     $donation_id
     * @return  boolean
     * @access  public
     * @static
     * @since   1.0.0
     */
    public static function send_with_donation_id( $donation_id ) {

        if ( ! charitable_get_helper( 'emails' )->is_enabled_email( self::get_email_id() ) ) {
            return false;
        }
        
        if ( ! charitable_is_approved_status( get_post_status( $donation_id ) ) ) {
            return false;
        }
        
        $donation = charitable_get_donation( $donation_id );

        // cannot send if we don't have an actual donation or if donation has no campaigns( which happens when trying to send before campaign donations are saved )
        if ( ! is_object( $donation ) || 0 == count( $donation->get_campaign_donations() ) ) {
            return false;
        }

        // if donation doesn't have a post_parent then don't send
        if( $donation->get_donation_plan_id() == 0 ){
            return false;
        }

        // add toggle filter
        if ( ! apply_filters( 'charitable_send_' . self::get_email_id(), true, $donation ) ) {
            return false;
        }

        // kill the regular donation email
        add_filter( 'charitable_send_donation_receipt', '__return_false' );
        
        // finally, send our version of the email
        $email = new Charitable_Recurring_Email_Recurring_Donation_Receipt( array( 
            'donation' => $donation 
        ) );

        // Don't resend the email.
        if ( $email->is_sent_already( $donation_id ) ) {
            return false;
        }

        $sent = $email->send();

        // Log that the email was sent.
        if ( apply_filters( 'charitable_log_email_send', true, self::get_email_id(), $email ) ) {
            $email->log( $donation_id, $sent );
        }

        return $sent;

    }

    /**
     * Return the recipient for the email.
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_recipient() {
        if ( ! $this->has_valid_donation() ) {
            return '';
        }
        
        return apply_filters( 'charitable_email_receipient', $this->donation->get_donor()->get_email(), $this );
    }

    /**
     * Return the default subject line for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_subject() {
        return apply_filters( 'charitable_email_default_subject', __( 'Thank you for your donation', 'charitable-recurring' ), $this );   
    }

    /**
     * Return the default headline for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_headline() {
        return apply_filters( 'charitable_email_default_headline', __( 'Your Donation Receipt', 'charitable-recurring' ), $this );    
    }

    /**
     * Return the default body for the email.
     *
     * @return  string
     * @access  protected
     * @since   1.0.0
     */
    protected function get_default_body() {
        ob_start();
?>
Dear [charitable_email show=donor_first_name],

Thank you so much for your generous ongoing donation.

<strong>Your Receipt</strong>
[charitable_email show=donation_summary]

<strong>Your Subscription Details</strong>
[charitable_email show=recurring_summary]

With thanks, [charitable_email show=site_name]
<?php
        $body = ob_get_clean();

        return apply_filters( 'charitable_email_default_body', $body, $this );
    }
}

endif; // End class_exists check