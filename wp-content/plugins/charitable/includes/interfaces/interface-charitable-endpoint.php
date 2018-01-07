<?php
/**
 * Endpoint interface.
 *
 * This defines a strict interface that all endpoint classes must implement.
 *
 * @version     1.5.0
 * @package     Charitable/Interfaces/Charitable_Endpoint_Interface
 * @author      Eric Daams
 * @copyright   Copyright (c) 2017, Studio 164a
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if ( ! interface_exists( 'Charitable_Endpoint_Interface' ) ) :

	/**
	 * Charitable_Endpoint_Interface interface.
	 *
	 * @since  1.5.0
	 */
	interface Charitable_Endpoint_Interface {

		/**
		 * Return the endpoint ID.
		 *
		 * @since  1.5.0
		 *
		 * @return string
		 */
		public static function get_endpoint_id();

		/**
		 * Return the endpoint URL.
		 *
		 * @since  1.5.0
		 *
		 * @return string
		 */
		public function get_page_url();

		/**
		 * Return whether we are currently viewing the endpoint.
		 *
		 * @since  1.5.0
		 *
		 * @return boolean
		 */
		public function is_page();
	}

endif;
