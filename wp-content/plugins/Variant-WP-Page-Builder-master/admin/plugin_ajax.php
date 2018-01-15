<?php 

/**
 * Check if user is logged in
 */
function variant_page_builder_check_user_ajax(){
	$output = 'false';
	if( is_user_logged_in() && current_user_can('edit_others_pages') ){
		$output = 'true';	
	}
	wp_die($output);
}
add_action('wp_ajax_variant_page_builder_check_user_ajax', 'variant_page_builder_check_user_ajax');
add_action('wp_ajax_nopriv_variant_page_builder_check_user_ajax', 'variant_page_builder_check_user_ajax');

/**
 * Update an option name with a value, both given by $_POST data
 */
function variant_page_builder_update_option(){
	if( current_user_can('edit_others_pages') ){
		
		$name = $_POST['optionName'];
		if( 'variant_page_builder_vc_notification' == $name || 'variant_show_welcome_modal' == $name ){
			update_option($name, 'no');
		}
		
	}
}
add_action('wp_ajax_variant_page_builder_update_option', 'variant_page_builder_update_option');

function variant_page_builder_update_wp_data(){
	
	$output = 'Fail';
	
	if( isset($_POST['post_id']) ){
		$output = json_encode(variant_page_builder_create_wp_data($_POST['post_id']));
	}
	
	wp_die($output);	
}
add_action('wp_ajax_variant_page_builder_update_wp_data', 'variant_page_builder_update_wp_data');

/**
 * variant_page_builder_save_html
 * 
 * Saves HTML output from variant to the _variant_page_builder_html meta key
 * This is accessed via wp_ajax so that multiple saves can be made whilst editing a page in variant
 * 
 * @blame tommus
 * @since v1.0.0
 * 
 * @toDo: Version control?
 */
function variant_page_builder_save_html($html = false, $post_id = false) {
	
	$ID = $_POST['post_id'];
	if( !$post_id == false ){
		$ID = $post_id	;
	}
	
	$input = $_POST['variant_page_builder_html'];
	if( !$html == false ){
		$input = $html;
	}
	
	$find = array(
		' id=\"\"', 
		'\"\"]', 
		'\\', 
		'<p></p>', 
		'<p> </p>', 
		home_url('/')
	);
	
	$replace = array(
		' id=\"', 
		'\"]', 
		'', 
		'', 
		'', 
		'/'
	);
	
	//Quick string replace to fix double quotes in shortcodes, and make URLs relative.
	$html = str_replace( $find, $replace, $input);
	
	//Update the post meta.
	update_post_meta($ID, '_variant_page_builder_html', $html);
	
	//Kill AJAX and return.
	wp_die('variant_page_builder_save_html_success');
	
}
add_action('wp_ajax_variant_page_builder_save_html', 'variant_page_builder_save_html');

/**
 * variant_page_builder_save_variant
 * 
 * Saves variant output to the _variant_page_builder_variant meta key. Variant output is JSON based but is saved as plain text
 * Called back into variant via variant_page_builder_load_variant() which is called via wp_ajax and parses this data as JSON.
 * 
 * @blame tommus
 * @since v1.0.0
 */
function variant_page_builder_save_variant($variant = false, $post_id = false) {
	
	$ID = $_POST['post_id'];
	if( !$post_id == false ){
		$ID = $post_id;	
	}
	
	$input = $_POST['variant_page_builder_variant'];
	if( !$variant == false ){
		$input = $variant;
	}
	
	//Quick string replace to fix double quotes in shortcodes, and make URLs relative.
	$input = str_replace( home_url('/'), '/', $input );

	//Update the post meta.
	update_post_meta($ID, '_variant_page_builder_variant', wp_slash($input));
	
	//Trigger post update for revision system
	if( isset($_POST['save_type']) && 'manual' == $_POST['save_type'] ){
		wp_update_post(array('ID' => $ID));
	}
	
	//Kill AJAX and return.
	wp_die('variant_page_builder_save_variant_success');
	
}
add_action('wp_ajax_variant_page_builder_save_variant', 'variant_page_builder_save_variant');

/**
 * variant_page_builder_load_variant
 * 
 * Loads variant data from the _variant_page_builder_variant meta key when a page is loaded in Variant
 * This is accessed via wp_ajax so that it can be returned as parsable JSON
 * 
 * @blame tommus
 * @since v1.0.0
 */
