<?php
class Wdsei_admin_setttings_itpixelz
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action('admin_menu', array( $this, 'wdsei_add_admin_itpixelz_page' ));
        add_action('admin_init', array( $this, 'wdsei_page_init' ));
    }

    /**
     * Add options page
     */
    public function wdsei_add_admin_itpixelz_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin',
            'WP Default Mail',
            'manage_options',
            'wp-default-sender-email-itpixelz',
            array( $this, 'wdsei_create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function wdsei_create_admin_page()
    {
        // Set class property
        $this->options = get_option('wdsei_itpixelz_options'); ?>
        <div class="wrap wdsei_settings_area">
		        <h2>Wp Default Sender Email (by IT Pixelz)</h2>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields('wdsei_itpixez_settings_options');
                do_settings_sections('wp-default-sender-email-itpixelz');
                submit_button(); ?>
            </form>
			<p><strong>Warning:</strong> If you are going to use email account other than the domain on your hosting, then you may have to confirm if your current web hosting is offering so.</p>
        </div>
        <?php

    }

    /**
     * Register and add settings
     */
    public function wdsei_page_init()
    {
        register_setting(
            'wdsei_itpixez_settings_options', // Option group
            'wdsei_itpixelz_options', // Option name
            array( $this, 'wdsei_sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Update Your Default Mail Sender Details', // Sender Mail ID
            array( $this, 'wdsei_print_section_info' ), // Callback
            'wp-default-sender-email-itpixelz' // Page
        );

        add_settings_field(
            'sender_name', // ID
            'Sender Name', // Sender Mail ID
            array( $this, 'wdsei_sender_name_callback' ), // Callback
            'wp-default-sender-email-itpixelz', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'sender_mail',
            'Sender Mail ID',
            array( $this, 'wdsei_sender_email_callback' ),
            'wp-default-sender-email-itpixelz',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function wdsei_sanitize($input)
    {
        $new_input = array();
        if (isset($input['sender_name'])) {
            $new_input['sender_name'] = sanitize_text_field($input['sender_name']);
        }

        if (isset($input['sender_mail'])) {
            $new_input['sender_mail'] = sanitize_text_field($input['sender_mail']);
        }

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function wdsei_print_section_info()
    {
        print '<p>Enter the details below:</p>';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function wdsei_sender_name_callback()
    {
        printf(
            '<input type="text" id="sender_name" name="wdsei_itpixelz_options[sender_name]" value="%s" />',
            isset($this->options['sender_name']) ? esc_attr($this->options['sender_name']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function wdsei_sender_email_callback()
    {
        printf(
            '<input type="text" id="sender_mail" name="wdsei_itpixelz_options[sender_mail]" value="%s" />',
            isset($this->options['sender_mail']) ? esc_attr($this->options['sender_mail']) : ''
        );
    }
}

if (is_admin()) {
    $my_settings_page = new Wdsei_admin_setttings_itpixelz();
}
