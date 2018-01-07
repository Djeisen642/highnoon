<?php
/**
 * The main Charitable Recurring class.
 * 
 * The responsibility of this class is to load all the plugin's functionality.
 *
 * @package     Charitable Recurring
 * @copyright   Copyright (c) 2015, Kathy Darling  
 * @license     http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @since       1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Charitable_Recurring' ) ) :

/**
 * Charitable_Recurring
 *
 * @since   1.0.0
 */
class Charitable_Recurring {

    /**
     * @var string
     */
    const VERSION = '1.0.7';

    /**
     * @var string  A date in the format: YYYYMMDD
     */
    const DB_VERSION = '20160110';  

    /**
     * @var string The product name. 
     */
    const NAME = 'Charitable Recurring Donations'; 

    /**
     * @var string The product author.
     */
    const AUTHOR = 'Kathy Darling';

    /**
     * @var     string      The Recurring Donation post type.
     */
    const POST_TYPE = 'recurring_donation';

    /**
     * @var Charitable_Recurring
     */
    private static $instance = null;

    /**
     * The root file of the plugin. 
     * 
     * @var     string
     * @access  private
     */
    private $plugin_file; 

    /**
     * The root directory of the plugin.  
     *
     * @var     string
     * @access  private
     */
    private $directory_path;

    /**
     * The root directory of the plugin as a URL.  
     *
     * @var     string
     * @access  private
     */
    private $directory_url;

    /**
     * Create class instance. 
     * 
     * @return  void
     * @since   1.0.0
     */
    public function __construct( $plugin_file ) {
        $this->plugin_file      = $plugin_file;
        $this->directory_path   = plugin_dir_path( $plugin_file );
        $this->directory_url    = plugin_dir_url( $plugin_file );

        add_action( 'charitable_start', array( $this, 'start' ), 6 );
    }

    /**
     * Returns the original instance of this class. 
     * 
     * @return  Charitable
     * @since   1.0.0
     */
    public static function get_instance() {
        return self::$instance;
    }

    /**
     * Run the startup sequence on the charitable_start hook. 
     *
     * This is only ever executed once.  
     * 
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function start() {
        // If we've already started (i.e. run this function once before), do not pass go. 
        if ( $this->started() ) {
            return;
        }

        // Set static instance
        self::$instance = $this;

        $this->load_dependencies();

        $this->maybe_start_ajax();

        $this->maybe_start_admin();  

        $this->maybe_start_public();       

        $this->setup_licensing();

        $this->setup_i18n();

        // Hook in here to do something when the plugin is first loaded.
        do_action('charitable_recurring_start', $this);
    }

    /**
     * Include necessary files.
     * 
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function load_dependencies() {     
        $includes_path = $this->get_path( 'includes' );

        /* Core functions */
        require_once( $includes_path . 'charitable-recurring-core-functions.php' );
        require_once( $includes_path . 'class-charitable-recurring-post-types.php' );

        /* Emails */
        require_once( $includes_path . 'emails/class-charitable-recurring-emails.php' );  
        //require_once( $includes_path . 'emails/class-charitable-recurring-admin-email-cancelled-recurring-donation.php' );
        //require_once( $includes_path . 'emails/class-charitable-recurring-admin-email-failed-recurring-donation.php' );
        require_once( $includes_path . 'emails/class-charitable-recurring-admin-email-new-recurring-donation.php' );
        require_once( $includes_path . 'emails/class-charitable-recurring-admin-email-new-renewal-donation.php' );
        require_once( $includes_path . 'emails/class-charitable-recurring-email-recurring-donation-receipt.php' );
        //require_once( $includes_path . 'emails/class-charitable-recurring-email-failed-recurring-donation.php' );
        //require_once( $includes_path . 'emails/class-charitable-recurring-email-cancelled-recurring-donation.php' );
        require_once( $includes_path . 'emails/charitable-recurring-email-hooks.php' );

        /* Campaigns */
        require_once( $includes_path . 'campaigns/charitable-recurring-campaign-functions.php' );
        require_once( $includes_path . 'campaigns/charitable-recurring-campaign-hooks.php' );

        /* Gateways */
        require_once( $includes_path . 'gateways/class-charitable-recurring-paypal.php' );  
        require_once( $includes_path . 'gateways/charitable-recurring-paypal-hooks.php' );        
        
        /* Recurring Donations */              
        require_once( $includes_path . 'donations/class-charitable-recurring-donation-processor.php' );
        require_once( $includes_path . 'donations/class-charitable-recurring-donation.php' );
        require_once( $includes_path . 'donations/class-charitable-recurring-donation-query.php' );
        require_once( $includes_path . 'donations/class-charitable-recurring-donation-form.php' );
        require_once( $includes_path . 'donations/charitable-recurring-donation-hooks.php' );
        require_once( $includes_path . 'donations/charitable-recurring-donation-functions.php' );        

