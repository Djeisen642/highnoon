<?php
/**
 * Donation form model class.
 * 
 * @package   Charitable/Classes/Charitable_Donation_Form
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.0.0
 * @version   1.5.1
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Donation_Form' ) ) :

	/**
	 * Charitable_Donation_Form
	 *
	 * @since  1.0.0
	 */
	class Charitable_Donation_Form extends Charitable_Form implements Charitable_Donation_Form_Interface {

		/**
		 * The current campaign.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_Campaign
		 */
		protected $campaign;

		/**
		 * The current user, or false if the user is not logged in.
		 *
		 * @since 1.0.0
		 *
		 * @var   Charitable_User|false
		 */
		protected $user;

		/**
		 * Form fields.
		 *
		 * @since 1.0.0
		 *
		 * @var   array
		 */
		protected $form_fields;

		/**
		 * Nonce action.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		protected $nonce_action = 'charitable_donation';

		/**
		 * Nonce name.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		protected $nonce_name = '_charitable_donation_nonce';

		/**
		 * Action to be executed upon form submission.
		 *
		 * @since 1.0.0
		 *
		 * @var   string
		 */
		protected $form_action = 'make_donation';

		/**
		 * Value to indicate whether the user has all required fields filled out.
		 *
		 * @since 1.0.0
		 *
		 * @var   boolean
		 */
		protected $user_has_required_fields;

		/**
		 * Flag thrown when the form submission has been validated.
		 *
		 * @since 1.0.0
		 *
		 * @var   boolean
		 */
		protected $validated = false;

		/**
		 * Whether the form submission is valid.
		 *
		 * @since 1.0.0
		 *
		 * @var   boolean
		 */
		protected $valid;

		/**
		 * Create a donation form object.
		 *
		 * @since 1.0.0
		 * @since 1.5.0 $campaign argument became optional. Previously it was required.
		 *
		 * @param Charitable_Campaign|null $campaign Optional. Campaign receiving the donation, or NULL if 
		 *                                           the campaign will be selected in the form.
		 */
		public function __construct( Charitable_Campaign $campaign = null ) {
			$this->campaign = $campaign;
			$this->id       = uniqid();			

			$this->setup_payment_fields();
			$this->check_test_mode();

			/* For backwards-compatibility */
			add_action( 'charitable_form_field', array( $this, 'render_field' ), 10, 6 );
		}

		/**
		 * Returns the campaign associated with this donation form object.
		 *
		 * @since  1.0.0
		 * @since  1.5.0 May now return NULL when the donation form was set up without a campaign.
		 *
		 * @return Charitable_Campaign|null
		 */
		public function get_campaign() {
			return $this->campaign;
		}

		/**
		 * Return the current user.
		 *
		 * @since  1.0.0
		 *
		 * @return Charitable_User|false Object if the user is logged in. False otherwise.
		 */
		public function get_user() {
			if ( ! isset( $this->user ) ) {
				$user       = wp_get_current_user();
				$this->user = $user->ID ? new Charitable_User( $user ) : false;
			}

			return $this->user;
		}

		/**
		 * Returns the set value for a particular user field.
		 *
		 * @since  1.0.0
		 *
		 * @param  string $key
		 * @param  string $default Optional. The value that will be used if none is set.
		 * @return mixed
		 */
		public function get_user_value( $key, $default = '' ) {
			if ( isset( $_POST[ $key ] ) ) {
				return $_POST[ $key ];
			}

			if ( isset( $_GET['donation_id'] ) ) {
				$donation = charitable_get_donation( $_GET['donation_id'] );
				$value    = $donation->get_donor()->get_donor_meta( $key );

				if ( $value ) {
					return $value;
				}
			}

			if ( ! $this->get_user() || ! $this->get_user()->has_prop( $key ) ) {
				return $default;
			}

			return $this->get_user()->get( $key );
		}

		/**
		 * Returns the fields related to the person making the donation.
		 *
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_user_fields() {
			$fields = charitable()->donation_fields()->get_donation_form_fields();
			$keys   = array_keys( $fields );
			$fields = array_combine(
				$keys,
				array_map( array( $this, 'set_field_value' ), wp_list_pluck( $fields, 'donation_form' ), $keys )
			);

			/**
			 * Filter the donor fields.
			 *
			 * @since 1.0.0
			 *
			 * @param array                    $fields Set of donor fields.
			 * @param Charitable_Donation_Form $form   Instance of `Charitable_Donation_Form`.
			 */
			$fields = apply_filters( 'charitable_donation_form_user_fields', $fields, $this );
			$fields = $this->hide_non_required_user_fields( $fields );

			uasort( $fields, 'charitable_priority_sort' );

			return $fields;
		}

		/**
		 * Only show the required user fields if that option was enabled by the site admin.
		 *
		 * @since  1.2.0
		 *
		 * @return array[]
		 */
		public function hide_non_required_user_fields( $fields ) {
			if ( ! charitable_get_option( 'donation_form_minimal_fields', false ) ) {
				return $fields;
			}

			return array_filter( $fields, array( $this, 'filter_required_fields' ) );
		}

		/**
		 * Return fields used for account creation.
		 *
		 * By default, this just returns the password field. You can include a username
		 * field with ...
		 *
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_user_account_fields() {
			$account_fields = array(
				'user_pass' => array(
					'label'     => __( 'Password', 'charitable' ),
					'type'      => 'password',
					'priority'  => 4,
					'required'  => true,
					'requires_registration' => true,
					'data_type' => 'user',
				),
			);

			if ( apply_filters( 'charitable_donor_usernames', false ) ) {
				$account_fields['user_login'] = array(
					'label'     => __( 'Username', 'charitable' ),
					'type'      => 'text',
					'priority'  => 2,
					'required'  => true,
					'requires_registration' => true,
					'data_type' => 'user',
				);
			}

			return $account_fields;
		}

		/**
		 * Returns the donation fields.
		 *
		 * @since  1.0.0
		 *
		 * @return array[]
		 */
		public function get_donation_fields() {
			$fields = array(
				'donation_amount' => array(
					'type'      => 'donation-amount',
					'priority'  => 4,
					'required'  => false,
				),
			);

			/**
			 * Filter the donation amount fields.
			 *
			 * @since 1.0.0
			 *
			 * @param array                    $fields The list of fields.
			 * @param Charitable_Donation_Form $form   Instance of `Charitable_Donation_Form`.
			 */
			$fields = apply_filters( 'charitable_donation_form_donation_fields', $fields, $this );

			uasort( $fields, 'charitable_priority_sort' );

			return $fields;
		}

		/**
		 * Return the donation form fields.
		 *
		 * @since  1.0.0
		 *
		 * @return array[]
		 */
		public function get_fields() {
			$fields = apply_filters( 'charitable_donation_form_fields', array(
				'donation_fields' => array(
					'legend'   => __( 'Your Donation', 'charitable' ),
					'type'     => 'donation-amount-wrapper',
					'fields'   => $this->get_donation_fields(),
					'priority' => 20,
				),
				'user_fields' => array(
					'legend'   => __( 'Your Details', 'charitable' ),
					'type'     => 'donor-fields',
					'fields'   => $this->get_user_fields(),
					'class'    => 'fieldset',
					'priority' => 40,
				),
			), $this );

			uasort( $fields, 'charitable_priority_sort' );

			return $fields;
		}

		/**
		 * Add payment fields to the donation form if necessary.
		 *
		 * @since  1.0.0
		 *
		 * @param  array[] $fields
		 * @return array[]
		 */
		public function add_payment_fields( $fields ) {
			$gateways_helper    = charitable_get_helper( 'gateways' );
			$default_gateway    = $gateways_helper->get_default_gateway();
			$gateways           = array();
			$has_gateway_fields = false;

			foreach ( $gateways_helper->get_active_gateways() as $gateway_id => $gateway_class ) {
				$gateway        = new $gateway_class;
				$gateway_fields = $this->add_credit_card_fields( array(), $gateway );

				/**
				 * Filter the gateway fields.
				 *
				 * @since 1.0.0
				 *
				 * @param array              $gateway_fields List of gateway fields.
				 * @param Charitable_Gateway $gateway        Instance of `Charitable_Gateway`.
				 */
				$gateway_fields          = apply_filters( 'charitable_donation_form_gateway_fields', $gateway_fields, $gateway );
				$has_gateway_fields      = $has_gateway_fields || ! empty( $gateway_fields );
				$gateways[ $gateway_id ] = array(
					'label'  => $gateway->get_label(),
					'fields' => $gateway_fields,
				);
			}

			/* Add the payment section if there are gateway fields to be filled out. */
			if ( $has_gateway_fields || count( $gateways ) > 1 ) {
				$fields['payment_fields'] = array(
					'type'     => 'gateway-fields',
					'legend'   => __( 'Payment', 'charitable' ),
					'default'  => $default_gateway,
					'gateways' => $gateways,
					'priority' => 60,
				);
			}

			return $fields;
		}

		/**
		 * Include a paragraph showing the currently set donation
		 * amount before the amount form, if one is set.
		 *
		 * @since  1.4.14
		 * @since  1.5.0 $fields argument deprecated and return
		 *               type changed to string.
		 *
		 * @return string
		 */
		public function maybe_show_current_donation_amount() {
			if ( ! $this->get_campaign() ) {
				return '';
			}

			$amount = $this->get_campaign()->get_donation_amount_in_session();

			if ( ! $amount ) {	
				$content = charitable_template_from_session_content( 'donation_form_current_amount_text', array(
					'campaign_id' => $this->get_campaign()->ID,
					'form_id'     => $this->get_form_identifier(),
				), '' );
			} else {
				$content = charitable_template_donation_form_current_amount_text( $amount, $this->get_form_identifier(), $this->get_campaign()->ID );
			}

			return $content;
		}

		/**
		 * Add credit card fields to the donation form if this gateway requires it.
		 *
		 * @since  1.0.0
		 *
		 * @param  array[]            $fields  Current gateway fields. Deprecated as of 1.5.0.
		 * @param  Charitable_Gateway $gateway Instance of `Charitable_Gateway`.
		 * @return array[]
		 */
		public function add_credit_card_fields( $fields, Charitable_Gateway $gateway ) {
			if ( $gateway->supports( 'credit-card' ) ) {
				$fields = array_merge( $fields, $gateway->get_credit_card_fields() );
			}

			return $fields;
		}

		/**
		 * Render the donation form.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		public function render() {
			charitable_template( 'donation-form/form-donation.php', array(
				'campaign' => $this->get_campaign(),
				'form'     => $this,
			) );
		}

		/**
		 * Adds hidden fields to the start of the donation form.
		 *
		 * @since  1.5.0
		 *
		 * @return array
		 */
		public function get_hidden_fields() {
			$fields = parent::get_hidden_fields();

			if ( ! is_null( $this->campaign ) ) {
				$fields['campaign_id'] = $this->campaign->ID;
				$fields['description'] = get_the_title( $this->campaign->ID );
			}

			if ( isset( $_GET['donation_id'] ) ) {
				$fields['ID'] = $_GET['donation_id'];
			}

			return apply_filters( 'charitable_donation_form_hidden_fields', $fields, $this );
		}

		/**
		 * Set the gateway as a hidden field when there is only one gateway.
		 *
		 * @since  1.0.0
		 *
		 * @return string[]
		 */
		public function add_hidden_gateway_field( $fields ) {
			$gateways = charitable_get_helper( 'gateways' )->get_active_gateways();

			if ( count( $gateways ) !== 1 ) {
				return $fields;
			}

			$gateway_keys = array_keys( $gateways );

			$fields['gateway'] = $gateway_keys[0];

			return $fields;
		}

		/**
		 * Add a password field to the end of the form.
		 *
		 * @since  1.0.0
		 *
		 * @param  Charitable_Donation_Form $form
		 * @return void
		 */
		public function add_password_field( $form ) {
			if ( ! $form->is_current_form( $this->id ) ) {
				return;
			}

			/* Make sure we are not logged in. */
			if ( 0 !== wp_get_current_user()->ID ) {
				return;
			}

			charitable_template( 'donation-form/user-login-fields.php' );
		}

		/**
		 * Validate the form submission.
		 *
		 * @since  1.0.0
		 *
		 * @return boolean
		 */
		public function validate_submission() {
			/* If we have already validated the submission, return the value. */
			if ( $this->validated ) {
				return $this->valid;
			}

			$this->validated = true;

			$this->valid = $this->validate_security_check()
				&& $this->check_required_fields( $this->get_merged_fields() )
				&& $this->validate_email()
				&& $this->validate_amount()
				&& $this->validate_gateway();

			/**
			 * Filter the overall validation result.
			 *
			 * @since 1.0.0
			 *
			 * @param boolean                  $ret  The result to be returned. True or False.
			 * @param Charitable_Donation_Form $form This instance of `Charitable_Donation_Form`.
			 */
			$this->valid = apply_filters( 'charitable_validate_donation_form_submission', $this->valid, $this );

			return $this->valid;
		}

		/**
		 * Checks whether the security checks (nonce and honeypot) pass.
		 *
		 * @since  1.4.6
		 *
		 * @return boolean
		 */
		public function validate_security_check() {
			$ret = true;

			if ( ! $this->validate_nonce() || ! $this->validate_honeypot() ) {

				charitable_get_notices()->add_error( __( 'There was an error with processing your form submission. Please reload the page and try again.', 'charitable' ) );

				$ret = false;

			}

			/**
			 * Filter the security validation result.
			 *
			 * @since 1.4.7
			 *
			 * @param boolean                  $ret  The result to be returned. True or False.
			 * @param Charitable_Donation_Form $form This instance of `Charitable_Donation_Form`.
			 */
			return apply_filters( 'charitable_validate_donation_form_submission_security_check', $ret, $this );
		}

		/**
		 * Checks whether the submitted email is valid.
		 *
		 * @since  1.4.6
		 *
		 * @return boolean
		 */
		public function validate_email() {
			$ret = true;

			/* Don't process donations with dummy emails. */
			if ( array_key_exists( 'email', $_POST ) && ! is_email( $_POST['email'] ) ) {
				charitable_get_notices()->add_error( sprintf(
					__( '%s is not a valid email address.', 'charitable' ),
					$_POST['email']
				) );

				$ret = false;
			}

			/**
			 * Filter the email validation result.
			 *
			 * @since 1.4.7
			 *
			 * @param boolean                  $ret  The result to be returned. True or False.
			 * @param Charitable_Donation_Form $form This instance of `Charitable_Donation_Form`.
			 */
			return apply_filters( 'charitable_validate_donation_form_submission_email_check', $ret, $this );
		}

		/**
		 * Checks whether the submitted gateway is valid.
		 *
		 * @since  1.4.6
		 *
		 * @return boolean
		 */
		public function validate_gateway() {

			$ret = true;

			/* Validate the gateway. */
			if ( ! Charitable_Gateways::get_instance()->is_valid_gateway( $_POST['gateway'] ) ) {

				charitable_get_notices()->add_error( __( 'The gateway submitted is not valid.', 'charitable' ) );

				$ret = false;

			}

			/**
			 * Filter the gateway validation result.
			 *
			 * @since 1.4.7
			 *
			 * @param boolean                  $ret  The result to be returned. True or False.
			 * @param Charitable_Donation_Form $form This instance of `Charitable_Donation_Form`.
			 */
			return apply_filters( 'charitable_validate_donation_form_submission_gateway_check', $ret, $this );

		}

		/**
		 * Checks whether the set amount is valid.
		 *
		 * @since  1.4.6
		 *
		 * @return boolean
		 */
		public function validate_amount() {
			$ret = true;

			/* Ensure that a valid amount has been submitted. */
			$minimum = charitable_get_minimum_donation_amount();
			$amount  = self::get_donation_amount();

			if ( $minimum > 0 && $amount < $minimum ) {
				charitable_get_notices()->add_error( sprintf(
					__( 'You must donate more than %s.', 'charitable' ),
					charitable_format_money( $minimum )
				) );

				$ret = false;
			} elseif ( $minimum == 0 && $amount <= 0 && ! apply_filters( 'charitable_permit_0_donation', false ) ) {
				charitable_get_notices()->add_error( sprintf(
					__( 'You must donate more than %s.', 'charitable' ),
					charitable_format_money( $minimum )
				) );

				$ret = false;
			}

			/**
			 * Filter the amount validation result.
			 *
			 * @since 1.4.7
			 *
			 * @param boolean                  $ret  The result to be returned. True or False.
			 * @param Charitable_Donation_Form $form This instance of `Charitable_Donation_Form`.
			 */
			return apply_filters( 'charitable_validate_donation_form_submission_amount_check', $ret, $this );

		}

		/**
		 * Return the donation values.
		 *
		 * @since  1.0.0
		 *
		 * @return array
		 */
		public function get_donation_values() {
			$submitted = $this->get_submitted_values();
			$values    = array(
				'user_id'   => get_current_user_id(),
				'gateway'   => $submitted['gateway'],
				'campaigns' => array(
					array(
						'campaign_id' => $submitted['campaign_id'],
						'amount'      => self::get_donation_amount(),
					),
				),
			);

			/* Update an existing donation instead of creating a new one. */
			if ( isset( $submitted['ID'] ) ) {
				$values['ID'] = $submitted['ID'];
			}

			foreach ( $this->get_merged_fields() as $key => $field ) {

				if ( isset( $field['data_type'] ) || 'gateways' == $key ) {

					if ( 'gateways' == $key ) {

						foreach ( $field as $gateway_id => $gateway_fields ) {

							foreach ( $gateway_fields as $key => $field ) {

								if ( ! isset( $field['type'] ) ) {
									continue;
								}

								$field_type = $field['type'];
								$default    = 'checkbox' == $field_type ? false : '';
								$value      = isset( $submitted[ $key ] ) ? $submitted[ $key ] : $default;

								/* Strip extra spaces from the credit card number. */
								if ( 'cc_number' == $key ) {
									$value = trim( str_replace( ' ', '', $value ) );
								}

								$values['gateways'][ $gateway_id ][ $key ] = $value;
							}
						}
					} elseif ( isset( $field['type'] ) ) {

						$data_type  = $field['data_type'];
						$field_type = $field['type'];
						$default    = 'checkbox' == $field_type ? false : '';

						$values[ $data_type ][ $key ] = isset( $submitted[ $key ] ) ? $submitted[ $key ] : $default;

					}
				}
			}

			return apply_filters( 'charitable_donation_form_submission_values', $values, $submitted, $this );
		}

		/**
		 * Returns all fields as a merged array.
		 *
		 * @since  1.0.0
		 *
		 * @return array[]
		 */
		public function get_merged_fields() {
			$fields = array();

			foreach ( $this->get_fields() as $section_id => $section ) {

				if ( 'payment_fields' == $section_id ) {

					$section_fields = array();

					foreach ( $section['gateways'] as $gateway_id => $gateway_section ) {
						if ( isset( $gateway_section['fields'] ) ) {
							$section_fields['gateways'][ $gateway_id ] = $gateway_section['fields'];
						}
					}

					$fields = array_merge( $fields, $section_fields );

				} elseif ( isset( $section['fields'] ) ) {

					$fields = array_merge( $fields, $section['fields'] );

				} else {

					$fields[ $section_id ] = $section;
				}
			}

			return $fields;
		}

		/**
		 * Checks whether the user has all required fields.
		 *
		 * @since  1.2.0
		 *
		 * @return boolean
		 */
		public function user_has_required_fields() {
			if ( ! isset( $this->user_has_required_fields ) ) {

				foreach ( $this->get_user_fields() as $field ) {

					if ( ! isset( $field['required'] ) || false == $field['required'] ) {
						continue;
					}

					if ( empty( $field['value'] ) ) {
						$this->user_has_required_fields = false;
						return $this->user_has_required_fields;
					}
				}

				$this->user_has_required_fields = true;
			}

			return $this->user_has_required_fields;
		}

		/**
		 * Return the donation amount.
		 *
		 * @since  1.0.0
		 *
		 * @return float
		 */
		public static function get_donation_amount() {
			$amount = isset( $_POST['donation_amount'] ) ? $_POST['donation_amount'] : 0;

			if ( 0 === $amount || 'custom' == $amount ) {
				$amount = isset( $_POST['custom_donation_amount'] ) ? $_POST['custom_donation_amount'] : 0;
			}

			$amount = charitable_get_currency_helper()->sanitize_monetary_amount( (string) $amount );

			return apply_filters( 'charitable_donation_form_amount', $amount );
		}

		/**
		 * Set up payment fields based on the gateways that are installed and which one is default.
		 *
		 * @since  1.0.0
		 *
		 * @return void
		 */
		protected function setup_payment_fields() {
			$active_gateways = charitable_get_helper( 'gateways' )->get_active_gateways();
			$has_gateways    = apply_filters( 'charitable_has_active_gateways', ! empty( $active_gateways ) );

			/* If no gateways have been selected, display a notice and return the fields */
			if ( ! $has_gateways ) {
				charitable_get_notices()->add_error( $this->get_no_active_gateways_notice() );
				return;
			}

			if ( count( $active_gateways ) == 1 ) {
				add_filter( 'charitable_donation_form_hidden_fields', array( $this, 'add_hidden_gateway_field' ) );
			}

			add_action( 'charitable_donation_form_fields', array( $this, 'add_payment_fields' ) );
		}

		/**
		 * Set a field's initial value.
		 *
		 * @since  1.5.0
		 *
		 * @param  array  $field Field definition.
		 * @param  string $key   The key of the field.
		 * @return array
		 */
		protected function set_field_value( $field, $key ) {
			if ( array_key_exists( $key, $_POST ) ) {
				$field['value'] = $_POST[ $key ];
			} elseif ( array_key_exists( 'donation_id', $_GET ) ) {
				$donation       = charitable_get_donation( $_GET['donation_id'] );
				$field['value'] = is_a( $donation, 'Charitable_Donation' ) ? $donation->get( $key ) : '';
			}

			if ( array_key_exists( 'value', $field ) ) {
				return $field;
			}

			if ( 'user' == $field['data_type'] ) {
				$field['value'] = $this->get_user_value( $key, $field['default'] );
			} else {
				$field['value'] = $field['default'];
			}

			return $field;
		}

		/**
		 * Given a notice and an additional action that requires
		 * admin credentials, return the appropriate notice for
		 * the current user.
		 *
		 * @since  1.5.0
		 *
		 * @return string
		 */
		protected function get_credentialed_notice( $notice, $admin_action ) {
			if ( current_user_can( 'manage_charitable_settings' ) ) {
				$notice .= '&nbsp;' . $admin_action;
			}

			return $notice;
		}

		/**
		 * A formatted notice to advise that there are no gateways active.
		 *
		 * @since  1.0.0
		 *
		 * @return string
		 */
		protected function get_no_active_gateways_notice() {
			$notice = $this->get_credentialed_notice(
				__( 'There are no active payment gateways.', 'charitable' ),
				sprintf( '<a href="%s">%s</a>.', admin_url( 'admin.php?page=charitable-settings&tab=gateways' ), _x( 'Enable one now', 'enable payment gateway', 'charitable' ) )
			);

			return apply_filters( 'charitable_no_active_gateways_notice', $notice, current_user_can( 'manage_charitable_settings' ) );
		}

		/**
		 * Determine the status of Test Mode and display an alert if it is active
		 *
		 * @since  1.4.7
		 *
		 * @return void
		 */
		protected function check_test_mode() {
			$in_test_mode = charitable_get_option( 'test_mode', 0 );

			/* If test mode is enabled, and current user is an admin, display an alert on the form. */
			if ( $in_test_mode && current_user_can( 'manage_charitable_settings' ) ) {
				charitable_get_notices()->add_error( $this->get_test_mode_active_notice() );
			}
		}

		/**
		 * A formatted notice to advise that Test Mode is active.
		 *
		 * @since  1.4.7
		 *
		 * @return string
		 */
		protected function get_test_mode_active_notice() {
			$notice = $this->get_credentialed_notice(
				__( 'Test mode is active.', 'charitable' ),
				sprintf( '<a href="%s">%s</a>.',
					admin_url( 'admin.php?page=charitable-settings&tab=gateways' ),
					__( 'Disable Test Mode', 'charitable' )
				)
			);

			return apply_filters( 'charitable_test_mode_active_notice', $notice, current_user_can( 'manage_charitable_settings' ) );
		}

		/**
		 * Return the donor value fields.
		 *
		 * @since  1.0.0
		 *
		 * @return string[]
		 */
		protected function get_donor_value_fields( $submitted ) {
			$donor_fields = array();

			if ( isset( $submitted['first_name'] ) ) {
				$donor_fields['first_name'] = $submitted['first_name'];
			}

			if ( isset( $submitted['last_name'] ) ) {
				$donor_fields['last_name'] = $submitted['last_name'];
			}

			if ( isset( $submitted['user_email'] ) ) {
				$donor_fields['email'] = $submitted['user_email'];
			}

			return $donor_fields;
		}

		/**
		 * Checks whether the form submission contains profile fields.
		 *
		 * @since  1.0.0
		 *
		 * @return boolean
		 */
		protected function has_profile_fields( $submitted, $user_fields ) {
			foreach ( $user_fields as $key => $field ) {
				if ( $field['requires_registration'] && isset( $submitted[ $key ] ) ) {
					return true;
				}
			}

			return false;
		}

		/**
		 * Returns true if required fields are missing.
		 *
		 * @since  1.0.0
		 *
		 * @param  array   $required_fields
		 * @return boolean
		 */
		protected function is_missing_required_fields( $required_fields ) {
			if ( is_user_logged_in() ) {
				return false;
			}

			if ( is_null( $this->get_submitted_value( 'gateway' ) ) ) {

				charitable_get_notices()->add_error( sprintf( '<p>%s</p>',
					__( 'Your donation could not be processed. No payment gateway was selected.', 'charitable' )
				) );

				return false;
			}

			return ! $this->check_required_fields( $required_fields );
		}

		/**
		 * Use custom template for some form fields.
		 *
		 * @since  1.0.0
		 * @since  1.5.0 Deprecated. This is handled by `Charitable_Public_Form_View` now.
		 *
		 * @param  string|false $custom_template
		 * @return string|false|Charitable_Template
		 */
		public function use_custom_templates( $custom_template ) {
			charitable_get_deprecated()->deprecated_function(
				__METHOD__,
				'1.5.0'
			);

			return $custom_template;			
		}
	}

endif;
