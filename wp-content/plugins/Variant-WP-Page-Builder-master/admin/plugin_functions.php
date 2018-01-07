<?php 

/**
 * Simple notification if both Visual Composer & Variant are installed at the same time.
 */
function variant_visual_composer_notification(){
	
	if( 'no' == get_option('variant_page_builder_vc_notification', 'yes') ){
		return false;	
	}
	
	echo '
		<div class="notice notice-warning is-dismissible variant-vc-dismiss">
	        <p><strong>Warning</strong>: Both Visual Composer & Variant Page Builder Installed, we recommend only using 1 as needed. Disable the plugin you are not using.</p>
	    </div>
    ';
    
}

/**
 * Check if user is logged in
 */
function variant_page_builder_check_user(){
	return ( is_user_logged_in() && current_user_can('edit_others_pages') ) ? true : false;
}

/**
 * Print out the sections (blocks) for this theme, pulls a template part
 * from the main theme.
 */
function variant_page_builder_print_sections(){
	if( function_exists('ebor_get_variant_sections') ){
		
		$sections = ebor_get_variant_sections();
		
		/**
		 * Pull each section out from the current theme
		 * See /variant_templates/ in current theme
		 * 
		 * Uses get_template_part() for child theme compatibility
		 */
		foreach( $sections as $section ){
			get_template_part('variant_templates/' . $section);
		}
	
	}
}

/**
 * Print out the JSON options for this theme, pulls a template part
 * from the main theme.
 */
function variant_page_builder_print_json(){
	get_template_part('variant_config/variant_options_json');
}

/**
 * variant_page_builder_list_pages
 * 
 * Build an array of all registered pages in WP
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_list_pages(){
	$pages_query_args = array(
	    'post_type'      => 'page',
	    'post_status'    => 'publish',
	    'posts_per_page' => - 1,
	);
	
	$pages_query = new WP_Query( $pages_query_args );
	
	$all_pages = array();
	
	foreach ( $pages_query->posts as $page ) {
		
		if( $meta = get_post_meta($page->ID, '_variant_page_builder_variant', 1) ){
			
			//Check if the page content came from demo data
			if( strpos($meta, '\\\\') == false ){
				continue;
			}
			
			$all_pages[] = array(
				'title' => $page->post_title,
				'variant_url'  => variant_page_builder_build_launch_url($page->ID),
				'url'          => get_permalink($page->ID),
				'relative_url' => str_replace(home_url(), '',  get_permalink($page->ID)),
				'ID'           => $page->ID
			);
		}
		
	}	
	
	return $all_pages;
}

function variant_page_builder_create_wp_data($post_id){
	
	$default = array(
		'text' => 'Use Global Option',
		'value' => 'none'
	);
	
	$header_layouts_final[] = $default;
	$footer_layouts_final[] = $default;
	
	if( function_exists('ebor_get_header_options') ){
		$header_layouts = ebor_get_header_options();
		foreach( $header_layouts as $key => $value ){
			$header_layouts_final[] = array(
				'text' => $value,
				'value' => $key
			);
		}
	}
	
	if( function_exists('ebor_get_footer_options') ){
		$footer_layouts = ebor_get_footer_options();
		foreach( $footer_layouts as $key => $value ){
			$footer_layouts_final[] = array(
				'text' => $value,
				'value' => $key
			);
		}
	}
	
	/**
	 * Build the section image URL
	 * 
	 * Start by having the placeholder image ready, then check if our key image exists
	 * and replace section_image_directory if it does.
	 */
	$section_image_directory = VARIANT_PAGE_BUILDER_PATH . 'img/placeholder.png?real=';
	
	if( function_exists('ebor_check_variant_section_img_directory') ){
		$section_image_directory = trailingslashit(VARIANT_PAGE_BUILDER_PATH . 'img/sections/' . ebor_check_variant_section_img_directory());
	}
	
	$options = array( 
		'site_url'          => home_url('/'),
		'plugin_url'        => VARIANT_PAGE_BUILDER_PATH,
		'ajax_url'          => str_replace(array('http:', 'https:'), '', admin_url('admin-ajax.php')),
		'post_id'           => $post_id,
		'all_pages'         => variant_page_builder_list_pages(),
		'header_layouts_default' => $default,
		'footer_layouts_default' => $default,
		'header_layouts'    => $header_layouts_final,
		'footer_layouts'    => $footer_layouts_final,
		'current_page'      => array(
			'post_id'           => $post_id,
			'url'           	=> get_permalink($post_id),
			'variant_url'   	=> variant_page_builder_build_launch_url($post_id),
			'post_title'    	=> get_the_title($post_id),
			'wp_edit_url'   	=> get_edit_post_link($post_id)
		),
		'config' 			=> array(
			'autosave_interval' => (int) get_option('variant_page_builder_autosave_interval', '2'), //minutes
			'section_img_url'   => $section_image_directory
		)
	);	
	
	if( function_exists('ebor_get_header_layout') ){
		$options['current_page']['header_layout'] = ebor_get_header_layout($post_id);
	} else {
		$options['current_page']['header_layout'] = 'default';
	}
	
	if( function_exists('ebor_get_footer_layout') ){
		$options['current_page']['footer_layout'] = ebor_get_footer_layout($post_id);
	}else {
		$options['current_page']['footer_layout'] = 'default';
	}
	
	return $options;
}

