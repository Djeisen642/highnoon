<?php 

/**
 * Build theme options
 * Uses the Ebor_Options class found in the ebor-framework plugin
 * Panels are WP 4.0+!!!
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if( class_exists('Ebor_Options') ){
	
	/**
	 * Variables
	 */
	$variant_options = new Ebor_Options;
	$yesNo = array('yes' => 'Yes', 'no' => 'No');
	
	$variant_options->add_panel( 'Variant Page Builder: Settings', 999, 'All of the controls in this section directly relate to settings for Variant Page Builder');
	
	/**
	 * Autosave
	 */
	$variant_options->add_section('variant_autosave_section', 'Autosave Settings', 15, 'Variant Page Builder: Settings', 'These are the autosave settings for Variant Page Builder.<br /><br />Enter your autosave interval as number only. Default is 2, which is minutes. To turn autosave off, enter 0');
	
	$variant_options->add_setting('input', 'variant_page_builder_autosave_interval', 'Autosave Interval (Minutes)', 'variant_autosave_section', '2', 15);
	
	/**
	 * Dismiss
	 */
	$variant_options->add_section('variant_dismiss_section', 'Dismissed Items Settings', 35, 'Variant Page Builder: Settings', '');
	
	$variant_options->add_setting('select', 'variant_show_welcome_modal', 'Show welcome modal on Variant start?', 'variant_dismiss_section', 'yes', 35, $yesNo);
	$variant_options->add_setting('select', 'variant_page_builder_vc_notification', 'Show Variant + VC warning notification?', 'variant_dismiss_section', 'yes', 40, $yesNo);
	
}