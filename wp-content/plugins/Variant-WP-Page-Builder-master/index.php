<?php

/*
Plugin Name: Variant Page Builder
Plugin URI: http://www.tommusrhodus.com/variant/
Description: Variant Page Builder
Version: 1.2.1
Author: TommusRhodus & MediumRare
Author URI: http://www.tommusrhodus.com/
*/

//Vars & definitions
define('VARIANT_PAGE_BUILDER_PATH', trailingslashit(plugin_dir_url( __FILE__ )));
define('VARIANT_PAGE_BUILDER_VERSION', '1-2-1');

//Load all functions which enqueue styles or scripts
require('admin/plugin_styles_scripts.php');

//Load all AJAX functions
require('admin/plugin_ajax.php');

//Load all functions which filter data
require('admin/plugin_filters.php');

//Load all generic callable functions
require('admin/plugin_functions.php');

//Create options panel
require('admin/plugin_options.php');

/**
 * variant_page_builder_init
 * 
 * Setup variant based on whether we're active or not.
 * 
 * @blame tommus
 * @since v1.0.0
 */
function variant_page_builder_init(){
	
	/**
	 * Check we're in a variant supported theme
	 */
	if(!( function_exists('ebor_check_variant_img_directory') )){
		return false;
	}
	
	/**
	 * Add content filters once we know we're in a Variant supported theme
	 */
	if( !is_admin() || is_admin() && defined( 'DOING_AJAX' ) && DOING_AJAX ){
		add_filter('the_content', 'variant_page_builder_content_filter', 20 );
	}
	
	add_filter('template_include', 'variant_page_builder_display_template', 999 );
	add_filter('body_class', 'variant_page_builder_front_end_body_classes', 10);
	
	$variant_active = false;
	$secure = variant_page_builder_check_user();
	
	if( is_admin() && function_exists('variant_page_builder_page_template') && function_exists('vc_set_as_theme') ){
		add_action( 'admin_notices', 'variant_visual_composer_notification' );
	}
	
	/**
	 * If ?variant-active is ?variant-active=true, set variant to active
	 * Includes a check that the user is logged in and is an editor or administrator
	 * 
	 * @toDo: Improve security here, test that Variant cannot be launched by anyone other than editor or admin
	 * @toDo: Tie into a plugin options page allowing the user to define who has access to variant
	 */
	if( isset($_GET['variant-active']) && 'true' == $_GET['variant-active'] && $secure ){
		$variant_active = true;
	}
	
	/**
	 * Redirect users who are not logged in, but are accessing a link with ?variant-active
	 * Redirects to wp-login.php and properly sets up the URL to return to this page
	 */
	if( isset($_GET['variant-active']) && !( $secure && is_user_logged_in() ) ){
		auth_redirect();
	}
	
	/**
	 * Quick and dirty delete functionality
	 * 
	 * @toDo: improve this, was added quickly and likely will have issues
	 */
	if( isset($_GET['delete-variant']) && isset($_GET['post']) && 'true' == $_GET['delete-variant'] && $secure && is_admin() ){
		variant_page_builder_delete_variant_data($_GET['post']);	
	}
	
	/**
	 * Run items if variant IS NOT active, but we're logged in and have rights to edit ($secure)
	 */
	if( !$variant_active && $secure ){
		add_action('wp_enqueue_scripts', 'variant_page_builder_load_scripts_front', 200);
		add_action('admin_bar_menu', 'variant_page_builder_add_toolbar_items', 100); 
	}
	
	/**
	 * Run items if variant IS active & we're logged in and rights to edit ($secure)
	 */
	if( $variant_active && $secure ){
		add_filter('body_class', 'variant_page_builder_body_classes', 10);
		add_filter('template_include', 'variant_page_builder_page_template', 999);
		add_action('wp_enqueue_scripts', 'variant_page_builder_load_styles', 109);
		add_action('wp_enqueue_scripts', 'variant_page_builder_load_scripts', 200);
		add_action('wp_footer', 'variant_page_builder_render_wysiwyg', 1);
		add_action('variant_print_sections', 'variant_page_builder_print_sections', 10);
		add_action('variant_print_json', 'variant_page_builder_print_json', 10);
	}
	
	/**
	 * Items to run only on wp-admin side
	 */
	if( is_admin() && $secure  ){
		add_action('admin_enqueue_scripts', 'variant_page_builder_load_scripts_admin', 200);
		add_action('add_meta_boxes', 'variant_page_builder_register_meta_boxes' );	
	}
	
	/**
	 * Items to run only if in wp-admin && variant post data exists
	 */
	if( is_admin() && isset($_GET['post']) && variant_page_builder_check_html($_GET['post']) && $secure  ){
		add_action('admin_enqueue_scripts', 'variant_page_builder_load_scripts_admin_data_active', 200);
	}
	
}
add_action('init', 'variant_page_builder_init', 100);

/**
 * variant_page_builder_page_template
 * 
 * If variant is active, make WordPress load page-variant-builder.php as the active page template
 *
 * @blame tommus
 * @since v1.0.0
 */
function variant_page_builder_page_template( $template ){
	$template = trailingslashit(dirname(__FILE__)) . "page-variant-builder.php";
	return $template;
}