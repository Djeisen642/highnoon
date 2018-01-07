<?php
/**
 * The class that defines Charitable Recurring's custom post types, taxonomies and post statuses.
 *
 * @version     1.0.0
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Post_Types
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License  
 */

if ( ! defined( 'ABSPATH' ) ) exit; 

if ( ! class_exists( 'Charitable_Recurring_Post_Types' ) ) : 

/**
 * Charitable_Recurring_Post_Types
 *
 * @since       1.0.0
 */
final class Charitable_Recurring_Post_Types {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_Post_Types|null
     * @access  private
     * @static
     */
    private static $instance = null;

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Post_Types
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Post_Types();
        }

        return self::$instance;
    }

    /**
     * Set up the class. 
     * 
     * Note that the only way to instantiate an object is with the on_start method, 
     * which can only be called during the start phase. In other words, don't try 
     * to instantiate this object. 
     *
     * @access  private
     * @since   1.0.0
     */
    private function __construct() {    
        add_filter( 'charitable_valid_donation_types', array( $this, 'register_donation_type' ) );
        add_action( 'init', array( $this, 'register_post_types' ), 10 );
        add_action( 'init', array( $this, 'register_post_statuses' ), 10 );
    }


    /**
     * Tell Charitable about the recurring donation type 
     *
     * @param array $types
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function register_donation_type( $types ) {
        $types[] = Charitable_Recurring::POST_TYPE;
        return $types;
    }


    /**
     * Register plugin post types. 
     *
     * @hook    init
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function register_post_types() {

        /**
         * Donation post type. 
         *
         * To change any of the arguments used for the post type, other than the name
         * of the post type itself, use the 'charitable_donation_post_type' filter. 
         */ 
        register_post_type( Charitable_Recurring::POST_TYPE, 
            apply_filters( 'charitable_recurring_donation_post_type',
                array(
                    'labels' => array(
                        'name'                  => __( 'Recurring Donations', 'charitable-recurring' ),
                        'singular_name'         => __( 'Recurring Donation', 'charitable-recurring' ),
                        'menu_name'             => _x( 'Recurring Donations', 'Admin menu name', 'charitable-recurring' ),
                        'add_new'               => __( 'Add Recurring Donation', 'charitable-recurring' ),
                        'add_new_item'          => __( 'Add New Recurring Donation', 'charitable-recurring' ),
                        'edit'                  => __( 'Edit', 'charitable-recurring' ),
                        'edit_item'             => __( 'Recurring Donation Details', 'charitable-recurring' ),
                        'new_item'              => __( 'New Recurring Donation', 'charitable-recurring' ),
                        'view'                  => __( 'View Recurring Donation', 'charitable-recurring' ),
                        'view_item'             => __( 'View Recurring Donation', 'charitable-recurring' ),
                        'search_items'          => __( 'Search Recurring Donations', 'charitable-recurring' ),
                        'not_found'             => __( 'No Recurring Donations found', 'charitable-recurring' ),
                        'not_found_in_trash'    => __( 'No Recurring Donations found in trash', 'charitable-recurring' ),
                        'parent'                => __( 'Parent Recurring Donation', 'charitable-recurring' )
                    ),
                    'public'                => false,
                    'show_ui'               => true,
                    'capability_type'       => 'donation',
                    'capabilities' => array(
                        'create_posts' => 'do_not_allow'
                    ),
                    'menu_icon'             => '',
                    'map_meta_cap'          => true,
                    'publicly_queryable'    => false,
                    'exclude_from_search'   => true,
                    'hierarchical'          => false,
                    'rewrite'               => false,
                    'query_var'             => false,
                    'supports'              => false,
                    'has_archive'           => false,
                    'show_in_nav_menus'     => false, 
                    'show_in_menu'        => false,
                )
            ) 
        );
    }

    /**
     * Register custom post statuses. 
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function register_post_statuses() {

        $recurring_statuses = charitable_recurring_get_valid_donation_statuses();

        $registered_statuses = apply_filters( 'charitable_recurring_donation_registered_statuses', array(
            'charitable-active'         => _nx_noop( 'Active <span class="count">(%s)</span>', 'Active <span class="count">(%s)</span>', 'post status label including post count', 'charitable-recurring' ),
            'charitable-onhold'       => _nx_noop( 'On-Hold <span class="count">(%s)</span>', 'On-Hold <span class="count">(%s)</span>', 'post status label including post count', 'charitable-recurring' ),
            'charitable-expired'        => _nx_noop( 'Expired <span class="count">(%s)</span>', 'Expired <span class="count">(%s)</span>', 'post status label including post count', 'charitable-recurring' ),
            'charitable-cancel' => _nx_noop( 'Pending Cancellation <span class="count">(%s)</span>', 'Pending Cancellation <span class="count">(%s)</span>', 'post status label including post count', 'charitable-recurring' ),
        ) );

        if ( is_array( $recurring_statuses ) && is_array( $registered_statuses ) ) {

            foreach ( $registered_statuses as $status => $label_count ) {

                register_post_status( $status, array(
                    'label'                     => $recurring_statuses[ $status ], // use same label/translations as charitable_recurring_get_valid_donation_statuses()
                    'label_count'               => $label_count,
                    'public'                    => false,
                    'exclude_from_search'       => false,
                    'show_in_admin_all_list'    => true,
                    'show_in_admin_status_list' => true,
                    
                ) );
            }
        }

    }

}

endif; // End class_exists check.