/**
 * Revision System
 * 
 * @see https://johnblackbourn.com/post-meta-revisions-wordpress
 */
function variant_page_builder_fields( $fields ) {
	$fields['_variant_page_builder_variant'] = 'Variant Page Builder';
	return $fields;
}
add_filter( '_wp_post_revision_fields', 'variant_page_builder_fields' );

function variant_page_builder_field( $value, $field ) {
	global $revision;
	return get_metadata( 'post', $revision->ID, $field, true );
}
add_filter( '_wp_post_revision_field_variant_page_builder_field', 'variant_page_builder_field', 10, 2 );

function variant_page_builder_restore_revision( $post_id, $revision_id ) {
	$post     = get_post( $post_id );
	$revision = get_post( $revision_id );
	$meta     = get_metadata( 'post', $revision->ID, '_variant_page_builder_variant', true );

	if ( false === $meta ){
		delete_post_meta( $post_id, '_variant_page_builder_variant' );
	} else {
		update_post_meta( $post_id, '_variant_page_builder_variant', wp_slash(addslashes($meta)) );
	}
}
add_action( 'wp_restore_post_revision', 'variant_page_builder_restore_revision', 10, 2 );

function variant_page_builder_save_post( $post_id, $post ) {
	if ( $parent_id = wp_is_post_revision( $post_id ) ) {

		$parent = get_post( $parent_id );
		$meta = get_post_meta( $parent->ID, '_variant_page_builder_variant', true );

		if ( false !== $meta )
			add_metadata( 'post', $post_id, '_variant_page_builder_variant', $meta );

	}
}
add_action( 'save_post', 'variant_page_builder_save_post', 10, 2 );

/**
 * variant_page_builder_delete_variant_data
 * 
 * Deletes all variant data for a given post ID
 * 
 * @blame tommus
 * @since v1.0.0
 */
function variant_page_builder_delete_variant_data($post_id){
	delete_post_meta($post_id, '_variant_page_builder_html');
	delete_post_meta($post_id, '_variant_page_builder_variant');
}

/**
 * variant_page_builder_check_meta
 * 
 * Checks if variant HTML data exists for a given post ID
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_check_meta($post_id){
	$meta = trim(get_post_meta($post_id, '_variant_page_builder_variant', 1));
	return ( $meta && !($meta == '') ) ? true : false;
}

function variant_page_builder_check_html($post_id){
	$meta = trim(get_post_meta($post_id, '_variant_page_builder_html', 1));
	return ( $meta && !($meta == '') ) ? true : false;
}

/**
 * variant_page_builder_build_launch_url
 * 
 * Builds the launch URL for variant on the current permalink
 * 
 * @blame tommus
 * @since v1.0.0
 * @var $post_id -> the ID of the post we're building the URL to edit for
 * @return string - the built URL to launch Variant
 */
function variant_page_builder_build_launch_url($post_id){
	return add_query_arg('variant-active', 'true', get_permalink($post_id));
}

