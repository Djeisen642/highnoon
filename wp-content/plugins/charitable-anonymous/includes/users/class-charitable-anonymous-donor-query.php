<?php
/**
 * This class is responsible for modifying the Charitable_Donor_Query.
 *
 * @see Charitable_Donor_Query
 * 
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_Donor_Query 
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.2.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_Donor_Query' ) ) : 

    /**
     * Charitable_Anonymous_Donor_Query
     *
     * @since 1.0.0
     */
    class Charitable_Anonymous_Donor_Query {

        /**
         * Single instance of this class.
         *
         * @since 1.1.0
         *
         * @var   Charitable_Anonymous_Donor_Query
         */
        private static $instance = null;

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
         * @return Charitable_Anonymous_Donor_Query
         */
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new Charitable_Anonymous_Donor_Query();            
            }

            return self::$instance;
        }

        /**
         * Set up the donor loop queries.
         *
         * @since  1.2.0
         *
         * @param  array Query args.
         * @return array
         */
        public function setup_donor_loop_query_args( $query_args ) {
            return array_merge( array(
                'respect_anonymity' => true,
            ), $query_args );
        }

        /**
         * Set up customizations of `Charitable_Donor_Query`.
         *
         * @since  1.2.0
         *
         * @param  array $defaults Default query args.
         * @return array
         */
        public function setup_donor_query( $defaults ) {
            /* Define our default arguments. */
            $defaults['exclude_anonymous'] = false;
            $defaults['respect_anonymity'] = false;

            /* Set up our hooks. */
            add_action( 'charitable_pre_query', array( $this, 'prepare_donor_query' ) );
            add_action( 'charitable_post_query', array( $this, 'unhook_callbacks' ) );

            return $defaults;
        }

        /**
         * Set up the Charitable_Donor_Query.
         *
         * @since  1.2.0
         *
         * @param  Charitable_Donor_Query $query Instance of `Charitable_Donor_Query`.
         * @return void
         */
        public function prepare_donor_query( Charitable_Donor_Query $query ) {
            if ( $query->get( 'exclude_anonymous' ) ) {
                add_filter( 'charitable_query_join', array( $this, 'join_postmeta_table' ) );
                add_filter( 'charitable_query_where', array( $this, 'exclude_anonymous_sql' ) );
            }

            if ( $query->get( 'respect_anonymity' ) && $query->get( 'distinct_donors', true ) ) {
                add_filter( 'charitable_query_fields', array( $this, 'add_anonymous_field' ) );
                add_filter( 'charitable_query_join', array( $this, 'join_postmeta_table' ) );
                add_filter( 'charitable_query_groupby', array( $this, 'setup_query_groupby' ), 11 );
            }
        }

        /**
         * Add a join to the postmeta table if we are excluding anonymous donations.
         *
         * @since  1.0.0
         *
         * @global WPDB $wpdb
         * @param  string $sql SQL string.
         * @return string
         */
        public function join_postmeta_table( $sql ) {
            global $wpdb;

            return $sql . " LEFT JOIN {$wpdb->postmeta} anonmeta ON {$wpdb->posts}.ID = anonmeta.post_id AND anonmeta.meta_key = 'anonymous_donation'";
        }

        /**
         * Modify the WHERE statement to exclude anonymous donations.
         *
         * @since  1.0.0
         *
         * @param  string $sql SQL string.
         * @return string
         */
        public function exclude_anonymous_sql( $sql ) {
            return $sql . " AND ( anonmeta.meta_value = 0 OR anonmeta.meta_value IS NULL )";
        }

        /**
         * Add a "anonymous" field when distinct_donors and respect_anonymity are true.
         *
         * @since  1.2.0
         *
         * @param  string $select_statement The default select statement.
         * @return string
         */
        public function add_anonymous_field( $select_statement ) {
            return $select_statement . ", anonmeta.meta_value AS anonymous";
        }

        /**
         * Set up the query's groupby clause.
         *
         * @since  1.2.0
         *
         * @return string
         */
        public function setup_query_groupby() {
            return 'GROUP BY cd.donor_id, anonmeta.meta_value';
        }

        /**
         * Remove any hooks that have been attached by the class to prevent contaminating other queries. 
         *
         * @since  1.0.0
         *
         * @return void
         */
        public function unhook_callbacks() {
            remove_action( 'charitable_pre_query', array( $this, 'prepare_donor_query' ) );
            remove_action( 'charitable_post_query', array( $this, 'unhook_callbacks' ) );

            remove_filter( 'charitable_query_join', array( $this, 'join_postmeta_table' ) );
            remove_filter( 'charitable_query_where', array( $this, 'exclude_anonymous_sql' ) );
            remove_filter( 'charitable_query_fields', array( $this, 'add_anonymous_field' ) );
            remove_filter( 'charitable_query_groupby', array( $this, 'setup_query_groupby' ), 11 );
        }
    }

endif;
