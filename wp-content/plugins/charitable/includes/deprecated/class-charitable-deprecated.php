<?php
/**
 * A helper class for logging deprecated arguments, functions and methods.
 *
 * @package     Charitable/Classes/Charitable_Deprecated
 * @version     1.4.0
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Deprecated' ) ) :

	/**
	 * Charitable_Deprecated
	 *
	 * @since   1.4.0
	 */
	class Charitable_Deprecated {

		/**
		 * One true class object.
		 *
		 * @var     Charitable_Deprecated
		 * @since   1.4.0
		 */
		private static $instance = null;

		/**
		 * Whether logging is enabled.
		 *
		 * @var     $logging
		 * @since   1.4.0
		 */
		private static $logging;

		/**
		 * Create class object. Private constructor.
		 *
		 * @since   1.4.0
		 */
		private function __construct() {
		}

		/**
		 * Create and return the class object.
		 *
		 * @since   1.4.0
		 */
		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * Log a deprecated argument.
		 *
		 * @since   1.4.0
		 *
		 * @param   string      $function      The deprecated function.
		 * @param   string      $version       The version when this argument became deprecated.
		 * @param   string|null $extra_message An extra message to include for the notice.
		 * @return  boolean Whether the notice was logged.
		 */
		public function deprecated_argument( $function, $version, $extra_message = null ) {
			if ( ! $this->is_logging_enabled() ) {
				return false;
			}

			if ( ! is_null( $extra_message ) ) {
				$message = sprintf( __( '%1$s was called with an argument that is <strong>deprecated</strong> since version %2$s of Charitable! %3$s', 'charitable' ), $function, $version, $extra_message );
			} else {
				$message = sprintf( __( '%1$s was called with an argument that is <strong>deprecated</strong> since version %2$s of Charitable with no alternatives available.', 'charitable' ), $function, $version );
			}

			trigger_error( $message );

			return true;
		}

		/**
		 * Log a deprecated function.
		 *
		 * @since   1.4.0
		 *
		 * @param   string      $function    The function that has been deprecated.
		 * @param   string      $version     The version of Charitable where the function was deprecated.
		 * @param   string|null $replacement Optional. The function to use instead.
		 * @return  boolean Whether the notice was logged.
		 */
		public function deprecated_function( $function, $version, $replacement = null ) {
			if ( ! $this->is_logging_enabled() ) {
				return false;
			}

			if ( ! is_null( $replacement ) ) {
				$message = sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s of Charitable! Use %3$s instead.', 'charitable' ), $function, $version, $replacement );
			} else {
				$message = sprintf( __( '%1$s is <strong>deprecated</strong> since version %2$s of Charitable with no alternatives available.', 'charitable' ), $function, $version );
			}

			trigger_error( $message );

			return true;
		}

		/**
		 * Log a general "doing it wrong" notice.
		 *
		 * @since   1.4.0
		 *
		 * @param   string $function
		 * @param   string $message
		 * @param   string $version
		 * @return  boolean Whether the notice was logged.
		 */
		public function doing_it_wrong( $function, $message, $version ) {
			if ( ! $this->is_logging_enabled() ) {
				return false;
			}

			$version = is_null( $version ) ? '' : sprintf( __( '(This message was added in Charitable version %s.)', 'charitable' ), $version );

			$message = sprintf( __( '%1$s was called <strong>incorrectly</strong>. %2$s %3$s', 'charitable' ), $function, $message, $version );

			trigger_error( $message );

			return true;
		}

		/**
		 * Returns whether logging is enabled.
		 *
		 * @since   1.4.0
		 *
		 * @return  boolean
		 */
		private function is_logging_enabled() {
			if ( ! isset( self::$logging ) ) {
				self::$logging = WP_DEBUG && apply_filters( 'charitable_log_deprecated_notices', true );
			}

			return self::$logging;
		}
	}

endif;
