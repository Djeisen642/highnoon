<?php
/**
 * Class responsible for adding Charitable Anonymous settings in admin area.
 *
 * @package   Charitable Anonymous/Classes/Charitable_Anonymous_Admin
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Charitable_Anonymous_Admin' ) ) : 

/**
 * Charitable_Anonymous_Admin
 *
 * @since 1.0.0
 */
class Charitable_Anonymous_Admin {

    /**
     * Single instance of this class.
     *
     * @since 1.1.0
     *
     * @var   Charitable_Anonymous_Admin
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
     * @return Charitable_Anonymous_Admin
     */
    public static function get_instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new Charitable_Anonymous_Admin();            
        }

        return self::$instance;
    }

    /**
     * Set the admin view path to our views folder for any of our views.  
     *
     * @since  1.0.0
     *
     * @param  string $path
     * @param  string $view
     * @param  array  $view_args
     * @return string
     */
    public function admin_view_path( $path, $view, $view_args ) {
        if ( isset( $view_args[ 'view_source' ] ) && 'charitable-anonymous' == $view_args[ 'view_source' ] ) {
            $path = charitable_anonymous()->get_path( 'includes' ) . 'admin/views/' . $view . '.php';
        }

        return $path;
    }

    /**
     * If the donation was made anonymously, show that in the donation details. 
     *
     * @since  1.1.0
     *
     * @param  Charitable_User|Charitable_Donor $donor
     * @param  Charitable_Donation $donation
     * @return void
     */
    public function add_anonymous_donation_information( $donor, Charitable_Donation $donation ) {        
        if ( ! $donation->anonymous_donation ) {
            return;
        }

        charitable_admin_view( 'anonymous-donation-note', array( 
            'view_source'   => 'charitable-anonymous', 
            'donation'      => $donation
        ) );
    }

    /**
     * Do not filter the donor names in the Donation list table.
     *
     * @see    WP_Posts_List_Table::single_row()
     *
     * @since  1.2.0
     *
     * @global string $typenow The current post type.
     * @param  string $which The extra tablenav location.
     * @return boolean Whether the filter was disabled.
     */
    public function maybe_disable_anonymous_donation_filter_in_table( $which ) {
        global $typenow;

        if ( 'top' != $which ) {
            return false;
        }

        if ( ! in_array( $typenow, array( Charitable::DONATION_POST_TYPE ) ) ) {
            return false;
        }

        if ( ! current_user_can( 'view_charitable_sensitive_data' ) ) {
            return false;
        }

        remove_filter( 'charitable_donor_name', array( Charitable_Anonymous_Donor::get_instance(), 'set_donor_name_to_anonymous' ), 10, 2 );
        add_action( 'manage_posts_extra_tablenav', array( $this, 'reenable_anonymous_donation_filter_in_table' ) );

        return true;
    }

    /**
     * Re-enable the anonymous donor filter at the bottom of the donation list table.
     *
     * @since  1.2.0
     *
     * @param  string $which The extra tablenav location.
     * @return boolean Whether the filter was re-enabled.
     */
    public function reenable_anonymous_donation_filter_in_table( $which ) {
        if ( 'bottom' != $which ) {
            return false;
        }

        add_filter( 'charitable_donor_name', array( Charitable_Anonymous_Donor::get_instance(), 'set_donor_name_to_anonymous' ), 10, 2 );
        remove_action( 'manage_posts_extra_tablenav', array( $this, 'reenable_anonymous_donation_filter' ) );

        return true;
    }

    /**
     * Disable the anonymous donor filter in Donation Details pages.
     *
     * @since  1.2.0
     *
     * @return boolean
     */
    public function maybe_disable_anonymous_donation_filter_in_details() {
        if ( ! current_user_can( 'view_charitable_sensitive_data' ) ) {
            return false;
        }

        remove_filter( 'charitable_donor_name', array( Charitable_Anonymous_Donor::get_instance(), 'set_donor_name_to_anonymous' ), 10, 2 );
        add_action( 'dbx_post_sidebar', array( $this, 'reenable_anonymous_donation_filter_in_details' ) );

        return true;
    }

    /**
     * Re-enable the anonymous donor filter in Donation Details pages.
     *
     * @since  1.2.0
     *
     * @param  WP_Post $post Post object.
     * @return boolean
     */
    public function reenable_anonymous_donation_filter_in_details( WP_Post $post ) {
        if ( Charitable::DONATION_POST_TYPE != $post->post_type ) {
            return false;
        }

        add_filter( 'charitable_donor_name', array( Charitable_Anonymous_Donor::get_instance(), 'set_donor_name_to_anonymous' ), 10, 2 );
        remove_action( 'dbx_post_sidebar', array( $this, 'reenable_anonymous_donation_filter_in_details' ) );

        return true;
    }
}

endif;
