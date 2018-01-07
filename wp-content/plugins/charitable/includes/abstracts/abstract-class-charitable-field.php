<?php
/**
 * Base Charitable_Field model.
 *
 * @package   Charitable/Classes/Charitable_Field
 * @version   1.5.0
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Field' ) ) :

	/**
	 * Charitable_Field
	 *
	 * @since 1.5.0
	 */
	abstract class Charitable_Field implements Charitable_Field_Interface {

		/**
		 * Field identifier.
		 *
		 * @since 1.5.0
		 *
		 * @var   string
		 */
		protected $field;

		/**
		 * Field arguments.
		 *
		 * @since 1.5.0
		 *
		 * @var   array
		 */
		protected $args;

		/**
		 * Create class object.
		 *
		 * @since 1.5.0
		 *
		 * @param string $field The field key.
		 * @param array  $args  Mixed arguments.
		 */
		public function __construct( $field, array $args = array() ) {
			$this->field = $field;
			$this->args  = $this->parse_args( $args );
		}   

		/**
		 * Set a specific argument.
		 *
		 * @since  1.5.0
		 *
		 * @param  string The field's key.
		 * @param  mixed  The field's value.
		 * @return Charitable_Field
		 */
		public function __set( $key, $value ) {
			$this->args[ $key ] = $this->sanitize_arg( $key, $value );
			return $this;
		}

		/**
		 * Get a particular argument value.
		 *
		 * @since  1.5.0
		 *
		 * @param  string The field's key.
		 * @return mixed
		 */
		public function __get( $key ) {
			return 'field' == $key ? $this->field : $this->args[ $key ];
		}

		/**
		 * Return the default arguments for this field type.
		 *
		 * @since  1.5.0
		 *
		 * @return array
		 */
		protected function get_defaults() {
			return array();			
		}

		/**
		 * Parse the passed arguments against a set of defaults and sanitize them.
		 *
		 * @since  1.5.0
		 *
		 * @param  array $args Mixed set of field arguments.
		 * @return array       Parsed arguments.
		 */
		protected function parse_args( $args ) {		
			$args      = array_merge( $this->get_defaults(), $args );
			$keys      = array_keys( $args );
			$sanitized = array_map( array( $this, 'sanitize_arg' ), $keys, $args );

			return array_combine( $keys, $sanitized );
		}

		/**
		 * Sanitize the argument.
		 *
		 * @since  1.5.0
		 *
		 * @param  string The argument's key.
		 * @param  mixed  The argument's value.
		 * @return mixed  The argument value after being registered.
		 */
		protected function sanitize_arg( $key, $value ) {
			$method = 'sanitize_' . $key;
			if ( method_exists( $this, $method ) ) {
				return $this->$method( $value );
			}

			return $value;
		}
	}

endif;
