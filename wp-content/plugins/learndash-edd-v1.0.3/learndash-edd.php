<?php
/**
 * Plugin Name:     LearnDash EDD Integration Plugin
 * Plugin URI:      http://learndash.com
 * Description:     LearnDash integration plugin for EDD
 * Version:         1.0.3
 * Author:          LearnDash
 * Author URI:      http://learndash.com
 * Text Domain:     learndash-edd
 *
 * @package         LearnDash\EDD
 */

// Exit if accessed directly
if( ! defined( 'ABSPATH' ) ) exit;


if( ! class_exists( 'LearnDash_EDD' ) ) {


    /**
     * Main LearnDash_EDD class
     *
     * @since       1.0.0
     */
    class LearnDash_EDD {


        /**
         * @var         LearnDash_EDD $instance The one true LearnDash_EDD
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      self::$instance The one true LearnDash_EDD
         */
        public static function instance() {
            if( ! self::$instance ) {
                self::$instance = new LearnDash_EDD();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin path
            define( 'LEARNDASH_EDD_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'LEARNDASH_EDD_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include required files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            require_once LEARNDASH_EDD_DIR . 'includes/scripts.php';

            if( is_admin() ) {
                require_once LEARNDASH_EDD_DIR . 'includes/metabox.php';
            }
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function hooks() {
            // Update courses
            add_action( 'edd_updated_edited_purchase', array( $this, 'update_course_access' ) );
            add_action( 'edd_complete_purchase', array( $this, 'update_course_access' ) );
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $lang_dir = apply_filters( 'learndash_edd_language_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), '' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'learndash-edd', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/learndash-edd' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/learndash-edd/ folder
                load_textdomain( 'learndash-edd', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/learndash-edd/languages/ folder
                load_textdomain( 'learndash-edd', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'learndash-edd', false, $lang_dir );
            }
        }


        /**
         * Update LearnDash course access
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function update_course_access( $transaction_id ) {

            // Get transaction data
			$transaction = get_post( $transaction_id );
			if ($transaction) {
				error_log('transaction_id['. $transaction_id .'] transaction<pre>'. print_r($transaction, true) .'</pre>', 3, ABSPATH.'ld-edd.log');
				
				$edd_payment_meta = get_post_meta( $transaction_id, '_edd_payment_meta', true );
				error_log('edd_payment_meta<pre>'. print_r($edd_payment_meta, true) .'</pre>', 3, ABSPATH.'ld-edd.log');
				
				if ( ( isset( $edd_payment_meta['downloads'] ) ) 
				 && ( is_array( $edd_payment_meta['downloads'] ) ) 
				 && ( !empty( $edd_payment_meta['downloads'] ) ) ) {
					
					foreach( $edd_payment_meta['downloads'] as $download ) {
						
						if ( isset( $download['id'] ) ) {
						
							$download_id = intval($download['id']);
							if (!empty( $download_id ) ) {
						
								// Get customer ID.
								$customer_id 	= get_post_meta( $transaction_id, '_edd_payment_user_id', true );
								error_log('customer_id['. $customer_id .']', 3, ABSPATH.'ld-edd.log');
								
								if (!empty($customer_id)) {

									// Get the Courses 
									$courses = get_post_meta( $download_id, '_edd_learndash_course', true );
									error_log('courses<pre>'. print_r($courses, true) .'</pre>', 3, ABSPATH.'ld-edd.log');

									if ( ( is_array( $courses ) ) && ( !empty( $courses ) ) ) {
										foreach( $courses as $course_id ) {
											error_log('calling ld_update_course_access: customer_id['. $customer_id .'] course_id['. $course_id .']', 3, ABSPATH.'ld-edd.log');
											ld_update_course_access( (int) $customer_id, (int) $course_id );
										}
									}
								}
							}
						}
					}
				}
			}
        }
    }
}


/**
 * The main function responsible for returning the one true LearnDash_EDD
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      LearnDash_EDD The one true LearnDash_EDD
 */
function learndash_edd_load() {
    return LearnDash_EDD::instance();
}
add_action( 'plugins_loaded', 'learndash_edd_load' );
