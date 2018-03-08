<?php
if ( ( class_exists( 'LearnDash_Settings_Section' ) ) && ( !class_exists( 'LearnDash_Settings_Section_General_Per_Page' ) ) ) {
	class LearnDash_Settings_Section_General_Per_Page extends LearnDash_Settings_Section {

		function __construct() {
			$this->settings_page_id					=	'learndash_lms_settings';
		
			// This is the 'option_name' key used in the wp_options table
			$this->setting_option_key 				= 	'learndash_settings_per_page';

			// This is the HTML form field prefix used. 
			$this->setting_field_prefix				= 	'learndash_settings_per_page';
	
			// Used within the Settings API to uniquely identify this section
			$this->settings_section_key				= 	'settings_per_page';
		
			// Section label/header
			$this->settings_section_label			=	esc_html__( 'Per Page Default Settings', 'learndash' );
		
			parent::__construct(); 
		}
				
		function load_settings_fields() {

			$this->setting_option_fields = array(
				'registered_num' => array(
					'name'  		=> 	'registered_num', 
					'type'  		=> 	'number',
					'label' 		=> 	sprintf( esc_html_x( 'Registered %s per page', 'placeholder: Courses', 'learndash' ), LearnDash_Custom_Label::label_to_lower( 'courses' ) ),
					'help_text'  	=> 	esc_html__( 'Numer of items to show per page. 0 to display all.', 'learndash' ),
					'value' 		=> 	isset( $this->setting_option_values['progress_num'] ) ? $this->setting_option_values['registered_num'] : LEARNDASH_LMS_DEFAULT_WIDGET_PER_PAGE,
					'attrs'			=>	array(
											'step'	=>	1,
											'min'	=>	0
					)
				),
				'progress_num' => array(
					'name'  		=> 	'progress_num', 
					'type'  		=> 	'number',
					'label' 		=> 	sprintf( esc_html_x( '%s progress per page', 'placeholder: Course', 'learndash' ), LearnDash_Custom_Label::get_label( 'course' ) ),
					'help_text'  	=> 	esc_html__( 'Numer of items to show per page. 0 to display all.', 'learndash' ),
					'value' 		=> 	isset( $this->setting_option_values['progress_num'] ) ? $this->setting_option_values['progress_num'] : LEARNDASH_LMS_DEFAULT_WIDGET_PER_PAGE,
					'attrs'			=>	array(
											'step'	=>	1,
											'min'	=>	0
					)
				),
				'quiz_num' => array(
					'name'  		=> 	'quiz_num', 
					'type'  		=> 	'number',
					'label' 		=> 	sprintf( esc_html_x( '%s per page', 'placeholder: Quizzes', 'learndash' ), LearnDash_Custom_Label::get_label( 'quizzes' ) ),
					'help_text'  	=> 	esc_html__( 'Numer of items to show per page. 0 to display all.', 'learndash' ),
					'value' 		=> 	isset( $this->setting_option_values['quiz_num'] ) ? $this->setting_option_values['quiz_num'] : LEARNDASH_LMS_DEFAULT_WIDGET_PER_PAGE,
					'attrs'			=>	array(
											'step'	=>	1,
											'min'	=>	0
					)
				),
			);
		
			$this->setting_option_fields = apply_filters( 'learndash_settings_fields', $this->setting_option_fields, $this->settings_section_key );
			
			parent::load_settings_fields();
		}
	}
}
add_action( 'learndash_settings_sections_init', function() {
	LearnDash_Settings_Section_General_Per_Page::add_section_instance();
} );
