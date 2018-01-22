<?php 

/**
 * Here is all the custom colours for the theme.
 * $handle is a reference to the handle used with wp_enqueue_style()
 */	
if (class_exists('WPLessPlugin')){

    $less = WPLessPlugin::getInstance();

    $less->setVariables(
    	array(
    		//logo
    		'logo-max-height'               => str_replace('px', '', get_option('stack_logo_size', '23px')) . 'px',
    		
    		//Colours
	        'color-bg-site'                 => get_option('background_color', '#FFFFFF'),
	        'color-primary'                 => get_option('color_primary', '#4A90E2'),
	        'color-primary-1'               => get_option('color_primary_1', '#31639C'),
	        'color-primary-2'               => get_option('color_primary_2', '#465773'),
	        'color-primary-3'               => get_option('color_primary_3', '#4A90E2'),
	        'color-dark'                    => get_option('color_dark', '#252525'),
	        'color-bg-secondary'            => get_option('color_bg_secondary', '#FAFAFA'),
	        'color-on-dark'                 => get_option('color_on_dark', '#FFFFFF'),
	        'color-body'                    => get_option('color_body', '#666666'),
	        'color-heading'                 => get_option('color_heading', '#252525'),
	        
	        //typography
	        'wordpress-base-size'           => str_replace('px', '', get_option('stack_base_size', '14')),
	        'wordpress-base-size-mobile'    => str_replace('px', '', get_option('stack_base_size_mobile', '13')),
	        'wordpress-base-size-px'        => str_replace('px', '', get_option('stack_base_size', '14')) . 'px',
	        'wordpress-base-size-mobile-px' => str_replace('px', '', get_option('stack_base_size_mobile', '13')) . 'px',
	        'h1-size'                       => get_option('stack_h1_size', '3.14285714285714em'),
	        'h2-size'                       => get_option('stack_h2_size', '2.35714285714286em'),
	        'h3-size'                       => get_option('stack_h3_size', '1.78571428571429em'),
	        'h4-size'                       => get_option('stack_h4_size', '1.35714285714286em'),
	        'h5-size'                       => get_option('stack_h5_size', '1em'),
	        'h6-size'                       => get_option('stack_h6_size', '0.85714285714286em'),
	        'base-line-height'              => get_option('stack_base_line_height', '1.85714285714286em'),
	        'h1-line-height'                => get_option('stack_h1_line_height', '1.31818181818182em'),
	        'h2-line-height'                => get_option('stack_h2_line_height', '1.36363636363636em'),
	        'h3-line-height'                => get_option('stack_h3_line_height', '1.5em'),
	        'h4-line-height'                => get_option('stack_h4_line_height', '1.68421052631579em'),
	        'h6-line-height'                => get_option('stack_h6_line_height', '2.16666666666667em'),
	        'p-lead-size'                   => get_option('stack_p_lead_size', '1.35714285714286em'),
	        'p-lead-line-height'            => get_option('stack_p_lead_line_height', '1.68421052631579em'),
	        'fine-print-size'               => get_option('stack_fine_size', '0.85714285714286em'),
	        'body-font'                     => get_option('stack_body_font', 'Open Sans'),
	        'heading-font'                  => get_option('stack_heading_font', 'Open Sans'),
	        'button-font'                   => get_option('stack_button_font', 'Open Sans'),
	        'nav-item-font'                 => get_option('stack_nav_font', 'Open Sans'),
	        'heading-weight'                => get_option('stack_heading_weight', '300'),
	        'body-font-weight'              => get_option('stack_body_weight', '400'),
	        'strong-font-weight'            => get_option('stack_strong_weight', '600'),
	        
	        //buttons
	        'button-radius'                 => str_replace('px', '', get_option('stack_button_border_radius', '6px')) . 'px',
	        'border-width'                  => str_replace('px', '', get_option('stack_button_border_width', '1px')) . 'px',
	        'button-font-weight'            => get_option('stack_button_font_weight', '700'),
	        
	        //Forms
	        'input-label-font-size'          => get_option('stack_label_font_size', '1.14285714285714em'),
	        'input-label-font-weight'        => get_option('stack_label_font_weight', '400'),
	        'input-border-radius'            => get_option('stack_input_border_radius', '6px'),
	        'input-placeholder-size'         => get_option('stack_input_font_size', '1.14285714285714em'),
	        'checkbox-border-radius'         => get_option('stack_checkbox_border_radius', '6px'),
	        'checkbox-padding'               => get_option('stack_checkbox_padding', '0'),
	        'radio-border-radius'            => get_option('stack_radio_border_radius', '50%'),
	        'radio-padding'                  => get_option('stack_radio_padding', '0')
	        
    	)
    );
    
}

