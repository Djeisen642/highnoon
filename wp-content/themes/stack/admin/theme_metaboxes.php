<?php 

/**
 * Build theme metaboxes
 * Uses the cmb metaboxes class found in the ebor framework plugin
 * More details here: https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('ebor_custom_metaboxes') )){
	function ebor_custom_metaboxes( $meta_boxes ) {
		
		$prefix             = '_ebor_';
		$footer_options     = ebor_get_footer_options();
		$header_options     = ebor_get_header_options();
		$new_social_options = ebor_get_social_icons();
		
		$footer_overrides['none'] = 'Do Not Override Footer Option On This Page';
		foreach( $footer_options as $key => $value ){
			$footer_overrides[$key] = 'Override Footer: ' . $value; 	
		}
		
		$header_overrides['none'] = 'Do Not Override Header Option On This Page';
		foreach( $header_options as $key => $value ){
			$header_overrides[$key] = 'Override Header: ' . $value; 	
		}
		
		/**
		 * Social Icons for Team Members
		 */
		$meta_boxes[] = array(
			'id' => 'social_metabox',
			'title' => esc_html__('Team Member Details', 'stack'),
			'object_types' => array('team'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__('Job Title', 'stack'),
					'desc' => '(Optional) Enter a Job Title for this Team Member',
					'id'   => $prefix . 'the_job_title',
					'type' => 'text',
				),
				array(
				    'id'          => $prefix . 'team_social_icons',
				    'type'        => 'group',
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Icon', 'stack' ),
				        'remove_button' => esc_html__( 'Remove Icon', 'stack' ),
				        'sortable'      => true
				    ),
				    'fields' => array(
						array(
							'name' => 'Social Icon',
							'desc' => 'What icon would you like for this team members first social profile?',
							'id' => $prefix . 'social_icon',
							'type' => 'select',
							'options' => $new_social_options
						),
						array(
							'name' => esc_html__('URL for Social Icon', 'stack'),
							'desc' => esc_html__("Enter the URL for Social Icon 1 e.g www.google.com", 'stack'),
							'id'   => $prefix . 'social_icon_url',
							'type' => 'text'
						),
				    ),
				),
			)
		);
		
		/**
		 * Testimonial job titles
		 */
		$meta_boxes[] = array(
			'id' => 'testimonial_metabox',
			'title' => esc_html__('Testimonial Details', 'stack'),
			'object_types' => array('testimonial'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__('Job Title', 'stack'),
					'id'   => $prefix . 'the_job_title',
					'type' => 'text',
				)
			)
		);
		
		$meta_boxes[] = array(
			'id' => 'career_metabox',
			'title' => esc_html__('Career Details', 'stack'),
			'object_types' => array('career'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__('Job Location', 'stack'),
					'id'   => $prefix . 'the_job_location',
					'type' => 'text',
				),
				array(
				    'id'          => $prefix . 'meta_repeat_group',
				    'type'        => 'group',
				    'description' => esc_html__( 'Additional Meta Titles & Descriptions', 'stack' ),
				    'options'     => array(
				        'add_button'    => esc_html__( 'Add Another Entry', 'stack' ),
				        'remove_button' => esc_html__( 'Remove Entry', 'stack' ),
				        'sortable'      => true, // beta
				    ),
				    'fields'      => array(
						array(
							'name' => esc_html__('Additional Item Title', 'stack'),
							'desc' => esc_html__("Title of your Additional Meta", 'stack'),
							'id'   => $prefix . 'the_additional_title',
							'type' => 'text'
						),
						array(
							'name' => esc_html__('Additional Item Content', 'stack'),
							'desc' => esc_html__("Content of your Additional Meta", 'stack'),
							'id'   => $prefix . 'the_additional_content',
							'type' => 'textarea_code'
						)
				    ),
				),
			)
		);

		$custom_menus = array();
		$menus = get_terms( 'nav_menu', array( 'hide_empty' => false ) );
		if ( is_array( $menus ) && ! empty( $menus ) ) {
			foreach ( $menus as $single_menu ) {
				if ( is_object( $single_menu ) && isset( $single_menu->name, $single_menu->slug ) ) {
					$custom_menus[ $single_menu->slug ] = $single_menu->name;
				}
			}
		}

		/**
		 * Mega menu metaboxes
		 */
		$meta_boxes[] = array(
			'id' => 'mega_menu_metabox',
			'title' => esc_html__('Menus to Show in Mega Menu Dropdown', 'stack'),
			'object_types' => array('mega_menu'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
				    'name'             => 'Menu Dropdown Width',
				    'desc'             => 'Select a width for the mega menu dropdown',
				    'id'               => $prefix . 'menu_width',
				    'type'             => 'select',
				    'show_option_none' => false,
				    'default'          => '12',
				    'options'          => array(
				    	'12' => '100% Width (3 Menu Columns Ideal)',
				    	'11' => '91% Width',
				    	'10' => '83% Width',
				    	'9'  => '74% Width',
				    	'8'  => '66% Width (2 Menu Column Ideal)',
				    	'7'  => '57% Width',
				    	'6'  => '49% Width',
				    	'5'  => '41% Width',
				    	'4'  => '33% Width (1 Menu Column Ideal)',
				    ),
				),
				array(
				    'name'             => 'Menu Column 1',
				    'desc'             => 'Select an option',
				    'id'               => $prefix . 'menu_1',
				    'type'             => 'select',
				    'show_option_none' => true,
				    'default'          => '',
				    'options'          => $custom_menus,
				),
				array(
				    'name'             => 'Menu Column 2',
				    'desc'             => 'Select an option',
				    'id'               => $prefix . 'menu_2',
				    'type'             => 'select',
				    'show_option_none' => true,
				    'default'          => '',
				    'options'          => $custom_menus,
				),
				array(
				    'name'             => 'Menu Column 3',
				    'desc'             => 'Select an option',
				    'id'               => $prefix . 'menu_3',
				    'type'             => 'select',
				    'show_option_none' => true,
				    'default'          => '',
				    'options'          => $custom_menus,
				),
				array(
					'name' => esc_html__('Top Level URL Link', 'stack'),
					'desc' => esc_html__("If you're using the hover option for menu dropdowns, entering a URL here will allow the top level link this mega menu generates to be clickable", 'stack'),
					'id'   => $prefix . 'top_level_url',
					'type' => 'text_url'
				),
			)
		);
		
		/**
		 * Post & Portfolio Header Images
		 */
		$meta_boxes[] = array(
			'id' => 'post_header_metabox',
			'title' => esc_html__('Page Overrides', 'stack'),
			'object_types' => array('page', 'portfolio', 'team', 'post', 'career', 'product'), // post type
			'context' => 'normal',
			'priority' => 'low',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name'         => esc_html__( 'Override Header?', 'stack' ),
					'desc'         => esc_html__( 'Header Layout is set in "appearance" -> "customise". To override this for this page only, use this control.', 'stack' ),
					'id'           => $prefix . 'header_override',
					'type'         => 'select',
					'options'      => $header_overrides,
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Override Footer?', 'stack' ),
					'desc'         => esc_html__( 'Footer Layout is set in "appearance" -> "customise". To override this for this page only, use this control.', 'stack' ),
					'id'           => $prefix . 'footer_override',
					'type'         => 'select',
					'options'      => $footer_overrides,
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Use boxed layout for this page?', 'stack' ),
					'desc'         => esc_html__( 'Boxed layout is set in "appearance" -> "customise" -> "site settings". To override this for this page only, use this control.', 'stack' ),
					'id'           => $prefix . 'boxed_override',
					'type'         => 'select',
					'options'      => array(
						'none' => 'Do not override "boxed" setting on this page',
						'boxed-layout' => 'Box this page',
						'normal-layout' => 'Unbox this page'
					),
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Use square buttons for this page?', 'stack' ),
					'desc'         => esc_html__( 'Square buttons are set in "appearance" -> "customise" -> "site settings". To override this for this page only, use this control.', 'stack' ),
					'id'           => $prefix . 'buttons_override',
					'type'         => 'select',
					'options'      => array(
						'none' => 'Do not override "buttons" setting on this page',
						'stack--rounded' => 'Rounded elements on this page',
						'stack--square'  => 'Square elements on this page'
					),
					'std'          => 'none'
				),
				array(
					'name'         => esc_html__( 'Use page navigator in this page?', 'stack' ),
					'desc'         => esc_html__( 'Set IDs on your rows in Visual Composer, or use the rename feature for sections in Variant.', 'stack' ),
					'id'           => $prefix . 'page_navigator',
					'type'         => 'select',
					'options'      => array(
						'none' => 'Do not override "page navigator" setting on this page',
						'yes' => 'Yes: Show the in-page-navigator in this page.'
					),
					'std'          => 'none'
				),
			)
		);
		
		return $meta_boxes;
	}
	add_filter( 'cmb2_meta_boxes', 'ebor_custom_metaboxes' );
}