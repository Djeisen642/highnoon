<?php
/**
 * Register and retrieve donation fields.
 *
 * @package   Charitable/Classes/Charitable_Donation_Field_Registry
 * @author    Eric Daams
 * @copyright Copyright (c) 2017, Studio 164a
 * @license   http://opensource.org/licenses/gpl-2.0.php GNU Public License
 * @since     1.5.0
 * @version   1.5.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( ! class_exists( 'Charitable_Donation_Field_Registry' ) ) :

    /**
     * Charitable_Donation_Field_Registry
     *
     * @since 1.5.0
     */
    class Charitable_Donation_Field_Registry implements Charitable_Field_Registry_Interface {

        /**
         * Registered fields.
         *
         * @since 1.5.0
         *
         * @var   array
         */
        protected $fields;

        /**
         * Admin form fields.
         *
         * @since 1.5.0
         *
         * @var   array
         */
        protected $admin_form_fields;

        /**
         * Create class object.
         *
         * @since 1.5.0
         */
        public function __construct() {
            $this->fields           = array();
            $this->default_sections = array();
        }

        /**
         * Return all the fields.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_fields() {
            return $this->fields;
        }

        /**
         * Return the donation form fields.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_donation_form_fields() {
            return array_filter( $this->fields, array( $this, 'show_field_in_donation_form' ) );
        }

        /**
         * Return the donation form fields.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_admin_form_fields( $section = '' ) {
            if ( ! isset( $this->admin_form_fields ) ) {
                $this->admin_form_fields = array_filter( $this->fields, array( $this, 'show_field_in_admin_form' ) );
            }

            if ( empty( $section ) ) {
                return $this->admin_form_fields;
            }

            $fields = array();

            foreach ( $this->admin_form_fields as $key => $field ) {
                if ( $section != $field->admin_form['section'] ) {
                    continue;
                }

                $fields[ $key ] = $field;
            }

            return $fields;
        }

        /**
         * Return the donation form fields.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_email_tag_fields() {
            return array_filter( $this->fields, array( $this, 'show_field_as_email_tag' ) );
        }

        /**
         * Return the fields to be included in the export.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_export_fields() {
            return array_filter( $this->fields, array( $this, 'show_field_in_export' ) );
        }

        /**
         * Return the fields to be included in the donation meta.
         *
         * @since  1.5.0
         *
         * @return array
         */
        public function get_meta_fields() {
            return array_filter( $this->fields, array( $this, 'show_field_in_meta' ) );
        }

        /**
         * Return a single field.
         *
         * @since  1.5.0
         *
         * @param  string $field_key               The field's key.
         * @return Charitable_Donation_Field|false Instance of `Charitable_Donation_Field` if
         *                                         the field is registered. False otherwise.
         */
        public function get_field( $field_key ) {
            return array_key_exists( $field_key, $this->fields ) ? $this->fields[ $field_key ] : false;
        }

        /**
         * Set the default form section.
         *
         * @since  1.5.0
         *
         * @param  string $section Section to register.
         * @param  string $form    Which form we're registering the section in.
         * @return void
         */
        public function set_default_section( $section, $form = 'public' ) {
            $this->default_sections[ $form ] = $section;
        }

        /**
         * Register a field.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Field_Interface $field Instance of `Charitable_Field_Interface`.
         * @return void
         */
        public function register_field( Charitable_Field_Interface $field ) {
            if ( ! is_a( $field, 'Charitable_Donation_Field' ) ) {
                return;
            }

            $field->value_callback         = $this->get_field_value_callback( $field );
            $field->donation_form          = $this->get_field_donation_form( $field );
            $field->admin_form             = $this->get_field_admin_form( $field );
            $field->email_tag              = $this->get_field_email_tag( $field );
            $this->fields[ $field->field ] = $field;
        }

        /**
         * Returns whether a field is set up to be shown in the donations export.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return boolean
         */
        public function show_field_in_export( Charitable_Donation_Field $field ) {
            return $field->show_in_export;
        }

        /**
         * Checks whether a field should be shown in the donation meta.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return boolean
         */
        public function show_field_in_meta( Charitable_Donation_Field $field ) {
            return $field->show_in_meta;
        }

        /**
         * Checks whether a field should be shown as an email tag.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return boolean
         */
        public function show_field_as_email_tag( Charitable_Donation_Field $field ) {
            return false !== $field->email_tag;
        }

        /**
         * Checks whether a field should be shown in the admin form.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return boolean
         */
        public function show_field_in_admin_form( Charitable_Donation_Field $field ) {
            return false !== $field->admin_form;
        }

        /**
         * Checks whether a field should be shown in the donation form.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return boolean
         */
        public function show_field_in_donation_form( Charitable_Donation_Field $field ) {
            return false !== $field->donation_form;
        }

        /**
         * Get priority for a field.
         *
         * @since  1.5.0
         *
         * @param  array $settings An array of donation form settings.
         * @return int
         */
        protected function get_field_priority( array $settings ) {
            /* The field was defined with a priority, so just return that. */
            if ( array_key_exists( 'priority', $settings ) ) {
                return $settings['priority'];
            }

            $fields = $this->get_donation_form_fields();
            $after  = false;
            $before = false;

            if ( array_key_exists( 'show_after', $settings ) && array_key_exists( $settings['show_after'], $fields ) ) {
                $after = $fields[ $settings['show_after'] ];
            }

            if ( array_key_exists( 'show_before', $settings ) && array_key_exists( $settings['show_before'], $fields )  ) {
                $before = $fields[ $settings['show_before'] ];
            }

            /* If the field was set to show after a certain field and before another field. */
            if ( $after && $before ) {
                return ( $after->donation_form['priority'] + $before->donation_form['priority'] ) / 2;
            }

            if ( $after ) {
                return $after->donation_form['priority'] + 0.5;
            }

            if ( $before ) {
                return $before->donation_form['priority'] - 0.5;
            }

            /* Otherwise, put it 2 after the most recently registered field. */
            return end( $fields )->donation_form['priority'] + 2;
        }

        /**
         * Return a callback for the field.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return false|string|callable            Returns a callable function or false if none is set and
         *                                          we don't have a default one for the data type.
         */
        public function get_field_value_callback( $field ) {
            if ( isset( $field->value_callback ) ) {
                return $field->value_callback;
            }            

            switch ( $field->data_type ) {
                case 'user' :
                    return 'charitable_get_donor_meta_value';

                case 'meta' :
                    return 'charitable_get_donation_meta_value';

                default : 
                    return false;
            }
        }

        /**
         * Return a parsed array of settings for the field, or false if it should not appear
         * in the donation form.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return array|false
         */
        protected function get_field_donation_form( Charitable_Donation_Field $field ) {
            $settings = $field->donation_form;

            if ( false === $settings ) {
                return $settings;
            }

            if ( ! array_key_exists( 'section', $settings ) ) {
                $settings['section'] = $this->default_sections['public'];    
            }

            return $this->parse_form_settings( $settings, $field );
        }

        /**
         * Return a parsed array of settings for the field, or false if it should not appear
         * in the donation form.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return array
         */
        protected function get_field_admin_form( Charitable_Donation_Field $field ) {
            $settings = $field->admin_form;

            if ( false === $settings ) {
                return $settings;
            }

            /* If the value is true, we use the same args as for the donation_form setting. */
            if ( true === $settings ) {
                return $field->donation_form;
            }

            if ( is_array( $field->donation_form ) ) {
                $settings = array_merge( $field->donation_form, $settings );
            }

            if ( ! array_key_exists( 'section', $settings ) ) {
                $settings['section'] = $this->default_sections['admin'];    
            }

            return $this->parse_form_settings( $settings, $field );
        }

        /**
         * Parse form settings.
         *
         * @since  1.5.0
         *
         * @param  array                     $settings An array of form settings.
         * @param  Charitable_Donation_Field $field    Instance of `Charitable_Donation_Field`.
         * @return array
         */
        protected function parse_form_settings( array $settings, Charitable_Donation_Field $field ) {
            $settings['priority']  = $this->get_field_priority( $settings );
            $settings['data_type'] = $field->data_type;

            /* Make sure a label is set. */
            if ( ! array_key_exists( 'label', $settings ) ) {
                $settings['label'] = $field->label;
            }

            /* Make sure that options are set for fields that need it. */
            if ( $this->field_needs_options( $settings['type'] ) ) {
                $has_options         = array_key_exists( 'options', $settings ) && is_array( $settings['options'] );
                $settings['options'] = $has_options ? $settings['options'] : array();
            }

            return $settings;
        }

        /**
         * Return a parsed array of email tag settings for the field, or false if no email 
         * tag should be created.
         *
         * @since  1.5.0
         *
         * @param  Charitable_Donation_Field $field Instance of `Charitable_Donation_Field`.
         * @return array|false                      False for fields without an email tag. Array of
         *                                          tag settings otherwise.
         */
        protected function get_field_email_tag( Charitable_Donation_Field $field ) {
            $settings = $field->email_tag;

            if ( false === $settings ) {
                return $settings;
            }

            $defaults = array(
                'description' => $field->label,
                'preview'     => $field->label,
                'tag'         => $field->field,
            );

            return array_merge( $defaults, $settings );
        }

        /**
         * Whether a field needs an array of options to be set.
         *
         * @since  1.5.0
         *
         * @param  string $field_type The type of field.
         * @return boolean
         */
        protected function field_needs_options( $field_type ) {
            return in_array( $field_type, array( 'select', 'multi-checkbox', 'radio' ) );        
        }
    }

endif;