/**
 * variant_page_builder_add_toolbar_items
 * 
 * Adds a "edit with Variant" link to the wp-admin toolbar
 * 
 * @blame tommus
 * @since v1.0.0
 * 
 * @toDo: replace the pencil icon in the toolbar with a small variant icon
 */
function variant_page_builder_add_toolbar_items($admin_bar){    
	global $post; 
	
	if( is_admin() && isset($_GET['post'] ) || !is_admin() && !is_archive() && !is_404() && !is_search() && !is_home() ){
		
		if( isset($post->ID) && !($post->ID == get_option('page_for_posts')) && 'publish' == get_post_status($post->ID) ){
			$admin_bar->add_menu( 
				array(         
					'id'    => 'edit-with-variant',         
					'title' => 'Launch Variant Page Builder',         
					'href'  => variant_page_builder_build_launch_url($post->ID)   
				)
			);
		}
		
	}
}

function variant_page_builder_add_row_items($actions, $page_object){
	
    $actions['variant_page_builder_link'] = '<a href="'. variant_page_builder_build_launch_url($page_object->ID)  .'">Edit With Variant Page Builder</a>';
    
   return $actions;
}
add_filter('page_row_actions', 'variant_page_builder_add_row_items', 10, 2);

/**
 * variant_page_builder_register_meta_boxes
 * 
 * Register the variant meta box, is transformed via JS to cover the regular WYSIWYG editor
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_register_meta_boxes() {
	$post_types = array('page', 'post', 'portfolio', 'team', 'career', 'product');
    add_meta_box( 'variant-meta-box', esc_html__( 'Variant Page Builder Settings', 'variant' ), 'variant_page_builder_display_meta_boxes', $post_types, 'normal', 'high' );
}
 
/**
 * variant_page_builder_display_meta_boxes
 * 
 * Displays a "delete variant content" button for the page, removes all variant data, bringing this back to a normal page
 *
 * @param WP_Post $post Current post object.
 * @blame tommus
 * @since 1.0.0
 * 
 * @toDo: make this conditional, make it safer
 */
function variant_page_builder_display_meta_boxes( $post ) {
	
	if( !( isset($post->ID) ) || $post->ID == get_option('page_for_posts') ){
		return false;	
	}
	
	$url = add_query_arg('delete-variant', 'true');
	
	if( 'publish' == get_post_status($post->ID) ){
		$output = '
			<div id="variant-launch">
				<a href="'. variant_page_builder_build_launch_url($post->ID) .'" class="mrv-button">
					<span><img alt="Variant Logo" src="'. VARIANT_PAGE_BUILDER_PATH .'img/vlogo-small.png" />Launch Variant Page Builder</span>
				</a>
		    </div>
		';
	} else {
		$output = '
			<div id="variant-launch">
				<a href="#" class="mrv-button disabled">
					<span><img alt="Variant Logo" src="'. VARIANT_PAGE_BUILDER_PATH .'img/vlogo-small.png" />Page Must Be Published To Launch Variant Page Builder</span>
				</a>
		    </div>
		';	
	}
	
	$output .= '<img alt="Variant HTML Page Builder" src="'. VARIANT_PAGE_BUILDER_PATH .'img/vlogo.png">';
	$output .= '<p class="lead">This page has been edited using<br />Variant Page Builder.</p>';
	$output .= '<p>When a page is edited by Variant, the regular page content is locked.<br />Use Variant to edit the page, or delete Variant content to unlock the regular content.</p>';
	$output .= '<div class="btn-group"><a href="'. variant_page_builder_build_launch_url($post->ID) .'" class="mrv-button"><span>Launch Variant Page Builder</span></a>';
    $output .= '<a href="'. $url .'" class="mrv-button" onclick="return confirm(\'Do you really want to delete all variant content for this page? This action is irreversible.\');"><span>Delete Variant Content</span></a></div>';
    
    echo $output;
}