/*
Register Fonts
*/
if(!( function_exists('ebor_fonts_url') )){
	function ebor_fonts_url(){
	    $font_url = '';

	    /*
	    	Translators: If there are characters in your language that are not supported
	   		by chosen font(s), translate this to 'off'. Do not translate into your own language.
	     */
	    if ( 'off' !== _x( 'on', 'Google font: on or off', 'stack' ) ) {
	        $font_url = add_query_arg( 'family', urlencode( str_replace('+', ' ', get_option('stack_google_font_string','Open Sans:200,300,400,400i,500,600,700|Merriweather:300,300i|Material Icons')) ), "//fonts.googleapis.com/css" );
	    }
	    return $font_url;
	}
}

/**
 * Ebor Load Scripts
 * Properly Enqueues Scripts & Styles for the theme
 * @since version 1.0
 * @author TommusRhodus
 */ 
if(!( function_exists('ebor_load_scripts') )){
	function ebor_load_scripts() {
	
		$theme_data = wp_get_theme();
		$version    = $theme_data->get( 'Version' );
		$extension  = ( class_exists('WPLessPlugin') ) ? '.less' : '.css';
			
		//Enqueue Styles
		wp_enqueue_style( 'ebor-google-font', ebor_fonts_url(), array(), $version );
		wp_enqueue_style( 'bootstrap', EBOR_THEME_DIRECTORY . 'style/css/bootstrap.css', array(), $version );
		wp_enqueue_style( 'ebor-icons', EBOR_THEME_DIRECTORY . 'style/css/icons.css', array(), $version );	
		wp_enqueue_style( 'ebor-plugins', EBOR_THEME_DIRECTORY . 'style/css/plugins.css', array(), $version );	
		wp_enqueue_style( 'ebor-theme', EBOR_THEME_DIRECTORY . 'style/css/theme' . $extension, array(), $version );	
		wp_enqueue_style( 'ebor-style', get_stylesheet_uri(), array(), $version );
		
		if( 'no' == get_option( 'stack_reduce_iconsmind', 'no' ) ){
			wp_enqueue_style( 'ebor-iconsmind', EBOR_THEME_DIRECTORY . 'style/css/iconsmind.css', array(), $version );	
		} else {
			wp_enqueue_style( 'ebor-iconsmind-light', EBOR_THEME_DIRECTORY . 'style/css/iconsmind-light.css', array(), $version );		
		}
		
		//Enqueue Scripts
		wp_enqueue_script( 'ebor-parallax', EBOR_THEME_DIRECTORY . 'style/js/parallax.js', array('jquery'), $version, true  );
		wp_enqueue_script( 'ebor-plugins', EBOR_THEME_DIRECTORY . 'style/js/plugins.js', array('jquery'), $version, true  );
		wp_enqueue_script( 'ebor-scripts-wp', EBOR_THEME_DIRECTORY . 'style/js/scripts_wp.js', array('jquery'), $version, true  );
		wp_enqueue_script( 'ebor-scripts', EBOR_THEME_DIRECTORY . 'style/js/scripts.js', array('jquery'), $version, true  );
		
		//Enqueue Comments
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
		
		$custom_css      = false;
		$nav_margin_top  = str_replace('px', '', get_option('stack_nav_margin_top', '0'));
		$nav_opacity     = get_option('stack_nav_opacity', '0.5');
		$sub_nav_opacity = get_option('stack_sub_nav_opacity', '0.75');
		$site_width      = get_option('stack_site_width');
		$dark            = get_option('color_dark', '#252525');
		$secondary       = get_option('color_bg_secondary', '#FAFAFA');
		
		$custom_css .= '
			input[type].btn--primary,
			.pos-fixed.bar--transparent.bg--primary,
			.ebor-cart-count {
				background: '. get_option('color_primary', '#4A90E2') .';	
			}
			.pos-fixed.bar--transparent.bg--secondary {
				background: '. $secondary .';
			}
			.pos-fixed.bar--transparent.bg--dark {
				background: '. $dark .';
			}
			.pos-fixed.bar--transparent.bg--primary-1 {
				background: '. get_option('color_primary_1', '#31639C') .';
			}
			.bg--white h1, .bg--white h2, .bg--white h3, .bg--white h4, .bg--white h5, .bg--white h6, .bg--white i, .mobile-header .cart-link {
			    color: '. get_option('color_heading', '#252525') .';
			}
			@media all and (max-width:767px) {
			    .bar.bg--dark.bar--mobile-sticky[data-scroll-class*="fixed"].pos-fixed,
			    .bar.bg--dark.bar--mobile-sticky[data-scroll-class*="fixed"]+.bar.pos-fixed {
			    	background: '. $dark .';
			    }
			    .bar.bg--secondary.bar--mobile-sticky[data-scroll-class*="fixed"].pos-fixed,
			    .bar.bg--secondary.bar--mobile-sticky[data-scroll-class*="fixed"]+.bar.pos-fixed {
			    	background: '. $secondary .';
			    }
			}
			.thumbnails-slider .thumbnail-trigger.active img {
				border: 1px solid '. get_option( 'color_primary', '#4A90E2' ) .';
			}
		';
		
		if(!( '0' == $nav_margin_top )){
			$custom_css .= '
				.col-md-11.col-sm-12.text-right.text-left-xs.text-left-sm {
					margin-top: '. (int) $nav_margin_top .'px;	
				}
			';
		}
		
		if(!( '0.5' == $nav_opacity )){
			$custom_css .= '
				.menu-horizontal > li:not(:hover) > a, .menu-horizontal > li:not(:hover) > span,
				.menu-horizontal > li:not(:hover) > .modal-instance > .modal-trigger {
				    opacity: '. $nav_opacity .'
				}
				.menu-vertical li:not(:hover):not(.dropdown--active) {
				    opacity: .75;
				}
			';	
		}
		
		if(!( '0.75' == $sub_nav_opacity )){
			$custom_css .= '
				.menu-vertical li:not(:hover):not(.dropdown--active) {
				    opacity: '. $sub_nav_opacity .';
				}
			';	
		}
		
		if( $site_width ){
			$custom_css .= '
				@media (min-width: 1200px) {
					.container {
						width: '. str_replace('px', '', $site_width) .'px;
						max-width: 100%;
					}
				}
			';	
		}
		
		wp_add_inline_style( 'ebor-style', $custom_css);

		/**
		 * localize script
		 */
		$script_data = array( 
			'access_token'       => get_option('instagram_token', 'replaceWithYourOwn'),
			'client_id'          => get_option('instagram_client', 'replaceWithYourOwn'),
			'typed_speed'        => (int) get_option('stack_typed_speed', '100'),
			'map_marker'         => get_option('stack_map_marker', EBOR_THEME_DIRECTORY .'style/img/mapmarker.png'),
			'map_marker_title'	 => get_option('stack_map_marker_title', 'Stack'),
			'lightbox_text'      => get_option('stack_lightbox_text', "Image %1 of %2")
		);
		wp_localize_script( 'ebor-scripts-wp', 'stack_data', $script_data );
		
	}
	add_action('wp_enqueue_scripts', 'ebor_load_scripts', 110);
}

/**
 * Ebor Load Admin Scripts
 * Properly Enqueues Scripts & Styles for the theme
 * 
 * @since version 1.0
 * @author TommusRhodus
 */
if(!( function_exists('ebor_admin_load_scripts') )){
	function ebor_admin_load_scripts(){
		wp_enqueue_style('ebor-theme-admin-css', EBOR_THEME_DIRECTORY . 'admin/ebor-theme-admin.css');
		wp_enqueue_style( 'ebor-iconsmind', EBOR_THEME_DIRECTORY . 'style/css/iconsmind.css' );	
		wp_enqueue_style( 'ebor-icons', EBOR_THEME_DIRECTORY . 'style/css/icons.css' );	
		wp_enqueue_script('ebor-theme-admin-js', EBOR_THEME_DIRECTORY . 'admin/ebor-theme-admin.js', array('jquery'), false, true);
	}
	add_action('admin_enqueue_scripts', 'ebor_admin_load_scripts', 200);
}