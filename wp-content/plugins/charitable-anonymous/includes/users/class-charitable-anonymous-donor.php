<?php
/**
 * Manages customizations to the donor & user models.
 *
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_Donor
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_Donor' ) ) : 

    /**
     * Charitable_Anonymous_Donor
     *
     * @since 1.0.0
     */
    class Charitable_Anonymous_Donor {

        /**
         * @var     Charitable_Anonymous_Donor
         * @access  private
         * @static
         * @since   1.1.0
         */
        private static $instance = null;

        /**
         * Local copy of the donor query results.
         *
         * @since 1.2.0
         *
         * @var   array
         */
        private $donor_query_results;

        /**
         * Index that we're up to while looping over the donors.
         *
         * @since 1.2.0
         *
         * @var   int
         */
        private $loop_index;

        /**
         * Create class object. Private constructor. 
         * 
         * @since 1.1.0
         */
        private function __construct() {        
        }

        /**
         * Create and return the class object.
         *
         * @since  1.1.0
         *
         * @return Charitable_Anonymous_Donor
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new Charitable_Anonymous_Donor();            
            }

            return self::$instance;
        }

        /**
         * Marks a Donor object as anonymous when returning it from a query.
         *
         * @since 1.2.1
         *
         * @param  Charitable_Donor $donor  The instance of `Charitable_Donor`.
         * @param  object           $record Database record for the donor.
         * @return Charitable_Donor
         */
        public function set_donor_object_anonymity( Charitable_Donor $donor, $record ) {
            $donor->is_anonymous = $this->is_donor_anonymous( $donor, $record );
            return $donor;
        }

        /**
         * Set the donor's name to anonymous.
         *
         * @since  1.0.0
         *
         * @param  string           $name  Donor name.
         * @param  Charitable_Donor $donor Instance of `Charitable_Donor`.
         * @return string
         */
        public function set_donor_name_to_anonymous( $name, Charitable_Donor $donor ) {
            if ( ! $this->is_donor_anonymous( $donor ) ) {
                return $name;
            }

            /**
             * Filter the name to show for anonymous donors.
             *
             * @since  1.0.0
             *
             * @param  string           $name  Donor name.
             * @param  Charitable_Donor $donor Instance of `Charitable_Donor`.
             */
            return apply_filters( 'charitable_donor_anonymous_name', __( 'Anonymous', 'charitable-anonymous' ), $name, $donor );
        }

        /**
         * Return the raw donor amount from the donor query results.
         *
         * This can differ from the donor's total amount because the donor
         * query splits the donations a donor made anonymously from those
         * they made publicly.
         *
         * @since  1.2.0
         *
         * @param  float $amount    The default donation amount.
         * @param  array $view_args The view args.
         * @return string
         */
        public function get_raw_donor_amount( $amount, $view_args ) {
            if ( $this->donations_are_grouped( $view_args ) ) {
                $query  = $view_args['donors']->query();
                $result = $query[ $view_args['donors']->key() ];
                $amount = charitable_format_money( $result->amount, false, true );
            }            

            return $amount;
        }

        /**
         * In the Donation Receipt and Admin Donation Notification, make sure the
         * donor name is included properly.
         *
         * @since  1.2.0
         *
         * @param  string           $value The field value.
         * @param  array            $args  Mixed arguments.
         * @param  Charitable_Email $email The Email object.
         * @return string
         */
        public function include_donor_name_in_emails( $value, $args, Charitable_Email $email ) {
            /**
             * Filter the list of emails in which the
             * anonymous donor's name will appear.
             *
             * @since 1.2.0
             *
             * @param array $emails An array of email IDs.
             */
            $email_whitelist = apply_filters( 'charitable_anonymous_email_whitelist', array(
                'donation_receipt',
                'new_donation',
                'offline_donation_notification',
                'offline_donation_receipt',
                'admin_new_recurring_donation',
                'admin_new_renewal_donation',
                'recurring_donation_receipt',
            ) );

            if ( ! in_array( $email->get_email_id(), $email_whitelist ) ) {
                return $value;
            }

            if ( is_null( $email->get_donation() ) ) {
                return $value;
            }

            $donor = $email->get_donation()->get_donor();

            return trim( sprintf( '%s %s', $donor->get_donor_meta( 'first_name' ) , $donor->get_donor_meta( 'last_name' ) ) );
        }

        /**
         * Display the default Gravatar.
         *
         * @since  1.0.0
         *
         * @param  string           $avatar Avatar URL.
         * @param  Charitable_Donor $donor  Instance of `Charitable_Donor`.
         * @return string
         */
        public function force_anonymous_gravatar( $avatar, Charitable_Donor $donor ) {
            if ( $this->is_donor_anonymous( $donor ) ) {
                $avatar = get_avatar( '' );
            }

            return $avatar;
        }

        /**
         * Checks whether the donor is anonymous.
         *
         * @since  1.2.1
         *
         * @param  Charitable_Donor $donor  The instance of `Charitable_Donor`.
         * @param  object|null      $record The raw database response. This may not be set, in which case it will be null.
         * @return boolean
         */
        public function is_donor_anonymous( Charitable_Donor $donor, $record = null ) {
            if ( isset( $donor->is_anonymous ) ) {
                return $donor->is_anonymous;
            }

            if ( $donor->get_donation() ) {
                return (int) get_post_meta( $donor->donation_id, 'anonymous_donation', true );
            }

            if ( is_null( $record ) ) {
                return false;
            }

            return isset( $record->anonymous ) ? $record->anonymous : false;
        }

        /**
         * Returns whether donations are grouped by donor.
         *
         * @since  1.2.1
         *
         * @param  array $view_args The view args.
         * @return boolean
         */
        private function donations_are_grouped( $view_args ) {
            foreach ( array( 'show_distinct', 'distinct_donors' ) as $key ) {
                if ( array_key_exists( $key, $view_args ) ) {
                    return true;
                }
            }

            return false;
        }
    }

endif;
