<?php 

/**
 * variant_page_builder_load_styles
 * 
 * Enqueues styles when Variant is active
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_styles(){  
	wp_enqueue_style('material-icons', 'https://fonts.googleapis.com/icon?family=Material+Icons' );    	
	wp_enqueue_style('variant-icons', VARIANT_PAGE_BUILDER_PATH .'css/variant-icons.css' );
	wp_enqueue_style('variant-social-icons', VARIANT_PAGE_BUILDER_PATH .'css/variant-social-icons.css' );
	wp_enqueue_style('variant-normalize', VARIANT_PAGE_BUILDER_PATH .'css/normalize.css' );
	wp_enqueue_style('variant-medium-editor', VARIANT_PAGE_BUILDER_PATH .'css/mediumEditor.css' );
	wp_enqueue_style('variant-style', VARIANT_PAGE_BUILDER_PATH .'css/style.css' );
	wp_enqueue_style('variant-wp-active', VARIANT_PAGE_BUILDER_PATH .'css/variant_wp-active.css' );
}

/**
 * variant_page_builder_load_scripts
 * 
 * Enqueues scripts when Variant is active
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_scripts(){   
	global $post;
	
	/**
	 * Enqueue Javascript files for Variant
	 */     
	wp_enqueue_script('variant-underscore', VARIANT_PAGE_BUILDER_PATH .'js/underscore.min.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-alter-class', VARIANT_PAGE_BUILDER_PATH .'js/alterClass.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-jQuery-ui', VARIANT_PAGE_BUILDER_PATH .'js/jquery-ui-1.10.4.custom.min.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-storage2', VARIANT_PAGE_BUILDER_PATH .'js/storage2.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-simple-modal', VARIANT_PAGE_BUILDER_PATH .'js/simpleModal.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-he', VARIANT_PAGE_BUILDER_PATH .'js/he.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-html-beautify', VARIANT_PAGE_BUILDER_PATH .'js/htmlBeautify.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-medium-editor', VARIANT_PAGE_BUILDER_PATH .'js/mediumEditor.min.js', array('jquery'), '1.0.0', true );
	wp_enqueue_script('variant-variant', VARIANT_PAGE_BUILDER_PATH .'js/variant.js', array('jquery'), false, true );
	wp_enqueue_script('variant-variant-wp', VARIANT_PAGE_BUILDER_PATH .'js/variant_wp.js', array('jquery'), false, true );
	wp_enqueue_script('variant-wp-shortcode-options', esc_url(trailingslashit( get_template_directory_uri() )) .'variant_config/shortcode_options.js', array('jquery'), false, true );
	wp_enqueue_media();
	
	/**
	 * localize script
	 */
	$script_data = variant_page_builder_create_wp_data($post->ID);
	wp_localize_script( 'variant-variant', 'wp_data', $script_data );
	
	/**
	 * Localize shortcode options
	 */
	$options_data = false;
	 
	/**
	 * Add post category selectors
	 */
	if( taxonomy_exists('category') ){
		
		$post_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'category'
		);
		$post_cats = get_categories( $post_args );
		$final_post_cats[] = array( 
			'text' => 'Show all categories',
			'value' => 'all' 
		);
	
		if( is_array($post_cats) ){
			foreach( $post_cats as $cat ){
				$final_post_cats[] = array(
					'text' => $cat->name,
					'value' => $cat->slug
				);
			}
		}
		
		$options_data['post_cats'] = json_encode($final_post_cats);
		
	}
	
	/**
	 * Add team category selectors
	 */
	if( taxonomy_exists('team_category') ){
		
		$team_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'team_category'
		);
		$team_cats = get_categories( $team_args );
		$final_team_cats[] = array( 
			'text' => 'Show all categories',
			'value' => 'all' 
		);
	
		if( is_array($team_cats) ){
			foreach( $team_cats as $cat ){
				$final_team_cats[] = array(
					'text' => $cat->name,
					'value' => $cat->slug
				);
			}
		}
		
		$options_data['team_cats'] = json_encode($final_team_cats);
		
	}
	
	/**
	 * Add career category selectors
	 */
	if( taxonomy_exists('career_category') ){
		
		$career_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'career_category'
		);
		$career_cats = get_categories( $career_args );
		$final_career_cats[] = array( 
			'text' => 'Show all categories',
			'value' => 'all' 
		);
	
		if( is_array($career_cats) ){
			foreach( $career_cats as $cat ){
				$final_career_cats[] = array(
					'text' => $cat->name,
					'value' => $cat->slug
				);
			}
		}
		
		$options_data['career_cats'] = json_encode($final_career_cats);
		
	}
	
	/**
	 * Add portfolio category selectors
	 */
	if( taxonomy_exists('portfolio_category') ){
	
		$portfolio_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'portfolio_category'
		);
		$portfolio_cats = get_categories( $portfolio_args );
		$final_portfolio_cats[] = array( 
			'text' => 'Show all categories',
			'value' => 'all' 
		);
	
		if( is_array($portfolio_cats) ){
			foreach( $portfolio_cats as $cat ){
				$final_portfolio_cats[] = array(
					'text' => $cat->name,
					'value' => $cat->slug
				);
			}
		}
		
		$options_data['portfolio_cats'] = json_encode($final_portfolio_cats);
		
	}
	
	/**
	 * Add testimonial category selectors
	 */
	if( taxonomy_exists('testimonial_category') ){
		
		$testimonial_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'testimonial_category'
		);
		$testimonial_cats = get_categories( $testimonial_args );
		$final_testimonial_cats[] = array( 
			'text' => 'Show all categories',
			'value' => 'all' 
		);
	
		if( is_array($testimonial_cats) ){
			foreach( $testimonial_cats as $cat ){
				$final_testimonial_cats[] = array(
					'text' => $cat->name,
					'value' => $cat->slug
				);
			}
		}
		
		$options_data['testimonial_cats'] = json_encode($final_testimonial_cats);
		
	}
	
	/**
	 * Add product category selectors
	 */
	if( taxonomy_exists('product_cat') ){
		
		$product_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'product_cat'
		);
		$product_cats = get_categories( $product_args );
		$final_product_cats[] = array( 
			'text' => 'Show all categories',
			'value' => 'all' 
		);
	
	
		if( is_array($product_cats) ){
			foreach( $product_cats as $cat ){
				$final_product_cats[] = array(
					'text' => $cat->name,
					'value' => $cat->slug
				);
			}
		}
		
		$options_data['product_cats'] = json_encode($final_product_cats);
		
	}
	
	/**
	 * Contact forms
	 */
	if( post_type_exists('wpcf7_contact_form') ){
		
		$args = array(
			'post_type' => 'wpcf7_contact_form',
			'posts_per_page' => -1
		);
		$form_options = get_posts( $args );
		$final_contact_forms[] = array( 
			'text' => 'No Contact Form',
			'value' => 'none' 
		);
		
		if( is_array($form_options) ){
			foreach( $form_options as $form_option ){
				$final_contact_forms[] = array(
					'text' => $form_option->post_title,
					'value' => '"'. $form_option->ID .'"'
				);
			}
		}
		
		$options_data['contact_forms'] = json_encode($final_contact_forms);
	
	}
	
	wp_localize_script( 'variant-wp-shortcode-options', 'options_data', $options_data );
	
}

