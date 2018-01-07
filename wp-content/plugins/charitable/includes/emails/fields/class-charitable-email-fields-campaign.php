<?php
/**
 * Email Fields Campaign class.
 *
 * @since   1.5.0
 * @version 1.5.0
 * @package Charitable/Classes/Charitable_Email_Fields_Campaign
 * @author  Eric Daams
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Email_Fields_Campaign' ) ) :

    /**
     * Charitable_Email_Fields class.
     *
     * @since 1.5.0
     */
    class Charitable_Email_Fields_Campaign implements Charitable_Email_Fields_Interface {

        /**
         * The Charitable_Campaign object.
         *
         * @since 1.5.0
         *
         * @var   Charitable_Campaign
         */
        private $campaign;

        /**
         * Set up class instance.
         *
         * @since 1.5.0
         *
         * @param Charitable_Email $email   The email object.
         * @param boolean          $preview Whether this is an email preview.
         */
        public function __construct( Charitable_Email $email, $preview ) {            
            $this->email    = $email;
            $this->preview  = $preview;
            $this->campaign = $email->get_campaign();
            $this->fields   = $this->init_fields();
        }

        /**
         * Get the fields that apply to the current email.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function init_fields() {
            $fields = array(
                'campaign_title' => array(
                    'description' => __( 'The title of the campaign', 'charitable' ),                    
                    'preview'     => __( 'Fake Campaign', 'charitable' ),
                ),
                'campaign_creator' => array(
                    'description' => __( 'The name of the campaign creator', 'charitable' ),                    
                    'preview'     => 'Harry Ferguson',
                ),
                'campaign_creator_email' => array(
                    'description' => __( 'The email address of the campaign creator', 'charitable' ),                    
                    'preview'     => 'harry@example.com',
                ),
                'campaign_end_date' => array(
                    'description' => __( 'The end date of the campaign', 'charitable' ),                    
                    'preview'     => date( get_option( 'date_format', 'd/m/Y' ) ),
                ),
                'campaign_achieved_goal' => array(
                    'description' => __( 'Display whether the campaign reached its goal. Add a `success` parameter as the message when the campaign was successful, and a `failure` parameter as the message when the campaign is not successful', 'charitable' ),                    
                    'preview'     => __( 'The campaign achieved its fundraising goal.', 'charitable' ),
                ),
                'campaign_donated_amount' => array(
                    'description' => __( 'Display the total amount donated to the campaign', 'charitable' ),                    
                    'preview'     => '$16,523',
                ),
                'campaign_donor_count' => array(
                    'description' => __( 'Display the number of campaign donors', 'charitable' ),                    
                    'preview'     => 23,
                ),
                'campaign_goal' => array(
                    'description' => __( 'Display the campaign\'s fundraising goal', 'charitable' ),                    
                    'preview'     => '$15,000',
                ),
                'campaign_url' => array(
                    'description' => __( 'Display the campaign\'s URL', 'charitable' ),                    
                    'preview'     => 'http://www.example.com/campaigns/fake-campaign',
                ),
                'campaign_dashboard_url' => array(
                    'description' => __( 'Display a link to the campaign in the dashboard', 'charitable' ),                    
                    'preview'     => get_edit_post_link( 1 ),
                ),
            );

            if ( $this->has_valid_campaign() ) {
                $fields = array_merge_recursive( $fields, array(
                    'campaign_title'          => array( 'value' => $this->campaign->post_title ),
                    'campaign_creator'        => array( 'callback' => array( $this, 'get_campaign_creator' ) ),
                    'campaign_creator_email'  => array( 'callback' => array( $this, 'get_campaign_creator_email' ) ),
                    'campaign_end_date'       => array( 'callback' => array( $this->campaign, 'get_end_date' ) ),
                    'campaign_achieved_goal'  => array( 'callback' => array( $this, 'get_campaign_achieved_goal' ) ),
                    'campaign_donated_amount' => array( 'callback' => array( $this, 'get_campaign_donated_amount' ) ),
                    'campaign_donor_count'    => array( 'callback' => array( $this->campaign, 'get_donor_count' ) ),
                    'campaign_goal'           => array( 'callback' => array( $this->campaign, 'get_monetary_goal' ) ),
                    'campaign_url'            => array( 'callback' => array( $this, 'get_campaign_url' ) ),
                    'campaign_dashboard_url'  => array( 'callback' => array( $this, 'get_campaign_dashboard_url' ) ),
                ) );
            }      

            /**
             * Filter the campaign email fields.
             *
             * @since 1.5.0
             *
             * @param array               $fields   The default set of fields.
             * @param Charitable_Campaign $campaign Instance of `Charitable_Campaign`.
             * @param Charitable_Email    $email    Instance of `Charitable_Email`.
             */
            return apply_filters( 'charitable_email_campaign_fields', $fields, $this->campaign, $this->email );
        }

        /**
         * Return fields.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_fields() {
            return $this->fields;
        }

        /**
         * Checks whether the email has a valid Campaign object set.
         *
         * @since  1.5.0
         *
         * @return boolean
         */
        public function has_valid_campaign() {
            return ! is_null( $this->campaign ) && is_a( $this->campaign, 'Charitable_Campaign' );
        }

        /**
         * Return the campaign creator's name.
         *
         * @since  1.5.0
         *
         * @return string
         */
        public function get_campaign_title() {
            return $this->campaign->post_title;
        }

        /**
         * Return the campaign creator's name.
         *
         * @since  1.5.0
         *
         * @return string
         */
        public function get_campaign_creator() {
            return get_the_author_meta( 'display_name', $this->campaign->get_campaign_creator() );
        }

        /**
         * Return the campaign creator's email address.
         *
         * @since  1.5.0
         *
         * @return string
         */
        public function get_campaign_creator_email() {
            return get_the_author_meta( 'user_email', $this->campaign->get_campaign_creator() );
        }

        /**
         * Display whether the campaign achieved its goal.
         *
         * @since  1.5.0
         *
         * @param  string $value The content to display in place of the shortcode.
         * @param  array  $args  Optional set of arguments.
         * @return string
         */
        public function get_campaign_achieved_goal( $value, $args ) {            
            $defaults = array(
                'success' => __( 'The campaign achieved its fundraising goal.', 'charitable' ),
                'failure' => __( 'The campaign did not reach its fundraising goal.', 'charitable' ),
            );

            $args = wp_parse_args( $args, $defaults );

            if ( $this->campaign->has_achieved_goal() ) {
                return $args['success'];
            }

            return $args['failure'];
        }

        /**
         * Display the total amount donated to the campaign.
         *
         * @since  1.5.0
         *
         * @return string
         */
        public function get_campaign_donated_amount() {
            return charitable_format_money( $this->campaign->get_donated_amount() );
        }

        /**
         * Display the campaign's URL
         *
         * @since  1.5.0
         *
         * @return string
         */
        public function get_campaign_url() {
            return get_permalink( $this->campaign->ID );
        }

        /**
         * Display the link to where the campaign can be edited in the dashboard.
         *
         * @since  1.5.0
         *
         * @return string
         */
        public function get_campaign_dashboard_url() {
            $post_type_object = get_post_type_object( Charitable::CAMPAIGN_POST_TYPE );

            if ( $post_type_object->_edit_link ) {
                $link = admin_url( sprintf( $post_type_object->_edit_link . '&amp;action=edit', $this->campaign->ID ) );
            } else {
                $link = '';
            }

            return $link;
        }
    }

endif;