/**
 * variant_page_builder_demo_img
 * 
 * Echos image URL for use in the sections as building.
 * Run via function rather than static URL so we can update this as needed without running find/replace over all sections.
 * Whether the image URLs exist is checked by jQuery, and a replacement URL is given if no image exists.
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_demo_img($img){
	
	/**
	 * Set a definition so that this only runs once
	 */
	if( function_exists('ebor_check_variant_img_directory') && !defined('VARIANT_PAGE_BUILDER_DEMO_IMAGES_EXIST') ){
		//First check variant_page_builder_key_[THEMENAME].png exists
		$demo_images_exist = ebor_check_variant_img_directory();
		define('VARIANT_PAGE_BUILDER_DEMO_IMAGES_EXIST', $demo_images_exist);
	}
	
	/**
	 * Set a definition so that this only runs once
	 */
	if( function_exists('ebor_get_variant_img_directory') && 'true' == VARIANT_PAGE_BUILDER_DEMO_IMAGES_EXIST && !defined('VARIANT_PAGE_BUILDER_DEMO_IMAGES_PATH') ){
		define('VARIANT_PAGE_BUILDER_DEMO_IMAGES_PATH', ebor_get_variant_img_directory());
	}
	
	if( 'true' == VARIANT_PAGE_BUILDER_DEMO_IMAGES_EXIST ){
		echo VARIANT_PAGE_BUILDER_DEMO_IMAGES_PATH . $img;
	} else {
		echo VARIANT_PAGE_BUILDER_PATH . 'img/placeholder.png';	
	}

}

/**
 * variant_page_builder_render_wysiwyg
 * 
 * Renders the WYSIWYG from the wp_footer function
 * Reduces the amount of changes needed to be made to page-variant-builder.php
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_render_wysiwyg(){
	ob_start();
?>
	
	<div class="wp-editor-overlay">
		<div class="wp-editor-wrapper">
			<?php
				wp_editor( 
					'', //content
					'mycustomeditor', //ID
					array( 
						'tinymce' => array( 
				            'content_css' => get_template_directory_uri() . '/style/css/editor-style.css' //CSS
				        ) 
				    ) 
				);
			?>
			<div class="wisywig-action-buttons">
				<a href="#" class="vhs wysiwyg-discard-changes"><span>Discard Changes</span></a>
				<a href="#" class="vhs wysiwyg-save-changes"><span>Save Changes</span></a>
			</div>
		</div>
	</div>
	
	<?php if( 'yes' == get_option('variant_show_welcome_modal', 'yes') ) : ?>
		<div class="variant-welcome-modal vin">
			<h3>Welcome to Variant</h3>
			<p class="lead">Begin adding content to the page using the sidebar to the left.</p>
			<p>If this is your first time using Variant Page Builder for WordPress, be sure to check our <a href="http://tommusdemos.wpengine.com/theme-assets/variant/README.html" target="_blank">written documentation</a>, and watch our introductory video to get yourself acquainted.</p>
			<iframe width="560" height="215" src="https://www.youtube.com/embed/AjTjq9zPmW0" frameborder="0" allowfullscreen></iframe>
			<div class="vjp">
				<a href="#" class="vhs vex">Don't Show This Again</a>
			</div>
		</div>
	<?php endif; ?>
	
	<?php 
		$login_url = wp_login_url();
	    $current_domain = ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'];
	    $same_domain = ( strpos( $login_url, $current_domain ) === 0 );
	    $same_domain = apply_filters( 'wp_auth_check_same_domain', $same_domain ); 
	    $class = ( $same_domain ) ? 'no-padding' : false;
	?>
	
	<div class="variant-login-modal vin <?php echo $class; ?>">
		<?php if( $same_domain ) : ?>
			<iframe id="wp-auth-check-frame" frameborder="0" title="Session expired" src="<?php echo untrailingslashit($login_url); ?>?interim-login=1"></iframe>
		<?php else : ?>
			<p><b class="wp-auth-fallback-expired" tabindex="0"><?php _e('Session expired'); ?></b></p>
			<p><a href="<?php echo esc_url( $login_url ); ?>" target="_blank"><?php _e('Please log in again.'); ?></a>
			<?php _e('The login page will open in a new window. After logging in you can close it and return to this page.'); ?></p>
			<div class="vjp">
				<a href="#" class="vhs vex">Close</a>
			</div>
		<?php endif; ?>
	</div>
	
	<textarea id="link-edit-area"></textarea>
	
<?php
	$output = ob_get_contents();
	ob_end_clean();
	
	echo $output;
}