/**
 * variant_page_builder_load_scripts_front
 * 
 * Enqueues styles when variant is NOT active
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_scripts_front(){      	
	wp_enqueue_style('variant-wp-inactive', VARIANT_PAGE_BUILDER_PATH .'css/variant_wp-inactive.css' );
}

/**
 * variant_page_builder_load_scripts_admin
 * 
 * Enqueues styles when we're in admin screens but there's no Variant data available
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_scripts_admin(){      	
	wp_enqueue_style('variant-wp-admin', VARIANT_PAGE_BUILDER_PATH .'css/variant_wp-admin.css' );
	wp_enqueue_script('variant-wp-admin', VARIANT_PAGE_BUILDER_PATH .'js/variant_wp-admin.js', array('jquery'), false, true  );
}

/**
 * variant_page_builder_load_scripts_admin_data_active
 * 
 * Enqueues styles when we're in admin screens && there's Variant data available
 * 
 * @blame tommus
 * @since 1.0.0
 */
function variant_page_builder_load_scripts_admin_data_active(){     
	global $post;
	 	
	wp_enqueue_style('variant-wp-admin-data-active', VARIANT_PAGE_BUILDER_PATH .'css/variant_wp-admin-data-active.css' );
	wp_enqueue_script('variant-wp-admin-data-active', VARIANT_PAGE_BUILDER_PATH .'js/variant_wp-admin-data-active.js', array('jquery'), false, true  );
	
	/**
	 * localize script
	 */
	$script_data = array( 
		'page_content' => do_shortcode(get_post_meta($post->ID, '_variant_page_builder_html', 1))
	);
	wp_localize_script( 'variant-wp-admin-data-active', 'wp_data', $script_data );
}