function variant_page_builder_load_variant() {
	$response = '{"newpage": "'. get_the_title($_POST['post_id']) .'"}';
	$meta = get_post_meta($_POST['post_id'], '_variant_page_builder_variant', 1);
	
	if( isset($meta) && !( '' == $meta )){
		
		//This checks if the page data was added from demo data
		if( strpos($meta, '\\\\') == false ){
			$meta = addslashes($meta);
		}
		
		$content = json_decode(stripslashes($meta));
		
		$search = array(
			' id=""', 
			'""]', 
			'src="//',
			'href="//',
			'src="/', 
			'href="/', 
			'src="doNotModify//',
			'href="doNotModify//',
		);
		
		$replace = array(
			' id="', 
			'"]', 
			'src="doNotModify//',
			'href="doNotModify//',
			'src="'. home_url('/'), 
			'href="'. home_url('/'), 
			'src="//',
			'href="//'
		);
		
		//Remove script tags
		$content->masterHtml = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $content->masterHtml);
		
		$content->masterHtml = do_shortcode(str_replace($search, $replace, $content->masterHtml));
		
		$response = json_encode($content);
	}
	
	wp_die($response);
}
add_action('wp_ajax_variant_page_builder_load_variant', 'variant_page_builder_load_variant');

function variant_page_builder_create_new_page(){

	$my_post = array(
	  'post_title'    => wp_strip_all_tags( $_POST['title'] ),
	  'post_status'   => 'publish',
	  'post_author'   => 1,
	  'post_type'     => 'page'
	);
	 
	// Insert the post into the database
	$post_id = wp_insert_post( $my_post );
	
	if( isset($_POST['variant']) ){
		variant_page_builder_save_html($_POST['html']);
		variant_page_builder_save_variant($_POST['variant']);
	}
	
	$data = json_encode(variant_page_builder_create_wp_data($post_id));
	
	wp_die($data);
	
}
add_action('wp_ajax_variant_page_builder_create_new_page', 'variant_page_builder_create_new_page');

/**
 * variant_page_builder_load_header
 * 
 * Dynamically load the selected header option
 * Based on wp_ajax, fires on request and collects layout within output buffer
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_header(){
	ob_start();
	
	$layout = ( 'none' == $_POST['layout'] ) ? get_option('header_layout', 'standard') : $_POST['layout'];
	get_template_part('inc/layout-header', $layout);
	
	$output = ob_get_contents();
	ob_end_clean();
	
	wp_die($output);
}
add_action('wp_ajax_variant_page_builder_load_header', 'variant_page_builder_load_header');

/**
 * variant_page_builder_update_header
 * 
 * Updates the header override post meta
 * Called via wp_ajax
 * 
 * @string $_POST['post_id'] = The given post id of the current page being edited in Variant
 * @string $_POST['layout']  = The selected layout to switch to, see ebor_get_header_layouts()
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_update_header(){
	update_post_meta($_POST['post_id'], '_ebor_header_override', $_POST['layout']);
	wp_die();
}
add_action('wp_ajax_variant_page_builder_update_header', 'variant_page_builder_update_header');

/**
 * variant_page_builder_load_footer
 * 
 * Dynamically load the selected footer option
 * Based on wp_ajax, fires on request and collects layout within output buffer
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_footer() {
	ob_start();
	
	$layout = ( 'none' == $_POST['layout'] ) ? get_option('footer_layout', 'short-3') : $_POST['layout'];
	get_template_part('inc/layout-footer', $layout);
	
	$output = ob_get_contents();
	ob_end_clean();
	
	wp_die($output);
}
add_action('wp_ajax_variant_page_builder_load_footer', 'variant_page_builder_load_footer');

/**
 * variant_page_builder_update_footer
 * 
 * Updates the footer override post meta
 * Called via wp_ajax
 * 
 * @string $_POST['post_id'] = The given post id of the current page being edited in Variant
 * @string $_POST['layout']  = The selected layout to switch to, see ebor_get_header_layouts()
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_update_footer(){
	update_post_meta($_POST['post_id'], '_ebor_footer_override', $_POST['layout']);
	wp_die();
}
add_action('wp_ajax_variant_page_builder_update_footer', 'variant_page_builder_update_footer');

/**
 * variant_page_builder_render_shortcode
 * 
 * Renders the shortcode passed in via AJAX and returns the output HTML
 * 
 * @blame tommus
 * @since v1.0.0
 */
function variant_page_builder_render_shortcode() {
	$output = 'No Shortcode Supplied';
	
	if( isset($_POST['shortcode']) ){
		$output = do_shortcode(str_replace('""', '"', stripslashes($_POST['shortcode'])));
	}
	
	wp_die($output);
}
add_action('wp_ajax_variant_page_builder_render_shortcode', 'variant_page_builder_render_shortcode');