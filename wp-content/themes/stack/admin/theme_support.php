<?php

/**
 * Load Theme Support on Init
 */
if(!( function_exists('ebor_add_editor_styles') )){
	function ebor_add_editor_styles() {
	    
	    add_editor_style( 'admin/editor-style.css' );
	    
	    /**
	     * Set Content Width
	     */
	    global $content_width;
	    if ( ! isset( $content_width ) ) $content_width = 1170;
	    
	    add_post_type_support('mega_menu', 'thumbnail');
	    add_post_type_support('testimonial', 'thumbnail');
	    
	}
	add_action( 'init', 'ebor_add_editor_styles', 10 );
}

/**
 * Load Theme Support after_theme_setup
 */
if(!( function_exists('ebor_add_theme_support') )){
	function ebor_add_theme_support() {
		
		/**
		 * Add post thumbnail (featured image) support
		 */
		add_theme_support( 'post-thumbnails' );
		
		/**
		 * Image Sizes used in the theme
		 */
		add_image_size( 'ebor-admin-list-thumb', 60, 60, true );

		/**
		 * Add Custom Background Support and Set Default
		 */
		add_theme_support( 'custom-background', array( 'default-color' => '#FFFFFF' ) );
		
		/**
		 * Add feed link support
		 */
		add_theme_support( 'automatic-feed-links' );
		
		/**
		 * Add html5 support
		 */
		add_theme_support( 'html5', array( 'comment-list', 'comment-form', 'search-form' ) );
		
		add_theme_support('post-formats', array('image'));
		
		/**
		 * Load Translation Files
		 */
		load_theme_textdomain('stack', trailingslashit(get_template_directory()) . 'languages');
		
		add_theme_support( 'title-tag' );
		
		add_theme_support( 'woocommerce' );
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		
		add_theme_support( 'customize-selective-refresh-widgets' );
		
	}
	add_action('after_setup_theme', 'ebor_add_theme_support', 10 );
}