<?php
/**
 * The main Charitable Anonymous class.
 * 
 * The responsibility of this class is to load all the plugin's functionality.
 *
 * @package   Charitable Anonymous
 * @copyright Copyright (c) 2017, Eric Daams
 * @license   http://opensource.org/licenses/gpl-1.0.0.php GNU Public License
 * @since     0.1.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! class_exists( 'Charitable_Anonymous' ) ) :

    /**
     * Charitable_Anonymous
     *
     * @since 0.1.0
     */
    class Charitable_Anonymous {

        /**
         * @var string
         */
        const VERSION = '1.2.1';

        /**
         * @var string  A date in the format: YYYYMMDD
         */
        const DB_VERSION = '20150805';  

        /**
         * @var string The product name. 
         */
        const NAME = 'Charitable Anonymous Donations'; 

        /**
         * @var string The product author.
         */
        const AUTHOR = 'Studio 164a';

        /**
         * @var Charitable_Anonymous
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
         * @var     array       Store of registered objects.  
         * @access  private
         */
        private $registry;

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

            $this->maybe_load_admin();

            $this->maybe_load_edd();

            $this->setup_licensing();

            $this->setup_i18n();

            // Hook in here to do something when the plugin is first loaded.
            do_action('charitable_anonymous_start', $this);
        }

        /**
         * Include necessary files.
         * 
         * @return  void
         * @access  private
         * @since   1.0.0
         */
        private function load_dependencies() {      
            require_once( $this->get_path( 'includes' ) . 'charitable-anonymous-core-functions.php' );
            require_once( $this->get_path( 'includes' ) . 'class-charitable-anonymous-template.php' );
            
            require_once( $this->get_path( 'includes' ) . 'users/class-charitable-anonymous-donor-query.php' );
            require_once( $this->get_path( 'includes' ) . 'users/class-charitable-anonymous-donor.php' );
            require_once( $this->get_path( 'includes' ) . 'users/charitable-anonymous-donor-hooks.php' );
              
            require_once( $this->get_path( 'includes' ) . 'donation-form/class-charitable-anonymous-donation-form.php' );
            require_once( $this->get_path( 'includes' ) . 'donation-form/charitable-anonymous-donation-form-hooks.php' );
            
            require_once( $this->get_path( 'includes' ) . 'widgets/class-charitable-anonymous-donors-widget.php' );
            require_once( $this->get_path( 'includes' ) . 'widgets/charitable-anonymous-donors-widget-hooks.php' );
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
                Charitable_Anonymous::NAME, 
                Charitable_Anonymous::AUTHOR, 
                Charitable_Anonymous::VERSION, 
                $this->plugin_file 
            );
        }

        /**
         * Set up the internationalisation for the plugin. 
         *
         * @return  void
         * @access  private
         * @since   1.1.0
         */
        private function setup_i18n() {
            if ( class_exists( 'Charitable_i18n' ) ) {

                require_once( $this->get_path( 'includes' ) . 'i18n/class-charitable-anonymous-i18n.php' );

                Charitable_Anonymous_i18n::get_instance();
            }
        }

        /**
         * Load the admin-only functionality. 
         *
         * @return  void
         * @access  private
         * @since   1.0.0
         */
        private function maybe_load_admin() {
            if ( ! is_admin() ) {
                return;
            }

            require_once( $this->get_path( 'includes' ) . 'admin/class-charitable-anonymous-admin.php' );
            require_once( $this->get_path( 'includes' ) . 'admin/charitable-anonymous-admin-hooks.php' );
        }

        /**
         * Load EDD functionality if EDD and Charitable EDD are both installed.
         *
         * @return  void
         * @access  private
         * @since   1.1.0
         */
        private function maybe_load_edd() {
            if ( ! class_exists( 'Charitable_EDD' ) || ! class_exists( 'Easy_Digital_Downloads' ) ) {
                return;
            }

            require_once( $this->get_path( 'includes' ) . 'easy-digital-downloads/class-charitable-anonymous-edd.php' );
            require_once( $this->get_path( 'includes' ) . 'easy-digital-downloads/charitable-anonymous-edd-hooks.php' );
        }

        /**
         * Returns whether we are currently in the start phase of the plugin. 
         *
         * @return  bool
         * @access  public
         * @since   1.0.0
         */
        public function is_start() {
            return current_filter() == 'charitable_anonymous_start';
        }

        /**
         * Returns whether the plugin has already started.
         * 
         * @return  bool
         * @access  public
         * @since   1.0.0
         */
        public function started() {
            return did_action( 'charitable_anonymous_start' ) || current_filter() == 'charitable_anonymous_start';
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
         * Returns plugin paths. 
         *
         * @param   string $path            // If empty, returns the path to the plugin.
         * @param   bool $absolute_path     // If true, returns the file system path. If false, returns it as a URL.
         * @return  string
         * @since   1.0.0
         */
        public function get_path($type = '', $absolute_path = true ) {      
            $base = $absolute_path ? $this->directory_path : $this->directory_url;

            switch( $type ) {
                case 'includes' : 
                    $path = $base . 'includes/';
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
         * Stores an object in the plugin's registry.
         *
         * @param   mixed       $object
         * @return  void
         * @access  public
         * @since   1.0.0
         */
        public function register_object( $object ) {
            if ( ! is_object( $object ) ) {
                return;
            }

            $class = get_class( $object );

            $this->registry[ $class ] = $object;
        }

        /**
         * Returns a registered object.
         * 
         * @param   string      $class  The type of class you want to retrieve.
         * @return  mixed               The object if its registered. Otherwise false.
         * @access  public
         * @since   1.0.0
         */
        public function get_object( $class ) {
            return isset( $this->registry[ $class ] ) ? $this->registry[ $class ] : false;
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
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable-anonymous' ), '1.0.0' );
        }

        /**
         * Disable unserializing of the class. 
         *
         * @since   1.0.0
         * @access  public
         * @return  void
         */
        public function __wakeup() {
            _doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'charitable-anonymous' ), '1.0.0' );
        }           
    }

endif; // End if class_exists check