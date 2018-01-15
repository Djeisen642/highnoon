<?php 

/**
 * Filter to add custom class to wp_list_categories()
 * 
 * @since 1.1.9
 * @author tommusrhodus
 */
function ebor_add_category_parent_css($css_classes, $category, $depth, $args){
    $css_classes[] = 'js-no-action';
    return $css_classes;
}
add_filter( 'category_css_class', 'ebor_add_category_parent_css', 10, 4);

/**
 * Filter menu item classes into wp_get_nav_menu_items() array return
 */
function ebor_nav_menu_classes($items, $menu, $args) {
    _wp_menu_item_classes_by_context($items);
    return $items;
}
add_filter( 'wp_get_nav_menu_items', 'ebor_nav_menu_classes', 10, 3 );

if(!( function_exists('ebor_excerpt_length') )){
	function ebor_excerpt_length( $length ) {
		return 22;
	}
	add_filter( 'excerpt_length', 'ebor_excerpt_length', 999 );
}

if(!( function_exists('ebor_excerpt_more') )){
	function ebor_excerpt_more( $more ) {
		return '...';
	}
	add_filter('excerpt_more', 'ebor_excerpt_more');
}

/**
 * Custom Sensei Wrapper Markup for Stack
 * 
 * @since 1.0.9
 * @author tommusrhodus
 */
function ebor_sensei_wrapper_start() {
	echo '<div class="container"><div id="content" class="col-md-8" role="main">';
}
add_action('sensei_before_main_content', 'ebor_sensei_wrapper_start', 10);

function ebor_sensei_wrapper_end() {
	echo '</div>';
	get_sidebar();
}
add_action('sensei_after_main_content', 'ebor_sensei_wrapper_end', 10);

function ebor_in_page_navigator(){
	global $post;
	if( isset( $post->ID ) && 'yes' == get_post_meta($post->ID, '_ebor_page_navigator', 1) ){
		echo '<section class="page-navigator"><ul></ul></section>';
	}
}
add_filter('wp_footer', 'ebor_in_page_navigator');

if(!( function_exists('ebor_filter_contact_form_7') )){ 
	function ebor_filter_contact_form_7( $output, $tag ) {
		
		//IF we're not dealing with contact form 7
		if ( 'contact-form-7' !== $tag ) {
			return $output;
		}
		
		$search = array(
			'<select',
			'</select>',
			'<input type="radio"',
			'<input type="checkbox"',
			'"><label></label><input type="radio"',
			'"><label></label><input type="checkbox"',
			'<input type="submit"'
		);
		
		$replace = array(
			'<div class="input-select"><select',
			'</select></div>',
			'<label></label><input type="radio"',
			'<label></label><input type="checkbox"',
			' input-radio"><label></label><input type="radio"',
			' input-checkbox"><label></label><input type="checkbox"',
			'<button type="submit"'
		);
		
		$output = str_replace($search, $replace, $output);
		
		//If we ARE dealing with contact form 7
		return $output;
	}
	add_filter('do_shortcode_tag', 'ebor_filter_contact_form_7', 999, 2);
}

if(!( function_exists('ebor_add_body_classes') )){ 
	function ebor_add_body_classes($classes) {
		global $post;

        //buttons layout
        if(!( isset($post->ID) )){
        	
        	$buttons = get_option('stack_rounded', 'stack--rounded');
        	
        } else {
        	
            $buttons = get_post_meta($post->ID, '_ebor_buttons_override', 1);
            if( '' == $buttons || false == $buttons || 'none' == $buttons ){
            	$buttons = get_option('stack_rounded', 'stack--rounded');
            }
        
        }
        
        //Boxed layout
        if(!( isset($post->ID) )){
        	
        	$boxed = get_option('stack_boxed', 'normal-layout');
        	
        } else {
        	
	        $boxed = get_post_meta($post->ID, '_ebor_boxed_override', 1);
	        if( '' == $boxed || false == $boxed || 'none' == $boxed ){
	        	$boxed = get_option('stack_boxed', 'normal-layout');
	        }
        
        }
        
        $classes[] = $buttons;
        $classes[] = $boxed;
        
        $method = get_option('stack_dropdown_method', 'dropdowns--click');
        if(!( 'dropdowns--click' == $method )){
        	$classes[] = $method;
        }
        
        $filters = get_option('stack_filters_position', 'filters-dropdown');
        if(!( 'filters-dropdown' == $filters )){
        	$classes[] = $filters;
        }
        
        $classes[] = get_option('stack_active_tabs', 'active-tabs');
        $classes[] = get_option('stack_active_accordions', 'active-accordions');
        
        $parallax_mobile = get_option('stack_disable_parallax_mobile', 'parallax-enable-mobile');
        if(!( 'parallax-disable-mobile' == $parallax_mobile )){
        	$classes[] = $parallax_mobile;
        }
        
        return $classes;
        
	}
	add_filter('body_class', 'ebor_add_body_classes');
}
	