         /**
         * We are registering this object only for backwards compatibility. It
         * will be removed in or after Charitable 1.3.
         *
         * @deprecated
         */
      $this->register_object( Charitable_Recurring_Post_Types::get_instance() );
        
    }

    /**
     * Load the admin-only functionality. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_start_admin() {
        if ( ! is_admin() ) {
            return;
        }

        require_once( $this->get_path( 'includes' ) . 'admin/class-charitable-recurring-admin.php' );
        require_once( $this->get_path( 'admin' ) . 'charitable-recurring-admin-hooks.php' );

        /**
         * We are registering this object only for backwards compatibility. It
         * will be removed in or after Charitable 1.3.
         *
         * @deprecated
         */
        $this->register_object( Charitable_Recurring_Admin::get_instance() );

    }

    /**
     * Checks whether we're on the public-facing side and if so, loads the public-facing functionality.
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_start_public() {
        if ( is_admin() ) {
            return;
        }

        /* Public */
        require_once( $this->get_path( 'public' ) . 'class-charitable-recurring-public.php' );

        /* Template and Hooks */
        require_once( $this->get_path( 'includes' ) . 'class-charitable-recurring-template.php' );  
        require_once( $this->get_path( 'public' )  . 'charitable-recurring-template-hooks.php' );

        /**
         * We are registering this object only for backwards compatibility. It
         * will be removed in or after Charitable 1.3.
         *
         * @deprecated
         */
        $this->register_object( Charitable_Recurring_Public::get_instance() );

    }

    /**
     * Checks whether we're executing an AJAX hook and if so, loads some AJAX functionality.
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function maybe_start_ajax() {
        if ( false === ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {
            return;
        }

        require_once( $this->get_path( 'includes' ) . 'ajax/charitable-recurring-ajax-functions.php' );
        require_once( $this->get_path( 'includes' ) . 'ajax/charitable-recurring-ajax-hooks.php' );
    }

    /**
     * Set up licensing for the extension. 
     *
     * @return  void
     * @access  private
     * @since   1.0.0
     */
    private function setup_licensing() {
        charitable_get_helper( 'licenses' )->register_licensed_product( 
            Charitable_Recurring::NAME, 
            Charitable_Recurring::AUTHOR, 
            Charitable_Recurring::VERSION,
            $this->plugin_file 
        );
    }

    /**
     * Set up the internationalisation for the plugin. 
     *
     * @return  void
     * @access  private
     * @since   0.1.0
     */
    private function setup_i18n() {
        if ( class_exists( 'Charitable_i18n' ) ) {

            require_once( $this->get_path( 'includes' ) . 'i18n/class-charitable-recurring-i18n.php' );

            Charitable_Recurring_i18n::get_instance();
        }
    }

    /**
     * Returns whether we are currently in the start phase of the plugin. 
     *
     * @return  bool
     * @access  public
     * @since   1.0.0
     */
    public function is_start() {
        return current_filter() == 'charitable_recurring_start';
    }

    /**
     * Returns whether the plugin has already started.
     * 
     * @return  bool
     * @access  public
     * @since   1.0.0
     */
    public function started() {
        return did_action( 'charitable_recurring_start' ) || current_filter() == 'charitable_recurring_start';
    }

    /**
     * Returns the plugin's version number. 
     *
     * @return  string
     * @access  public
     * @since   1.0.0
     */
    public function get_version() {
        return self::VERSION;
    }

    /**
     * Stores an object in the plugin's registry.
     *
     * @param   mixed $object
     * @return  void
     * @access  public
     * @since   1.0.0
     */
    public function register_object($object) {
        if ( ! is_object( $object ) ) {
            return;
        }

        $class = get_class( $object );

        $this->registry[$class] = $object;
    }

    /**
     * Returns a registered object.
     * 
     * @param   string $class   The type of class you want to retrieve.
     * @return  mixed           The object if its registered. Otherwise false.
     * @access  public
     * @since   1.0.0
     */
    public function get_registered_object($class) {
        return isset( $this->registry[$class] ) ? $this->registry[$class] : false;
    }

    /**
     * Returns plugin paths. 
     *
     * @param   string $type        If empty, returns the path to the plugin.
     * @param   bool $absolute_path If true, returns the file system path. If false, returns it as a URL.
     * @return  string
     * @since   1.0.0
     */
    public function get_path($type = '', $absolute_path = true ) {      
        $base = $absolute_path ? $this->directory_path : $this->directory_url;

        switch( $type ) {
            case 'includes' : 
                $path = $base . 'includes/';
                break;

            case 'admin' :
                $path = $base . 'includes/admin/';
                break;

            case 'public' : 
                $path = $base . 'includes/public/';
                break;

            case 'assets' : 
                $path = $base . 'assets/';
                break;

            case 'templates' : 
                $path = $base . 'templates/';
                break;

            case 'directory' : 
                $path = $base;
                break;

            default :
                $path = $this->plugin_file;
                
        }

        return $path;
    }

    /**
     * Throw error on object clone. 
     *
     * This class is specifically designed to be instantiated once. You can retrieve the instance using charitable()
     *
     * @since   1.0.0
     * @access  public
     * @return  void
     */
    public function __clone() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable-recurring' ), '1.0.0' );
    }

    /**
     * Disable unserializing of the class. 
     *
     * @since   1.0.0
     * @access  public
     * @return  void
     */
    public function __wakeup() {
        _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable-recurring' ), '1.0.0' );
    }           
}

endif; // End if class_exists check