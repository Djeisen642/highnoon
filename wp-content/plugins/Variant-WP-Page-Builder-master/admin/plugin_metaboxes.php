<?php 

/**
 * Build theme metaboxes
 * Uses the cmb metaboxes class found in the ebor framework plugin
 * More details here: https://github.com/WebDevStudios/Custom-Metaboxes-and-Fields-for-WordPress
 * 
 * @since 1.0.0
 * @author tommusrhodus
 */
if(!( function_exists('variant_custom_metaboxes') )){
	function variant_custom_metaboxes( $meta_boxes ) {

		/**
		 * Social Icons for Team Members
		 */
		$meta_boxes[] = array(
			'id' => 'variant_debug_metabox',
			'title' => esc_html__('Variant Debugging', 'stack'),
			'object_types' => array('team', 'page', 'post', 'portfolio', 'product'), // post type
			'context' => 'normal',
			'priority' => 'high',
			'show_names' => true, // Show field names on the left
			'fields' => array(
				array(
					'name' => esc_html__('Variant Data', 'stack'),
					'id'   => '_variant_page_builder_variant',
					'type' => 'textarea_code',
				),
				array(
					'name' => esc_html__('Variant HTML Data', 'stack'),
					'id'   => '_variant_page_builder_html',
					'type' => 'textarea_code',
				),
			)
		);
		
		return $meta_boxes;
		
	}
	add_filter( 'cmb2_meta_boxes', 'variant_custom_metaboxes' );
}