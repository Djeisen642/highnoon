<?php
/**
 * Class that sets up the WordPress Customizer integration.
 *
 * @package   Charitable/Classes/Charitable_Customizer
 * @author    Eric Daams
 * @copyright Copyright (c) 2018, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.2.0
 * @version   1.2.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Customizer' ) ) :

	/**
	 * Sets up the Wordpress customizer
	 *
	 * @since 1.2.0
	 */
	class Charitable_Customizer {

		/**
		 * The single instance of this class.
		 *
		 * @var Charitable_Customizer|null
		 */
		private static $instance = null;

		/**
		 * Create object instance.
		 *
		 * @since 1.2.0
		 */
		private function __construct() {
			add_action( 'customize_save_after', array( $this, 'customize_save_after' ) );
			add_action( 'customize_register', array( $this, 'register_customizer_fields' ) );
			add_action( 'customize_preview_init', array( $this, 'load_customizer_script' ) );
		}

		/**
		 * Returns and/or create the single instance of this class.
		 *		 
		 * @since  1.2.0
		 *
		 * @global WP_Customize_Manager $wp_customize
		 * @return Charitable_Customizer
		 */
		public static function start() {
			global $wp_customize;

			if ( ! $wp_customize ) {
				return;
			}

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		/**
		 * After the customizer has finished saving each of the fields, delete the transient.
		 *
		 * @see    customize_save_after hook
		 *
		 * @since  1.2.0
		 *
		 * @return void
		 */
		public function customize_save_after() {
			delete_transient( 'charitable_custom_styles' );
		}

		/**
		 * Plugin customization.
		 *
		 * @return void
		 */
		public function register_customizer_fields() {
			$fields = array(
				'title'      => __( 'Charitable', 'charitable' ),
				'priority'   => 1000,
				'capability' => 'manage_charitable_settings',
				'sections'   => array(
					'charitable_donation_form' => array(
						'title'    => __( 'Donation Form', 'charitable' ),
						'priority' => 1020,
						'settings' => array(
							'donation_form_display' => array(
								'setting' => array(
									'transport'         => 'refresh',
									'default'           => 'separate_page',
									'sanitize_callback' => array( $this, 'sanitize_donation_form_display_option' ),
								),
								'control' => array(
									'type'     => 'select',
									'label'    => __( 'How do you want a campaign\'s donation form to show?', 'charitable' ),
									'priority' => 1022,
									'choices'  => array(
										'separate_page' => __( 'Show on a Separate Page', 'charitable' ),
										'same_page'     => __( 'Show on the Same Page', 'charitable' ),
										'modal'         => __( 'Reveal in a Modal', 'charitable' ),
									),
								),
							),
							'donation_form_minimal_fields' => array(
								'setting' => array(
									'transport' => 'refresh',
									'default'   => 0,
								),
								'control' => array(
									'type'     => 'radio',
									'label'    => __( 'Only show required fields', 'charitable' ),
									'priority' => 1024,
									'choices'  => array(
										1 => __( 'Yes', 'charitable' ),
										0 => __( 'No', 'charitable' ),
									),
								),
							),
						),
					),
				),
			);

			/**
			 * Set whether to add custom styles.
			 *
			 * @since 1.2.3
			 *
			 * @param boolean Whether to add styles.
			 */
			if ( apply_filters( 'charitable_add_custom_styles', true ) ) {

				/**
				 * Filter the default highlight colour.
				 *
				 * @since 1.2.0
				 *
				 * @param string $colour_code HEX color code.
				 */
				$highlight_colour = apply_filters( 'charitable_default_highlight_colour', '#f89d35' );

				$fields['sections']['charitable_design'] = array(
					'title'    => __( 'Design Options', 'charitable' ),
					'priority' => 1010,
					'settings' => array(
						'highlight_colour' => array(
							'setting' => array(
								'transport'         => 'postMessage',
								'default'           => $highlight_colour,
								'sanitize_callback' => 'sanitize_hex_color',
							),
							'control' => array(
								'control_type' => 'WP_Customize_Color_Control',
								'priority'     => 1110,
								'label'        => __( 'Highlight Color', 'charitable' ),
							),
						),
					),
				);
			}//end if

			/**
			 * Filter the customizer fields.
			 *
			 * @since 1.2.0
			 *
			 * @param array $fields The array of customizer fields.
			 */
			$fields = apply_filters( 'charitable_customizer_fields', $fields );

			$this->add_panel( 'charitable', $fields );
		}

		/**
		 * Make sure the donation form display option is valid.
		 *
		 * @since  1.2.0
		 *
		 * @param  string $option Submitted option.
		 * @return string
		 */
		public function sanitize_donation_form_display_option( $option ) {
			if ( ! in_array( $option, array( 'separate_page', 'same_page', 'modal' ) ) ) {
				$option = 'separate_page';
			}

			return $option;
		}

		/**
		 * Adds a panel.
		 *
		 * @since  1.2.0
		 *
		 * @global WP_Customize_Manager $wp_customize
		 * @param  string $panel_id Panel identifier.
		 * @param  array  $panel    Panel definition.
		 * @return void
		 */
		private function add_panel( $panel_id, $panel ) {
			global $wp_customize;

			if ( empty( $panel ) ) {
				return;
			}

			$wp_customize->add_panel( $panel_id, array(
				'title'    => $panel['title'],
				'priority' => $panel['priority'],
			) );

			$this->add_panel_sections( $panel_id, $panel['sections'] );
		}

		/**
		 * Adds sections to a panel.
		 *
		 * @since  1.2.0
		 *
		 * @param  string $panel_id Panel identifier.
		 * @param  array  $sections Array of sections inside panel.
		 * @return void
		 */
		private function add_panel_sections( $panel_id, $sections ) {
			if ( empty( $sections ) ) {
				return;
			}

			foreach ( $sections as $section_id => $section ) {
				$this->add_section( $section_id, $section, $panel_id );
			}
		}

		/**
		 * Adds section & settings
		 *
		 * @since  1.2.0
		 *
		 * @global WP_Customize_Manager $wp_customize
		 * @param  string $section_id Section identifier.
		 * @param  array  $section    Section definition.
		 * @param  string $panel      Panel identifier.
		 * @return void
		 */
		private function add_section( $section_id, $section, $panel ) {
			global $wp_customize;

			if ( empty( $section ) ) {
				return;
			}

			$settings = $section['settings'];

			unset( $section['settings'] );

			$section['panel'] = $panel;

			$wp_customize->add_section( $section_id, $section );

			$this->add_section_settings( $section_id, $settings );
		}


		/**
		 * Adds settings to a given section.
		 *
		 * @since  1.2.0
		 *
		 * @global WP_Customize_Manager $wp_customize
		 * @param  string $section_id Section identifier.
		 * @param  array  $settings   Section definition.
		 * @return void
		 */
		private function add_section_settings( $section_id, $settings ) {
			global $wp_customize;

			if ( empty( $settings ) ) {
				return;
			}

			foreach ( $settings as $setting_id => $setting ) {
				if ( ! isset( $setting['setting']['type'] ) ) {
					$setting['setting']['type'] = 'option';
				}

				$setting_id = "charitable_settings[$setting_id]";

				$wp_customize->add_setting( $setting_id, $setting['setting'] );

				$setting_control = $setting['control'];
				$setting_control['section'] = $section_id;

				if ( isset( $setting_control['control_type'] ) ) {

					$setting_control_type = $setting_control['control_type'];

					unset( $setting_control['control_type'] );

					$wp_customize->add_control( new $setting_control_type( $wp_customize, $setting_id, $setting_control ) );

				} else {

					$wp_customize->add_control( $setting_id, $setting_control );

				}
			}
		}

		/**
		 * Load the theme-customizer.js file.
		 *
		 * @since  1.2.0
		 *
		 * @return void
		 */
		public function load_customizer_script() {
			$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

			wp_register_script(
				'charitable-customizer',
				charitable()->get_path( 'assets', false ) . 'js/charitable-customizer' . $suffix . '.js',
				array( 'jquery-core', 'customize-preview' ),
				'1.2.0-beta5',
				true
			);

			wp_enqueue_script( 'charitable-customizer' );
		}
	}

endif;