if(!( function_exists('ebor_tag_cloud') )){	
	function ebor_tag_cloud($string){
	   return preg_replace("/style='font-size:.+pt;'/", '', $string);
	}
	add_filter('wp_generate_tag_cloud', 'ebor_tag_cloud',10,1);
}

// stop wp removing span tags
if(!( function_exists('ebor_tinymce_fix') )){
	function ebor_tinymce_fix( $init ){
	    $init['extended_valid_elements'] = 'span[*]';
	    return $init;
	}
	add_filter('tiny_mce_before_init', 'ebor_tinymce_fix');
}

if(!( function_exists('ebor_disable_wp_emojicons') )){
	function ebor_disable_wp_emojicons() {
		// all actions related to emojis
		remove_action( 'admin_print_styles', 'print_emoji_styles' );
		remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
		remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
		remove_action( 'wp_print_styles', 'print_emoji_styles' );
		remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email' );
		remove_filter( 'the_content_feed', 'wp_staticize_emoji' );
		remove_filter( 'comment_text_rss', 'wp_staticize_emoji' );
	}
	add_action( 'init', 'ebor_disable_wp_emojicons' );
}

if(!( function_exists('ebor_single_oembed_result') )){
	function ebor_single_oembed_result($html, $url, $args) {
		global $post;

		if( has_post_thumbnail() && strpos($html, 'youtube') !== false || has_post_thumbnail() && strpos($html, 'vimeo') !== false ){
			
			//HTML returns removed to avoid wpautop
			$html = '<div class="video-cover border--round"><div class="background-image-holder">'. wp_get_attachment_image(get_post_thumbnail_id(), 'large') .'</div><div class="video-play-icon"></div>'. $html .'</div><!--end video cover-->';
			
		}
		
	    return $html;
	}
	add_filter('embed_oembed_html','ebor_single_oembed_result', 10, 3);
}

