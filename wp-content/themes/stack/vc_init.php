<?php 

if(!( function_exists('ebor_custom_css_classes_for_vc_row_and_vc_column') )){
	function ebor_custom_css_classes_for_vc_row_and_vc_column( $class_string, $tag ) {
		if ( $tag == 'vc_column' || $tag == 'vc_column_inner' ) {
			$class_string = preg_replace( '/vc_col-sm/', 'col-sm', $class_string );
		}
		return $class_string;
	}
	add_filter( 'vc_shortcodes_css_class', 'ebor_custom_css_classes_for_vc_row_and_vc_column', 10, 2 );
}

/**
 * Redirect page template if vc_row shortcode is found in the page.
 * This lets us use a dedicated page template for Visual Composer pages
 * without the need for on page checks, or custom page templates.
 * 
 * It's buyer-proof basically.
 */
if(!( function_exists('ebor_vc_page_template') )){
	function ebor_vc_page_template( $template ){
		global $post;
		
		if( is_archive() || is_404() || is_home() || !( isset($post->post_content) ) || is_search() )
			return $template;
			
		if( 'no' == get_option('stack_vc_redirect_' . $post->post_type) )
			return $template;
			
		if( has_shortcode($post->post_content, 'vc_row') ){
			
			$new_template = locate_template( array( 'page_visual_composer.php' ) );
			
			if (!( '' == $new_template )){
				return $new_template;
			}
			
		}
		
		return $template;
	}
	add_filter( 'template_include', 'ebor_vc_page_template', 100 );
}

/**
 * Add additional functions to certain blocks.
 * vc_map runs before custom post types and taxonomies are created, so this function is used
 * to add custom taxonomy selectors to VC blocks, a little annoying, but works perfectly.
 */
if(!( function_exists('ebor_vc_add_att') )){
	function ebor_vc_add_attr(){

		$attributes = array(
			"type" => "textfield",
			"heading" => esc_html__("Tab Title", 'stack'),
			"param_name" => "ebor_title"
		);
		vc_add_param('vc_row_inner', $attributes);
		
		$attributes = array(
			"type" => "textfield",
			"heading" => esc_html__("Row Type", 'stack'),
			"param_name" => "ebor_type"
		);
		vc_add_param('vc_row_inner', $attributes);
		
		$attributes = array(
			"type" => "",
			"heading" => "",
			"param_name" => "ebor_docs",
			"description" => 'For help on how to use background classes, please see the <a target="_blank" href="https://tommusrhodus.ticksy.com/article/9990/">Feature Documentation</a>',
		);
		vc_add_param('vc_row_inner', $attributes);
		
		$attributes = array(
			"type" => "",
			"heading" => "",
			"param_name" => "ebor_docs",
			"description" => 'For help on how to use background classes, please see the <a target="_blank" href="https://tommusrhodus.ticksy.com/article/9990/">Feature Documentation</a>',
		);
		vc_add_param('vc_section', $attributes);
		
		$attributes = array(
			"type" => "textfield",
			"heading" => esc_html__("Gradient Colours", 'stack'),
			"param_name" => "ebor_gradient",
			"description" => 'For help on how to use this feature, please see the <a target="_blank" href="https://www.youtube.com/watch?v=CLiNWmfDkow">Video Tutorial</a>',
		);
		vc_add_param('vc_row_inner', $attributes);
		
		$attributes = array(
			"type" => "textfield",
			"heading" => esc_html__("Gradient Colours", 'stack'),
			"param_name" => "ebor_gradient",
			"description" => 'For help on how to use this feature, please see the <a target="_blank" href="https://www.youtube.com/watch?v=CLiNWmfDkow">Video Tutorial</a>',
		);
		vc_add_param('vc_section', $attributes);
		
		/**
		 * Add team category selectors
		 */
		$team_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'team_category'
		);
		$team_cats = get_categories( $team_args );
		$final_team_cats = array( 'Show all categories' => 'all' );
		
		if( taxonomy_exists('team_category') ){
			if( is_array($team_cats) ){
				foreach( $team_cats as $cat ){
					$final_team_cats[$cat->name] = $cat->slug;
				}
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Team Category?",
			'param_name' => 'filter',
			'value' => $final_team_cats
		);
		vc_add_param('stack_team', $attributes);
		
		/**
		 * Add testimonial category selectors
		 */
		$testimonial_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'testimonial_category'
		);
		$testimonial_cats = get_categories( $testimonial_args );
		$final_testimonial_cats = array( 'Show all categories' => 'all' );
		
		if( taxonomy_exists('testimonial_category') ){
			if( is_array($testimonial_cats) ){
				foreach( $testimonial_cats as $cat ){
					$final_testimonial_cats[$cat->name] = $cat->slug;
				}
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Testimonial Category?",
			'param_name' => 'filter',
			'value' => $final_testimonial_cats
		);
		vc_add_param('stack_testimonial', $attributes);
		
		/**
		 * Add portfolio category selectors
		 */
		$portfolio_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'portfolio_category'
		);
		$portfolio_cats = get_categories( $portfolio_args );
		$final_portfolio_cats = array( 'Show all categories' => 'all' );
		
		if( taxonomy_exists('portfolio_category') ){
			if( is_array($portfolio_cats) ){
				foreach( $portfolio_cats as $cat ){
					$final_portfolio_cats[$cat->name] = $cat->slug;
				}
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Portfolio Category?",
			'param_name' => 'filter',
			'value' => $final_portfolio_cats
		);
		vc_add_param('stack_portfolio', $attributes);
		
		/**
		 * Add product category selectors
		 */
		$product_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'product_cat'
		);
		$product_cats = get_categories( $product_args );
		$final_product_cats = array( 'Show all categories' => 'all' );
		
		if( taxonomy_exists('product_cat') ){
			if( is_array($product_cats) ){
				foreach( $product_cats as $cat ){
					$final_product_cats[$cat->name] = $cat->slug;
				}
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific product Category?",
			'param_name' => 'filter',
			'value' => $final_product_cats
		);
		vc_add_param('stack_product', $attributes);
		
		/**
		 * Add blog category selectors
		 */
		$blog_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'category'
		);
		$blog_cats = get_categories( $blog_args );
		$final_blog_cats = array( 'Show all categories' => 'all' );
		
		if( is_array($blog_cats) ){
			foreach( $blog_cats as $cat ){
				$final_blog_cats[$cat->name] = $cat->slug;
			}
		}
		
		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific blog Category?",
			'param_name' => 'filter',
			'value' => $final_blog_cats
		);
		vc_add_param('stack_post', $attributes);

		/**
		 * Add career category selectors
		 */
		$career_args = array(
			'orderby'                  => 'name',
			'hide_empty'               => 0,
			'hierarchical'             => 1,
			'taxonomy'                 => 'career_category'
		);
		$career_cats = get_categories( $career_args );
		$final_career_cats = array( 'Show all categories' => 'all' );
		
		if( taxonomy_exists('testimonial_category') ){
			if( is_array($career_cats) ){
				foreach( $career_cats as $cat ){
					$final_career_cats[$cat->name] = $cat->slug;
				}
			}
		}

		$attributes = array(
			'type' => 'dropdown',
			'heading' => "Show Specific Career Category?",
			'param_name' => 'filter',
			'value' => $final_career_cats
		);
		vc_add_param('stack_career', $attributes);
		
	}
	add_action('init', 'ebor_vc_add_attr', 999);
}