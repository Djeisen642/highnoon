<?php 
/**
 * Class that sets up the Charitable Admin functionality.
 * 
 * @package     Charitable_Recurring/Classes/Charitable_Recurring_Admin
 * @version     1.0.0
 * @author      Kathy Darling
 * @copyright   Copyright (c) 2016, Kathy Darling
 * @license     http://opensource.org/licenses/gpl-3.0.php GNU Public License   
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring_Admin' ) ) : 

/**
 * Charitable_Recurring_Admin 
 *
 * @final
 * @since       1.0.0
 */
final class Charitable_Recurring_Admin {

    /**
     * The single instance of this class.  
     *
     * @var     Charitable_Recurring_Admin|null
     * @access  private
     * @static
     */
    private static $instance = null;

     /**
     * Store whether we're showing related donations 
     *
     * @since 2.0
     */
    private static $found_related_donations = false;


    /**
     * Set up the class. 
     * 
     * Note that the only way to instantiate an object is with the charitable_start method, 
     * which can only be called during the start phase. In other words, don't try 
     * to instantiate this object. 
     *
     * @access  protected
     * @since   1.0.0
     */
    protected function __construct() {
        $this->load_dependencies();
    }

    /**
     * Returns and/or create the single instance of this class.  
     *
     * @return  Charitable_Recurring_Admin
     * @access  public
     * @since   1.2.0
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Recurring_Admin();
        }

        return self::$instance;
    } 

    /**
     * Include admin-only files.
     * 
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function load_dependencies() {
        $admin_dir = charitable_recurring()->get_path( 'admin' );        
      
        /* Campaigns */
        require_once( $admin_dir . 'campaigns/class-charitable-recurring-campaign-meta.php' ); 
        require_once( $admin_dir . 'campaigns/charitable-recurring-campaign-meta-hooks.php' ); 

        /* Donations */
        require_once( $admin_dir . 'donations/class-charitable-recurring-admin-post-type.php' ); 
        require_once( $admin_dir . 'donations/charitable-recurring-admin-post-type-hooks.php' ); 

    }

    /**
     * Load our admin script.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function load_admin_script() {        

        /* The following styles are only loaded on Charitable screens. */
        $screen = get_current_screen();

        if ( is_null( $screen ) || ! in_array( $screen->id, Charitable_Admin::get_instance()->get_charitable_screens() ) ) {
            return;
        }

        if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) {
            $suffix  = '';
            $version = time();
        } else {
            $suffix  = '.min';
            $version = charitable_recurring()->get_version();
        }

        wp_register_script(
            'charitable-recurring-admin',
            charitable_recurring()->get_path( 'assets', false ) . 'js/charitable-recurring-admin' . $suffix . '.js',
            array( 'charitable-admin' ),
            $version,
            false
        );

        wp_enqueue_script( 'charitable-recurring-admin' );

    }

    /**
     * Returns an array of screen IDs where the Charitable scripts should be loaded. 
     *
     * @uses charitable_admin_screens
     *
     * @param array $screens
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function add_admin_screens( $screens ) {
        return array_merge( $screens, $this->get_charitable_recurring_screens() );
    }   


    /**
     * Adds one or more classes to the body tag in the dashboard.
     *
     * @param  String $classes Current body classes.
     * @return String          Altered body classes.
     * @since 1.0.0
     */
    public function add_admin_body_class( $classes ) {
        $screen = get_current_screen();
        if( Charitable_Recurring::POST_TYPE == $screen->post_type ){
            $classes .= ' post-type-charitable';
        }  
        return $classes;
    }
    

    /**
     * Returns an array of screen IDs where the Charitable scripts should be loaded. 
     *
     * @uses charitable_admin_screens
     * 
     * @return  array
     * @access  private
     * @since   1.0.0
     */
    private function get_charitable_recurring_screens() {
        return apply_filters( 'charitable_recurring_admin_screens', array(
            Charitable_Recurring::POST_TYPE,
            'edit-' . Charitable_Recurring::POST_TYPE,
        ) );
    }  

    /**
     * Add recurring donations to the submenu pages. 
     *
     * @param array $pages pages in the Charitable submenu
     * @return  array
     * @access  public
     * @since   1.0.0
     */
    public function add_submenu_page( $pages ) {

        $admin_menu_capability = apply_filters( 'charitable_admin_menu_capability', 'manage_options' );
        $admin_menu_parent_page = 'charitable';

        if( current_user_can( $admin_menu_capability ) ){
            $post_type = get_post_type_object( Charitable_Recurring::POST_TYPE );

            $new_pages = array(
                array( 
                    'page_title'    => $post_type->labels->menu_name,
                    'menu_title'    => $post_type->labels->menu_name,
                    'menu_slug'     => 'edit.php?post_type=' . Charitable_Recurring::POST_TYPE
                )    
            );

            $pages = array_merge( array_slice( $pages, 0, 3, true), $new_pages, array_slice( $pages, 3, count( $pages) - 1, true) );

        }
        return $pages;
    }


    /**
     * Filter the "Donations" list to show only donations associated with a specific recurring donation.
     *
     * @param string $where
     * @param string $request
     * @return string
     * @since 2.0
     */
    public static function filter_donations( $where ) {
        global $typenow, $wpdb;

        if ( is_admin() && 'donation' == $typenow ) { 

            if ( isset( $_GET['_recurring_related_donations'] ) && $_GET['_recurring_related_donations'] > 0 ) {

                $recurring_id = absint( $_GET['_recurring_related_donations'] );

                $recurring = charitable_get_donation( $recurring_id );

                $notice_helper = charitable_get_admin_notices();

                if ( ! is_object( $recurring ) || $recurring->get_donation_type() != 'recurring' ) {
                    // translators: placeholder is a number
                    $notice_helper->add_error( sprintf( __( 'We can\'t find a recurring donation with ID #%d. Perhaps it was deleted?', 'charitable-recurring' ), $recurring_id ) );
                    
                    $where .= " AND {$wpdb->posts}.ID = 0";
                } else { 
             
                    $notice_helper->add_success( sprintf( esc_html__( 'Showing donations for %sRecurring Donation #%s%s', 'charitable-recurring' ), '<a href="' . esc_url( get_edit_post_link( absint( $_GET["_recurring_related_donations"] ) ) ) . '">', esc_html( $recurring->get_donation_id() ), '</a>' ) );

                    $where .= sprintf( " AND {$wpdb->posts}.ID IN (%s)", implode( ',', array_map( 'absint', array_unique( $recurring->get_related_donations( 'ids' ) ) ) ) );

                }
            }
        }

        return $where;
    }


    /**
     * Export recurring donations.
     *
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function export_recurring_donations() {

        if ( ! wp_verify_nonce( $_GET['_charitable_recurring_export_nonce'], 'charitable_export_recurring_donations' ) ) {
            return false;
        }

        require_once( 'reports/class-charitable-export-recurring-donations.php' );

        $export_args = apply_filters( 'charitable_recurring_recurring_donations_export_args', array(
            'status'      => $_GET['post_status'],
            'campaign_id' => $_GET['campaign_id'],
        ) );

        new Charitable_Export_Recurring_Donations( $export_args );

        exit();

    }
}

endif;