if( class_exists('OCDI_Plugin') ){
	
	function ebor_ocdi_plugin_intro_text( $default_text ) {
	    $default_text .= '
	    	<div class="ocdi__intro-text">
	    		<h3>Read this before importing demo data!</h3>
	    		<p>We have prepared full written & video documentation to make your life with Stack much more easy. It is worth spending a few minutes with this to familiarise yourself with the theme & its plugins before jumping in with your demo data, so <a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">please read the theme documentation</a> before importing the demo data.</p>
	    		<hr />
	    	</div>
	    ';
	
	    return $default_text;
	}
	add_filter( 'pt-ocdi/plugin_intro_text', 'ebor_ocdi_plugin_intro_text' );
	
	function ebor_ocdi_confirmation_dialog_options ( $options ) {
	    return array_merge( $options, array(
	        'width'       => 600,
	        'dialogClass' => 'wp-dialog',
	        'resizable'   => false,
	        'height'      => 'auto',
	        'modal'       => true,
	    ) );
	}
	add_filter( 'pt-ocdi/confirmation_dialog_options', 'ebor_ocdi_confirmation_dialog_options', 10, 1 );
	
	//Setup basic demo import
	function ebor_import_files() {
		
		$import_notice_vc = '
			<h3>Ready to Import Stack + Visual Composer Demo Data</h3>
			<p>Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.</p>
			<p>Since you\'re importing Stack + Visual Composer Demo Data, please ensure Visual Composer is enabled in "plugins" and Variant Page Builder is either uninstalled, or disabled.</p>
			<p><a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">Please read the theme documentation.</a></p>
		';
		
		$import_notice_variant = '
			<h3>Ready to Import Stack + Variant Demo Data</h3>
			<p>Please ensure all required plugins in "appearance => install plugins" are installed before running this demo importer.</p>
			<p>Since you\'re importing Stack + Variant Demo Data, please ensure Variant Page Builder is enabled in "plugins" and Visual Composer is either uninstalled, or disabled.</p>
			<p><a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">Please read the theme documentation.</a></p>
		';
		
		$import_notice_variant_images = '
			<h3>Ready to Import Variant Demo Images ONLY</h3>
			<p>This will import the required demo images for Variant Page Builder only. This will not add any page or post data.</p>
			<p><a href="https://tommusrhodus.ticksy.com/articles/100007405?print" target="_blank">Please read the theme documentation.</a></p>
		';
				
	    return array(
	        array(
	            'import_file_name'             => 'Stack + Visual Composer Demo Data',
	            'import_file_url'              => 'http://tommusdemos.wpengine.com/theme-assets/stack/demo-data-vc.xml',
	            'import_widget_file_url'       => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-widgets.wie',
	            'import_customizer_file_url'   => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-options.dat',
	            'import_preview_image_url'     => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-vc.png',
	            'import_notice'                => $import_notice_vc,
	        ),
	        array(
	            'import_file_name'             => 'Stack + Variant Demo Data',
	            'import_file_url'              => 'http://tommusdemos.wpengine.com/theme-assets/stack/demo-data-variant.xml',
	            'import_widget_file_url'       => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-widgets.wie',
	            'import_customizer_file_url'   => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-options.dat',
	            'import_preview_image_url'     => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-variant.png',
	            'import_notice'                => $import_notice_variant,
	        ),
	        array(
	            'import_file_name'             => 'Variant Demo Images ONLY',
	            'import_file_url'              => 'http://tommusdemos.wpengine.com/theme-assets/stack/demo-data-variant-images.xml',
	            'import_preview_image_url'     => 'http://tommusdemos.wpengine.com/theme-assets/stack/stack-variant.png',
	            'import_notice'                => $import_notice_variant_images,
	        )
	    );
	    
	}
	add_filter( 'pt-ocdi/import_files', 'ebor_import_files' );
	
	//Setup front page and menus
	function ebor_after_import_setup() {
		
	    // Assign menus to their locations.
	    $main_menu = get_term_by( 'name', 'Standard Navigation', 'nav_menu' );
	    $side_menu = get_term_by( 'name', 'Sidebar Menu', 'nav_menu' );
	    $footer_menu = get_term_by( 'name', 'Footer Menu', 'nav_menu' );
	    $top_bar_menu = get_term_by( 'name', 'Top Bar', 'nav_menu' );
	    $vertical_menu = get_term_by( 'name', 'Vertical Menu', 'nav_menu' );
	
	    set_theme_mod( 'nav_menu_locations', array(
	            'primary'  => $main_menu->term_id,
	            'sidebar'  => $side_menu->term_id,
	            'footer'   => $footer_menu->term_id,
	            'top-bar'  => $top_bar_menu->term_id,
	            'vertical' => $vertical_menu->term_id,
	        )
	    );
	
	    // Assign front page and posts page (blog page).
	    $front_page_id = get_page_by_title( 'Stack - Home' );
	    $blog_page_id  = get_page_by_title( 'Blog' );
	
	    update_option( 'show_on_front', 'page' );
	    update_option( 'page_on_front', $front_page_id->ID );
	    update_option( 'page_for_posts', $blog_page_id->ID );
	
	}
	add_action( 'pt-ocdi/after_import', 'ebor_after_import_setup' );
	
	//Remove Branding
	add_filter( 'pt-ocdi/disable_pt_branding', '__return_true' );
	
	//Save customize options
	add_action( 'pt-ocdi/enable_wp_customize_save_hooks', '__return_true' );
	
	//Stop thumbnail generation
	add_filter( 'pt-ocdi/regenerate_thumbnails_in_content_import', '__return_false' );
	
}

/**
 * Add additional settings to gallery shortcode
 */
if(!( function_exists('ebor_add_gallery_settings') )){ 
	function ebor_add_gallery_settings(){
?>
	
		<script type="text/html" id="tmpl-stack-gallery-setting">
			<h3>Stack Theme Gallery Settings</h3>
			<label class="setting">
				<span><?php esc_html_e('Gallery Layout', 'stack'); ?></span>
				<select data-setting="layout">
					<option value="default">Default Layout</option>     
					<option value="slider">Stack Slider</option>
					<option value="carousel">Stack Carousel</option>
					<option value="lightbox">Stack Lightbox Gallery</option>
					<option value="masonry">Stack Masonry Gallery</option>
				</select>
			</label>
		</script>
	
		<script>
			jQuery(document).ready(function(){
				jQuery.extend(wp.media.gallery.defaults, { layout: 'default' });
				
				wp.media.view.Settings.Gallery = wp.media.view.Settings.Gallery.extend({
					template: function(view){
					  return wp.media.template('gallery-settings')(view)
					       + wp.media.template('stack-gallery-setting')(view);
					}
				});
			});
		</script>
	  
<?php
	}
	add_action('print_media_templates', 'ebor_add_gallery_settings');
}


/**
 * Custom gallery shortcode
 *
 * Filters the standard WordPress gallery shortcode.
 *
 * @since 1.0.0
 */
if(!( function_exists('ebor_post_gallery') )){
	function ebor_post_gallery( $output, $attr) {
		
		global $post, $wp_locale;
	
	    static $instance = 0;
	    $instance++;
	
	    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	    if ( isset( $attr['orderby'] ) ) {
	        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
	        if ( !$attr['orderby'] )
	            unset( $attr['orderby'] );
	    }
	
	    extract(shortcode_atts(array(
	        'order'      => 'ASC',
	        'orderby'    => 'menu_order ID',
	        'id'         => $post->ID,
	        'itemtag'    => 'div',
	        'icontag'    => 'dt',
	        'captiontag' => 'dd',
	        'columns'    => 3,
	        'size'       => 'large',
	        'include'    => '',
	        'exclude'    => '',
	        'layout'     => ''
	    ), $attr));
	
	    $id = intval($id);
	    if ( 'RAND' == $order )
	        $orderby = 'none';
	
	    if ( !empty($include) ) {
	        $include = preg_replace( '/[^0-9,]+/', '', $include );
	        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	
	        $attachments = array();
	        foreach ( $_attachments as $key => $val ) {
	            $attachments[$val->ID] = $_attachments[$key];
	        }
	    } elseif ( !empty($exclude) ) {
	        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
	        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	    } else {
	        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	    }
	
	    if ( empty($attachments) )
	        return '';
	
	    if ( is_feed() ) {
	        $output = "\n";
	        foreach ( $attachments as $att_id => $attachment )
	            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
	        return $output;
	    }

	    /**
	     * Return Slider Layout
	     */
	    if( $layout == 'slider' ){

	    	$output = '<section class="slider extend-width" data-arrows="true" data-paging="true"><ul class="slides">';
	    	
    		foreach ( $attachments as $id => $attachment ) {
    		    $output .= '<li>'. wp_get_attachment_image($id, 'large') .'</li>';
    		} 
	    		
	    	$output .= '</ul></section>';
	    	
	    	return $output;
	    	
	    }
	    
	    if( 'masonry' == $layout ){
	    	
	    	$rand = wp_rand(0, 10000);
	    	$output = '<div class="row"><div class="masonry"><div class="masonry__container">';
	    	
	    	foreach ( $attachments as $id => $attachment ) {
	    		
	    		$src = wp_get_attachment_image_src($id, 'full');
	    		
	    		$output .= '
	    			<div class="col-md-4 col-sm-6 col-xs-12 masonry__item">
	    			    <a href="'. $src[0] .'" data-lightbox="gallery-'. $rand .'">
	    			    	'. wp_get_attachment_image($id, 'large') .'   
	    			    </a>
	    			</div>
	    		';

	    	}
	    	
	    	$output .= '<div></div></div>';
	    	
	    	return $output;
	    	
	    }
	    
	    if( 'carousel' == $layout ){
	    	
	    	$rand = wp_rand(0, 10000);
	    	
	    	$output = '<div class="slider slider--columns" data-arrows="true" data-paging="true"><ul class="slides">';
	    	
	    	foreach ( $attachments as $id => $attachment ) {  
	    	
	    		$src = wp_get_attachment_image_src($id, 'full');
	    	
		    	$output .= '
			        <li class="col-sm-4 col-xs-6">
			            <a href="'. $src[0] .'" data-lightbox="Gallery '. $rand .'">
			                '. wp_get_attachment_image($id, 'large') .'
			            </a>
			        </li>
		    	';
		    	
	    	}

	    	$output .= '</ul></div>';
	    	
	    	return $output;
	    	
	    }
	    
	    if( 'lightbox' == $layout ){
	    	
	    	$i = 0;
	    	$rand = wp_rand(0, 10000);
	    	
	    	$output = '<div class="masonry masonry--gapless extend-width"><div class="masonry__container masonry--active">';
	    	
	    	foreach ( $attachments as $id => $attachment ) {
	    		$i++;
	    		
	    		if( 4 == $i || 1 == $i ){
	    			$output .= '<div class="masonry__item col-md-4 col-sm-6 col-xs-12">';
	    		} else {
	    			$output .= '<div class="masonry__item col-md-8 col-sm-6 col-xs-12">';
	    		}
	    		
	    		if( 4 == $i ){
	    			$i = 0;	
	    		}
	    		
	    		$src = wp_get_attachment_image_src($id, 'full');
	    		
	    		$output .= '
		    		<div class="project-thumb hover-element height-30">
		    		    <a href="'. $src[0] .'" data-lightbox="gallery-'. $rand .'">
		    		    
		    		        <div class="hover-element__initial">
		    		            <div class="background-image-holder">
		    		                '. wp_get_attachment_image($id, 'large') .'
		    		            </div>
		    		        </div>
		    		        
		    		    </a>
		    		</div>
	    		';
	    		
	    		$output .= '</div>';
	    	}
	    	
	    	$output .= '</div></div>';
	    	
	    	return $output;
	    	
	    }
	    
	}
	add_filter( 'post_gallery', 'ebor_post_gallery', 10, 2 );
}